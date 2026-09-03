import './blocks-queue-transition.css';
import { toValue } from 'ui.vue3';
import { useBlockDiagram, useCanvas, useHighlightedBlocks } from '../../composables';
import { matchesTransitionEl } from '../../utils';
import { ANIMATED_TYPES } from '../../constants';

type BlocksQueueTransitionSetup = {
	isAnimate: boolean;
	onEnter: () => void;
	onAfterEnter: (el: HTMLElement) => void;
	onBeforeEnter: () => void;
	onAfterLeave: (el: HTMLElement) => void;
};

// @vue/component
export const BlocksQueueTransition = {
	name: 'blocks-queue-transition',
	setup(): BlocksQueueTransitionSetup
	{
		const {
			isAnimate,
			currentAnimationItem,
			animationStep,
			hooks,
		} = useBlockDiagram();
		const canvas = useCanvas();
		const highlighted = useHighlightedBlocks();

		// Совпадает ли завершившийся переход с текущим элементом очереди. Логика
		// сопоставления вынесена в чистую matchesTransitionEl; здесь только достаём
		// текущий элемент очереди. Без сопоставления по data-id отсечение при
		// движении камеры выталкивает соседние блоки из этой же TransitionGroup, их
		// сторонние enter/leave продвинули бы чужой шаг рывками.
		function isCurrentTransitionEl(el: HTMLElement): boolean
		{
			return matchesTransitionEl(el, toValue(currentAnimationItem)?.item);
		}

		function advanceForCurrent(): void
		{
			// Продвигаем шаг через контроллер: он гарантирует ровно одно
			// продвижение на шаг (переход vs резервный таймер) по токену шага.
			animationStep.settle(animationStep.currentToken);
		}

		function onBeforeEnter(): void
		{
			hooks.blockTransitionStart.trigger(toValue(currentAnimationItem)?.item);
		}

		function onEnter(): void
		{
			// Сторонний enter-переход (отсечение при движении камеры) может сработать
			// уже ПОСЛЕ завершения очереди, когда stop() обнулил currentAnimationItem.
			// В этом случае подсвечивать и двигать камеру нечего — просто выходим.
			const current = toValue(currentAnimationItem);
			if (!current?.item)
			{
				return;
			}

			highlighted.clear();
			highlighted.add(current.item.id);
			// Наводим камеру по уже готовому объекту блока из очереди, без линейного
			// поиска по id (O(N) на каждый блок).
			canvas.goToBlock(current.item);
		}

		function onAfterEnter(el: HTMLElement): void
		{
			hooks.blockTransitionEnd.trigger(toValue(currentAnimationItem)?.item);
			// Продвигаем очередь только для перехода текущего элемента-блока. При
			// включённой оптимизации центрирование камеры выталкивает ранее
			// показанные блоки из видимой области, отсечение убирает их из этой же
			// TransitionGroup и запускает сторонние enter/leave-переходы. Тип-фильтр
			// плюс сопоставление по id отсекают их; контроллер — двойное продвижение.
			if (toValue(currentAnimationItem)?.type === ANIMATED_TYPES.BLOCK && isCurrentTransitionEl(el))
			{
				advanceForCurrent();
			}
		}

		function onAfterLeave(el: HTMLElement): void
		{
			if (toValue(currentAnimationItem)?.type === ANIMATED_TYPES.REMOVE_BLOCK && isCurrentTransitionEl(el))
			{
				advanceForCurrent();
			}
		}

		return {
			isAnimate,
			onBeforeEnter,
			onEnter,
			onAfterEnter,
			onAfterLeave,
		};
	},
	template: `
		<TransitionGroup
			v-if="isAnimate"
			name="ui-block-diagram-blocks-queue-transition"
			@before-enter="onBeforeEnter"
			@enter="onEnter"
			@after-enter="onAfterEnter"
			@after-leave="onAfterLeave"
		>
			<slot/>
		</TransitionGroup>
		<template v-else>
			<slot/>
		</template>
	`,
};
