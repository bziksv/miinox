document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll('.brands-track').forEach(track => {
    const baseContent = track.innerHTML;
    let totalWidth = track.scrollWidth;

    // Пока не заполнили ширину экрана — добавляем ещё копии
    while (totalWidth < window.innerWidth * 2) {
      track.innerHTML += baseContent;
      totalWidth = track.scrollWidth;
    }

    // Ставим ширину трека равной полной длине
    track.style.width = totalWidth + 'px';

    // Скрываем до готовности
    track.style.animationPlayState = 'paused';
    track.style.opacity = '0';

    // Через короткое время включаем движение
    setTimeout(() => {
      track.style.animationPlayState = 'running';
      track.style.transition = 'opacity 0.6s ease';
      track.style.opacity = '1';
    }, 300);
  });
});