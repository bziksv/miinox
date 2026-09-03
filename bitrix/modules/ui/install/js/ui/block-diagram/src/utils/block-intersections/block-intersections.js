import { toValue, ref, computed, toRaw, unref } from 'ui.vue3';
import { BlockRBush } from './block-rbush';
import { ConnectionRBush, computeConnectionBBox } from './connection-rbush';
import { collectEndpointsToMeasure, takeFirstMeasureBatch } from './first-measure';
import { BLOCK_GROUP_DEFAULT_NAME, CONNECTION_GROUP_DEFAULT_NAME, FIRST_MEASURE_BATCH_SIZE } from '../../constants';
import { type DiagramBlock, type DiagramBlockId, type DiagramConnection, type GroupedConnections, type ConnectionGroupNames, type DiagramPortsMap, type DiagramBlockGroupNames, type DiagramGroupedBlocks, type State, type DiagramInstancesContext, type DiagramSearchBlockRect } from '../../types';

export class BlockIntersections
{
	#tree: typeof BlockRBush | null = null;
	#connectionTree: typeof ConnectionRBush | null = null;
	#state: State | null = null;
	#selectVisibleBlocksRafId: number | null = null;
	#selectVisibleConnectionsRafId: number | null = null;
	#loadConnectionsRafId: number | null = null;

	visibleBlocks: DiagramBlock[] = ref([]);

	visibleBlockIds: Set<string> = computed(() => {
		return new Set(toValue(this.visibleBlocks).map((block) => block.id));
	});

