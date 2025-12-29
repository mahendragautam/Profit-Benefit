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