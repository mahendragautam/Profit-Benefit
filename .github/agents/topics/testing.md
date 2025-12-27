# Theme Testing - Complete Guide

---
name: testing
hidden: true

> **Load this file when**: Keywords detected - test theme, theme check, validate theme, accessibility test, browser test
>
> **Purpose**: WordPress theme-specific testing patterns and validation

---

## 🎯 THEME TESTING vs PLUGIN TESTING

**Themes = Presentation Testing**
- ✅ Visual rendering (browser tests)
- ✅ Accessibility (WCAG compliance)
- ✅ Performance (Core Web Vitals)
- ✅ WordPress standards (Theme Check)
- ✅ Responsive design
- ✅ Cross-browser compatibility

**Plugins = Logic Testing** (NOT for themes)
- ❌ PHPUnit unit tests
- ❌ Integration tests
- ❌ Mocking/dependency injection
- ❌ REST API testing

---

## 1️⃣ AUTOMATED THEME VALIDATION

### Theme Check Plugin:

**Installation:**
```bash
# Via WP-CLI
wp plugin install theme-check --activate

# Or download from WordPress.org
# Then activate in WP Admin → Plugins
```

**Usage:**
```bash
# Via WordPress Admin
Appearance → Themes → Select your theme → Theme Check

# Via WP-CLI
wp theme check my-theme

# Expected checks:
# ✓ Required files present (style.css, index.php)
# ✓ Proper escaping used (esc_html, esc_attr, esc_url)
# ✓ Translation ready (__(), _e(), textdomain)
# ✓ No deprecated functions
# ✓ Coding standards met
# ✓ No plugin functionality
# ✓ Proper enqueuing
```

**Common Issues Detected:**
```
WARNING: Found wp_deregister_script in functions.php
→ Themes should not deregister core scripts

ERROR: All text output must be escaped
→ Use esc_html(), esc_attr(), esc_url()

INFO: Theme doesn't have tags in style.css
→ Add tags for WordPress.org submission

REQUIRED: screenshot.png is missing
→ Add 1200x900 screenshot
```

### PHP_CodeSniffer (WordPress Standards):

**Setup:**
```bash
# Install via Composer
composer require --dev squizlabs/php_codesniffer
composer require --dev wp-coding-standards/wpcs

# Configure PHPCS
./vendor/bin/phpcs --config-set installed_paths vendor/wp-coding-standards/wpcs
```

**Usage:**
```bash
# Check entire theme
./vendor/bin/phpcs --standard=WordPress ./

# Check specific directory
./vendor/bin/phpcs --standard=WordPress ./template-parts/

# Auto-fix issues
./vendor/bin/phpcbf --standard=WordPress ./

# Generate report
./vendor/bin/phpcs --standard=WordPress --report=summary ./ > phpcs-report.txt
```

**PHPCS Configuration (.phpcs.xml):**
```xml
<?xml version="1.0"?>
<ruleset name="Theme Coding Standards">
    <description>WordPress Coding Standards for Theme</description>
    
    <!-- Check all PHP files -->
    <file>.</file>
    
    <!-- Exclude vendor and node_modules -->
    <exclude-pattern>*/vendor/*</exclude-pattern>
    <exclude-pattern>*/node_modules/*</exclude-pattern>
    
    <!-- Use WordPress standards -->
    <rule ref="WordPress"/>
    <rule ref="WordPress-Extra"/>
    
    <!-- Check text domain -->
    <rule ref="WordPress.WP.I18n">
        <properties>
            <property name="text_domain" type="array">
                <element value="themename"/>
            </property>
        </properties>
    </rule>
    
    <!-- Set minimum PHP version -->
    <config name="minimum_supported_wp_version" value="6.0"/>
</ruleset>
```

---

## 2️⃣ BROWSER TESTING (AUTOMATED)

### Playwright Setup:

**Installation:**
```bash
npm init -y
npm install -D @playwright/test
npx playwright install
```

