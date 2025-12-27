# ThemeForest/Envato Marketplace Submission Specification

## Overview
This specification covers all requirements for successfully submitting and selling WordPress themes on ThemeForest (Envato Market), the world's largest WordPress theme marketplace.

## 1. Quality Requirements

### Technical Quality Checklist
- [ ] **100% WordPress Coding Standards** - Must pass WordPress-Coding-Standards checks
- [ ] **Theme Check Plugin** - Zero errors, minimal warnings
- [ ] **WP_DEBUG Clean** - No PHP errors, warnings, or notices
- [ ] **Valid HTML5** - Pass W3C validation
- [ ] **Valid CSS3** - Pass W3C CSS validation
- [ ] **Cross-browser Compatible** - Chrome, Firefox, Safari, Edge
- [ ] **Responsive Design** - Mobile, tablet, desktop
- [ ] **Performance Optimized** - Fast loading, optimized assets
- [ ] **Security Hardened** - Data sanitization, nonce verification, capability checks
- [ ] **Accessibility** - WCAG 2.1 AA compliance preferred

### Design Quality Checklist
- [ ] **Professional Design** - High-quality, modern aesthetic
- [ ] **Consistent Typography** - Proper hierarchy and readability
- [ ] **Color Harmony** - Professional color scheme
- [ ] **Proper Spacing** - Adequate white space and padding
- [ ] **High-Quality Images** - Professional screenshots and demos
- [ ] **Attention to Detail** - Pixel-perfect implementation
- [ ] **Modern UI/UX** - Current design trends and best practices

### Code Quality Checklist
- [ ] **Well-Organized** - Logical file/folder structure
- [ ] **Commented Code** - Clear inline comments
- [ ] **PHPDoc Documentation** - Complete function documentation
- [ ] **Modular Code** - Reusable, maintainable components
- [ ] **No Hard-coding** - Dynamic, customizable code
- [ ] **Best Practices** - WordPress functions over custom solutions
- [ ] **Clean Code** - No unused code, debug statements, or TODOs

## 2. Required Files for Submission

### Main Package Structure
```
themename-package/
├── themename/                    # Main theme folder (installable)
│   ├── style.css
│   ├── functions.php
│   ├── screenshot.png
│   └── [all theme files]
├── themename-child/              # Child theme (optional but recommended)
│   ├── style.css
│   └── functions.php
├── documentation/                # User documentation (REQUIRED)
│   ├── index.html                # Main documentation file
│   └── assets/                   # Documentation images/CSS
├── licensing/                    # License files (REQUIRED)
│   ├── license.txt               # GPL license
│   ├── theme-license.txt         # Your theme license terms
│   └── bundled-licenses.txt      # Third-party resource licenses
└── readme.txt                    # Installation instructions
```

### Documentation Requirements (CRITICAL)
Must include comprehensive HTML documentation covering:

**Installation Section:**
```html
<h2>Installation</h2>
<ol>
    <li>Upload theme via WordPress admin (Appearance → Themes → Add New)</li>
    <li>Click "Activate" to enable the theme</li>
    <li>Navigate to customizer or theme settings</li>
    <li>Configure your preferences</li>
</ol>
```

**Required Documentation Sections:**
1. **Introduction** - Theme overview and features
2. **Installation** - Step-by-step setup guide
3. **Theme Features** - Complete feature list with explanations
4. **Customizer Settings** - All customization options
5. **Widget Areas** - Location and usage of widget areas
6. **Navigation Menus** - Menu locations and setup
7. **Page Templates** - Available templates and usage
8. **Plugins** - Required and recommended plugins
9. **Demo Import** - How to import demo content (if applicable)
10. **Troubleshooting** - Common issues and solutions
11. **Support** - How to get help
12. **Changelog** - Version history
13. **Credits** - Third-party resources attribution

**Documentation Best Practices:**
- Use clear, simple language
- Include screenshots for complex steps
- Provide video tutorials (bonus points)
- Mobile-responsive documentation
- Professional design and branding
- Table of contents with anchor links
- Search functionality (recommended)

