// Tariq Mohammed Areesh: 2237498 | Majd Ahmed Al-farasani: 2237426

// Client-side image gallery functionality
document.addEventListener("DOMContentLoaded", function () {
    var mainImage = document.getElementById("gallery-main-image");
    var thumbs = document.querySelectorAll(".gallery-grid img");

    if (!mainImage || thumbs.length === 0) return;

    thumbs.forEach(function (thumb) {
        thumb.addEventListener("click", function () {
            var largeSrc = thumb.getAttribute("data-large") || thumb.getAttribute("src");
            mainImage.setAttribute("src", largeSrc);
            mainImage.setAttribute("alt", thumb.getAttribute("alt") || "ESP32 project image");
        });
    });
});