**Configuration (playwright.config.js):**
```javascript
// playwright.config.js
const { defineConfig, devices } = require('@playwright/test');

module.exports = defineConfig({
  testDir: './tests',
  fullyParallel: true,
  forbidOnly: !!process.env.CI,
  retries: process.env.CI ? 2 : 0,
  workers: process.env.CI ? 1 : undefined,
  reporter: 'html',
  
  use: {
    baseURL: 'http://localhost:8080',
    trace: 'on-first-retry',
    screenshot: 'only-on-failure',
  },
  
  projects: [
    {
      name: 'chromium',
      use: { ...devices['Desktop Chrome'] },
    },
    {
      name: 'firefox',
      use: { ...devices['Desktop Firefox'] },
    },
    {
      name: 'webkit',
      use: { ...devices['Desktop Safari'] },
    },
    {
      name: 'Mobile Chrome',
      use: { ...devices['Pixel 5'] },
    },
    {
      name: 'Mobile Safari',
      use: { ...devices['iPhone 12'] },
    },
  ],
});
```

### Theme Tests (tests/theme.spec.js):

```javascript
const { test, expect } = require('@playwright/test');

test.describe('Theme Basic Functionality', () => {
  
  test('homepage loads correctly', async ({ page }) => {
    await page.goto('/');
    
    // Header present
    await expect(page.locator('.site-header')).toBeVisible();
    
    // Site title/logo
    await expect(page.locator('.site-title, .custom-logo')).toBeVisible();
    
    // Navigation menu
    await expect(page.locator('#primary-menu')).toBeVisible();
    
    // Main content
    await expect(page.locator('#primary')).toBeVisible();
    
    // Footer present
    await expect(page.locator('.site-footer')).toBeVisible();
  });
  
  test('navigation menu works', async ({ page }) => {
    await page.goto('/');
    
    // Get first menu link
    const firstLink = page.locator('#primary-menu a').first();
    const linkText = await firstLink.textContent();
    
    // Click menu item
    await firstLink.click();
    
    // Wait for navigation
    await page.waitForLoadState('networkidle');
    
    // Verify page changed
    await expect(page).not.toHaveURL('/');
  });
  
  test('responsive mobile menu', async ({ page }) => {
    // Set mobile viewport
    await page.setViewportSize({ width: 375, height: 667 });
    await page.goto('/');
    
    // Menu toggle visible on mobile
    await expect(page.locator('.menu-toggle')).toBeVisible();
    
    // Menu hidden by default
    const menu = page.locator('#primary-menu');
    const isVisible = await menu.isVisible();
    
    // Click toggle
    await page.click('.menu-toggle');
    
    // Wait for animation
    await page.waitForTimeout(500);
    
    // Menu should toggle state
    const isVisibleAfter = await menu.isVisible();
    expect(isVisibleAfter).not.toBe(isVisible);
  });
  
  test('single post template renders', async ({ page }) => {
    await page.goto('/sample-post/');
    
    // Post title
    await expect(page.locator('.entry-title')).toBeVisible();
    
    // Post content
    await expect(page.locator('.entry-content')).toBeVisible();
    
    // Post meta
    await expect(page.locator('.entry-meta')).toBeVisible();
    
    // Check for author
    await expect(page.locator('.byline, .author')).toBeVisible();
    
    // Check for date
    await expect(page.locator('.posted-on, .entry-date')).toBeVisible();
  });
  
  test('page template renders', async ({ page }) => {
    await page.goto('/sample-page/');
    
    // Page title
    await expect(page.locator('.entry-title')).toBeVisible();
    
    // Page content
    await expect(page.locator('.entry-content')).toBeVisible();
  });
  
  test('archive page renders', async ({ page }) => {
    await page.goto('/category/uncategorized/');
    
    // Archive title
    await expect(page.locator('.page-title, .archive-title')).toBeVisible();
    
    // Post list
    const posts = page.locator('article');
    await expect(posts).toHaveCount({ min: 1 });
  });
  
  test('search functionality works', async ({ page }) => {
    await page.goto('/');
    
    // Find search form
    const searchForm = page.locator('.search-form, form[role="search"]');
    await expect(searchForm).toBeVisible();
    
    // Enter search term
    await page.fill('.search-form input[type="search"]', 'test');
    
    // Submit search
    await page.click('.search-form button[type="submit"]');
    
    // Wait for results
    await page.waitForLoadState('networkidle');
    
    // Check we're on search results page
    expect(page.url()).toContain('?s=test');
  });
  
  test('404 page renders', async ({ page }) => {
    const response = await page.goto('/this-page-does-not-exist/');
    
    // Check 404 status
    expect(response.status()).toBe(404);
    
    // Check 404 content
    await expect(page.locator('body')).toContainText(/not found|404/i);
  });
  
  test('widgets render correctly', async ({ page }) => {
    await page.goto('/');
    
    // Check if sidebar has widgets
    const sidebar = page.locator('.widget-area, #secondary');
    
    if (await sidebar.isVisible()) {
      const widgets = sidebar.locator('.widget');
      await expect(widgets).toHaveCount({ min: 1 });
    }
  });
  
  test('footer widgets render', async ({ page }) => {
    await page.goto('/');
    
    // Check footer widget areas
    const footerWidgets = page.locator('.footer-widgets .widget');
    
    if (await footerWidgets.first().isVisible()) {
      await expect(footerWidgets).toHaveCount({ min: 1 });
    }
  });
});

test.describe('Theme Responsiveness', () => {
  
  const viewports = [
    { name: 'Mobile', width: 375, height: 667 },
    { name: 'Tablet', width: 768, height: 1024 },
    { name: 'Desktop', width: 1920, height: 1080 },
  ];
  
  for (const viewport of viewports) {
    test(`${viewport.name} layout`, async ({ page }) => {
      await page.setViewportSize({ 
        width: viewport.width, 
        height: viewport.height 
      });
      
      await page.goto('/');
      
      // Content should be visible
      await expect(page.locator('#primary')).toBeVisible();
      
      // No horizontal scroll
      const bodyWidth = await page.evaluate(() => document.body.scrollWidth);
      expect(bodyWidth).toBeLessThanOrEqual(viewport.width);
    });
  }
});
```

