// ============================================
// Clean Main Frontend JavaScript
// - Hero Slider
// - Dynamic Pixel-Based Tab System with "More" Dropdown
// - Smooth Horizontal Scroll for Trending Section (mouse + touch)
// - Newsletter Form with Inline Feedback
// ============================================

// Hero Slider
(() => {
    const heroSlides = document.querySelectorAll('.hero-slide');
    const heroDots = document.querySelectorAll('.hero-dot');
    if (!heroSlides.length || !heroDots.length) return;

    let currentSlide = 0;
    let slideInterval;

    const showSlide = (index) => {
        const idx = Math.max(0, Math.min(index, heroSlides.length - 1));
        heroSlides.forEach(slide => slide.classList.remove('active'));
        heroDots.forEach(dot => dot.classList.remove('active'));
        heroSlides[idx].classList.add('active');
        heroDots[idx].classList.add('active');
    };

    const nextSlide = () => {
        currentSlide = (currentSlide + 1) % heroSlides.length;
        showSlide(currentSlide);
    };

    const startSlider = () => {
        clearInterval(slideInterval);
        slideInterval = setInterval(nextSlide, 5000);
    };

    heroDots.forEach((dot, index) => {
        dot.addEventListener('click', () => {
            currentSlide = index;
            showSlide(currentSlide);
            startSlider();
        });
    });

    startSlider();
})();