	// P3.T1: ends of viewport-crossing connections whose geometry was never measured.
	blocksToMeasureIds: Set<DiagramBlockId> = computed(() => {
		if (!(this.#state?.isRenderOptimizationAvailable ?? false))
		{
			return new Set();
		}

		return collectEndpointsToMeasure(
			toValue(this.visibleConnections),
			this.#state?.portsRectMap ?? {},
			this.#blockByIdMap(this.#state?.blocks ?? []),
		);
	});

	groupedVisibleBlocks: DiagramGroupedBlocks = computed(() => {
		const blocks = (this.#state?.isRenderOptimizationAvailable ?? false)
			? this.#visibleBlocksWithMeasureEndpoints()
			: this.#state?.blocks ?? [];

		return toValue(blocks)
			.reduce((acc, block) => {
				const type = block?.type ?? BLOCK_GROUP_DEFAULT_NAME;

				if (type in acc)
				{
					acc[type].push(block);
				}
				else
				{
					acc[type] = [block];
				}

				return acc;
			}, { [BLOCK_GROUP_DEFAULT_NAME]: [] });
	});

	visibleBlockGroupNames: DiagramBlockGroupNames = computed(() => {
		return Object.keys(toValue(this.groupedVisibleBlocks));
	});

	visiblePorts: DiagramPortsMap = computed(() => {
		const portsMap = new Map();

		for (const block of toValue(this.visibleBlocks))
		{
			for (const port of block.ports)
			{
				if (!portsMap.has(block.id))
				{
					portsMap.set(block.id, new Map());
				}

				portsMap.get(block.id).set(port.id, port);
			}
		}

		return portsMap;
	});

	visibleConnections: DiagramConnection[] = ref([]);

	groupedVisibleConnections: GroupedConnections = computed(() => {
		const connections = (this.#state?.isRenderOptimizationAvailable ?? false)
			? this.visibleConnections
			: this.#state?.connections ?? [];

		return toValue(connections)
			.reduce((acc, connection) => {
				const type = connection?.type ?? CONNECTION_GROUP_DEFAULT_NAME;

				if (type in acc)
				{
					acc[type].push(connection);
				}
				else
				{
					acc[type] = [connection];
				}

				return acc;
			}, { [CONNECTION_GROUP_DEFAULT_NAME]: [] });
	});

	visibleConnectionGroupNames: ConnectionGroupNames = computed(() => {
		return Object.keys(toValue(this.groupedVisibleConnections));
	});

	constructor(ctx: DiagramInstancesContext)
	{
		this.#state = ctx.state;
		this.#tree = new BlockRBush();
		this.#connectionTree = new ConnectionRBush();
	}

	// P3.T2: for one render cycle, append the never-measured connection ends to the
	// visible set so their ports mount and onMountedPort measures them. After that
	// Phase 1 retention keeps the geometry, blocksToMeasureIds no longer returns them,
	// and they are culled again — the measure-mount is strictly one-time per end.
	//
	// At most FIRST_MEASURE_BATCH_SIZE ends are added per cycle. Measuring the batch
	// writes portsRectMap, which blocksToMeasureIds reads, so the computed re-runs, drops
	// the measured ends, and the next cycle takes the next batch — a large off-screen set
	// drains over several frames without a single long measure task (no explicit queue).
	#visibleBlocksWithMeasureEndpoints(): DiagramBlock[]
	{
		const visible = toValue(this.visibleBlocks);
		const measureIds = toValue(this.blocksToMeasureIds);

		if (measureIds.size === 0)
		{
			return visible;
		}

		const visibleIds = toValue(this.visibleBlockIds);
		const blockById = this.#blockByIdMap(this.#state?.blocks ?? []);
		const measureBlocks = takeFirstMeasureBatch(measureIds, visibleIds, blockById, FIRST_MEASURE_BATCH_SIZE);

		return measureBlocks.length === 0 ? visible : [...visible, ...measureBlocks];
	}

	// Builds an O(1) id→block lookup once per index rebuild, so connection boxes resolve
	// their endpoints in O(C + B) instead of O(C × B) (a linear blocks.find per connection).
	#blockByIdMap(blocks: DiagramBlock[]): Map<DiagramBlockId, DiagramBlock>
	{
		return new Map(
			toValue(blocks ?? []).map((block) => [toValue(block).id, block]),
		);
	}

	// Connection index items (connection + routing-aware bbox), skipping any connection
	// whose endpoint block is absent from the given lookup. Routing params come from the
	// live state refs so the box matches the geometry connection-state.js actually draws.
	#buildConnectionItems(
		connections: DiagramConnection[],
		blockById: Map<DiagramBlockId, DiagramBlock>,
	): DiagramConnection[]
	{
		const offset = toValue(this.#state?.connectionOffset);
		const bendOffset = toValue(this.#state?.connectionBendOffset);
		const borderRadius = toValue(this.#state?.connectionBorderRadius);
		const offsetMap = toValue(this.#state?.connectionsOffsetMap) ?? {};

		const portsRectMap = toValue(this.#state?.portsRectMap) ?? {};

		return toValue(connections ?? [])
			.map((connection) => {
				const rawConnection = toRaw(unref(connection));
				const routing = {
					offset,
					bendOffset,
					borderRadius,
					secondSegmentOrder: this.#connectionSecondSegmentOrder(rawConnection, offsetMap),
					sourceFirstSegmentSize: this.#endpointFirstSegmentSize(
						rawConnection.sourceBlockId,
						rawConnection.sourcePortId,
						rawConnection.id,
						offsetMap,
						portsRectMap,
						offset,
						blockById,
					),
					targetFirstSegmentSize: this.#endpointFirstSegmentSize(
						rawConnection.targetBlockId,
						rawConnection.targetPortId,
						rawConnection.id,
						offsetMap,
						portsRectMap,
						offset,
						blockById,
					),
					sourceSecondSegmentSize: this.#endpointSecondSegmentSize(
						rawConnection.sourceBlockId,
						rawConnection.sourcePortId,
						rawConnection.id,
						offsetMap,
						portsRectMap,
						bendOffset,
						blockById,
					),
					targetSecondSegmentSize: this.#endpointSecondSegmentSize(
						rawConnection.targetBlockId,
						rawConnection.targetPortId,
						rawConnection.id,
						offsetMap,
						portsRectMap,
						bendOffset,
						blockById,
					),
				};
				const bbox = computeConnectionBBox(
					rawConnection,
					(blockId) => blockById.get(blockId) ?? null,
					routing,
				);

				return bbox === null ? null : { ...rawConnection, ...bbox };
			})
			.filter((connection) => connection !== null);
	}

	// Real first-segment length of one connection end, resolved the same way drawing
	// resolves it (connection-state.js:75-140): a many-connection port uses the per-
	// connection firstSegmentSize (count * offset), a single-connection port uses the
	// measured/retained value in portsRectMap ((portIndex + 1) * offset). When the port
	// was never measured, fall back to a conservative estimate from its index in the
	// model block's ports, so the bbox never underestimates a high-index port.
	#endpointFirstSegmentSize(
		blockId: DiagramBlockId,
		portId: string,
		connectionId: string,
		offsetMap: Object,
		portsRectMap: Object,
		offset: number,
		blockById: Map<DiagramBlockId, DiagramBlock>,
	): number
	{
		const portOffsets = offsetMap?.[blockId]?.[portId];
		const hasManyConnection = Object.keys(portOffsets ?? {}).length > 1;

		if (hasManyConnection)
		{
			const size = portOffsets?.[connectionId]?.firstSegmentSize;
			if (Number.isFinite(size))
			{
				return size;
			}
		}

		const measured = portsRectMap?.[blockId]?.[portId]?.firstSegmentSize;
		if (Number.isFinite(measured) && measured > 0)
		{
			return measured;
		}

		return this.#estimateFirstSegmentSize(blockId, portId, offset, blockById);
	}

	// Conservative first-segment estimate for a never-measured port: (portIndex + 1) *
	// offset, matching updatePortSegmentSizes (actions.js:508/514). Falls back to a single
	// offset when the port is not found in the model block.
	#estimateFirstSegmentSize(
		blockId: DiagramBlockId,
		portId: string,
		offset: number,
		blockById: Map<DiagramBlockId, DiagramBlock>,
	): number
	{
		const step = Number.isFinite(offset) ? offset : 0;
		const block = blockById.get(blockId);
		const ports = toValue(block)?.ports ?? [];
		const index = ports.findIndex((port) => (toValue(port)?.id ?? port?.id) === portId);

		return (index >= 0 ? index + 1 : 1) * step;
	}

	// Real second-segment length of one connection end, resolved the same way drawing
	// resolves it (connection-state.js:126-128, 137-139): a many-connection port uses the
	// measured secondSegmentSizeWithoutOffset plus bendOffset * secondSegmentOrder, a
	// single-connection port uses the measured secondSegmentSize. When the port was never
	// measured, fall back to a conservative upper bound from the model block's dimensions,
	// so the bbox never underestimates the perpendicular bend excursion (~block size).
	#endpointSecondSegmentSize(
		blockId: DiagramBlockId,
		portId: string,
		connectionId: string,
		offsetMap: Object,
		portsRectMap: Object,
		bendOffset: number,
		blockById: Map<DiagramBlockId, DiagramBlock>,
	): number
	{
		const portRect = portsRectMap?.[blockId]?.[portId];
		const portOffsets = offsetMap?.[blockId]?.[portId];
		const hasManyConnection = Object.keys(portOffsets ?? {}).length > 1;
		const step = Number.isFinite(bendOffset) ? bendOffset : 0;

		if (hasManyConnection)
		{
			const withoutOffset = portRect?.secondSegmentSizeWithoutOffset;
			if (Number.isFinite(withoutOffset) && withoutOffset > 0)
			{
				const order = portOffsets?.[connectionId]?.secondSegmentOrder ?? 0;

				return withoutOffset + step * Math.max(order, 0);
			}
		}
		else
		{
			const measured = portRect?.secondSegmentSize;
			if (Number.isFinite(measured) && measured > 0)
			{
				return measured;
			}
		}

		return this.#estimateSecondSegmentSize(blockId, portId, connectionId, offsetMap, step, blockById);
	}

	// Conservative second-segment estimate for a never-measured port: the drawn value is
	// blockDimension - portOffset + bendOffset * (order + 1) (actions.js:519-520), and
	// portOffset >= 0, so max(blockWidth, blockHeight) + bendOffset * (order + 1) is a safe
	// upper bound regardless of the port's (still unknown) side. Direction is safe — the
	// padding only grows.
	#estimateSecondSegmentSize(
		blockId: DiagramBlockId,
		portId: string,
		connectionId: string,
		offsetMap: Object,
		bendStep: number,
		blockById: Map<DiagramBlockId, DiagramBlock>,
	): number
	{
		const block = blockById.get(blockId);
		const dimensions = toValue(block)?.dimensions ?? {};
		const width = Number.isFinite(dimensions.width) ? dimensions.width : 0;
		const height = Number.isFinite(dimensions.height) ? dimensions.height : 0;
		const order = offsetMap?.[blockId]?.[portId]?.[connectionId]?.secondSegmentOrder ?? 0;

		return Math.max(width, height) + bendStep * (Math.max(order, 0) + 1);
	}

