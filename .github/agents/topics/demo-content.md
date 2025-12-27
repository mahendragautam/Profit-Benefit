---
name: demo-content
hidden: true
protected: true
---

# Demo Content & Import - Complete Guide

> **Load this file when**: Keywords detected - demo content, demo import, sample data, one click demo, OCDI, demo data
>
> **Purpose**: Professional demo content creation and import standards
>
> **Version**: 2.0 | **Last Updated**: December 2025

---

## 1. DEMO CONTENT REQUIREMENTS

### Why Demo Content Matters

**For ThemeForest/Premium Themes:**
- ✅ **REQUIRED** - Buyers expect instant demo replication
- ✅ Showcases theme capabilities immediately
- ✅ Reduces support requests ("How do I make it look like the demo?")
- ✅ Increases positive reviews and sales
- ✅ Differentiates from free themes

**For WordPress.org Themes:**
- ⚠️ **OPTIONAL** - But highly recommended
- ✅ Helps users get started quickly
- ✅ Shows theme features in action

---

## 2. DEMO CONTENT STANDARDS

### Content Quality Requirements

#### Sample Posts (10-15 minimum)
```
Requirements:
✓ Featured images (high-quality, 1200×800 minimum)
✓ Varied post formats (standard, gallery, video, audio)
✓ Realistic titles (not "Post Title 1, 2, 3")
✓ Full content (minimum 300 words per post)
✓ Categories assigned (3-5 categories)
✓ Tags assigned (5-10 tags total)
✓ Comments (sample comments on posts)
✓ Post metadata (author, date, excerpt)
✓ Varied post lengths (short, medium, long)
```

**Example Post Structure:**
```xml
<item>
    <title><![CDATA[The Ultimate Guide to Web Design Best Practices]]></title>
    <pubDate>Mon, 15 Jan 2024 10:00:00 +0000</pubDate>
    <category domain="category" nicename="design"><![CDATA[Design]]></category>
    <category domain="post_tag" nicename="web-design"><![CDATA[Web Design]]></category>
    <wp:post_id>101</wp:post_id>
    <wp:post_type><![CDATA[post]]></wp:post_type>
    <wp:status><![CDATA[publish]]></wp:status>
    <wp:featured_image>http://demo.com/image.jpg</wp:featured_image>
    <content:encoded><![CDATA[
        <p>Web design has evolved significantly over the past decade...</p>

        <h2>Understanding Modern Design Principles</h2>
        <p>Modern web design focuses on user experience...</p>

        <!-- Minimum 300 words of real, valuable content -->
    ]]></content:encoded>
</item>
```

#### Sample Pages (5-10 minimum)
```
Required Pages:
✓ Homepage (with page builder content if used)
✓ About Us
✓ Services / Features
✓ Contact
✓ Blog (if separate from home)
✓ Portfolio / Projects (if applicable)
✓ FAQ (optional but recommended)
✓ Privacy Policy
✓ Terms & Conditions
```

#### Navigation Menus
```
Primary Menu:
✓ Home
✓ About
✓ Services
✓ Blog
✓ Contact

Footer Menu:
✓ Privacy Policy
✓ Terms & Conditions
✓ Support
✓ FAQ

Social Menu (if theme supports):
✓ Facebook
✓ Twitter
✓ Instagram
✓ LinkedIn
```

#### Widgets
```
Sidebar Widgets:
✓ Search
✓ Recent Posts
✓ Categories
✓ Tags Cloud
✓ Custom widget (if theme provides)

Footer Widgets:
✓ About widget (text)
✓ Recent posts
✓ Contact info
✓ Social links
```

#### Media Library
```
Image Requirements:
✓ Minimum 30 images
✓ High resolution (1920×1080 or higher)
✓ GPL-compatible licenses (Unsplash, Pexels, Pixabay)
✓ Properly named (not IMG_1234.jpg)
✓ Optimized file sizes (under 200KB each)
✓ Varied subjects (people, nature, technology, business)

Video Requirements (if theme supports):
✓ Embed codes (YouTube, Vimeo)
✓ Placeholder videos
✓ Properly attributed
```

---

## 3. ONE-CLICK DEMO IMPORT (OCDI)

### Plugin Integration

**Install One Click Demo Import Plugin:**
```php
<?php
/**
 * functions.php - TGMPA recommendation
 */

$plugins = [
    [
        'name'     => 'One Click Demo Import',
        'slug'     => 'one-click-demo-import',
        'required' => false, // Recommended, not required
    ],
];
```

### Demo Files Setup

**Required File Structure:**
```
/demo/
├── content.xml              # WordPress export (posts, pages, media)
├── widgets.wie              # Widget Importer & Exporter format
├── customizer.dat           # Customizer Export/Import format
├── options.json             # Theme options (if using options framework)
└── preview.png              # Demo preview screenshot
```

