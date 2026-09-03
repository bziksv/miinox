import { toValue, markRaw } from 'ui.vue3';
import { Type } from 'main.core';
import { PORT_POSITION } from '../constants';
import { commandToArray, promiseWithResolvers, getCanvasRect } from '../utils';
import { useAutoScroll } from './autoscroll';
import { type HandlerOptions } from './history';
import { type DiagramBlockId, type DiagramBlock, type DiagramConnectionId, type DiagramConnection, type DiagramAddConnection, type DiagramPortId, type Point } from '../types';

export type UseActions = {
	setState: () => void,
	setUnmountedBlocks: (newBlocks: DiagramBlock[], oldBlocks: DiagramBlock[]) => void,
	blockMounted: (blockId: DiagramBlockId) => void,
	setUnmountedPorts: (newBlocks: DiagramBlock[], oldBlocks: DiagramBlock[]) => void,
	portMounted: (blockId: DiagramBlockId, portId: DiagramPortId) => void,
	setConnectionsOffsets: (connections: DiagramConnection[]) => void,
	setHistoryBlocksCurrentState: (blocks: DiagramBlock[]) => void,
	setHistoryConnectionsCurrentState: (connections: DiagramConnection[]) => void,
	isExistConnection: (connection: DiagramConnection) => boolean,
	addConnection: (connection: DiagramAddConnection) => void,
	addConnections: (connections: DiagramConnection[]) => void,
	deleteConnectionById: (connectionId: DiagramConnectionId) => void,
	addBlock: (block: DiagramBlock) => void,
	addBlocks: (...blocks: DiagramBlock[]) => void,
	updateBlockPositionByIndex: (index: number, x: number, y: number) => void,
	updateBlock: (newBlock: DiagramBlock) => void,
	deleteBlockById: (blockId: DiagramBlockId) => void,
	transformEventToPoint: (point: { clientX: number, clientY: number }) => Point,
	setMovingBlock: (block: DiagramBlock) => void,
	updateMovingBlockPosition: (x: number, y: number) => void,
	resetMovingBlock: () => void,
	setHistoryHandlers: (HandlerOptions) => void,
	setPortOffsetByBlockId: (blockId: string, offsets: { x: number, y: number }) => void;
	purgeBlockGeometry: (blockId: DiagramBlockId) => void;
	purgeBlockGeometryExcept: (keepBlockIds: DiagramBlockId[]) => void;
};

