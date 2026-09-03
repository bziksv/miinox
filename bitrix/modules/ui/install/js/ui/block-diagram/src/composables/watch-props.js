import { watch, effectScope, toValue } from 'ui.vue3';
import { useBlockDiagram } from './block-diagram';

export type UseWatchProps = {
	dispose: () => void,
};

function blockGeometryKey(block): string
{
	const { x = 0, y = 0 } = toValue(block.position) ?? {};
	const { width = 0, height = 0 } = toValue(block.dimensions) ?? {};
	const ports = toValue(block.ports) ?? [];

	return JSON.stringify({ x, y, width, height, ports });
}

function buildBlockModel(list): Map<string, string>
{
	return new Map(toValue(list ?? []).map((block) => [block.id, blockGeometryKey(block)]));
}

// Free geometry that the model no longer backs: (a) blocks removed from the model,
// (b) unmounted (culled) blocks whose position/size changed — their retained
// coordinates are now stale and no unmount will fire to clear them.
function purgeStaleGeometry(previousModel, newModel, blockElMap, purgeBlockGeometry): void
{
	for (const blockId of previousModel.keys())
	{
		if (!newModel.has(blockId))
		{
			purgeBlockGeometry(blockId);
		}
	}

	for (const [blockId, geometry] of newModel)
	{
		if (toValue(blockElMap)?.has(blockId) ?? false)
		{
			continue;
		}

		const previous = previousModel.get(blockId);
		if (previous !== undefined && previous !== geometry)
		{
			purgeBlockGeometry(blockId);
		}
	}
}

export function useWatchProps(props): UseWatchProps
{
	const {
		blocks,
		connections,
		zoom,
		isDisabled,
		connectionOffset,
		connectionBendOffset,
		connectionBorderRadius,
		setUnmountedBlocks,
		setUnmountedPorts,
		setConnectionsOffsets,
		setHistoryBlocksCurrentState,
		setHistoryConnectionsCurrentState,
		blockIntersections,
		isRunUpdateBlocksCommand,
		purgeBlockGeometry,
		blockElMap,
		isRenderOptimizationAvailable,
	} = useBlockDiagram();
	const scope = effectScope(true);

	// Own snapshot of the previous block model (id → geometry key). Independent of the
	// watcher's oldValue, which is unreliable: props.blocks mutated in place yields
	// oldBlocks === newBlocks, so a diff against it sees no change.
	let previousBlockModel = new Map();

	scope.run(() => {
		watch([
			() => props.blocks,
			() => props.blocks.length,
		], ([newBlocks = [], newLength = 0], [oldBlocks = [], oldLength = 0]) => {
			if (newBlocks && Array.isArray(newBlocks))
			{
				setHistoryBlocksCurrentState(newBlocks);
				setUnmountedPorts(newBlocks, oldBlocks);
				setUnmountedBlocks(newBlocks, oldBlocks);
				blocks.value = newBlocks;

				const optimizationEnabled = toValue(isRenderOptimizationAvailable);
				const newBlockModel = optimizationEnabled ? buildBlockModel(newBlocks) : null;

				if (!toValue(isRunUpdateBlocksCommand))
				{
					// Direct props.blocks mutation bypasses DELETE_BLOCK hooks, so retained
					// geometry of removed or shifted culled nodes would leak: purge via the
					// own snapshot instead of the unreliable oldBlocks diff.
					if (optimizationEnabled)
					{
						purgeStaleGeometry(previousBlockModel, newBlockModel, blockElMap, purgeBlockGeometry);
					}

					blockIntersections.clear();
					blockIntersections.load(blocks.value);
				}

				if (optimizationEnabled)
				{
					previousBlockModel = newBlockModel;
				}

				isRunUpdateBlocksCommand.value = false;

				// Connection boxes derive from block positions, so any block change
				// (move/add/delete) must rebuild the connection index too.
				blockIntersections.loadConnections();
			}
		}, { immediate: true, deep: true });

		watch([() => props.connections, () => props.connections.length], ([newConnections]) => {
			setConnectionsOffsets(newConnections);
			setHistoryConnectionsCurrentState(newConnections);
			connections.value = [...newConnections];
			blockIntersections.loadConnections();
		}, { immediate: true, deep: true });

		watch(() => props.zoom, (newZoom: number) => {
			zoom.value = newZoom;
		}, { immediate: true });

		watch(() => props.minZoom, (newMinZoom: number) => {
			zoom.value = newMinZoom;
		}, { immediate: true });

		watch(() => props.maxZoom, (newMaxZoom: number) => {
			zoom.value = newMaxZoom;
		}, { immediate: true });

		watch(() => props.connectionOffset, (newConnectionOffset: number): void => {
			connectionOffset.value = newConnectionOffset;
			// Routing param feeds connection bbox padding: rebuild the index to match.
			blockIntersections.loadConnections();
		}, { immediate: true });

		watch(() => props.connectionBendOffset, (newConnectionOffsetBend: number): void => {
			connectionBendOffset.value = newConnectionOffsetBend;
			// Routing param feeds connection bbox padding: rebuild the index to match.
			blockIntersections.loadConnections();
		}, { immediate: true });

		watch(() => props.connectionBorderRadius, (newConnectionBorderRadius: number): void => {
			connectionBorderRadius.value = newConnectionBorderRadius;
			// Routing param feeds connection bbox padding: rebuild the index to match.
			blockIntersections.loadConnections();
		}, { immediate: true });

		watch(() => props.disabled, (disabled: boolean) => {
			isDisabled.value = disabled;
		}, { immediate: true });
	});

	function dispose(): void
	{
		scope.stop();
	}

	return {
		dispose,
	};
}
