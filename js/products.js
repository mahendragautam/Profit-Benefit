// Sample product data
const products = [
    {
        id: 1,
        name: "Premium UI Kit for Figma",
        category: "Design Assets",
        description: "Complete UI kit with 200+ components for modern web and mobile apps",
        price: 49.99,
        originalPrice: 79.99,
        rating: 4.8,
        reviews: 124,
        icon: "🎨",
        badge: "Bestseller",
        gradient: "linear-gradient(135deg, #667eea 0%, #764ba2 100%)",
        features: [
            "200+ customizable components",
            "Dark and light mode support",
            "Responsive design system",
            "Free lifetime updates",
            "Commercial license included"
        ],
        fileType: "Figma, Sketch, Adobe XD",
        fileSize: "24 MB",
        lastUpdated: "November 2024"
    },
    {
        id: 2,
        name: "JavaScript Master Course",
        category: "E-books",
        description: "Comprehensive guide to modern JavaScript, from basics to advanced concepts",
        price: 39.99,
        originalPrice: 59.99,
        rating: 4.9,
        reviews: 342,
        icon: "📚",
        badge: "New",
        gradient: "linear-gradient(135deg, #f093fb 0%, #f5576c 100%)",
        features: [
            "300+ pages of content",
            "50+ code examples",
            "Project-based learning",
            "PDF, EPUB, and MOBI formats",
            "Access to code repository"
        ],
        fileType: "PDF, EPUB, MOBI",
        fileSize: "12 MB",
        lastUpdated: "December 2024"
    },
    {
        id: 3,
        name: "React Component Library",
        category: "Developer Tools",
        description: "Production-ready React components with TypeScript support",
        price: 69.99,
        originalPrice: null,
        rating: 4.7,
        reviews: 89,
        icon: "⚛️",
        badge: "Trending",
        gradient: "linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)",
        features: [
            "50+ React components",
            "Full TypeScript support",
            "Comprehensive documentation",
            "Storybook included",
            "Tailwind CSS integration"
        ],
        fileType: "NPM Package, Source Code",
        fileSize: "8 MB",
        lastUpdated: "November 2024"
    },
    {
        id: 4,
        name: "Lightroom Presets Collection",
        category: "Photography",
        description: "Professional photo editing presets for stunning images",
        price: 29.99,
        originalPrice: 49.99,
        rating: 4.6,
        reviews: 267,
        icon: "📸",
        badge: "Sale",
        gradient: "linear-gradient(135deg, #fa709a 0%, #fee140 100%)",
        features: [
            "100+ unique presets",
            "Mobile and desktop versions",
            "Before/after examples",
            "Installation guide included",
            "Compatible with Lightroom CC"
        ],
        fileType: ".XMP, .DNG",
        fileSize: "156 MB",
        lastUpdated: "October 2024"
    },
    {
        id: 5,
        name: "Stock Music Library",
        category: "Audio & Video",
        description: "Royalty-free music tracks for content creators",
        price: 89.99,
        originalPrice: 129.99,
        rating: 4.8,
        reviews: 156,
        icon: "🎵",
        badge: "Bestseller",
        gradient: "linear-gradient(135deg, #a8edea 0%, #fed6e3 100%)",
        features: [
            "200+ high-quality tracks",
            "Multiple genres included",
            "Commercial license",
            "WAV and MP3 formats",
            "Lifetime access"
        ],
        fileType: "WAV, MP3",
        fileSize: "2.4 GB",
        lastUpdated: "November 2024"
    },
    {
        id: 6,
        name: "Notion Productivity System",
        category: "Productivity",
        description: "Complete productivity system template for Notion",
        price: 19.99,
        originalPrice: 34.99,
        rating: 4.9,
        reviews: 423,
        icon: "🚀",
        badge: "Bestseller",
        gradient: "linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%)",
        features: [
            "Task management system",
            "Goal tracking templates",
            "Habit tracker included",
            "Weekly planner layouts",
            "Customization guide"
        ],
        fileType: "Notion Template",
        fileSize: "N/A",
        lastUpdated: "December 2024"
    },
    {
        id: 7,
        name: "3D Icon Pack",
        category: "Design Assets",
        description: "Modern 3D icons perfect for web and app design",
        price: 44.99,
        originalPrice: null,
        rating: 4.7,
        reviews: 198,
        icon: "🎭",
        badge: "New",
        gradient: "linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%)",
        features: [
            "500+ 3D icons",
            "Multiple file formats",
            "Customizable colors",
            "High resolution PNG",
            "Figma source files"
        ],
        fileType: "PNG, SVG, Figma",
        fileSize: "420 MB",
        lastUpdated: "December 2024"
    },
    {
        id: 8,
        name: "WordPress Theme Bundle",
        category: "Developer Tools",
        description: "Premium WordPress themes for various industries",
        price: 79.99,
        originalPrice: 149.99,
        rating: 4.5,
        reviews: 234,
        icon: "💻",
        badge: "Sale",
        gradient: "linear-gradient(135deg, #f5576c 0%, #667eea 100%)",
        features: [
            "10 premium themes",
            "Elementor compatible",
            "WooCommerce ready",
            "SEO optimized",
            "6 months support"
        ],
        fileType: "WordPress Theme Files",
        fileSize: "156 MB",
        lastUpdated: "November 2024"
    },
    {
        id: 9,
        name: "Video Editing Templates",
        category: "Audio & Video",
        description: "Professional video templates for Adobe Premiere Pro",
        price: 54.99,
        originalPrice: 84.99,
        rating: 4.6,
        reviews: 178,
        icon: "🎬",
        badge: "Trending",
        gradient: "linear-gradient(135deg, #667eea 0%, #764ba2 100%)",
        features: [
            "50+ video templates",
            "Title and transition effects",
            "Color grading presets",
            "Tutorial videos included",
            "4K resolution support"
        ],
        fileType: "Premiere Pro, After Effects",
        fileSize: "1.2 GB",
        lastUpdated: "November 2024"
    },
    {
        id: 10,
        name: "Font Collection Pro",
        category: "Design Assets",
        description: "Hand-picked font families for professional projects",
        price: 34.99,
        originalPrice: 59.99,
        rating: 4.8,
        reviews: 312,
        icon: "✒️",
        badge: "Bestseller",
        gradient: "linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)",
        features: [
            "25 premium font families",
            "Web and desktop licenses",
            "Variable font support",
            "Complete character sets",
            "Commercial use allowed"
        ],
        fileType: "OTF, TTF, WOFF2",
        fileSize: "89 MB",
        lastUpdated: "October 2024"
    },
    {
        id: 11,
        name: "Social Media Templates",
        category: "Design Assets",
        description: "Ready-to-use templates for Instagram, Facebook, and more",
        price: 24.99,
        originalPrice: 39.99,
        rating: 4.7,
        reviews: 445,
        icon: "📱",
        badge: "Trending",
        gradient: "linear-gradient(135deg, #f093fb 0%, #f5576c 100%)",
        features: [
            "300+ social media templates",
            "Instagram, Facebook, Twitter",
            "Canva and Photoshop formats",
            "Fully customizable",
            "Monthly updates"
        ],
        fileType: "PSD, Canva",
        fileSize: "234 MB",
        lastUpdated: "December 2024"
    },
    {
        id: 12,
        name: "Python Automation Scripts",
        category: "Developer Tools",
        description: "Time-saving Python scripts for common automation tasks",
        price: 29.99,
        originalPrice: null,
        rating: 4.9,
        reviews: 167,
        icon: "🐍",
        badge: "New",
        gradient: "linear-gradient(135deg, #a8edea 0%, #fed6e3 100%)",
        features: [
            "50+ automation scripts",
            "Well-documented code",
            "Easy to customize",
            "Requirements.txt included",
            "Video tutorials"
        ],
        fileType: "Python (.py)",
        fileSize: "4 MB",
        lastUpdated: "December 2024"
    }
];

// Function to get product by ID
function getProductById(id) {
    return products.find(product => product.id === parseInt(id));
}

// Function to get random products (for related products)
function getRandomProducts(count, excludeId = null) {
    const filtered = excludeId
        ? products.filter(p => p.id !== excludeId)
        : products;

    const shuffled = [...filtered].sort(() => 0.5 - Math.random());
    return shuffled.slice(0, count);
}

// Function to get products by category
function getProductsByCategory(category) {
    return products.filter(product => product.category === category);
}