---

## 4. IMPLEMENTING OCDI

### Basic OCDI Integration

```php
<?php
/**
 * inc/demo-import.php - One Click Demo Import integration
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register demo import files
 */
function themename_import_files() {
    return [
        [
            'import_file_name'           => 'Demo Import',
            'categories'                 => ['Main'],
            'import_file_url'            => get_template_directory_uri() . '/demo/content.xml',
            'import_widget_file_url'     => get_template_directory_uri() . '/demo/widgets.wie',
            'import_customizer_file_url' => get_template_directory_uri() . '/demo/customizer.dat',
            'import_preview_image_url'   => get_template_directory_uri() . '/demo/preview.png',
            'preview_url'                => 'https://demo.yourtheme.com',
        ],
    ];
}
add_filter('ocdi/import_files', 'themename_import_files');

/**
 * After import setup
 */
function themename_after_import_setup() {
    // Assign menus to their locations
    $main_menu = get_term_by('name', 'Primary Menu', 'nav_menu');
    $footer_menu = get_term_by('name', 'Footer Menu', 'nav_menu');

    set_theme_mod('nav_menu_locations', [
        'primary' => $main_menu ? $main_menu->term_id : 0,
        'footer'  => $footer_menu ? $footer_menu->term_id : 0,
    ]);

    // Assign front page and posts page
    $front_page = get_page_by_title('Home');
    $blog_page = get_page_by_title('Blog');

    if ($front_page) {
        update_option('show_on_front', 'page');
        update_option('page_on_front', $front_page->ID);
    }

    if ($blog_page) {
        update_option('page_for_posts', $blog_page->ID);
    }

    // Set permalink structure
    update_option('permalink_structure', '/%postname%/');

    // Flush rewrite rules
    flush_rewrite_rules();
}
add_action('ocdi/after_import', 'themename_after_import_setup');
```

### Multiple Demo Variations

```php
<?php
/**
 * Multiple demo imports (for premium themes with variations)
 */
function themename_import_files() {
    return [
        // Demo 1: Business
        [
            'import_file_name'           => 'Business Demo',
            'categories'                 => ['Business', 'Corporate'],
            'import_file_url'            => get_template_directory_uri() . '/demo/business/content.xml',
            'import_widget_file_url'     => get_template_directory_uri() . '/demo/business/widgets.wie',
            'import_customizer_file_url' => get_template_directory_uri() . '/demo/business/customizer.dat',
            'import_preview_image_url'   => get_template_directory_uri() . '/demo/business/preview.png',
            'preview_url'                => 'https://demo.yourtheme.com/business',
        ],

        // Demo 2: Portfolio
        [
            'import_file_name'           => 'Portfolio Demo',
            'categories'                 => ['Portfolio', 'Creative'],
            'import_file_url'            => get_template_directory_uri() . '/demo/portfolio/content.xml',
            'import_widget_file_url'     => get_template_directory_uri() . '/demo/portfolio/widgets.wie',
            'import_customizer_file_url' => get_template_directory_uri() . '/demo/portfolio/customizer.dat',
            'import_preview_image_url'   => get_template_directory_uri() . '/demo/portfolio/preview.png',
            'preview_url'                => 'https://demo.yourtheme.com/portfolio',
        ],

        // Demo 3: Blog
        [
            'import_file_name'           => 'Blog Demo',
            'categories'                 => ['Blog', 'Magazine'],
            'import_file_url'            => get_template_directory_uri() . '/demo/blog/content.xml',
            'import_widget_file_url'     => get_template_directory_uri() . '/demo/blog/widgets.wie',
            'import_customizer_file_url' => get_template_directory_uri() . '/demo/blog/customizer.dat',
            'import_preview_image_url'   => get_template_directory_uri() . '/demo/blog/preview.png',
            'preview_url'                => 'https://demo.yourtheme.com/blog',
        ],
    ];
}
add_filter('ocdi/import_files', 'themename_import_files');

/**
 * After import setup for specific demos
 */
function themename_after_import_specific($selected_import) {
    // Get import file name
    $import_file_name = $selected_import['import_file_name'];

    // Common setup
    themename_after_import_setup();

    // Demo-specific customizer settings
    if ($import_file_name === 'Business Demo') {
        set_theme_mod('primary_color', '#0066cc');
        set_theme_mod('layout', 'boxed');
    } elseif ($import_file_name === 'Portfolio Demo') {
        set_theme_mod('primary_color', '#ff6600');
        set_theme_mod('layout', 'full-width');
    } elseif ($import_file_name === 'Blog Demo') {
        set_theme_mod('primary_color', '#333333');
        set_theme_mod('layout', 'sidebar-right');
    }
}
add_action('ocdi/after_import', 'themename_after_import_specific');
```

