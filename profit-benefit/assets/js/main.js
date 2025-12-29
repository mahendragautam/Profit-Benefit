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
    const categoryHeader = document.querySelector('.category-header');
    const categoryTabs = document.querySelector('.category-tabs');
    const contentWrappers = document.querySelectorAll('.content-wrapper');
    
    // All tab categories in order
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
    let isInitialized = false;
    let lastVisibleCount = -1; // Track visible tab count to avoid unnecessary rebuilds
    
    // Create a hidden measurement container
    const measureContainer = document.createElement('div');
    measureContainer.style.cssText = 'position:absolute;visibility:hidden;white-space:nowrap;top:-9999px;left:-9999px;';
    document.body.appendChild(measureContainer);
    
    // Measure all tab widths once
    function measureTabWidths() {
        allCategories.forEach(cat => {
            const tempBtn = document.createElement('button');
            tempBtn.className = 'tab-button';
            tempBtn.textContent = cat.label;
            tempBtn.style.cssText = 'padding:18px 20px;font-size:14px;font-weight:500;font-family:DM Sans,sans-serif;';
            measureContainer.appendChild(tempBtn);
            tabWidths[cat.id] = tempBtn.offsetWidth;
            measureContainer.removeChild(tempBtn);
        });
        
        // Measure "More" button width
        const moreBtn = document.createElement('button');
        moreBtn.className = 'tab-button';
        moreBtn.style.cssText = 'padding:18px 20px;font-size:14px;font-weight:500;font-family:DM Sans,sans-serif;';
        moreBtn.textContent = 'More ';
        const moreSpan = document.createElement('span');
        moreSpan.style.fontSize = '10px';
        moreSpan.textContent = '▼';
        moreBtn.appendChild(moreSpan);
        measureContainer.appendChild(moreBtn);
        tabWidths['more'] = moreBtn.offsetWidth + 10;
        measureContainer.removeChild(moreBtn);
    }
    
    // Calculate which tabs should be visible
    function calculateVisibleTabs() {
        const dontMissLabel = document.querySelector('.dont-miss-label');
        const labelWidth = dontMissLabel ? dontMissLabel.offsetWidth : 150;
        const containerWidth = categoryHeader.offsetWidth;
        const availableWidth = containerWidth - labelWidth - 40;
        
        let usedWidth = tabWidths['more'];
        const visibleTabs = [];
        const hiddenTabs = [];
        
        for (let i = 0; i < allCategories.length; i++) {
            const cat = allCategories[i];
            const tabWidth = tabWidths[cat.id];
            
            if (usedWidth + tabWidth <= availableWidth) {
                visibleTabs.push(cat);
                usedWidth += tabWidth;
            } else {
                hiddenTabs.push(cat);
            }
        }
        
        return { visibleTabs, hiddenTabs };
    }
    
    // Build tabs dynamically based on available width
    function buildTabs(forceRebuild = false) {
        const { visibleTabs, hiddenTabs } = calculateVisibleTabs();
        
        // Only rebuild if the number of visible tabs changed (prevents scroll jumping)
        if (!forceRebuild && visibleTabs.length === lastVisibleCount) {
            return;
        }
        lastVisibleCount = visibleTabs.length;
        
        // No scroll tracking - just rebuild tabs
        const headerHeight = categoryHeader.offsetHeight;
        categoryHeader.style.minHeight = headerHeight + 'px';
        
        // Clear and rebuild tabs container
        categoryTabs.innerHTML = '';
        
        // Add visible tabs
        visibleTabs.forEach(cat => {
            const btn = document.createElement('button');
            btn.className = 'tab-button' + (activeCategory === cat.id ? ' active' : '');
            btn.dataset.category = cat.id;
            btn.textContent = cat.label;
            btn.addEventListener('click', () => switchCategory(cat.id));
            categoryTabs.appendChild(btn);
        });
        
        // Add More dropdown if there are hidden tabs
        if (hiddenTabs.length > 0) {
            const dropdown = document.createElement('div');
            dropdown.className = 'more-dropdown';
            dropdown.id = 'moreDropdownDynamic';
            
            const moreBtn = document.createElement('button');
            moreBtn.className = 'tab-button more-button';
            const activeInHidden = hiddenTabs.some(cat => cat.id === activeCategory);
            if (activeInHidden) {
                moreBtn.classList.add('active');
            }
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
                if (activeCategory === cat.id) {
                    item.classList.add('active');
                }
                item.addEventListener('click', (e) => {
                    e.stopPropagation();
                    switchCategory(cat.id);
                    dropdown.classList.remove('open');
                });
                menu.appendChild(item);
            });
            
            // Accessibility: ARIA + keyboard
            moreBtn.setAttribute('aria-haspopup', 'true');
            moreBtn.setAttribute('aria-expanded', 'false');
            moreBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                const open = dropdown.classList.toggle('open');
                moreBtn.setAttribute('aria-expanded', open ? 'true' : 'false');
                if (open) {
                    // focus first menu item
                    const first = menu.querySelector('button');
                    if (first) first.focus();
                }
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
            menu.setAttribute('role', 'menu');
            dropdown.appendChild(menu);
            categoryTabs.appendChild(dropdown);
        }
        
        // Close dropdown when clicking outside
        document.addEventListener('click', (e) => {
            const dropdown = document.getElementById('moreDropdownDynamic');
            if (dropdown && !dropdown.contains(e.target)) {
                dropdown.classList.remove('open');
                const mb = dropdown.querySelector('.more-button');
                if (mb) mb.setAttribute('aria-expanded', 'false');
            }
        });
        
        // Initialize when DOM is ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', init);
        } else {
            init();
        }
        
        // Also initialize after a short delay to ensure fonts are loaded
        setTimeout(init, 100);
        
    })();

    // Smooth Scroll for Trending Section (mouse + touch + pointer)
    (function() {
        const trendingScroll = document.querySelector('.trending-scroll');
        if (!trendingScroll) return;

        let isDown = false;
        let startX = 0;
        let scrollLeft = 0;

        function startDrag(x) {
            isDown = true;
            trendingScroll.style.cursor = 'grabbing';
            startX = x - trendingScroll.offsetLeft;
            scrollLeft = trendingScroll.scrollLeft;
        }

        function endDrag() {
            isDown = false;
            trendingScroll.style.cursor = 'grab';
        }

        function moveDrag(x) {
            if (!isDown) return;
            const currentX = x - trendingScroll.offsetLeft;
            const walk = (currentX - startX) * 2;
            trendingScroll.scrollLeft = scrollLeft - walk;
        }

        trendingScroll.addEventListener('mousedown', (e) => startDrag(e.pageX));
        trendingScroll.addEventListener('mouseup', endDrag);
        trendingScroll.addEventListener('mouseleave', endDrag);
        trendingScroll.addEventListener('mousemove', (e) => moveDrag(e.pageX));

        // Touch support
        trendingScroll.addEventListener('touchstart', (e) => startDrag(e.touches[0].clientX));
        trendingScroll.addEventListener('touchend', endDrag);
        trendingScroll.addEventListener('touchmove', (e) => moveDrag(e.touches[0].clientX));

        // Pointer support (if needed)
        trendingScroll.addEventListener('pointerdown', (e) => startDrag(e.clientX));
        trendingScroll.addEventListener('pointerup', endDrag);
        trendingScroll.addEventListener('pointermove', (e) => moveDrag(e.clientX));
    })();

        // Newsletter Form Handler (inline feedback instead of alert)
        (function() {
            const newsletterForm = document.querySelector('.newsletter-form');
            if (!newsletterForm) return;

            // Ensure a feedback container exists
            let feedback = newsletterForm.querySelector('.newsletter-feedback');
            if (!feedback) {
                feedback = document.createElement('div');
                feedback.className = 'newsletter-feedback';
                feedback.style.display = 'none';
                feedback.style.marginTop = '8px';
                newsletterForm.appendChild(feedback);
            }

            function showFeedback(message, type = 'success') {
                feedback.textContent = message;
                feedback.classList.remove('success', 'error');
                feedback.classList.add(type);
                feedback.setAttribute('role', 'status');
                feedback.setAttribute('aria-live', 'polite');
                feedback.style.display = 'block';
                clearTimeout(feedback._timer);
                feedback._timer = setTimeout(() => {
                    feedback.style.display = 'none';
                    feedback.textContent = '';
                }, 4000);
            }

            newsletterForm.addEventListener('submit', (e) => {
                e.preventDefault();
                const input = newsletterForm.querySelector('.newsletter-input');
                if (!input) return;
                const email = input.value.trim();

                if (email && /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                    showFeedback('Thank you for subscribing! Check your email for confirmation.', 'success');
                    input.value = '';
                } else {
                    showFeedback('Please enter a valid email address.', 'error');
                }
            });
        })();

        // ============================================
        // Smooth Scroll for Trending Section
        // ============================================
        const trendingScroll = document.querySelector('.trending-scroll');
        let isDown = false;
        let startX;
        let scrollLeft;

        if (trendingScroll) {
            trendingScroll.addEventListener('mousedown', (e) => {
                isDown = true;
                trendingScroll.style.cursor = 'grabbing';
                startX = e.pageX - trendingScroll.offsetLeft;
                scrollLeft = trendingScroll.scrollLeft;
            });

            trendingScroll.addEventListener('mouseleave', () => {
                isDown = false;
                trendingScroll.style.cursor = 'grab';
            });

            trendingScroll.addEventListener('mouseup', () => {
                isDown = false;
                trendingScroll.style.cursor = 'grab';
            });

            trendingScroll.addEventListener('mousemove', (e) => {
                if (!isDown) return;
                e.preventDefault();
                const x = e.pageX - trendingScroll.offsetLeft;
                const walk = (x - startX) * 2;
                trendingScroll.scrollLeft = scrollLeft - walk;
            });
        }

        // Duplicate newsletter handler (old alert-based) removed — inline feedback handler kept above.