**Run Tests:**
```bash
# Run all tests
npx playwright test

# Run specific test
npx playwright test theme.spec.js

# Run with UI
npx playwright test --ui

# Show report
npx playwright show-report
```

---

## 3️⃣ ACCESSIBILITY TESTING

### Axe Core (Automated):

**Installation:**
```bash
npm install -D @axe-core/playwright
```

**Tests (tests/accessibility.spec.js):**
```javascript
const { test, expect } = require('@playwright/test');
const AxeBuilder = require('@axe-core/playwright').default;

test.describe('Accessibility Tests', () => {
  
  test('homepage accessibility', async ({ page }) => {
    await page.goto('/');
    
    const results = await new AxeBuilder({ page })
      .withTags(['wcag2a', 'wcag2aa', 'wcag21a', 'wcag21aa'])
      .analyze();
    
    expect(results.violations).toEqual([]);
  });
  
  test('single post accessibility', async ({ page }) => {
    await page.goto('/sample-post/');
    
    const results = await new AxeBuilder({ page })
      .withTags(['wcag2a', 'wcag2aa'])
      .analyze();
    
    expect(results.violations).toEqual([]);
  });
  
  test('navigation accessibility', async ({ page }) => {
    await page.goto('/');
    
    const results = await new AxeBuilder({ page })
      .include('#site-navigation')
      .analyze();
    
    expect(results.violations).toEqual([]);
  });
  
  test('forms accessibility', async ({ page }) => {
    await page.goto('/');
    
    const results = await new AxeBuilder({ page })
      .include('.search-form')
      .analyze();
    
    expect(results.violations).toEqual([]);
  });
});
```

### Pa11y (CLI Tool):

**Installation:**
```bash
npm install -g pa11y
```

**Usage:**
```bash
# Test single page
pa11y http://localhost:8080

# Test with WCAG 2.1 AA standard
pa11y --standard WCAG2AA http://localhost:8080

# Test multiple pages
pa11y http://localhost:8080 \
      http://localhost:8080/sample-post/ \
      http://localhost:8080/category/news/

# Generate HTML report
pa11y --reporter html http://localhost:8080 > accessibility-report.html

# CI mode (exit with error if issues found)
pa11y --threshold 0 http://localhost:8080
```

