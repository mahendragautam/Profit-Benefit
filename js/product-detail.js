// Product Detail Page JavaScript

document.addEventListener('DOMContentLoaded', function() {
    initProductDetail();
    initTabs();
    initProductActions();
    initMobileMenu();
});

// Initialize Product Detail Page
function initProductDetail() {
    // Get product ID from URL
    const urlParams = new URLSearchParams(window.location.search);
    const productId = urlParams.get('id');

    if (!productId) {
        window.location.href = 'index.html';
        return;
    }

    // Get product data
    const product = getProductById(productId);

    if (!product) {
        window.location.href = 'index.html';
        return;
    }

    // Load product details
    loadProductDetails(product);
    loadRelatedProducts(product.id);
}

// Load Product Details
function loadProductDetails(product) {
    // Update breadcrumb
    document.getElementById('breadcrumbCategory').textContent = product.category;
    document.getElementById('breadcrumbProduct').textContent = product.name;

    // Update product images
    const mainImage = document.getElementById('mainImage');
    mainImage.style.background = product.gradient;
    mainImage.innerHTML = `<span style="font-size: 6rem;">${product.icon}</span>`;

    // Create thumbnails
    const thumbnails = document.getElementById('thumbnails');
    for (let i = 0; i < 4; i++) {
        const thumb = document.createElement('div');
        thumb.className = 'thumbnail' + (i === 0 ? ' active' : '');
        thumb.style.background = product.gradient;
        thumb.innerHTML = `<div style="display: flex; align-items: center; justify-content: center; height: 100%; font-size: 2rem; opacity: 0.5;">${product.icon}</div>`;
        thumb.addEventListener('click', () => {
            document.querySelectorAll('.thumbnail').forEach(t => t.classList.remove('active'));
            thumb.classList.add('active');
        });
        thumbnails.appendChild(thumb);
    }

    // Update badges
    const badges = document.getElementById('productBadges');
    if (product.badge) {
        const badgeClass = product.badge.toLowerCase().replace(' ', '-');
        badges.innerHTML = `<span class="badge badge-${badgeClass}">${product.badge}</span>`;
    }

    // Update product info
    document.getElementById('productTitle').textContent = product.name;

    // Update rating
    const stars = document.getElementById('productStars');
    stars.textContent = generateStars(product.rating);

    const ratingCount = document.getElementById('ratingCount');
    ratingCount.textContent = `(${product.reviews} reviews)`;

    // Update price
    document.getElementById('currentPrice').textContent = `$${product.price.toFixed(2)}`;

    if (product.originalPrice) {
        const originalPrice = document.getElementById('originalPrice');
        originalPrice.textContent = `$${product.originalPrice.toFixed(2)}`;
        originalPrice.style.display = 'inline';

        const discount = Math.round(((product.originalPrice - product.price) / product.originalPrice) * 100);
        const discountBadge = document.getElementById('discountBadge');
        discountBadge.textContent = `-${discount}%`;
        discountBadge.style.display = 'inline-block';
    }

    // Update description
    document.getElementById('productDescription').textContent = product.description;

    // Update features
    const featuresContainer = document.getElementById('productFeatures');
    featuresContainer.innerHTML = `
        <h4>Key Features</h4>
        <ul>
            ${product.features.map(feature => `<li>${feature}</li>`).join('')}
        </ul>
    `;

    // Update metadata
    document.getElementById('productCategory').textContent = product.category;
    document.getElementById('productFileType').textContent = product.fileType;
    document.getElementById('productFileSize').textContent = product.fileSize;
    document.getElementById('productUpdated').textContent = product.lastUpdated;

    // Update detail tab
    document.getElementById('detailProductId').textContent = `#${product.id.toString().padStart(5, '0')}`;

    // Update page title
    document.title = `${product.name} - Digital Product Hub`;
}

// Load Related Products
function loadRelatedProducts(currentProductId) {
    const relatedGrid = document.getElementById('relatedProducts');

    if (!relatedGrid) return;

    const relatedProducts = getRandomProducts(3, currentProductId);

    relatedGrid.innerHTML = '';
    relatedProducts.forEach((product, index) => {
        const card = createProductCard(product);
        card.style.animationDelay = `${index * 0.1}s`;
        relatedGrid.appendChild(card);
    });
}