## 3. Screenshot Requirements

### screenshot.png Specifications
- **Dimensions:** 1200 x 900 pixels (exactly)
- **Format:** PNG
- **Content:** Homepage mockup/design preview
- **Quality:** High-resolution, professional
- **Accuracy:** Must represent actual theme design
- **No Text Overlays:** Avoid promotional text
- **File Size:** Keep under 1MB

### Additional Preview Images
Include in item description:
- Multiple angle/page previews
- Mobile responsive views
- Different page templates
- Color scheme variations
- Feature highlights

## 4. Demo Content

### Live Preview Requirements
- **Working Demo URL** - Fully functional live demo (REQUIRED)
- **Demo Content** - Complete, realistic content
- **All Features Shown** - Demonstrate every feature
- **Performance** - Fast loading demo site
- **Accessibility** - Demo should be accessible
- **No Lorem Ipsum** - Use real, relevant content
- **Professional** - High-quality images and copy

### Demo Data Package (Recommended)
Include importable demo content:
```
demo-data/
├── demo-content.xml              # WordPress export file
├── theme-settings.dat            # Customizer settings export
├── widgets.wie                   # Widget settings export
└── images/                       # Demo images folder
```

**Demo Import Plugin Integration:**
```php
// Support One Click Demo Import plugin
function themename_import_files() {
    return array(
        array(
            'import_file_name'           => 'Demo Import',
            'import_file_url'            => 'URL_to_content.xml',
            'import_widget_file_url'     => 'URL_to_widgets.wie',
            'import_customizer_file_url' => 'URL_to_settings.dat',
            'import_preview_image_url'   => 'URL_to_preview.png',
            'preview_url'                => 'https://demo.yourtheme.com',
        ),
    );
}
add_filter( 'pt-ocdi/import_files', 'themename_import_files' );
```

## 5. Third-Party Resources

### Licensing Requirements
All bundled resources MUST be:
- GPL compatible
- Properly licensed for commercial use
- Credited in documentation
- License files included

### Common Resource Types

**Fonts:**
- Google Fonts (SIL OFL) ✅
- Font Awesome (SIL OFL + MIT) ✅
- Custom fonts - verify license

**Images:**
- Unsplash (Free to use) ✅
- Pexels (Free to use) ✅
- Pixabay (Free to use) ✅
- Stock photos - verify license

**Icons:**
- Font Awesome (SIL OFL) ✅
- Dashicons (GPL) ✅
- Ionicons (MIT) ✅
- Feather Icons (MIT) ✅

**JavaScript Libraries:**
- jQuery (MIT) ✅
- Swiper (MIT) ✅
- AOS (MIT) ✅
- GLightbox (MIT) ✅

**CSS Frameworks:**
- Bootstrap (MIT) ✅
- Tailwind CSS (MIT) ✅
- Foundation (MIT) ✅

### Resource Attribution
Create `credits.txt` file:
```
THEME CREDITS
=============

Theme Design & Development
---------------------------
By: Your Name/Company
Website: https://yourwebsite.com

Fonts
-----
- DM Sans (https://fonts.google.com/specimen/DM+Sans)
  License: SIL Open Font License

- Crimson Pro (https://fonts.google.com/specimen/Crimson+Pro)
  License: SIL Open Font License

Icons
-----
- Font Awesome (https://fontawesome.com)
  License: SIL OFL 1.1 (Icons), MIT (Code)

Images
------
- Unsplash (https://unsplash.com)
  License: Unsplash License

JavaScript Libraries
--------------------
- Swiper (https://swiperjs.com)
  License: MIT

CSS Framework
-------------
- Custom CSS
  License: GPL v2 or later
```

## 6. Plugin Dependencies

### Recommended vs Required
**Required Plugins:**
- Only if theme is completely non-functional without it
- Must be free and available on WordPress.org
- Example: WooCommerce for an eCommerce theme