**Pa11y Config (pa11y.json):**
```json
{
  "standard": "WCAG2AA",
  "timeout": 30000,
  "wait": 1000,
  "ignore": [
    "notice",
    "warning"
  ],
  "hideElements": ".cookie-banner",
  "urls": [
    "http://localhost:8080",
    "http://localhost:8080/sample-post/",
    "http://localhost:8080/sample-page/",
    "http://localhost:8080/category/uncategorized/"
  ]
}
```

### Manual Accessibility Checklist:

```
Keyboard Navigation:
✓ Tab through all interactive elements
✓ Shift+Tab moves backward
✓ Enter activates links/buttons
✓ Esc closes modals/menus
✓ Focus visible on all elements
✓ No keyboard traps

Skip Links:
✓ Skip to content link present
✓ Skip link functional
✓ Skip link visible on focus

ARIA:
✓ Landmarks used (header, nav, main, footer)
✓ Proper ARIA labels on icons
✓ aria-expanded on toggles
✓ aria-current on active menu item

Forms:
✓ All inputs have labels
✓ Required fields marked
✓ Error messages clear
✓ Fieldsets for radio/checkbox groups

Images:
✓ Decorative images: alt=""
✓ Content images: descriptive alt text
✓ No text in images

Color Contrast:
✓ Text: 4.5:1 minimum
✓ Large text (18pt+): 3:1 minimum
✓ UI components: 3:1 minimum
✓ Don't rely on color alone

Semantic HTML:
✓ Proper heading hierarchy (h1-h6)
✓ Lists use <ul>, <ol>, <li>
✓ Buttons use <button>
✓ Links use <a>
```

---

## 4️⃣ PERFORMANCE TESTING

### Lighthouse:

**Installation:**
```bash
npm install -g lighthouse
```

**Usage:**
```bash
# Test homepage
lighthouse http://localhost:8080 --view

# Test with specific categories
lighthouse http://localhost:8080 \
  --only-categories=performance,accessibility,best-practices,seo \
  --output=html \
  --output-path=./lighthouse-report.html

# CI mode with budget
lighthouse http://localhost:8080 \
  --budget-path=./budget.json \
  --preset=desktop
```

**Lighthouse Budget (budget.json):**
```json
{
  "timings": [
    {
      "metric": "first-contentful-paint",
      "budget": 2000
    },
    {
      "metric": "largest-contentful-paint",
      "budget": 2500
    },
    {
      "metric": "cumulative-layout-shift",
      "budget": 0.1
    },
    {
      "metric": "total-blocking-time",
      "budget": 200
    }
  ],
  "resourceSizes": [
    {
      "resourceType": "script",
      "budget": 300
    },
    {
      "resourceType": "stylesheet",
      "budget": 100
    },
    {
      "resourceType": "image",
      "budget": 500
    },
    {
      "resourceType": "total",
      "budget": 1000
    }
  ],
  "resourceCounts": [
    {
      "resourceType": "third-party",
      "budget": 10
    }
  ]
}
```

**Core Web Vitals Targets:**
```
✓ LCP (Largest Contentful Paint) < 2.5s
✓ FID (First Input Delay) < 100ms
✓ CLS (Cumulative Layout Shift) < 0.1
✓ FCP (First Contentful Paint) < 1.8s
✓ Speed Index < 3.4s
✓ Time to Interactive < 3.8s
```

### Query Monitor Plugin:

**Installation:**
```bash
wp plugin install query-monitor --activate
```

**What to Check:**
```
Database Queries:
✓ Total queries < 30
✓ Query time < 0.05s per query
✓ No duplicate queries
✓ No slow queries (> 0.1s)

Scripts & Styles:
✓ No dependency errors
✓ Proper enqueuing
✓ Version strings present
✓ Conditional loading

PHP Errors:
✓ No errors
✓ No warnings
✓ No notices
✓ No deprecated functions

Memory:
✓ Peak memory < 32MB
✓ No memory leaks
```

---

## 5️⃣ VISUAL REGRESSION TESTING

### BackstopJS:

**Installation:**
```bash
npm install -g backstopjs
backstop init
```

