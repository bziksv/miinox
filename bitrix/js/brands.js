document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll(".brands-track").forEach((track) => {
    const baseContent = track.innerHTML;
    let unit = baseContent;

    // Всегда две одинаковые половины — для seamless translateX(-50%)
    const fill = () => {
      track.innerHTML = unit + unit;
      return track.scrollWidth / 2;
    };

    while (fill() < window.innerWidth) {
      unit += baseContent;
    }

    track.style.width = track.scrollWidth + "px";
    track.style.animationPlayState = "paused";
    track.style.opacity = "0";

    setTimeout(() => {
      track.style.animationPlayState = "running";
      track.style.transition = "opacity 0.6s ease";
      track.style.opacity = "1";
    }, 300);
  });
});