	// Largest secondSegmentOrder among the connection's two endpoints — a port carrying
	// several connections fans each bend out by bendOffset * order (connection-state.js).
	#connectionSecondSegmentOrder(connection: DiagramConnection, offsetMap: Object): number
	{
		const {
			id,
			sourceBlockId,
			sourcePortId,
			targetBlockId,
			targetPortId,
		} = connection;

		const sourceOrder = offsetMap?.[sourceBlockId]?.[sourcePortId]?.[id]?.secondSegmentOrder ?? 0;
		const targetOrder = offsetMap?.[targetBlockId]?.[targetPortId]?.[id]?.secondSegmentOrder ?? 0;

		return Math.max(sourceOrder, targetOrder);
	}

	// Clears and reloads the connection index from the given blocks/connections. Shared
	// by the coalesced current-model rebuild and the synchronous history-snapshot rebuild.
	#rebuildConnectionIndex(connections: DiagramConnection[], blocks: DiagramBlock[]): void
	{
		const blockById = this.#blockByIdMap(blocks);
		const prepared = this.#buildConnectionItems(connections, blockById);

		this.#connectionTree?.clear();
		this.#connectionTree?.load(prepared);
		this.#updateVisibleConnections();
	}

	// Rebuilds the whole connection index from the current model. Connection boxes
	// derive from block positions, so a block move refreshes them here too; a full
	// rebuild avoids RBush remove-by-navigation, which is unsafe for items whose
	// geometry lives outside the item. Coalesced through a RAF so a drag (deep block
	// watcher firing per mousemove) triggers at most one rebuild per frame. No-op while
	// render optimization is disabled.
	loadConnections(): void
	{
		if (!(this.#state?.isRenderOptimizationAvailable ?? false))
		{
			return;
		}

		if (this.#loadConnectionsRafId !== null)
		{
			return;
		}

		this.#loadConnectionsRafId = requestAnimationFrame(() => {
			this.#loadConnectionsRafId = null;
			this.#rebuildConnectionIndex(this.#state?.connections ?? [], this.#state?.blocks ?? []);
		});
	}

	// Rebuilds the connection index from an explicit snapshot (blocks + connections),
	// used by history undo/redo. On revert the props watcher does re-run loadConnections
	// (the revert emits update:blocks/connections → props change), but only on the next
	// flush; the history hook's clear() empties the index synchronously, so without an
	// immediate rebuild it would stay empty for a frame while blocks are restored.
	// Resolves endpoint boxes from the snapshot's own blocks (state refs may not yet
	// reflect the snapshot at hook time). No-op while render optimization is disabled.
	loadConnectionsFromSnapshot(connections: DiagramConnection[], blocks: DiagramBlock[]): void
	{
		if (!(this.#state?.isRenderOptimizationAvailable ?? false))
		{
			return;
		}

		// Intentionally synchronous: closes the one-frame index gap after the history
		// hook's clear(); must not be coalesced through a RAF.
		this.#rebuildConnectionIndex(connections, blocks);
	}

	selectVisibleConnections(): void
	{
		if (!(this.#state?.isRenderOptimizationAvailable ?? false))
		{
			return;
		}

		if (this.#selectVisibleConnectionsRafId !== null)
		{
			return;
		}

		this.#selectVisibleConnectionsRafId = requestAnimationFrame(() => {
			this.#selectVisibleConnectionsRafId = null;
			this.#updateVisibleConnections();
		});
	}

	#updateVisibleConnections(): void
	{
		const {
			transformX,
			transformY,
			zoom,
			canvasWidth,
			canvasHeight,
		} = this.#state;

		this.visibleConnections.value = this.#connectionTree.search({
			minX: toValue(transformX),
			minY: toValue(transformY),
			maxX: toValue(transformX) + toValue(canvasWidth) / toValue(zoom),
			maxY: toValue(transformY) + toValue(canvasHeight) / toValue(zoom),
		});
	}

	load(blocks: DiagramBlock[])
	{
		this.#tree?.load(toRaw(unref(blocks)));
		this.selectVisibleBlocks();
	}

	search(searchRect: DiagramSearchBlockRect): DiagramBlock[]
	{
		return this.#tree.search(searchRect);
	}

	selectVisibleBlocks(): void
	{
		if (this.#selectVisibleBlocksRafId !== null)
		{
			return;
		}

		this.#selectVisibleBlocksRafId = requestAnimationFrame(() => {
			this.#selectVisibleBlocksRafId = null;
			this.#updateVisibleBlocks();
		});
	}

	#updateVisibleBlocks(): void
	{
		const {
			transformX,
			transformY,
			zoom,
			canvasWidth,
			canvasHeight,
		} = this.#state;

		this.visibleBlocks.value = this.#withResizingBlock(this.#tree.search({
			minX: toValue(transformX),
			minY: toValue(transformY),
			maxX: toValue(transformX) + toValue(canvasWidth) / toValue(zoom),
			maxY: toValue(transformY) + toValue(canvasHeight) / toValue(zoom),
		}));
	}

	// The index holds the pre-gesture box of a block being resized: its staged geometry
	// reaches the model only on mouseup. Autoscroll can pan the camera past that box, and
	// culling the block mid-gesture unmounts it, which tears the gesture down. Keep it in
	// the visible set until the gesture ends and the index catches up. Every writer of
	// visibleBlocks goes through here, not just the culling pass: clear() empties the set
	// synchronously while the refill waits for a RAF, so an unretained block would unmount
	// for a frame. Duplicates are ruled out by id, so it does not matter that the appended
	// model block is not always the object the tree holds — insertBlock/updateBlock store
	// a copy, only load() puts the model objects themselves into the index.
	#withResizingBlock(blocks: DiagramBlock[]): DiagramBlock[]
	{
		if (!(this.#state?.isRenderOptimizationAvailable ?? false))
		{
			return blocks;
		}

		const resizingId = this.#state?.resizingBlock?.id ?? null;

		if (resizingId === null || blocks.some((block) => toValue(block).id === resizingId))
		{
			return blocks;
		}

		// A single block is looked up here on every visibility pass, so it goes without the
		// intermediate Map: building one would be O(N) allocations per frame.
		const resizingBlock = toValue(this.#state?.blocks ?? [])
			.find((block) => toValue(block).id === resizingId);

		return resizingBlock === undefined ? blocks : [...blocks, toRaw(unref(resizingBlock))];
	}

	updateBlock(oldBlock: DiagramBlock, newBlock: DiagramBlock): void
	{
		this.removeBlock(oldBlock);
		this.insertBlock(newBlock);
	}

	#preparedBlock(block: DiagramBlock): DiagramBlock
	{
		return toRaw(unref({
			...block,
			position: toRaw(toValue(block).position),
			dimensions: toRaw(toValue(block).dimensions),
			ports: toRaw(unref(toValue(block).ports)),
		}));
	}

	insertBlock(block: DiagramBlock): void
	{
		this.#tree?.insert(this.#preparedBlock(block));
		this.selectVisibleBlocks();
	}

	removeBlock(block: DiagramBlock): void
	{
		this.#tree?.remove(
			this.#preparedBlock(block),
			(blockA: DiagramBlock, blockB: DiagramBlock): boolean => {
				return toValue(blockA).id === toValue(blockB).id;
			},
		);
		this.selectVisibleBlocks();
	}

	clear(): void
	{
		if (this.#selectVisibleBlocksRafId !== null)
		{
			cancelAnimationFrame(this.#selectVisibleBlocksRafId);
			this.#selectVisibleBlocksRafId = null;
		}

		if (this.#selectVisibleConnectionsRafId !== null)
		{
			cancelAnimationFrame(this.#selectVisibleConnectionsRafId);
			this.#selectVisibleConnectionsRafId = null;
		}

		if (this.#loadConnectionsRafId !== null)
		{
			cancelAnimationFrame(this.#loadConnectionsRafId);
			this.#loadConnectionsRafId = null;
		}

		this.#tree?.clear();
		this.visibleBlocks.value = this.#withResizingBlock([]);

		this.#connectionTree?.clear();
		this.visibleConnections.value = [];
	}
}