**Configuration (backstop.json):**
```json
{
  "id": "my-theme",
  "viewports": [
    {
      "label": "phone",
      "width": 375,
      "height": 667
    },
    {
      "label": "tablet",
      "width": 768,
      "height": 1024
    },
    {
      "label": "desktop",
      "width": 1920,
      "height": 1080
    }
  ],
  "scenarios": [
    {
      "label": "Homepage",
      "url": "http://localhost:8080",
      "selectors": ["document"],
      "delay": 500,
      "misMatchThreshold": 0.1
    },
    {
      "label": "Single Post",
      "url": "http://localhost:8080/sample-post/",
      "selectors": ["document"],
      "delay": 500
    },
    {
      "label": "Archive",
      "url": "http://localhost:8080/category/news/",
      "selectors": ["document"],
      "delay": 500
    },
    {
      "label": "Mobile Menu Open",
      "url": "http://localhost:8080",
      "selectors": ["document"],
      "clickSelector": ".menu-toggle",
      "delay": 500,
      "viewports": [
        {
          "label": "phone",
          "width": 375,
          "height": 667
        }
      ]
    }
  ],
  "paths": {
    "bitmaps_reference": "backstop_data/bitmaps_reference",
    "bitmaps_test": "backstop_data/bitmaps_test",
    "engine_scripts": "backstop_data/engine_scripts",
    "html_report": "backstop_data/html_report"
  },
  "report": ["browser"],
  "engine": "puppeteer"
}
```

**Usage:**
```bash
# Create reference screenshots (baseline)
backstop reference

# Test for visual changes
backstop test

# Approve new changes
backstop approve

# Open last report
backstop openReport
```

---

## 6️⃣ CROSS-BROWSER TESTING

### Playwright (Built-in):
```bash
# Test all browsers
npx playwright test

# Test specific browser
npx playwright test --project=chromium
npx playwright test --project=firefox
npx playwright test --project=webkit
```

### BrowserStack (Cloud Testing):

**Configuration (wdio.conf.js):**
```javascript
exports.config = {
  user: process.env.BROWSERSTACK_USERNAME,
  key: process.env.BROWSERSTACK_ACCESS_KEY,
  hostname: 'hub.browserstack.com',
  
  capabilities: [
    {
      browserName: 'Chrome',
      browser_version: 'latest',
      os: 'Windows',
      os_version: '10',
      'browserstack.local': 'false'
    },
    {
      browserName: 'Safari',
      browser_version: 'latest',
      os: 'OS X',
      os_version: 'Monterey'
    },
    {
      browserName: 'Firefox',
      browser_version: 'latest',
      os: 'Windows',
      os_version: '10'
    },
    {
      browserName: 'Edge',
      browser_version: 'latest',
      os: 'Windows',
      os_version: '10'
    }
  ],
  
  specs: ['./tests/**/*.spec.js']
};
```

---

## 7️⃣ WORDPRESS-SPECIFIC TESTING

### Theme Unit Test Data:

**Import Test Data:**
```bash
# Install WordPress Importer
wp plugin install wordpress-importer --activate

# Import theme unit test data
wp import https://raw.githubusercontent.com/WPTT/theme-unit-test/master/themeunittestdata.wordpress.xml --authors=create

# Or download and import via WP Admin
# Tools → Import → WordPress
```

**What It Tests:**
```
✓ Very long titles
✓ Posts with no titles
✓ Posts with many categories/tags
✓ Nested comments (10 levels)
✓ Gallery posts
✓ Video embeds
✓ Audio embeds
✓ Blockquotes
✓ Lists (ordered, unordered)
✓ Code blocks
✓ Tables
✓ Images (various sizes)
✓ Pagination
```

**Manual Checks:**
```
✓ Long titles don't break layout
✓ Empty titles handled gracefully
✓ Many tags display properly
✓ Nested comments render correctly
✓ Galleries work on mobile
✓ Embedded media responsive
✓ Code blocks don't overflow
```

---

## 8️⃣ CI/CD FOR THEMES

### GitHub Actions Workflow:

**.github/workflows/theme-tests.yml:**
```yaml
name: Theme Tests

on:
  push:
    branches: [ main, develop ]
  pull_request:
    branches: [ main ]

jobs:
  theme-check:
    name: Theme Check
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v3
      
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.2'
      
      - name: Install Theme Check
        run: |
          wget https://downloads.wordpress.org/plugin/theme-check.latest-stable.zip
          unzip theme-check.latest-stable.zip -d /tmp/
      
      - name: Run Theme Check
        run: |
          # Theme Check validation
          # (requires WordPress environment)
          echo "Theme Check would run here"
  
  phpcs:
    name: WordPress Coding Standards
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v3
      
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.2'
      
      - name: Install dependencies
        run: |
          composer require --dev squizlabs/php_codesniffer
          composer require --dev wp-coding-standards/wpcs
          ./vendor/bin/phpcs --config-set installed_paths vendor/wp-coding-standards/wpcs
      
      - name: Run PHPCS
        run: ./vendor/bin/phpcs --standard=WordPress --extensions=php .
  
  playwright:
    name: Browser Tests
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v3
      
      - name: Setup Node
        uses: actions/setup-node@v3
        with:
          node-version: '18'
      
      - name: Install dependencies
        run: npm ci
      
      - name: Install Playwright
        run: npx playwright install --with-deps
      
      - name: Setup WordPress
        run: |
          docker-compose up -d
          # Wait for WordPress
          sleep 30
      
      - name: Run Playwright tests
        run: npx playwright test
      
      - uses: actions/upload-artifact@v3
        if: always()
        with:
          name: playwright-report
          path: playwright-report/
  
  accessibility:
    name: Accessibility Tests
    runs-on: ubuntu-latest
    needs: playwright
    steps:
      - uses: actions/checkout@v3
      
      - name: Setup Node
        uses: actions/setup-node@v3
        with:
          node-version: '18'
      
      - name: Setup WordPress
        run: |
          docker-compose up -d
          sleep 30
      
      - name: Run Pa11y
        run: |
          npm install -g pa11y
          pa11y --standard WCAG2AA http://localhost:8080
  
  lighthouse:
    name: Performance Tests
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v3
      
      - name: Setup WordPress
        run: |
          docker-compose up -d
          sleep 30
      
      - name: Run Lighthouse CI
        uses: treosh/lighthouse-ci-action@v9
        with:
          urls: |
            http://localhost:8080
            http://localhost:8080/sample-post/
          budgetPath: ./budget.json
          uploadArtifacts: true
```

---

## ✅ COMPLETE THEME TESTING CHECKLIST

### Before Each Commit:
- [ ] Theme Check passes (no errors)
- [ ] PHPCS passes (WordPress standards)
- [ ] No PHP errors/warnings
- [ ] No JavaScript errors

### Before Each Release:
- [ ] All Playwright tests pass
- [ ] Accessibility tests pass (WCAG 2.1 AA)
- [ ] Performance tests pass (Lighthouse > 90)
- [ ] Visual regression tests approved
- [ ] Cross-browser testing complete
- [ ] Theme Unit Test data displays correctly
- [ ] Mobile responsive (all viewports)
- [ ] Query Monitor shows no issues
- [ ] All templates render correctly
- [ ] Navigation works
- [ ] Search works
- [ ] Comments work (if enabled)
- [ ] Widgets display correctly
- [ ] Translation ready
- [ ] Child theme compatible
- [ ] Documentation updated

---

## 🛠️ TESTING TOOLS SUMMARY

| Tool | Purpose | Frequency |
|------|---------|-----------|
| **Theme Check** | WordPress compliance | Every commit |
| **PHPCS** | Coding standards | Every commit |
| **Playwright** | Browser automation | Daily |
| **Axe/Pa11y** | Accessibility | Every release |
| **Lighthouse** | Performance | Every release |
| **BackstopJS** | Visual regression | After CSS changes |
| **Query Monitor** | WordPress debugging | Development |
| **BrowserStack** | Cross-browser | Before release |
| **Theme Unit Test** | Content edge cases | Before release |

---

## 🎯 TESTING PRIORITIES

### Critical (Must Pass):
1. Theme Check (no errors)
2. PHPCS (WordPress standards)
3. Accessibility (WCAG 2.1 AA)
4. No JavaScript errors
5. No PHP errors

### Important (Should Pass):
1. Performance (Lighthouse > 90)
2. Cross-browser compatibility
3. Responsive design
4. Visual regression

### Nice to Have:
1. Performance (Lighthouse > 95)
2. Zero accessibility warnings
3. Perfect visual regression

---

**Theme testing focuses on USER EXPERIENCE, not code logic!** ✅
