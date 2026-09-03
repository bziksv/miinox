import { Event } from 'main.core';
import { toValue, ref, computed } from 'ui.vue3';
import { useBlockDiagram } from './block-diagram';
import { CURSOR_TYPES } from '../constants';

type ResizeHandler = (event: MouseEvent) => void;

// eslint-disable-next-line max-lines-per-function
export function useResizableBlock(options): {...}
{
	const {
		cursorType,
		resizingBlock,
		blockDiagramTop,
		blockDiagramLeft,
		transformX,
		transformY,
		zoom,
		updateBlock,
		startAutoScroll,
		stopAutoScroll,
		updateMousePosition,
	} = useBlockDiagram();
	const {
		block,
		minWidth,
		minHeight,
		leftSideRef,
		topSideRef,
		rightSideRef,
		bottomSideRef,
		leftTopCornerRef,
		rightTopCornerRef,
		rightBottomCornerRef,
		leftBottomCornerRef,
	} = options;

	const isResize = ref(false);
	let prevBlockX = 0;
	let prevBlockY = 0;
	let prevBlockWidth = 0;
	let prevBlockHeight = 0;
	let activeResizeHandlers: ResizeHandler[] = [];
	let lastResizeEvent: MouseEvent | null = null;
	let isAutoScrollStarted = false;

	const sizeBlockStyle = computed(() => {
		if (toValue(isResize))
		{
			const { position, dimensions } = toValue(resizingBlock);

			// Staged position has to win over blockPositionStyle: the model block keeps its
			// own position until mouseup, so left/top resize would not move the block.
			return {
				top: `${position.y}px`,
				left: `${position.x}px`,
				width: `${dimensions.width}px`,
				height: `${dimensions.height}px`,
				cursor: toValue(cursorType),
			};
		}

		return {
			width: `${toValue(block).dimensions.width}px`,
			height: `${toValue(block).dimensions.height}px`,
			cursor: toValue(cursorType),
		};
	});

	// While resizing the model block keeps its old size, so slot content has to read the
	// staged geometry to stay in step with the frame it lives in.
	const blockDimensions = computed(() => {
		const staged = toValue(resizingBlock);

		return toValue(isResize) && staged !== null
			? staged.dimensions
			: toValue(block).dimensions;
	});

	function isGeometryStaged(): boolean
	{
		const staged = toValue(resizingBlock);

		if (staged === null)
		{
			return false;
		}

		const { position, dimensions } = toValue(block);

		return staged.position.x !== position.x
			|| staged.position.y !== position.y
			|| staged.dimensions.width !== dimensions.width
			|| staged.dimensions.height !== dimensions.height;
	}

	function updateResizableBlock(): void
	{
		updateBlock({
			...toValue(block),
			position: {
				x: toValue(resizingBlock).position.x,
				y: toValue(resizingBlock).position.y,
			},
			dimensions: {
				width: toValue(resizingBlock).dimensions.width,
				height: toValue(resizingBlock).dimensions.height,
			},
		});
	}

	function onMounted(): void
	{
		Event.bind(toValue(rightSideRef), 'mousedown', onMouseDownRightSide);
		Event.bind(toValue(bottomSideRef), 'mousedown', onMouseDownBottomSide);
		Event.bind(toValue(leftSideRef), 'mousedown', onMouseDownLeftSide);
		Event.bind(toValue(topSideRef), 'mousedown', onMouseDownTopSide);

		Event.bind(toValue(rightTopCornerRef), 'mousedown', onMouseDownRightTopCorner);
		Event.bind(toValue(rightBottomCornerRef), 'mousedown', onMouseDownRightBottomCorner);
		Event.bind(toValue(leftTopCornerRef), 'mousedown', onMouseDownLeftTopCorner);
		Event.bind(toValue(leftBottomCornerRef), 'mousedown', onMouseDownLeftBottomCorner);
	}

	function onUnmounted(): void
	{
		Event.unbind(toValue(rightSideRef), 'mousedown', onMouseDownRightSide);
		Event.unbind(toValue(bottomSideRef), 'mousedown', onMouseDownBottomSide);
		Event.unbind(toValue(leftSideRef), 'mousedown', onMouseDownLeftSide);
		Event.unbind(toValue(topSideRef), 'mousedown', onMouseDownTopSide);

		Event.unbind(toValue(rightTopCornerRef), 'mousedown', onMouseDownRightTopCorner);
		Event.unbind(toValue(rightBottomCornerRef), 'mousedown', onMouseDownRightBottomCorner);
		Event.unbind(toValue(leftTopCornerRef), 'mousedown', onMouseDownLeftTopCorner);
		Event.unbind(toValue(leftBottomCornerRef), 'mousedown', onMouseDownLeftBottomCorner);

		// Autoscroll and the staged geometry belong to the whole diagram, so only the instance
		// that owns the gesture may wind it down: under render optimization neighbour blocks
		// are culled and unmounted exactly while the camera pans for this gesture.
		if (!toValue(isResize))
		{
			return;
		}

		// The block is gone, so the staged geometry is dropped without reaching the model.
		teardownGesture();
	}

	function teardownGesture(): void
	{
		stopAutoScroll();
		Event.unbind(document, 'mousemove', onMouseMove);
		Event.unbind(document, 'mouseup', endResize);
		cursorType.value = 'default';
		isResize.value = false;
		resizingBlock.value = null;
		activeResizeHandlers = [];
		lastResizeEvent = null;
		isAutoScrollStarted = false;
	}

	function startResize(event: MouseEvent, curType: string, resizeHandlers: ResizeHandler[]): void
	{
		event.stopPropagation();
		cursorType.value = curType;
		// Geometry is staged in its own objects. Sharing position/dimensions with the model
		// block turns every resize step into a deep mutation of props.blocks, which makes
		// useWatchProps rebuild the intersections index; under render optimization that
		// unmounts the block mid-gesture and onUnmounted then tears the gesture down.
		resizingBlock.value = {
			...toValue(block),
			position: { ...toValue(block).position },
			dimensions: { ...toValue(block).dimensions },
		};
		prevBlockX = toValue(block).position.x;
		prevBlockY = toValue(block).position.y;
		prevBlockWidth = toValue(block).dimensions.width;
		prevBlockHeight = toValue(block).dimensions.height;
		isResize.value = true;
		activeResizeHandlers = resizeHandlers;

		Event.bind(document, 'mousemove', onMouseMove);
		Event.bind(document, 'mouseup', endResize);
	}

	function endResize(event: MouseEvent): void
	{
		event.stopPropagation();

		// A click on a handle without a move stages nothing: emitting the command anyway would
		// mark the document dirty and wake autosave for an unchanged block.
		if (isGeometryStaged())
		{
			// The staged geometry is dropped as soon as the command is out, so the consumer has
			// to apply update:blocks synchronously: until the model catches up the block is drawn
			// with its pre-gesture position and size.
			updateResizableBlock();
		}

		teardownGesture();
	}

	function applyResize(): void
	{
		if (!toValue(isResize) || !lastResizeEvent)
		{
			return;
		}

		for (const resize of activeResizeHandlers)
		{
			resize(lastResizeEvent);
		}
	}

	function onMouseMove(event: MouseEvent): void
	{
		event.stopPropagation();

		if (!toValue(isResize))
		{
			return;
		}

		lastResizeEvent = event;

		if (isAutoScrollStarted)
		{
			updateMousePosition(event);
		}
		else
		{
			// Autoscroll moves the camera, so the same cursor point maps to a new world point.
			// It waits for the first move on purpose: started on mousedown it would resize a
			// block whose handle sits in the edge threshold on a plain click, with no move at
			// all. start() takes the cursor position from the event, so no extra update here.
			isAutoScrollStarted = true;
			startAutoScroll(event, applyResize);
		}

		applyResize();
	}

	function resizeTopSide(event: MouseEvent): void
	{
		let newY = event.clientY / toValue(zoom);
		newY += toValue(transformY);
		newY -= toValue(blockDiagramTop) / toValue(zoom);

		let newHeight = event.clientY / toValue(zoom);
		newHeight += toValue(transformY);
		newHeight -= toValue(blockDiagramTop) / toValue(zoom);
		newHeight -= prevBlockY + prevBlockHeight;
		newHeight = Math.abs(newHeight);

		const fixedPositionY = prevBlockY + prevBlockHeight - toValue(minHeight);

		resizingBlock.value.position.y = newHeight < toValue(minHeight) || newY >= fixedPositionY
			? fixedPositionY
			: newY;

		resizingBlock.value.dimensions.height = newHeight < toValue(minHeight) || newY >= fixedPositionY
			? toValue(minHeight)
			: newHeight;
	}

	function resizeRightSide(event: MouseEvent): void
	{
		let cursorX = event.clientX / toValue(zoom);
		cursorX += toValue(transformX);
		cursorX -= toValue(blockDiagramLeft) / toValue(zoom);

		let newWidth = prevBlockX;
		newWidth -= event.clientX / toValue(zoom);
		newWidth -= toValue(transformX);
		newWidth -= toValue(blockDiagramLeft) / toValue(zoom);
		newWidth = Math.abs(newWidth);

		resizingBlock.value.dimensions.width = newWidth < toValue(minWidth) || cursorX <= prevBlockX
			? toValue(minWidth)
			: newWidth;
	}

	function resizeBottomSide(event: MouseEvent): void
	{
		let cursorX = event.clientY / toValue(zoom);
		cursorX += toValue(transformY);
		cursorX -= toValue(blockDiagramTop) / toValue(zoom);

		let newHeight = event.clientY / toValue(zoom);
		newHeight -= prevBlockY;
		newHeight += toValue(transformY);
		newHeight -= toValue(blockDiagramTop) / toValue(zoom);
		newHeight = Math.abs(newHeight);

		resizingBlock.value.dimensions.height = newHeight < toValue(minHeight) || cursorX <= prevBlockY
			? toValue(minHeight)
			: newHeight;
	}

	function resizeLeftSide(event: MouseEvent): void
	{
		let newX = event.clientX / toValue(zoom);
		newX += toValue(transformX);
		newX -= toValue(blockDiagramLeft) / toValue(zoom);

		let newWidth = event.clientX / toValue(zoom);
		newWidth += toValue(transformX);
		newWidth -= toValue(blockDiagramLeft) / toValue(zoom);
		newWidth -= (prevBlockX + prevBlockWidth);
		newWidth = Math.abs(newWidth);

		const fixedPositionX = prevBlockX + prevBlockWidth - toValue(minWidth);

		resizingBlock.value.position.x = newWidth < toValue(minWidth) || newX >= fixedPositionX
			? fixedPositionX
			: newX;

		resizingBlock.value.dimensions.width = newWidth < toValue(minWidth) || newX >= fixedPositionX
			? toValue(minWidth)
			: newWidth;
	}

	function onMouseDownRightSide(event: MouseEvent): void
	{
		startResize(event, CURSOR_TYPES.EW_RESIZE, [resizeRightSide]);
	}

	function onMouseDownBottomSide(event: MouseEvent): void
	{
		startResize(event, CURSOR_TYPES.NS_RESIZE, [resizeBottomSide]);
	}

	function onMouseDownLeftSide(event: MouseEvent): void
	{
		startResize(event, CURSOR_TYPES.EW_RESIZE, [resizeLeftSide]);
	}

	function onMouseDownTopSide(event: MouseEvent): void
	{
		startResize(event, CURSOR_TYPES.NS_RESIZE, [resizeTopSide]);
	}

	function onMouseDownRightBottomCorner(event: MouseEvent): void
	{
		startResize(event, CURSOR_TYPES.NWSE_RESIZE, [resizeRightSide, resizeBottomSide]);
	}

	function onMouseDownRightTopCorner(event: MouseEvent): void
	{
		startResize(event, CURSOR_TYPES.NESW_RESIZE, [resizeTopSide, resizeRightSide]);
	}

	function onMouseDownLeftBottomCorner(event: MouseEvent): void
	{
		startResize(event, CURSOR_TYPES.NESW_RESIZE, [resizeLeftSide, resizeBottomSide]);
	}

	function onMouseDownLeftTopCorner(event: MouseEvent): void
	{
		startResize(event, CURSOR_TYPES.NWSE_RESIZE, [resizeLeftSide, resizeTopSide]);
	}

	return {
		isResize,
		sizeBlockStyle,
		blockDimensions,
		onMounted,
		onUnmounted,
	};
}