/* eslint-disable no-param-reassign */
// eslint-disable-next-line max-lines-per-function
export function useActions({ state, getters, hooks }): UseActions
{
	function setState(options): void
	{
		state.blocks = toValue(options.blocks);
		state.connections = toValue(options.connections);
		state.transformX = options.transform.x;
		state.transformY = options.transfrom.y;
		state.zoom = toValue(options.zoom);
	}

	function setUnmountedBlocks(newBlocks: DiagramBlock[], oldBlocks: DiagramBlock[] = []): void
	{
		const oldBlockIdsMap = new Set(oldBlocks.map((block) => block.id));
		const arrWaitedBlockIds = newBlocks
			.filter((block) => !oldBlockIdsMap.has(block.id))
			.map((block) => block.id);

		state.waitAllBlocksMounted = promiseWithResolvers();
		state.waitedBlockIds = new Set(arrWaitedBlockIds);
	}

	function blockMounted(blockId: DiagramBlockId): void
	{
		const { waitedBlockIds, waitAllBlocksMounted } = state;

		waitedBlockIds.delete(blockId);

		if (waitedBlockIds.size === 0)
		{
			waitAllBlocksMounted.resolve();
		}
	}

	function setUnmountedPorts(newBlocks: DiagramBlock[], oldBlocks: DiagramBlock[] = []): void
	{
		const oldBlockPortsIds = oldBlocks.reduce((accMap, block) => {
			block.ports.forEach((port) => accMap.add(`${block.id}_${port.id}`));

			return accMap;
		}, new Set());

		const arrNewBlockPortIds = newBlocks
			.flatMap((block) => block.ports.map((port) => `${block.id}_${port.id}`))
			.filter((blockPortId) => !oldBlockPortsIds.has(blockPortId));
		state.waitedBlockPortsIds = new Set(arrNewBlockPortIds);

		state.waitAllPortsMounted = promiseWithResolvers();
	}

	function portMounted(blockId: DiagramBlockId, portId: DiagramPortId): void
	{
		const { waitedBlockPortsIds, waitAllPortsMounted } = state;

		waitedBlockPortsIds.delete(`${blockId}_${portId}`);

		if (waitedBlockPortsIds.size === 0)
		{
			waitAllPortsMounted.resolve();
		}
	}

	function setConnectionsOffsets(connections: DiagramConnection[]): void
	{
		const { connectionOffset, connectionBendOffset } = state;

		state.connectionsOffsetMap = connections.reduce((accMap, connection) => {
			const {
				id,
				sourceBlockId,
				sourcePortId,
				targetBlockId,
				targetPortId,
			} = connection;

			accMap[sourceBlockId] = sourceBlockId in accMap
				? accMap[sourceBlockId]
				: {};
			accMap[sourceBlockId][sourcePortId] = sourcePortId in accMap[sourceBlockId]
				? accMap[sourceBlockId][sourcePortId]
				: {};
			accMap[targetBlockId] = targetBlockId in accMap
				? accMap[targetBlockId]
				: {};
			accMap[targetBlockId][targetPortId] = targetPortId in accMap[targetBlockId]
				? accMap[targetBlockId][targetPortId]
				: {};

			const sourceConnectionsCount = Object.keys(accMap[sourceBlockId][sourcePortId]).length + 1;
			const targetConnectionsCount = Object.keys(accMap[targetBlockId][targetPortId]).length + 1;

			accMap[sourceBlockId][sourcePortId][id] = {
				firstSegmentSize: sourceConnectionsCount * connectionOffset,
				secondSegmentSize: connectionBendOffset * connectionOffset,
				secondSegmentOrder: sourceConnectionsCount,
			};

			accMap[targetBlockId][targetPortId][id] = {
				firstSegmentSize: targetConnectionsCount * connectionOffset,
				secondSegmentSize: connectionBendOffset * connectionOffset,
				secondSegmentOrder: targetConnectionsCount,
			};

			return accMap;
		}, {});
	}

	function setHistoryBlocksCurrentState(blocks: DiagramBlock[]): void
	{
		state.historyCurrentState.blocks = markRaw(JSON.parse(JSON.stringify(blocks)));
	}

	function setHistoryConnectionsCurrentState(connections: DiagramConnection[]): void
	{
		state.historyCurrentState.connections = markRaw(JSON.parse(JSON.stringify(connections)));
	}

	function updateCanvasTransform(transform: Transform): void
	{
		const {
			x = 0,
			y = 0,
			zoom = 1,
			viewportX = 0,
			viewportY = 0,
		} = transform;
		state.transformX = x;
		state.transformY = y;
		state.viewportX = viewportX;
		state.viewportY = viewportY;
		state.zoom = zoom;
	}

	const isExistConnection = (connection: DiagramConnection): boolean => {
		const {
			sourceBlockId,
			sourcePortId,
			targetBlockId,
			targetPortId,
		} = connection;

		return state.connections.some(({
			sourceBlockId: exSourceBlockId,
			sourcePortId: exSourcePortId,
			targetBlockId: exTargetBlockId,
			targetPortId: exTargetPortId,
		}) => {
			const isSource = (
				exSourceBlockId === sourceBlockId
				&& exSourcePortId === sourcePortId
				&& exTargetBlockId === targetBlockId
				&& exTargetPortId === targetPortId
			);
			const isTarget = (
				exSourceBlockId === targetBlockId
				&& exSourcePortId === targetPortId
				&& exTargetBlockId === sourceBlockId
				&& exTargetPortId === sourcePortId
			);

			return isSource || isTarget;
		});
	};

	const addConnection = (newConnection: DiagramAddConnection): void => {
		if (!isExistConnection(newConnection))
		{
			hooks.changedConnections.trigger(commandToArray.commandPush(newConnection));
			hooks.createConnection.trigger(newConnection);
		}
	};

	const addConnections = (newConnections: DiagramAddConnection[]): void => {
		const notExistConnections = toValue(newConnections)
			.filter((connection) => !isExistConnection(connection));

		if (notExistConnections.length > 0)
		{
			setConnectionsOffsets(notExistConnections);
			hooks.changedConnections.trigger(
				commandToArray.commandPush(notExistConnections),
			);
			hooks.addConnections.trigger(notExistConnections);
		}
	};

	const deleteConnectionById = (connectionId: DiagramConnectionId): void => {
		hooks.changedConnections.trigger(
			commandToArray.commandDeleteById(connectionId),
		);
		hooks.deleteConnection.trigger(connectionId);
	};

	const deleteConnectionByBlockIdAndPortId = (blockId: DiagramBlockId, portId: DiagramPortId): void => {
		const block = state.blocks.find((stateBlock) => stateBlock.id === blockId);

		if (!block)
		{
			return;
		}

		const ports = Type.isArray(block.ports) ? block.ports : [];
		const portIdMap = new Set(
			ports.map((port) => port.id),
		);
		const removeConnectionIds = state.connections
			.filter((connection) => {
				const {
					sourceBlockId,
					sourcePortId,
					targetBlockId,
					targetPortId,
				} = connection;
				const isSource = sourceBlockId === blockId && portIdMap.has(sourcePortId);
				const isTarget = targetBlockId === blockId && portIdMap.has(targetPortId);

				return isSource || isTarget;
			})
			.map((connection) => connection.id);

		if (removeConnectionIds.length === 0)
		{
			return;
		}

		hooks.changedConnections.trigger(
			commandToArray.commandDeleteByIds(removeConnectionIds),
		);
	};

	const deleteBlockById = (blockId: DiagramBlockId): void => {
		const block = state.blocks.find((stateBlock) => stateBlock.id === blockId);

		if (!block)
		{
			return;
		}

		deleteConnectionByBlockIdAndPortId(blockId);

		hooks.changedBlocks.trigger(
			commandToArray.commandDeleteById(blockId),
		);
		hooks.deleteBlock.trigger(block);
	};

	const getBlockById = (blockId: DiagramBlockId): DiagramBlock | null => {
		return state.blocks
			.find((block) => block.id === blockId) ?? null;
	};

	const addBlock = (block: DiagramBlock): void => {
		setUnmountedPorts([block]);
		setUnmountedBlocks([block]);
		hooks.changedBlocks.trigger(
			commandToArray.commandPush(block),
		);
		hooks.addBlock.trigger(block);
	};

	const addBlocks = (blocks: DiagramBlock[]): void => {
		setUnmountedPorts(blocks);
		setUnmountedBlocks(blocks);
		hooks.changedBlocks.trigger(
			commandToArray.commandPush(blocks),
		);
		hooks.addBlocks.trigger(blocks);
	};

	const deleteBlock = (block: DiagramBlock[]): void => {
		deleteBlockById(toValue(block).id);
	};

	const deleteBlocks = (blocks: DiagramBlock[]): void => {
		const ids = toValue(blocks).map((block) => block.id);

		hooks.changedBlocks.trigger(
			commandToArray.commandDeleteByIds(ids),
		);
		hooks.deleteBlocks.trigger(blocks);
	};

	const addBlocksAndConnections = (newBlocks: DiagramBlocks[], newConnections: DiagramConnection[]): void => {
		addBlocks(newBlocks);
		addConnections(newConnections);
	};

	const updateBlockPositionByIndex = (index: number, x: number, y: number): void => {
		state.blocks[index].position.x = x;
		state.blocks[index].position.y = y;
	};

	const updateBlock = (newBlock: DiagramBlock): void => {
		const blockIndex = state.blocks.findIndex((block) => block.id === newBlock.id);

		if (blockIndex === -1)
		{
			return;
		}

		hooks.updateBlock.trigger(state.blocks[blockIndex], newBlock);
		hooks.changedBlocks.trigger(
			commandToArray.commandUpdateByIndex(blockIndex, newBlock),
		);
	};

	const transformEventToPoint = (point: { clientX: number, clientY: number }): Point => {
		let transformedX: number = Math.round(point.clientX / toValue(state.zoom));
		let transformedY: number = Math.round(point.clientY / toValue(state.zoom));

		const { top, left } = toValue(state.blockDiagramRef)?.getBoundingClientRect() ?? { top: 0, left: 0 };

		transformedX -= Math.round(left / toValue(state.zoom));
		transformedY -= Math.round(top / toValue(state.zoom));

		return { x: transformedX, y: transformedY };
	};

	const setMovingBlock = (blockId: DiagramBlockId): void => {
		state.movingBlockId = toValue(blockId);
	};

	const resetMovingBlock = (): void => {
		state.movingBlockId = null;
	};

	const updateBlockRectById = (blockId: DiagramBlockId, rect): void => {
		state.blocksRectMap[blockId] = {
			...state.blocksRectMap[blockId],
			...rect,
		};
	};

	// Release geometry retained under culling. The only path that frees a retained
	// node's coordinates when it leaves the model without producing an unmount.
	const purgeBlockGeometry = (blockId: DiagramBlockId): void => {
		delete state.portsRectMap[blockId];
		delete state.blocksRectMap[blockId];
	};

	const purgeBlockGeometryExcept = (keepBlockIds: DiagramBlockId[]): void => {
		const keep = new Set(toValue(keepBlockIds));
		const trackedIds = new Set([
			...Object.keys(state.blocksRectMap),
			...Object.keys(state.portsRectMap),
		]);

		trackedIds.forEach((blockId) => {
			if (!keep.has(blockId))
			{
				purgeBlockGeometry(blockId);
			}
		});
	};

	const setHistoryHandlers = ({
		snapshotHandler: newSnapshotHandler = null,
		revertHandler: newRevertHandler = null,
	}: HandlerOptions): void => {
		state.snapshotHandler = newSnapshotHandler || state.snapshotHandler;
		state.revertHandler = newRevertHandler || state.revertHandler;
	};

	const setPortOffsetByBlockId = (blockId: string, offsets: { x: number, y: number }): void => {
		const ports = toValue(state.portsRectMap)?.[blockId] ?? {};

		Object.entries(ports)
			.forEach(([id, portRect]) => {
				ports[id].x = portRect.x - offsets.x;
				ports[id].y = portRect.y - offsets.y;
			});
	};

	const updateBlockRect = (blockId: DiagramBlockId): void => {
		const {
			blockElMap,
			blocksRectMap,
			blocks,
		} = state;
		const rect = getCanvasRect(toValue(blockElMap).get(toValue(blockId)));

		if (!rect)
		{
			return;
		}

		blocksRectMap[toValue(blockId)] = { ...rect };

		const block = toValue(blocks).find((b) => b.id === toValue(blockId));

		updateBlock({
			...toValue(block),
			position: {
				x: rect.x,
				y: rect.y,
			},
			dimensions: {
				width: rect.width,
				height: rect.height,
			},
		});
	};

	const updatePort = (
		blockId: DigramBlockId,
		portId: DiagramPortId,
		order: number = 0,
	): void => {
		updateBlockRect(blockId);
		updatePortRect(blockId, portId);
		updatePortSegmentSizes(blockId, portId, order);
	};

	const updatePortRect = (blockId: DigramBlockId, portId: DiagramPortId): void => {
		const {
			portsElMap,
			portsRectMap,
		} = state;
		const hasBlock = toValue(portsElMap).has(blockId);
		const hasPort = hasBlock && toValue(portsElMap).get(blockId).has(portId);

		if (!hasBlock || !hasPort)
		{
			return;
		}

		const rect = getCanvasRect(portsElMap.get(blockId)?.get(portId));

		if (!rect)
		{
			return;
		}

		portsRectMap[blockId][portId].x = rect.x;
		portsRectMap[blockId][portId].y = rect.y;
		portsRectMap[blockId][portId].width = rect.width;
		portsRectMap[blockId][portId].height = rect.height;
	};

	const updatePortSegmentSizes = (
		blockId: DigramBlockId,
		portId: DiagramPortId,
		order: number,
	): void => {
		const {
			connectionOffset,
			connectionBendOffset,
			blocksRectMap,
			portsRectMap,
		} = state;
		if (!blocksRectMap[blockId] || !portsRectMap[blockId]?.[portId])
		{
			return;
		}
		const {
			x: blockX,
			y: blockY,
			width: blockWidth,
			height: blockHeight,
		} = blocksRectMap[blockId];
		const {
			x: portX,
			y: portY,
			width: portWidth,
			height: portHeight,
			position,
		} = portsRectMap[blockId][portId];

		const isLeftOrRightPosition = position === PORT_POSITION.LEFT || position === PORT_POSITION.RIGHT;
		const additionalOffset = (order + 1) * connectionOffset;
		const additionalBendOffset = (order + 1) * connectionBendOffset;
		const offset = isLeftOrRightPosition
			? Math.abs(blockY - (portY + (portHeight / 2)))
			: Math.abs(blockX - (portX + (portWidth / 2)));

		portsRectMap[blockId][portId].firstSegmentSize = additionalOffset;
		portsRectMap[blockId][portId].secondSegmentSizeWithoutOffset = isLeftOrRightPosition
			? blockHeight - offset
			: blockWidth - offset;
		portsRectMap[blockId][portId].secondSegmentSize = isLeftOrRightPosition
			? blockHeight - offset + additionalBendOffset
			: blockWidth - offset + additionalBendOffset;
	};

	const setSelectionActive = (value: boolean): void => {
		state.isSelectionActive = value;
	};

	const setSelectionWorldRect = (rect: Rect | null): void => {
		state.selectionWorldRect = rect;
	};

	const setCamera = (params): void => {
		toValue(state.canvasInstance)?.setCamera(params);
	};

	const autoScroll = useAutoScroll(state, { setCamera });

	const transformMouseEventToPoint = (event: MouseEvent): Point => {
		const {
			zoom,
			blockDiagramTop,
			blockDiagramLeft,
			transformX,
			transformY,
		} = state;

		let x = event.clientX / toValue(zoom);
		x -= toValue(blockDiagramLeft) / toValue(zoom);
		x += toValue(transformX);

		let y = event.clientY / toValue(zoom);
		y -= toValue(blockDiagramTop) / toValue(zoom);
		y += toValue(transformY);

		return { x, y };
	};

	return {
		setState,
		setConnectionsOffsets,
		setHistoryBlocksCurrentState,
		setHistoryConnectionsCurrentState,
		setUnmountedBlocks,
		blockMounted,
		setUnmountedPorts,
		portMounted,
		updateCanvasTransform,
		isExistConnection,
		addConnection,
		addConnections,
		deleteConnectionById,
		getBlockById,
		addBlock,
		addBlocks,
		deleteBlock,
		deleteBlocks,
		addBlocksAndConnections,
		updateBlockPositionByIndex,
		updateBlock,
		deleteBlockById,
		transformEventToPoint,
		setMovingBlock,
		resetMovingBlock,
		setHistoryHandlers,
		setPortOffsetByBlockId,
		purgeBlockGeometry,
		purgeBlockGeometryExcept,
		updatePort,
		updatePortRect,
		updateBlockRectById,
		updatePortSegmentSizes,
		setSelectionActive,
		setSelectionWorldRect,
		startAutoScroll: autoScroll.start,
		stopAutoScroll: autoScroll.stop,
		updateMousePosition: autoScroll.updateMousePosition,
		setCamera,
		transformMouseEventToPoint,
	};
}