---

## 5. CREATING DEMO CONTENT

### Export Content from Live Demo

**Step 1: Create Live Demo Site**
```
1. Install WordPress
2. Install your theme
3. Create all demo content manually
4. Configure all settings
5. Test thoroughly
```

**Step 2: Export Content**
```
WordPress Admin:
Tools → Export → All content → Download Export File

Result: content.xml
```

**Step 3: Export Widgets**
```
Install: Widget Importer & Exporter plugin
Tools → Widget Importer & Exporter → Export Widgets

Result: widgets.wie
```

**Step 4: Export Customizer Settings**
```
Install: Customizer Export/Import plugin
Appearance → Customize → Export/Import → Export

Result: customizer.dat
```

---

## 6. DEMO CONTENT BEST PRACTICES

### Content Writing Guidelines

#### Post Titles
```
❌ BAD:
- "Post Title 1"
- "Lorem Ipsum"
- "Sample Post"

✅ GOOD:
- "10 Essential Web Design Trends for 2024"
- "How to Improve Your Website's User Experience"
- "The Ultimate Guide to WordPress SEO"
```

#### Post Content
```
❌ BAD:
- Lorem ipsum dolor sit amet...
- Copy-pasted generic text
- Short, meaningless paragraphs

✅ GOOD:
- Real, valuable content
- Minimum 300-500 words
- Proper headings (H2, H3)
- Lists and formatting
- Relevant to your theme's niche
```

#### Categories & Tags
```
❌ BAD:
- Category 1, Category 2
- Tag1, Tag2, Tag3

✅ GOOD:
Categories:
- Web Design
- Development
- Marketing
- Business

Tags:
- SEO
- WordPress
- Responsive Design
- UI/UX
```

---

## 7. IMAGE LICENSING & ATTRIBUTION

### GPL-Compatible Image Sources

**Recommended Sources:**
```
Unsplash (https://unsplash.com)
License: Unsplash License (Free to use)
✓ Commercial use allowed
✓ No attribution required (but appreciated)

Pexels (https://pexels.com)
License: Pexels License (Free to use)
✓ Commercial use allowed
✓ No attribution required

Pixabay (https://pixabay.com)
License: Pixabay License (Free to use)
✓ Commercial use allowed
✓ No attribution required

StockSnap.io (https://stocksnap.io)
License: CC0 Public Domain
✓ Commercial use allowed
✓ No attribution required
```

### Attribution in Demo

**Create credits.txt in demo folder:**
```txt
DEMO CONTENT CREDITS
====================

Images
------
All images sourced from Unsplash (https://unsplash.com)
License: Unsplash License

Photo Credits:
- Homepage Hero: Photo by John Doe (@johndoe)
- About Page: Photo by Jane Smith (@janesmith)
- Blog Images: Various photographers

Fonts
-----
- Google Fonts (https://fonts.google.com)
  License: SIL Open Font License

Icons
-----
- Font Awesome (https://fontawesome.com)
  License: SIL OFL 1.1 (Icons), MIT (Code)

Content
-------
- Sample blog posts written specifically for this demo
- No copyrighted content included
```

---

## 8. DEMO IMPORT ADMIN NOTICE

### Guide Users to Import

```php
<?php
/**
 * Admin notice for demo import
 */
function themename_demo_import_notice() {
    // Only show on theme activation
    if (get_transient('themename_activated')) {
        // Delete transient
        delete_transient('themename_activated');

        // Check if OCDI is installed
        if (!class_exists('OCDI_Plugin')) {
            ?>
            <div class="notice notice-info is-dismissible">
                <h3><?php esc_html_e('Welcome to Your Theme!', 'themename'); ?></h3>
                <p>
                    <?php esc_html_e('To import demo content and make your site look like our demo, please install the One Click Demo Import plugin.', 'themename'); ?>
                </p>
                <p>
                    <a href="<?php echo esc_url(admin_url('themes.php?page=tgmpa-install-plugins')); ?>" class="button button-primary">
                        <?php esc_html_e('Install Recommended Plugins', 'themename'); ?>
                    </a>
                </p>
            </div>
            <?php
        } else {
            ?>
            <div class="notice notice-success is-dismissible">
                <h3><?php esc_html_e('Welcome to Your Theme!', 'themename'); ?></h3>
                <p>
                    <?php esc_html_e('Ready to import demo content? Click the button below to get started.', 'themename'); ?>
                </p>
                <p>
                    <a href="<?php echo esc_url(admin_url('themes.php?page=one-click-demo-import')); ?>" class="button button-primary">
                        <?php esc_html_e('Import Demo Content', 'themename'); ?>
                    </a>
                </p>
            </div>
            <?php
        }
    }
}
add_action('admin_notices', 'themename_demo_import_notice');

/**
 * Set transient on theme activation
 */
function themename_activation_transient() {
    set_transient('themename_activated', true, 60);
}
add_action('after_switch_theme', 'themename_activation_transient');
```

