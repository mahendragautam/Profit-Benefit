    <script>
        // ============================================
        // Hero Slider
        // ============================================
        const heroSlides = document.querySelectorAll('.hero-slide');
        const heroDots = document.querySelectorAll('.hero-dot');
        let currentSlide = 0;
        let slideInterval;

        function showSlide(index) {
            heroSlides.forEach(slide => slide.classList.remove('active'));
            heroDots.forEach(dot => dot.classList.remove('active'));
            heroSlides[index].classList.add('active');
            heroDots[index].classList.add('active');
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

        // ============================================
        // DYNAMIC PIXEL-BASED TAB SYSTEM
        // ============================================
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
            // Force a fixed number of visible numeric tabs (as requested)
            const MAX_VISIBLE_TABS = 5;
            
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
                moreBtn.innerHTML = 'More <span style="font-size:10px;">▼</span>';
                moreBtn.style.cssText = 'padding:18px 20px;font-size:14px;font-weight:500;font-family:DM Sans,sans-serif;';
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

                // Enforce a maximum visible tab count so we get exactly the numbered tabs
                if (visibleTabs.length > MAX_VISIBLE_TABS) {
                    const overflow = visibleTabs.slice(MAX_VISIBLE_TABS);
                    hiddenTabs.unshift(...overflow);
                    visibleTabs = visibleTabs.slice(0, MAX_VISIBLE_TABS);
                }

                // If available space is extremely small and we didn't reach MAX_VISIBLE_TABS,
                // leave behavior as-is so the more dropdown will appear as needed.

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
                
                // Add visible tabs (render numeric tabs referencing category order)
                visibleTabs.forEach(cat => {
                    const btn = document.createElement('button');
                    btn.className = 'tab-button' + (activeCategory === cat.id ? ' active' : '');
                    btn.dataset.category = cat.id;
                    // Use the category label text for the tab (short label)
                    btn.setAttribute('aria-label', cat.label);
                    // Use a short label for visual tab text; keep long label in aria
                    const shortLabel = (cat.label.length > 12) ? cat.label.split(' ')[0] : cat.label;
                    btn.innerHTML = '<span class="tab-label">' + shortLabel + '</span>';
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
                    moreBtn.innerHTML = 'More <span class="dropdown-arrow">▼</span>';
                    
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
                    
                    moreBtn.addEventListener('click', (e) => {
                        e.stopPropagation();
                        dropdown.classList.toggle('open');
                    });
                    
                    dropdown.appendChild(moreBtn);
                    dropdown.appendChild(menu);
                    categoryTabs.appendChild(dropdown);
                }
                
                // Restore scroll position with precise element tracking (skip during resize)
                if (!skipScrollCorrection) {
                    requestAnimationFrame(() => {
                        // Calculate position shift of tracked element
                        if (trackedElement) {
                        const afterOffsetTop = trackedElement.offsetTop;
                        const positionShift = afterOffsetTop - beforeOffsetTop;
                        const targetScrollY = scrollY + positionShift;
                        
                        window.scrollTo({
                            top: targetScrollY,
                            left: scrollX,
                            behavior: 'instant'
                        });
                    } else {
                        window.scrollTo(scrollX, scrollY);
                    }
                    
                    // Remove height locks and fine-tune position
                    requestAnimationFrame(() => {
                        categoryHeader.style.minHeight = '';
                        if (activeContent) {
                            activeContent.style.minHeight = '';
                        }
                        
                        // Final precision adjustment to maintain exact viewport position
                        if (trackedElement) {
                            const currentRect = trackedElement.getBoundingClientRect();
                            const viewportDiff = currentRect.top - trackedElementViewportTop;
                            
                            // Adjust if difference is more than 1px
                            if (Math.abs(viewportDiff) > 1) {
                                window.scrollTo({
                                    top: window.scrollY - viewportDiff,
                                    left: scrollX,
                                    behavior: 'instant'
                                });
                            }
                            }
                        });
                    });
                } else {
                    // During resize, skip scroll correction entirely
                    requestAnimationFrame(() => {
                        categoryHeader.style.minHeight = '';
                        if (activeContent) {
                            activeContent.style.minHeight = '';
                        }
                    });
                }
                
                isInitialized = true;
            }
            
            // Switch category
            function switchCategory(categoryId) {
                activeCategory = categoryId;
                
                // Update content
                contentWrappers.forEach(wrapper => {
                    if (wrapper.dataset.content === categoryId) {
                        wrapper.classList.add('active');
                    } else {
                        wrapper.classList.remove('active');
                    }
                });
                
                // Update active states without full rebuild
                const allTabBtns = categoryTabs.querySelectorAll('.tab-button:not(.more-button)');
                const dropdownItems = categoryTabs.querySelectorAll('.dropdown-menu button');
                const moreBtn = categoryTabs.querySelector('.more-button');
                
                allTabBtns.forEach(btn => {
                    btn.classList.toggle('active', btn.dataset.category === categoryId);
                });
                
                let activeInDropdown = false;
                dropdownItems.forEach(item => {
                    const isActive = item.dataset.category === categoryId;
                    item.classList.toggle('active', isActive);
                    if (isActive) activeInDropdown = true;
                });
                
                if (moreBtn) {
                    moreBtn.classList.toggle('active', activeInDropdown);
                }
            }
            
            // Close dropdown when clicking outside
            document.addEventListener('click', (e) => {
                const dropdown = document.getElementById('moreDropdownDynamic');
                if (dropdown && !dropdown.contains(e.target)) {
                    dropdown.classList.remove('open');
                }
            });
            
            // Debounce function for resize
            function debounce(func, wait) {
                let timeout;
                return function(...args) {
                    clearTimeout(timeout);
                    timeout = setTimeout(() => func.apply(this, args), wait);
                };
            }
            
            // Initialize
            function init() {
                measureTabWidths();
                buildTabs(true);
            }
            
            // Safe scroll position maintenance during resize
            let resizeScrollData = {
                isResizing: false,
                section: null,
                sectionOffsetTop: 0,
                scrollY: 0,
                scrollX: 0,
                targetViewportTop: 0,
                correctionFrame: null,
                endTimer: null
            };
            
            // Capture scroll position relative to don't miss section
            const captureResizeScrollState = () => {
                const section = document.querySelector('.dont-miss-section');
                if (!section) return false;
                
                const sectionRect = section.getBoundingClientRect();
                const sectionOffsetTop = section.offsetTop;
                const currentScrollY = window.scrollY;
                const currentScrollX = window.scrollX;
                
                // Calculate how far down we are scrolled relative to the section
                const targetViewportTop = sectionRect.top;
                
                // Store state
                resizeScrollData = {
                    isResizing: true,
                    section: section,
                    sectionOffsetTop: sectionOffsetTop,
                    scrollY: currentScrollY,
                    scrollX: currentScrollX,
                    targetViewportTop: targetViewportTop,
                    correctionFrame: resizeScrollData.correctionFrame,
                    endTimer: resizeScrollData.endTimer
                };
                
                return true;
            };
            
            // NO scroll correction during resize - let CSS handle it
            const maintainPositionDuringResize = () => {
                // Disabled - was causing jumps to header
                // Just wait for resize to end
                return;
            };
            
            // Handle resize event
            const handleResize = () => {
                // First resize event - just mark that we're resizing
                if (!resizeScrollData.isResizing) {
                    resizeScrollData.isResizing = true;
                    // Don't start correction loop - was causing jumps
                }
                
                // Reset the end timer
                clearTimeout(resizeScrollData.endTimer);
                resizeScrollData.endTimer = setTimeout(() => {
                    // Resize ended - rebuild tabs without scroll correction
                    buildTabs(false);
                    
                    // Just clean up
                    if (resizeScrollData.correctionFrame) {
                        cancelAnimationFrame(resizeScrollData.correctionFrame);
                    }
                    resizeScrollData.isResizing = false;
                    resizeScrollData.section = null;
                    resizeScrollData.correctionFrame = null;
                }, 150);
            };
            
            window.addEventListener('resize', handleResize);
            
            // Also use ResizeObserver for container size changes
            if (typeof ResizeObserver !== 'undefined') {
                const observer = new ResizeObserver(() => {
                    if (isInitialized) {
                        buildTabs(false);
                    }
                });
                observer.observe(categoryHeader);
            }
            
            // Initialize when DOM is ready
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', init);
            } else {
                init();
            }
            
            // Also initialize after a short delay to ensure fonts are loaded
            setTimeout(init, 100);
            
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

        // ============================================
        // Newsletter Form Handler
        // ============================================
        const newsletterForm = document.querySelector('.newsletter-form');
        if (newsletterForm) {
            newsletterForm.addEventListener('submit', (e) => {
                e.preventDefault();
                const input = newsletterForm.querySelector('.newsletter-input');
                const email = input.value.trim();

                if (email && /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                    alert('Thank you for subscribing! Check your email for confirmation.');
                    input.value = '';
                } else {
                    alert('Please enter a valid email address.');
                }
            });
        }