// Dynamic Tab System with Responsive "More" Dropdown
(() => {
    const categoryHeader = document.querySelector('.category-header');
    const categoryTabs = document.querySelector('.category-tabs');
    const contentWrappers = document.querySelectorAll('.content-wrapper');

    if (!categoryHeader || !categoryTabs || !contentWrappers.length) return;

    const allCategories = [
        { id: 'all', label: 'All' },
        { id: 'health', label: 'Health & Fitness' },
        { id: 'travel', label: 'Travel' },
        { id: 'tech', label: 'Technology' },
        { id: 'business', label: 'Business' },
        { id: 'sports', label: 'Sports' },
        { id: 'entertainment', label: 'Entertainment' },
        { id: 'lifestyle', label: 'Lifestyle' },
        { id: 'science', label: 'Science' },
        { id: 'food', label: 'Food & Recipes' }
    ];

    let activeCategory = 'all';
    let tabWidths = {};
    let lastVisibleCount = -1;

    // Hidden container to measure tab widths accurately (including after fonts load)
    const measureContainer = document.createElement('div');
    measureContainer.style.cssText = 'position:absolute;visibility:hidden;white-space:nowrap;top:-9999px;left:-9999px;';
    document.body.appendChild(measureContainer);

    const measureTabWidths = () => {
        allCategories.forEach(cat => {
            const btn = document.createElement('button');
            btn.className = 'tab-button';
            btn.textContent = cat.label;
            btn.style.cssText = 'padding:18px 20px;font-size:14px;font-weight:500;font-family:DM Sans,sans-serif;';
            measureContainer.appendChild(btn);
            tabWidths[cat.id] = btn.offsetWidth;
            measureContainer.removeChild(btn);
        });

        // Measure "More ▼" button
        const moreBtn = document.createElement('button');
        moreBtn.className = 'tab-button';
        moreBtn.style.cssText = 'padding:18px 20px;font-size:14px;font-weight:500;font-family:DM Sans,sans-serif;';
        moreBtn.textContent = 'More ';
        const arrow = document.createElement('span');
        arrow.style.fontSize = '10px';
        arrow.textContent = '▼';
        moreBtn.appendChild(arrow);
        measureContainer.appendChild(moreBtn);
        tabWidths['more'] = moreBtn.offsetWidth + 10; // small buffer
        measureContainer.removeChild(moreBtn);
    };

    const calculateVisibleTabs = () => {
        const dontMissLabel = document.querySelector('.dont-miss-label');
        const labelWidth = dontMissLabel ? dontMissLabel.offsetWidth : 150;
        const containerWidth = categoryHeader.offsetWidth;
        const availableWidth = containerWidth - labelWidth - 40; // padding/margin buffer

        let usedWidth = tabWidths['more'] || 0;
        const visibleTabs = [];
        const hiddenTabs = [];

        allCategories.forEach(cat => {
            const width = tabWidths[cat.id];
            if (usedWidth + width <= availableWidth) {
                visibleTabs.push(cat);
                usedWidth += width;
            } else {
                hiddenTabs.push(cat);
            }
        });

        return { visibleTabs, hiddenTabs };
    };

    const buildTabs = (forceRebuild = false) => {
        const { visibleTabs, hiddenTabs } = calculateVisibleTabs();

        if (!forceRebuild && visibleTabs.length === lastVisibleCount) return;
        lastVisibleCount = visibleTabs.length;

        // Prevent layout shift during rebuild
        const headerHeight = categoryHeader.offsetHeight;
        categoryHeader.style.minHeight = `${headerHeight}px`;

        categoryTabs.innerHTML = '';

        // Visible tabs
        visibleTabs.forEach(cat => {
            const btn = document.createElement('button');
            btn.className = `tab-button${activeCategory === cat.id ? ' active' : ''}`;
            btn.dataset.category = cat.id;
            btn.textContent = cat.label;
            btn.addEventListener('click', () => switchCategory(cat.id));
            categoryTabs.appendChild(btn);
        });

        // "More" dropdown if needed
        if (hiddenTabs.length > 0) {
            const dropdown = document.createElement('div');
            dropdown.className = 'more-dropdown';
            dropdown.id = 'moreDropdownDynamic';

            const moreBtn = document.createElement('button');
            moreBtn.className = 'tab-button more-button';
            moreBtn.textContent = 'More ';
            const arrow = document.createElement('span');
            arrow.className = 'dropdown-arrow';
            arrow.textContent = '▼';
            moreBtn.appendChild(arrow);

            const menu = document.createElement('div');
            menu.className = 'dropdown-menu';

            hiddenTabs.forEach(cat => {
                const item = document.createElement('button');
                item.dataset.category = cat.id;
                item.textContent = cat.label;
                if (activeCategory === cat.id) item.classList.add('active');
                item.addEventListener('click', (e) => {
                    e.stopPropagation();
                    switchCategory(cat.id);
                    dropdown.classList.remove('open');
                });
                menu.appendChild(item);
            });

            // Accessibility & keyboard support
            moreBtn.setAttribute('aria-haspopup', 'true');
            moreBtn.setAttribute('aria-expanded', 'false');
            moreBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                const isOpen = dropdown.classList.toggle('open');
                moreBtn.setAttribute('aria-expanded', isOpen);
                if (isOpen) menu.querySelector('button')?.focus();
            });

            moreBtn.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    moreBtn.click();
                } else if (e.key === 'Escape') {
                    dropdown.classList.remove('open');
                    moreBtn.setAttribute('aria-expanded', 'false');
                    moreBtn.focus();
                }
            });

            dropdown.appendChild(moreBtn);
            dropdown.appendChild(menu);
            categoryTabs.appendChild(dropdown);
        }

        // Clean up min-height after rebuild
        requestAnimationFrame(() => {
            categoryHeader.style.minHeight = '';
        });
    };

    const switchCategory = (categoryId) => {
        if (activeCategory === categoryId) return;
        activeCategory = categoryId;

        // Update content visibility
        contentWrappers.forEach(wrapper => {
            wrapper.classList.toggle('active', wrapper.dataset.content === categoryId);
        });

        // Update tab active states
        categoryTabs.querySelectorAll('.tab-button:not(.more-button)').forEach(btn => {
            btn.classList.toggle('active', btn.dataset.category === categoryId);
        });

        const dropdownItems = categoryTabs.querySelectorAll('.dropdown-menu button');
        const moreBtn = categoryTabs.querySelector('.more-button');
        let activeInDropdown = false;

        dropdownItems.forEach(item => {
            const isActive = item.dataset.category === categoryId;
            item.classList.toggle('active', isActive);
            if (isActive) activeInDropdown = true;
        });

        if (moreBtn) moreBtn.classList.toggle('active', activeInDropdown);
    };

    // Close dropdown when clicking outside
    document.addEventListener('click', (e) => {
        const dropdown = document.getElementById('moreDropdownDynamic');
        if (dropdown && !dropdown.contains(e.target)) {
            dropdown.classList.remove('open');
            const moreBtn = dropdown.querySelector('.more-button');
            if (moreBtn) moreBtn.setAttribute('aria-expanded', 'false');
        }
    });

    // Resize handling with debounce
    const debounce = (func, wait) => {
        let timeout;
        return (...args) => {
            clearTimeout(timeout);
            timeout = setTimeout(() => func(...args), wait);
        };
    };

    const handleResize = debounce(() => {
        buildTabs(false);
    }, 100);

    window.addEventListener('resize', handleResize);

    // Optional: Use ResizeObserver for more precise container changes
    if (typeof ResizeObserver !== 'undefined') {
        const observer = new ResizeObserver(() => buildTabs(false));
        observer.observe(categoryHeader);
    }

    // Initial setup
    const init = () => {
        measureTabWidths();
        switchCategory('all'); // Ensure initial content is shown
        buildTabs(true);
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    // Re-measure and rebuild after fonts likely loaded
    setTimeout(init, 150);
})();

