# ProfitBenefit Tools WordPress Theme

A professional WordPress theme for digital products blogging with magazine-style layouts, designed specifically for business tools, software reviews, and productivity content.

## Features

### Design & Layout
- **Animated Hero Banner**: Eye-catching gradient banner with floating particles and customizable CTA
- **Magazine-Style Layout**: Professional multi-column blog grid
- **DON'T MISS Section**: Tabbed featured content showcase with related articles
- **Trending Posts**: Horizontal scrolling carousel of trending content
- **Category Boxes**: 2x3 grid of category-based content boxes
- **Responsive Design**: Mobile-first, fully responsive layout

### Content Features
- Sticky posts support for featured content
- Post view tracking for trending posts
- Reading time calculation
- Multiple sidebar widgets (Search, Categories, Popular Posts, Tags)
- Custom image sizes optimized for different layouts
- Threaded comments support

### Navigation
- Top bar with email and quick links
- Main navigation menu
- Three footer widget areas
- Breadcrumb-ready structure

### Customization
- **Hero Banner Customization**: Customize all hero text, button text/URL, and trust badges via Customizer
- Custom logo support
- Custom header email address
- Custom footer description
- Multiple navigation menu locations
- Widget-ready sidebars

## Installation

### Method 1: Manual Installation
1. Download the theme folder `profitbenefit-theme`
2. Upload to `/wp-content/themes/` directory
3. Activate the theme from WordPress Dashboard → Appearance → Themes

### Method 2: ZIP Installation
1. Create a ZIP file of the `profitbenefit-theme` folder
2. Go to WordPress Dashboard → Appearance → Themes → Add New
3. Click "Upload Theme" and select the ZIP file
4. Click "Install Now" and then "Activate"

## Setup

### Required Configuration

#### 1. Set Up Menus
Navigate to **Appearance → Menus** and create menus for:
- **Top Menu**: Links for About, Contact, Advertise
- **Main Menu**: Primary navigation (Home, Blog, Tools, Categories, About)
- **Footer Menu 1**: Quick links
- **Footer Menu 2**: Categories
- **Footer Menu 3**: Legal links

#### 2. Configure Homepage
1. Create a new page called "Home"
2. Go to **Settings → Reading**
3. Select "A static page" for homepage displays
4. Choose "Home" as your homepage
5. Create another page for "Blog" and select it as Posts page

#### 3. Set Up Sidebar Widgets
Go to **Appearance → Widgets** and add widgets to:
- **Main Sidebar**: Search, Categories, Popular Posts, Popular Tags widgets

#### 4. Customize Theme
Navigate to **Appearance → Customize**:
- **Hero Banner Settings**: Customize hero title, subtitle, button text/URL, and trust badges
- Set your site logo
- Update header email address
- Modify footer description
- Configure site identity

### Recommended Plugins
- **Yoast SEO** or **Rank Math**: For SEO optimization
- **WPForms** or **Contact Form 7**: For contact forms
- **Akismet**: For spam protection

## Theme Structure

```
profitbenefit-theme/
├── assets/
│   ├── css/
│   │   └── main.css          # Main stylesheet
│   ├── js/
│   │   └── main.js           # Main JavaScript
│   └── images/               # Theme images
├── template-parts/
│   ├── content.php           # Blog post card template
│   ├── dont-miss-section.php # DON'T MISS section
│   ├── trending-box.php      # Trending posts box
│   └── category-boxes.php    # Category boxes grid
├── inc/                      # Additional PHP files
├── archive.php               # Blog listing page
├── single.php                # Single post page
├── front-page.php            # Homepage template
├── index.php                 # Fallback template
├── header.php                # Header template
├── footer.php                # Footer template
├── sidebar.php               # Sidebar template
├── searchform.php            # Search form template
├── comments.php              # Comments template
├── functions.php             # Theme functions
└── style.css                 # Theme stylesheet (required)
```

## Template Hierarchy

- **Homepage**: `front-page.php`
- **Blog Listing**: `archive.php`
- **Single Post**: `single.php`
- **Category/Tag Archives**: `archive.php`
- **Search Results**: `index.php`
- **404 Page**: `index.php` (can create custom `404.php`)

## Customization

### Custom CSS
Add custom CSS via **Appearance → Customize → Additional CSS**

### Child Theme
For extensive customizations, create a child theme:

```php
// In child theme's style.css
/*
Theme Name: ProfitBenefit Tools Child
Template: profitbenefit-theme
*/
```

### Filters & Hooks
The theme includes standard WordPress hooks and filters for extensibility.

## Browser Support
- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)
- Mobile browsers (iOS Safari, Chrome Mobile)

## Credits
- **Fonts**: Google Fonts (Crimson Pro, DM Sans)
- **Icons**: Unicode characters
- **Design**: Inspired by modern magazine layouts

## Support
For issues, questions, or feature requests, please contact the theme developer.

## Changelog

### Version 1.0.0
- Initial release
- **Animated hero banner** with gradient background, floating particles, and customizable CTA
- Magazine-style layout
- DON'T MISS section with tabs
- Trending posts carousel
- Category boxes grid
- Responsive design
- Widget support
- Custom logo support
- Post view tracking
- Reading time calculation
- **Hero banner customizer** with 7 customizable fields (title, subtitle, button, trust badges)

## License
This theme is licensed under the GNU General Public License v2 or later.

## Author
**ProfitBenefit Team**
- Website: https://profitbenefit.co
- Email: hello@profitbenefit.com
