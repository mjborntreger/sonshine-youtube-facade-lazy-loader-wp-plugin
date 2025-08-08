<?php
/**
 * Plugin Name: Sonshine YouTube Facade
 * Description: Lightweight plugin to lazy-load YouTube videos using a facade with play overlay.
 * Version: 1.0
 * Author: SonShine Roofing
 */

add_action('wp_footer', function () {
    ?>

<style>
.youtube-facade {
  position: relative;
  width: 100%;
  aspect-ratio: 16 / 9;
  background-color: #000;
  background-size: cover;
  background-position: center;
  cursor: pointer;
  border-radius: 8px;
  overflow: hidden;
}
.youtube-facade.youtube-playing {
  background-image: none !important;
}
.youtube-facade .play-button {
  position: absolute;
  top: 50%;
  left: 50%;
  width: 68px;
  height: 48px;
  background: url("https://img.icons8.com/ios-filled/100/ffffff/play--v1.png") no-repeat center center;
  background-size: 100%;
  transform: translate(-50%, -50%);
  pointer-events: none;
  opacity: 0.85;
  transition: opacity 0.2s ease;
}
.youtube-facade:hover .play-button {
  opacity: 1;
}
</style>

<script>
function initYouTubeLazyLoad() {
  const observer = new IntersectionObserver((entries, obs) => {
    entries.forEach(entry => {
      if (!entry.isIntersecting) return;

      const facade = entry.target;
      const videoId = facade.dataset.id;
      if (!videoId) return;

      facade.style.backgroundImage = `url(https://img.youtube.com/vi/${videoId}/hqdefault.jpg)`;

      facade.addEventListener("click", () => {
        facade.classList.add("youtube-playing");
        const iframe = document.createElement("iframe");
        iframe.src = `https://www.youtube.com/embed/${videoId}?autoplay=1`;
        iframe.title = "YouTube video player";
        iframe.allow = "autoplay; encrypted-media";
        iframe.allowFullscreen = true;
        iframe.loading = "lazy";
        Object.assign(iframe.style, {
          width: "100%",
          height: "100%",
          position: "absolute",
          top: 0,
          left: 0,
          border: "0"
        });
        facade.innerHTML = "";
        facade.appendChild(iframe);
      });

      obs.unobserve(facade);
    });
  });

  document.querySelectorAll(".youtube-facade:not(.youtube-processed)").forEach(el => {
    el.classList.add("youtube-processed");
    observer.observe(el);
  });
}

// Run on DOMContentLoaded
document.addEventListener("DOMContentLoaded", initYouTubeLazyLoad);
</script>

<?php
}, 99);
