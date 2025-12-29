// ============================================
// Main frontend JS (cleaned)
// - removed embedded <script> wrapper
// - added guards for DOM elements
// - added basic ARIA + keyboard handling for dropdowns
// - added touch/pointer support for trending scroll
// ============================================

// Hero Slider
(() => {
    const heroSlides = document.querySelectorAll('.hero-slide');
    const heroDots = document.querySelectorAll('.hero-dot');
    if (!heroSlides.length || !heroDots.length) return;

    let currentSlide = 0;
    let slideInterval;

    function showSlide(index) {
        const idx = Math.max(0, Math.min(index, heroSlides.length - 1));
        heroSlides.forEach(slide => slide.classList.remove('active'));
        heroDots.forEach(dot => dot.classList.remove('active'));
        heroSlides[idx].classList.add('active');
        heroDots[idx].classList.add('active');
    }

    function nextSlide() {
        currentSlide = (currentSlide + 1) % heroSlides.length;
        showSlide(currentSlide);
    }

    function startSlider() {
        slideInterval = setInterval(nextSlide, 5000);
    }

    heroDots.forEach((dot, index) => {
        dot.addEventListener('click', () => {
            clearInterval(slideInterval);
            currentSlide = index;
            showSlide(currentSlide);
            startSlider();
        });
    });

    startSlider();
})();

// DYNAMIC PIXEL-BASED TAB SYSTEM
(function() {