---

## 9. ALTERNATIVE: MANUAL IMPORT GUIDE

### For Themes Without OCDI

**Create import-guide.md:**
```markdown
# Demo Content Import Guide

## Method 1: WordPress Importer

1. **Install WordPress Importer**
   - Go to: Tools → Import
   - Click "WordPress" and install the importer

2. **Import Content**
   - Go to: Tools → Import → WordPress
   - Upload: `demo/content.xml`
   - Check: "Download and import file attachments"
   - Click: "Submit"

3. **Import Widgets**
   - Install: Widget Importer & Exporter plugin
   - Go to: Tools → Widget Importer & Exporter
   - Upload: `demo/widgets.wie`
   - Click: "Import Widgets"

4. **Import Customizer Settings**
   - Install: Customizer Export/Import plugin
   - Go to: Appearance → Customize
   - Click: Export/Import tab
   - Upload: `demo/customizer.dat`
   - Click: "Import"

5. **Setup Homepage**
   - Go to: Settings → Reading
   - Select: "A static page"
   - Front page: "Home"
   - Posts page: "Blog"
   - Save Changes

6. **Setup Menus**
   - Go to: Appearance → Menus
   - Assign "Primary Menu" to "Primary" location
   - Assign "Footer Menu" to "Footer" location
   - Save Menu

7. **Setup Permalinks**
   - Go to: Settings → Permalinks
   - Select: "Post name"
   - Save Changes

## Done!
Your site should now match our demo.
```

---

## 10. DEMO CONTENT CHECKLIST

### Before Export

```
Content Creation:
✓ 10-15 blog posts created
✓ 5-10 pages created
✓ Real, meaningful content (no lorem ipsum)
✓ Featured images on all posts
✓ Categories assigned (3-5 categories)
✓ Tags assigned (10-15 tags total)
✓ Comments added to posts
✓ Proper post dates (spread over months)

Navigation Menus:
✓ Primary menu created and assigned
✓ Footer menu created and assigned
✓ Social menu created (if applicable)
✓ All menu items working

Widgets:
✓ Sidebar widgets configured
✓ Footer widgets configured
✓ All widgets have proper content

Settings:
✓ Front page set to static page
✓ Posts page assigned
✓ Permalink structure set
✓ Customizer options configured
✓ Theme options saved (if applicable)

Media:
✓ All images properly named
✓ Images optimized (under 200KB)
✓ Alt text added to images
✓ GPL-compatible licenses verified

Quality Check:
✓ No broken links
✓ No placeholder text
✓ No "coming soon" pages
✓ All forms working
✓ Mobile responsive verified
```

### After Import Testing

```
Test Installation:
✓ Fresh WordPress install
✓ Theme installed and activated
✓ Demo content imported successfully
✓ Homepage displays correctly
✓ Blog page displays correctly
✓ Single post page displays correctly
✓ Menus working
✓ Widgets displaying
✓ Customizer settings applied
✓ No errors or warnings
✓ Mobile display verified
```

---

## 11. DEMO HOSTING REQUIREMENTS

### Live Demo Site

**Requirements:**
```
✓ Dedicated subdomain (demo.yourtheme.com)
✓ Fast hosting (shared hosting acceptable)
✓ SSL certificate (https://)
✓ Regular backups
✓ Auto-reset (recommended: daily or weekly)
✓ Latest WordPress version
✓ Latest PHP version (8.0+)
```

**Auto-Reset Plugin:**
```
WP Reset (Free)
- Scheduled database resets
- Keeps theme files intact
- Prevents demo vandalism
```

---

## ✅ DEMO CONTENT FINAL CHECKLIST

### Premium Theme Requirements

- [ ] OCDI plugin integration implemented
- [ ] Demo content.xml file created (10-15 posts)
- [ ] Widgets.wie file exported
- [ ] Customizer.dat file exported
- [ ] All images GPL-compatible
- [ ] Credits.txt file created
- [ ] Multiple demo variations (optional)
- [ ] Live demo site hosted
- [ ] Demo import tested on fresh install
- [ ] Import guide documentation created
- [ ] Admin notice for import added
- [ ] Preview screenshots created (1200×900)

---

**Professional demo content = Higher sales + Better reviews!** ✨