// Trending Section Horizontal Scroll (Mouse + Touch + Pointer)
(() => {
    const trendingScroll = document.querySelector('.trending-scroll');
    if (!trendingScroll) return;

    let isDown = false;
    let startX = 0;
    let scrollLeftStart = 0;

    const startDrag = (clientX) => {
        isDown = true;
        trendingScroll.style.cursor = 'grabbing';
        startX = clientX - trendingScroll.offsetLeft;
        scrollLeftStart = trendingScroll.scrollLeft;
    };

    const endDrag = () => {
        isDown = false;
        trendingScroll.style.cursor = 'grab';
    };

    const moveDrag = (clientX) => {
        if (!isDown) return;
        const x = clientX - trendingScroll.offsetLeft;
        const walk = (x - startX) * 2; // scroll speed
        trendingScroll.scrollLeft = scrollLeftStart - walk;
    };

    // Mouse events
    trendingScroll.addEventListener('mousedown', e => startDrag(e.pageX));
    trendingScroll.addEventListener('mousemove', e => moveDrag(e.pageX));
    trendingScroll.addEventListener('mouseup', endDrag);
    trendingScroll.addEventListener('mouseleave', endDrag);

    // Touch events
    trendingScroll.addEventListener('touchstart', e => startDrag(e.touches[0].clientX), { passive: true });
    trendingScroll.addEventListener('touchmove', e => {
        // Prevent the page from vertically scrolling while the user is dragging horizontally
        if (isDown && e.cancelable) e.preventDefault();
        moveDrag(e.touches[0].clientX);
    }, { passive: false });
    trendingScroll.addEventListener('touchend', endDrag);

    // Pointer events (for broader compatibility)
    trendingScroll.addEventListener('pointerdown', e => startDrag(e.clientX));
    trendingScroll.addEventListener('pointermove', e => moveDrag(e.clientX));
    trendingScroll.addEventListener('pointerup', endDrag);
})();

// Newsletter Form with Inline Feedback
(() => {
    const form = document.querySelector('.newsletter-form');
    if (!form) return;

    let feedback = form.querySelector('.newsletter-feedback');
    if (!feedback) {
        feedback = document.createElement('div');
        feedback.className = 'newsletter-feedback';
        feedback.style.cssText = 'margin-top:8px;display:none;';
        form.appendChild(feedback);
    }

    const showFeedback = (message, type = 'success') => {
        feedback.textContent = message;
        feedback.classList.remove('success', 'error');
        feedback.classList.add(type);
        feedback.style.display = 'block';
        feedback.setAttribute('role', 'status');
        feedback.setAttribute('aria-live', 'polite');

        clearTimeout(feedback._timer);
        feedback._timer = setTimeout(() => {
            feedback.style.display = 'none';
            feedback.textContent = '';
        }, 4000);
    };

    form.addEventListener('submit', (e) => {
        e.preventDefault();
        const input = form.querySelector('.newsletter-input');
        if (!input) return;

        const email = input.value.trim();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (email && emailRegex.test(email)) {
            showFeedback('Thank you for subscribing! Check your email for confirmation.', 'success');
            input.value = '';
        } else {
            showFeedback('Please enter a valid email address.', 'error');
        }
    });
})();