**Recommended Plugins:**
- Enhance functionality but theme works without them
- Can be premium plugins
- Examples: Contact Form 7, Yoast SEO, WPBakery

### TGM Plugin Activation
Implement TGMPA for plugin recommendations:
```php
require_once get_template_directory() . '/inc/class-tgm-plugin-activation.php';

function themename_register_required_plugins() {
    $plugins = array(
        array(
            'name'     => 'Contact Form 7',
            'slug'     => 'contact-form-7',
            'required' => false,
        ),
        array(
            'name'     => 'Elementor',
            'slug'     => 'elementor',
            'required' => false,
        ),
    );

    $config = array(
        'id'           => 'themename',
        'default_path' => '',
        'menu'         => 'tgmpa-install-plugins',
        'has_notices'  => true,
        'dismissable'  => true,
        'is_automatic' => false,
    );

    tgmpa( $plugins, $config );
}
add_action( 'tgmpa_register', 'themename_register_required_plugins' );
```

## 7. Item Description (Product Page)

### Description Structure
1. **Introduction** - Compelling overview
2. **Key Features** - Bulleted list of main features
3. **Screenshots** - Multiple preview images
4. **Feature Details** - Expanded feature explanations
5. **Technical Specifications** - Requirements and compatibility
6. **Support Information** - How to get help
7. **Changelog** - Recent updates

### Feature Presentation
Use this format:
```markdown
## 🎨 Key Features

✅ **Fully Responsive** - Looks perfect on all devices
✅ **One-Click Demo Import** - Get started in minutes
✅ **Customizer Integration** - Live preview all changes
✅ **SEO Optimized** - Built-in best practices
✅ **Performance Optimized** - Fast loading times
✅ **Translation Ready** - Full i18n support
✅ **Child Theme Included** - Safe customization
✅ **Regular Updates** - Continuous improvements
✅ **6 Months Support** - Expert assistance included
✅ **Well Documented** - Comprehensive guides
```

### Technical Specifications
```markdown
## 📋 Technical Details

- **WordPress Version:** 6.0+
- **PHP Version:** 7.4+
- **MySQL Version:** 5.6+
- **Browser Compatibility:** Chrome, Firefox, Safari, Edge (latest 2 versions)
- **Mobile Optimized:** iOS, Android
- **Page Builders:** Compatible with Elementor, WPBakery (optional)
- **WooCommerce Ready:** Full eCommerce support
- **Translation Files:** .pot file included
- **RTL Support:** Right-to-left language ready
```

## 8. Support Requirements

### Support Commitment
- **6 months included** - Standard Envato license
- **Extended support available** - 12 months option
- **Response time** - Within 24-48 hours (business days)
- **Support channels** - Item comments, support tab, email
- **Support scope** - Bug fixes, usage questions, customization guidance

### Support Best Practices
- Create dedicated support system
- Maintain FAQ/knowledge base
- Respond professionally and promptly
- Document common issues
- Provide code examples
- Be patient and helpful

### Out of Scope
Clarify what's NOT included in support:
- Custom development
- Third-party plugin issues
- Server configuration
- Installation services (unless offered separately)

## 9. Pricing Strategy

### Price Tiers (Regular License)
- **Simple themes:** $19 - $39
- **Standard themes:** $39 - $59
- **Advanced themes:** $59 - $79
- **Premium themes:** $79 - $99

### Factors Affecting Price
- Feature complexity
- Design quality
- Plugin integrations
- Page builder inclusion
- Demo variations
- Support quality
- Update frequency

### License Types
**Regular License ($X):**
- Single end product
- Not for resale
- Free end product allowed

**Extended License ($X * ~31):**
- Charge end users
- SaaS applications
- Multiple end products

## 10. Update Policy

### Update Best Practices
- **Regular updates** - At least quarterly
- **WordPress compatibility** - Test with new WP versions
- **Security patches** - Immediate fixes for vulnerabilities
- **Feature updates** - Gradual improvements
- **Changelog** - Document all changes
- **Backward compatibility** - Don't break existing sites