// Create Product Card
function createProductCard(product) {
    const card = document.createElement('a');
    card.href = `product.html?id=${product.id}`;
    card.className = 'product-card';

    card.innerHTML = `
        <div class="product-image" style="background: ${product.gradient}">
            <span style="font-size: 4rem;">${product.icon}</span>
            ${product.badge ? `<span class="product-badge">${product.badge}</span>` : ''}
        </div>
        <div class="product-content">
            <span class="product-category">${product.category}</span>
            <h3 class="product-name">${product.name}</h3>
            <p class="product-description">${product.description}</p>
            <div class="product-footer">
                <div class="product-price">
                    $${product.price.toFixed(2)}
                    ${product.originalPrice ? `<span style="font-size: 0.875rem; color: var(--text-light); text-decoration: line-through; margin-left: 0.5rem;">$${product.originalPrice.toFixed(2)}</span>` : ''}
                </div>
                <div class="product-rating">
                    <span class="stars">${generateStars(product.rating)}</span>
                    <span style="font-size: 0.875rem; color: var(--text-secondary);">${product.rating}</span>
                </div>
            </div>
        </div>
    `;

    return card;
}

// Generate Stars
function generateStars(rating) {
    const fullStars = Math.floor(rating);
    const hasHalfStar = rating % 1 >= 0.5;
    let stars = '';

    for (let i = 0; i < fullStars; i++) {
        stars += '★';
    }

    if (hasHalfStar) {
        stars += '★';
    }

    const emptyStars = 5 - Math.ceil(rating);
    for (let i = 0; i < emptyStars; i++) {
        stars += '☆';
    }

    return stars;
}

// Initialize Tabs
function initTabs() {
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabPanels = document.querySelectorAll('.tab-panel');

    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            const tabId = button.getAttribute('data-tab');

            // Remove active class from all buttons and panels
            tabButtons.forEach(btn => btn.classList.remove('active'));
            tabPanels.forEach(panel => panel.classList.remove('active'));

            // Add active class to clicked button and corresponding panel
            button.classList.add('active');
            document.getElementById(tabId).classList.add('active');
        });
    });
}

// Initialize Product Actions
function initProductActions() {
    // Add to Cart
    const addToCartBtn = document.querySelector('.add-to-cart');
    if (addToCartBtn) {
        addToCartBtn.addEventListener('click', () => {
            showNotification('Product added to cart!', 'success');
        });
    }

    // Buy Now
    const buyNowBtn = document.querySelector('.buy-now');
    if (buyNowBtn) {
        buyNowBtn.addEventListener('click', () => {
            showNotification('Redirecting to checkout...', 'info');
        });
    }

    // Wishlist
    const wishlistBtn = document.querySelector('.wishlist-btn');
    if (wishlistBtn) {
        wishlistBtn.addEventListener('click', () => {
            const icon = wishlistBtn.textContent;
            if (icon === '♡') {
                wishlistBtn.textContent = '♥';
                wishlistBtn.style.color = '#ef4444';
                showNotification('Added to wishlist!', 'success');
            } else {
                wishlistBtn.textContent = '♡';
                wishlistBtn.style.color = '';
                showNotification('Removed from wishlist', 'info');
            }
        });
    }
}

// Show Notification
function showNotification(message, type = 'info') {
    const existingNotification = document.querySelector('.notification');
    if (existingNotification) {
        existingNotification.remove();
    }

    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.textContent = message;

    const bgColor = {
        'success': '#10b981',
        'error': '#ef4444',
        'info': '#3b82f6'
    }[type] || '#3b82f6';

    notification.style.cssText = `
        position: fixed;
        top: 100px;
        right: 20px;
        padding: 1rem 1.5rem;
        background-color: ${bgColor};
        color: white;
        border-radius: 0.5rem;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        z-index: 10000;
        animation: slideInRight 0.3s ease-out;
    `;

    document.body.appendChild(notification);

    setTimeout(() => {
        notification.style.animation = 'slideOutRight 0.3s ease-out';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// Mobile Menu (shared with main.js)
function initMobileMenu() {
    const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
    const navMenu = document.querySelector('.nav-menu');

    if (mobileMenuToggle) {
        mobileMenuToggle.addEventListener('click', function() {
            navMenu.classList.toggle('active');

            const spans = this.querySelectorAll('span');
            if (navMenu.classList.contains('active')) {
                spans[0].style.transform = 'rotate(45deg) translate(5px, 5px)';
                spans[1].style.opacity = '0';
                spans[2].style.transform = 'rotate(-45deg) translate(7px, -6px)';
            } else {
                spans[0].style.transform = 'none';
                spans[1].style.opacity = '1';
                spans[2].style.transform = 'none';
            }
        });

        const navLinks = document.querySelectorAll('.nav-menu a');
        navLinks.forEach(link => {
            link.addEventListener('click', () => {
                navMenu.classList.remove('active');
                const spans = mobileMenuToggle.querySelectorAll('span');
                spans[0].style.transform = 'none';
                spans[1].style.opacity = '1';
                spans[2].style.transform = 'none';
            });
        });
    }
}

// Add animation styles
const style = document.createElement('style');
style.textContent = `
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes slideOutRight {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);
