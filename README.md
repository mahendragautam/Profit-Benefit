# Digital Product Hub - Frontend Website

A modern, responsive website for showcasing and selling digital products. Built with HTML, CSS, and vanilla JavaScript.

## Features

### Homepage
- **Hero Section**: Eye-catching gradient background with call-to-action buttons
- **Product Grid**: Displays featured digital products with cards
- **Categories Section**: Browse products by category
- **Blog Section**: Latest articles and tutorials
- **Responsive Navigation**: Mobile-friendly hamburger menu
- **Newsletter Signup**: Email subscription form in footer

### Product Display Page
- **Product Gallery**: Main image with thumbnail navigation
- **Detailed Information**: Price, ratings, features, and specifications
- **Tabbed Content**: Description, details, reviews, and FAQ
- **Product Actions**: Add to cart, buy now, and wishlist buttons
- **Related Products**: Suggestions based on current product
- **Responsive Design**: Optimized for all screen sizes

## Technologies Used

- **HTML5**: Semantic markup
- **CSS3**: Modern styling with CSS Grid and Flexbox
- **JavaScript**: Vanilla JS for interactivity
- **Google Fonts**: Inter font family

## Project Structure

```
digital-product-bloging-website/
├── index.html              # Homepage
├── product.html            # Product detail page
├── css/
│   └── styles.css          # All styles
├── js/
│   ├── products.js         # Product data
│   ├── main.js            # Homepage scripts
│   └── product-detail.js  # Product page scripts
├── assets/
│   └── images/            # Image assets
└── README.md              # Documentation
```

## Getting Started

### Installation

1. Clone the repository
2. Open `index.html` in a web browser
3. No build process required!

### Usage

**Viewing Products:**
- Browse products on the homepage
- Click any product card to view details

**Product Detail Page:**
- View product information, features, and reviews
- Use tabs to navigate different sections
- Click "Add to Cart" or "Buy Now" for actions
- Toggle wishlist with the heart icon

**Navigation:**
- Use the top navigation menu
- On mobile, click the hamburger menu

## Features in Detail

### Responsive Design
- Desktop: Full-width layout with multi-column grids
- Tablet: Optimized 2-column layouts
- Mobile: Single-column stacked layout

### Interactive Elements
- Smooth scroll navigation
- Hover effects on cards and buttons
- Tab switching on product pages
- Mobile menu toggle
- Toast notifications

### Product Data
- 12 sample products across 6 categories
- Realistic pricing and ratings
- Detailed product specifications

## Customization

### Adding Products
Edit `js/products.js` to add or modify products:

```javascript
{
    id: 13,
    name: "Your Product Name",
    category: "Category",
    description: "Product description",
    price: 49.99,
    originalPrice: 79.99,
    rating: 4.8,
    reviews: 100,
    icon: "🎨",
    badge: "New",
    gradient: "linear-gradient(135deg, #667eea 0%, #764ba2 100%)",
    features: ["Feature 1", "Feature 2"],
    fileType: "File types",
    fileSize: "Size",
    lastUpdated: "Date"
}
```

### Styling
Modify CSS variables in `css/styles.css`:

```css
:root {
    --primary-color: #6366f1;
    --secondary-color: #10b981;
    --accent-color: #f59e0b;
    /* ... more variables */
}
```

## Browser Support

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)
- Mobile browsers

## Performance

- Lightweight (~100KB total)
- No external dependencies
- Fast page load times
- Optimized animations

## Future Enhancements

- [ ] Shopping cart functionality
- [ ] User authentication
- [ ] Payment integration
- [ ] Product search and filtering
- [ ] Backend API integration
- [ ] Product image upload
- [ ] Admin dashboard

## License

This project is open source and available under the MIT License.

## Credits

Created with ❤️ for digital product creators and entrepreneurs.