### Version Numbering
```
MAJOR.MINOR.PATCH

1.0.0 - Initial release
1.0.1 - Bug fix
1.1.0 - New feature (backward compatible)
2.0.0 - Major update (potential breaking changes)
```

### Update Notification
```php
// Theme update checker (use plugin like Envato Market)
function themename_update_check() {
    // Integrate with Envato API
    // Check for theme updates
    // Notify users
}
```

## 11. Rejection Prevention

### Common Rejection Reasons

**❌ Code Quality Issues:**
- PHP errors/warnings/notices
- WordPress coding standards violations
- Insecure code (SQL injection, XSS vulnerabilities)
- Non-escaped output
- Hard-coded values
- Deprecated functions

**❌ Design Issues:**
- Poor visual quality
- Inconsistent design
- Low-quality images
- Typography issues
- Color scheme problems
- Responsive design failures

**❌ Documentation Issues:**
- Missing documentation
- Incomplete instructions
- Poor documentation quality
- No troubleshooting section
- Missing credits

**❌ File Issues:**
- Incorrect file structure
- Missing required files
- Unnecessary files included
- Large file sizes
- Missing licenses

**❌ Functionality Issues:**
- Broken features
- Missing core WordPress support
- Poor user experience
- Compatibility problems
- Performance issues

### Pre-Submission Checklist

**Before submitting, verify:**

**Code Review:**
- [ ] Run Theme Check plugin
- [ ] Enable WP_DEBUG - check for errors
- [ ] Run PHPCS with WordPress standards
- [ ] Check all forms for nonce verification
- [ ] Verify all data sanitization
- [ ] Check all output escaping
- [ ] Remove debug code and console.logs
- [ ] Remove commented-out code
- [ ] Check for hardcoded URLs
- [ ] Verify translation functions

**Design Review:**
- [ ] Test on mobile devices
- [ ] Test on tablets
- [ ] Test on different screen sizes
- [ ] Check cross-browser compatibility
- [ ] Verify typography consistency
- [ ] Check color contrast ratios
- [ ] Verify image quality
- [ ] Check spacing and alignment
- [ ] Test all hover states
- [ ] Verify button/link styles

**Functionality Review:**
- [ ] Test all page templates
- [ ] Test all widgets
- [ ] Test all navigation menus
- [ ] Test customizer settings
- [ ] Test comments functionality
- [ ] Test search functionality
- [ ] Test pagination
- [ ] Test 404 page
- [ ] Test with default WordPress content
- [ ] Test demo import

**Documentation Review:**
- [ ] Complete all required sections
- [ ] Add screenshots for complex steps
- [ ] Verify all links work
- [ ] Check spelling and grammar
- [ ] Include troubleshooting section
- [ ] Add credits for all resources
- [ ] Include changelog

**File Review:**
- [ ] Correct folder structure
- [ ] Include all required files
- [ ] Remove unnecessary files
- [ ] Include all license files
- [ ] Verify screenshot.png dimensions
- [ ] Check total file size
- [ ] Include child theme
- [ ] Include demo data (optional)

**Legal Review:**
- [ ] Verify GPL compliance
- [ ] Check all third-party licenses
- [ ] Include license files
- [ ] Add credits to documentation
- [ ] Verify no copyright violations
- [ ] Check image licenses

## 12. Post-Approval Best Practices

### Launch Strategy
1. **Announcement** - Social media, email list
2. **Launch Price** - Consider introductory discount
3. **Marketing Materials** - Banners, promotional graphics
4. **Blog Post** - Feature announcement
5. **Community Engagement** - Forums, Facebook groups

### Customer Communication
- **Welcome message** - Thank purchasers
- **Getting started guide** - Quick start email
- **Update notifications** - Inform about new versions
- **Newsletter** - Optional for major updates

### Review Management
- **Request reviews** - Politely ask satisfied customers
- **Respond to reviews** - Thank positive, address negative
- **Fix issues** - Act on feedback quickly
- **Document solutions** - Update FAQ with common issues

