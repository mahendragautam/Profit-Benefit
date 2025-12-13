# HTML Design Source Files

This folder contains the original HTML design files that were converted into the WordPress theme.

## Files Overview

### Main Design Files (Converted to WordPress Theme)

| File | Converted To | Description |
|------|--------------|-------------|
| `home-page-design.html` | `front-page.php` | Homepage with animated hero banner, DON'T MISS section, trending posts, and category boxes |
| `blog-listing.html` | `archive.php` | Blog listing page with 2-column layout (trending + categories on left, sidebar on right) |
| `blog-post.html` | `single.php` | Single blog post template with sidebar |

### Assets

| File/Folder | Converted To | Description |
|-------------|--------------|-------------|
| `css/styles.css` | `profitbenefit-theme/assets/css/main.css` | All styles consolidated and converted |
| `js/main.js` | `profitbenefit-theme/assets/js/main.js` | JavaScript functionality |
| `tools1.svg` | Used in `header.php` | Logo SVG |

## Conversion Details

### home-page-design.html → front-page.php
**Features Converted:**
- ✅ Animated hero banner with gradient background
- ✅ Floating particles (5 particles with rotation animations)
- ✅ Hero content (title, subtitle, CTA button)
- ✅ Trust badges (3 customizable badges)
- ✅ DON'T MISS section with tabs
- ✅ Trending posts carousel
- ✅ Category boxes grid (2x3 layout)
- ✅ Responsive design

**WordPress Integration:**
- Hero banner customizable via Appearance → Customize → Hero Banner Settings
- Dynamic content from WordPress posts
- All animations preserved with CSS keyframes

### blog-listing.html → archive.php
**Features Converted:**
- ✅ Page header with title and subtitle
- ✅ DON'T MISS section
- ✅ 2-column layout (2fr left, 1fr right)
  - Left: Trending posts + Category boxes grid
  - Right: Sidebar widgets
- ✅ Blog posts grid (3 columns)
- ✅ Pagination

**WordPress Integration:**
- Dynamic post queries
- Category-based content organization
- Widget areas for sidebar

### blog-post.html → single.php
**Features Converted:**
- ✅ Page header with post title, category, meta info
- ✅ Featured image
- ✅ Post content area
- ✅ Tags display
- ✅ Comments section
- ✅ Previous/Next post navigation
- ✅ Sidebar with widgets

**WordPress Integration:**
- WordPress post content and metadata
- Comment system integration
- Post navigation functions

## How to Use These Files

These are reference files showing the original HTML/CSS/JS design before WordPress conversion. They can be useful for:

1. **Design Reference**: See the original static HTML structure
2. **Feature Comparison**: Compare static HTML vs WordPress template implementation
3. **Future Updates**: Reference for adding new features to the WordPress theme
4. **Documentation**: Understanding what was converted and how

## WordPress Theme Location

The converted WordPress theme is located at:
```
../profitbenefit-theme/
```

## Notes

- These are static HTML files and cannot be used directly in WordPress
- All functionality has been converted to WordPress-compatible PHP templates
- CSS has been reorganized and optimized for the theme
- JavaScript has been enqueued properly in WordPress
- All WordPress best practices have been followed in the conversion

---

**Created**: December 2024
**Purpose**: Archive of original design files used for WordPress theme conversion
