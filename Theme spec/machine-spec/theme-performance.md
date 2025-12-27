---
```markdown
# Performance — Theme Optimization and Core Web Vitals

Load when: optimize, performance, slow theme, Core Web Vitals, lazy load, critical css

Purpose: Provide actionable rules to keep the theme responsive and performant across devices.

Core rules

- Critical CSS: Keep above-the-fold CSS minimal. Consider generating critical CSS per template at build time.
- Asset loading: Defer non-critical JS, load scripts in footer when possible, and use `preload`/`prefetch` for important assets.
- Images: Serve responsive images via `srcset` and `sizes`. Use modern formats (WebP/AVIF) where supported.
- Lazy loading: Use native `loading="lazy"` for images and defer offscreen media.
- Caching: Use proper cache headers for static assets and version assets via query strings or file names.

Automated checks

- Lighthouse score (desktop & mobile) should be measured; critical thresholds: Performance >= 80 for release.
- Check Largest Contentful Paint (LCP) < 2.5s on simulated 75th percentile mobile network.

Example recommended practices

- Enqueue scripts/styles with versioning and localized data only when needed:

```php
wp_enqueue_script( 'pb-main', get_template_directory_uri() . '/assets/js/main.js', [], '1.2.0', true );
```

- Use responsive image markup in templates:

```php
the_post_thumbnail( 'large', [ 'loading' => 'lazy', 'sizes' => '(max-width: 600px) 100vw, 600px' ] );
```

Build-time tooling

- Minify and concatenate CSS/JS during build. Remove unused CSS with purge tools.
- Generate optimized image sizes and WebP conversions.

Accept/reject criteria

- No render-blocking CSS larger than 50KB on the critical path, LCP within threshold, and a passing Lighthouse baseline.

```