### Sales Optimization
- **Update item description** - Add new features
- **Create video demos** - YouTube previews
- **Add more screenshots** - Show different features
- **Competitive analysis** - Monitor similar themes
- **Seasonal updates** - Holiday versions, new trends

## 13. Prohibited Practices

### ThemeForest Specific Rules

**❌ DO NOT:**
- Include affiliate links in theme
- Add upsells or advertisements
- Phone home without permission
- Encrypt or obfuscate code
- Include tracking without disclosure
- Violate GPL licensing
- Steal designs/code
- Include malware or backdoors
- Submit nearly identical themes
- Manipulate reviews
- Spam item comments

**❌ AVOID:**
- Overpromising features
- Misleading screenshots
- Fake testimonials
- Clickbait descriptions
- Low-quality documentation
- Poor support response

## 14. Revenue Optimization

### Maximizing Earnings
1. **Quality first** - Better themes = more sales
2. **Regular updates** - Shows active development
3. **Excellent support** - Leads to positive reviews
4. **Good documentation** - Reduces support burden
5. **Marketing** - Promote your theme
6. **Bundle deals** - Multiple themes package
7. **Add-ons** - Premium child themes, plugins
8. **Community building** - Facebook group, Discord

### Envato Author Fees
- **Exclusive Author:** 62.5% commission
- **Non-Exclusive Author:** 45% commission
- **Buyer Fee:** Paid by customer (not you)

### Income Calculation
```
Regular License Sale: $59
Your Commission (Exclusive): $59 × 0.625 = $36.88
Your Commission (Non-Exclusive): $59 × 0.45 = $26.55

Monthly sales: 50 items
Monthly revenue (Exclusive): 50 × $36.88 = $1,844
Annual revenue: $1,844 × 12 = $22,128
```

## 15. Checklist Summary

### Pre-Submission Checklist (Essential)
```
TECHNICAL
☐ Zero PHP errors/warnings (WP_DEBUG enabled)
☐ Theme Check plugin passes
☐ WordPress Coding Standards compliant
☐ All data sanitized and escaped
☐ Nonce verification on forms
☐ No hardcoded values
☐ Translation ready (all strings translatable)
☐ Child theme included
☐ GPL v2+ license

DESIGN
☐ Professional, modern design
☐ Fully responsive (mobile, tablet, desktop)
☐ Cross-browser compatible
☐ High-quality screenshot.png (1200×900)
☐ Consistent typography
☐ Proper color contrast

FUNCTIONALITY
☐ All WordPress features supported
☐ Working live demo
☐ All features functional
☐ Widgets working
☐ Menus working
☐ Customizer working
☐ Comments working

DOCUMENTATION
☐ Comprehensive HTML documentation
☐ Installation instructions
☐ Feature explanations
☐ Customization guide
☐ Troubleshooting section
☐ Credits for resources
☐ Changelog included

FILES
☐ Correct folder structure
☐ All required files included
☐ License files included
☐ No unnecessary files
☐ readme.txt included

LEGAL
☐ GPL compatible
☐ All resources properly licensed
☐ Credits in documentation
☐ No copyright violations
```

---

## Support & Resources

### Official Documentation
- [Envato Author Help Center](https://help.author.envato.com/)
- [WordPress Theme Handbook](https://developer.wordpress.org/themes/)
- [ThemeForest Requirements](https://help.author.envato.com/hc/en-us/categories/202989588-ThemeForest)

### Useful Tools
- **Theme Check Plugin** - WordPress.org plugin for validation
- **Query Monitor** - Debug queries and performance
- **PHPCS** - PHP CodeSniffer with WordPress standards
- **Accessibility Checker** - WCAG compliance testing
- **BrowserStack** - Cross-browser testing
- **GTmetrix** - Performance testing

### Community
- Envato Forums
- WordPress Stack Exchange
- ThemeForest Facebook Groups
- WordPress Theme Review Slack

---

**Version:** 1.0.0
**Last Updated:** 2025-12-26
**Compliance:** ThemeForest Requirements 2025
