# Block Theme Development - Complete Guide (FSE)

---
name: block

hidden: true
always_on: false
protected: true
undeletable: true
 

> **Load this file when**: Keywords detected - block theme, theme.json, FSE, full site editing, patterns, templates, template parts
>
> **Purpose**: Comprehensive patterns for WordPress block themes using Full Site Editing
>
> **Version**: 2.0 | **Last Updated**: December 2025

---

## 📋 TABLE OF CONTENTS

1. [Block Theme vs Classic Theme](#1-block-theme-vs-classic-theme)
2. [Required Files & Structure](#2-required-files--structure)
3. [theme.json - Complete Configuration](#3-themejson---complete-configuration)
4. [Block Templates (HTML)](#4-block-templates-html)
5. [Template Parts](#5-template-parts)
6. [Block Patterns](#6-block-patterns)
7. [Custom Block Styles](#7-custom-block-styles)
8. [Global Styles & Settings](#8-global-styles--settings)
9. [Typography & Fonts](#9-typography--fonts)
10. [Colors & Gradients](#10-colors--gradients)
11. [Layout & Spacing](#11-layout--spacing)
12. [Custom Templates](#12-custom-templates)
13. [Style Variations](#13-style-variations)
14. [functions.php (Optional)](#14-functionsphp-optional)
15. [Block Editor Patterns](#15-block-editor-patterns)
16. [Child Block Themes](#16-child-block-themes)
17. [Migration from Classic](#17-migration-from-classic)
18. [Production Checklist](#18-production-checklist)

---

## 1. BLOCK THEME VS CLASSIC THEME

### Key Differences:

| Feature | Classic Theme | Block Theme |
|---------|---------------|-------------|
| **Templates** | PHP files | HTML files |
| **Configuration** | functions.php | theme.json |
| **Editing** | Code only | Visual Site Editor |
| **Styling** | CSS files | theme.json + CSS |
| **Parts** | PHP includes | HTML template parts |
| **Customization** | Customizer API | Site Editor |
| **Min WordPress** | Any version | 5.9+ (6.0+ recommended) |

### When to Use Block Themes:

✅ **Use Block Theme when:**
- Building new themes for WordPress 6.0+
- Want visual editing for users
- Need modern styling system
- Want consistent design tokens
- Target non-technical users

❌ **Use Classic Theme when:**
- Supporting WordPress < 5.9
- Need complex PHP logic in templates
- Require advanced custom queries
- Building developer-focused themes

---

## 2. REQUIRED FILES & STRUCTURE

### Minimum Required Files:

```
/wp-content/themes/blocktheme/
├── style.css              # Theme header (REQUIRED)
├── theme.json             # Theme configuration (REQUIRED)
├── templates/
│   └── index.html        # Main template (REQUIRED)
└── parts/
    ├── header.html       # Header template part
    └── footer.html       # Footer template part
```

### Recommended Complete Structure:

```
/wp-content/themes/blocktheme/
├── style.css
├── theme.json
├── functions.php          # Optional but recommended
├── screenshot.png         # 1200x900px
├── readme.txt
│
├── templates/             # Block templates (HTML)
│   ├── index.html        # Blog/archive
│   ├── home.html         # Front page
│   ├── single.html       # Single post
│   ├── page.html         # Single page
│   ├── archive.html      # Archive pages
│   ├── search.html       # Search results
│   ├── 404.html          # Not found
│   └── blank.html        # Blank template
│
├── parts/                 # Template parts (HTML)
│   ├── header.html
│   ├── footer.html
│   ├── sidebar.html
│   └── post-meta.html
│
├── patterns/              # Block patterns (PHP)
│   ├── hero-section.php
│   ├── call-to-action.php
│   ├── features-grid.php
│   └── testimonials.php
│
├── styles/                # Style variations (JSON)
│   ├── dark.json
│   ├── light.json
│   └── colorful.json
│
├── assets/                # Theme assets
│   ├── css/
│   │   └── custom.css
│   ├── js/
│   │   └── navigation.js
│   ├── fonts/
│   └── images/
│
└── languages/             # Translation files
    └── blocktheme.pot
```

---

## 3. THEME.JSON - COMPLETE CONFIGURATION

### Minimal theme.json (v2):

```json
{
	"$schema": "https://schemas.wp.org/trunk/theme.json",
	"version": 2,
	"settings": {
		"appearanceTools": true,
		"layout": {
			"contentSize": "800px",
			"wideSize": "1200px"
		}
	}
}
```

### Production-Ready theme.json:

```json
{
	"$schema": "https://schemas.wp.org/trunk/theme.json",
	"version": 2,
	"title": "My Block Theme",
	"settings": {
		"appearanceTools": true,
		"useRootPaddingAwareAlignments": true,
		"layout": {
			"contentSize": "800px",
			"wideSize": "1200px"
		},
		"color": {
			"custom": true,
			"customDuotone": true,
			"customGradient": true,
			"defaultDuotone": true,
			"defaultGradients": true,
			"defaultPalette": true,
			"link": true,
			"palette": [
				{
					"slug": "primary",
					"color": "#0073aa",
					"name": "Primary"
				},
				{
					"slug": "secondary",
					"color": "#005177",
					"name": "Secondary"
				},
				{
					"slug": "foreground",
					"color": "#000000",
					"name": "Foreground"
				},
				{
					"slug": "background",
					"color": "#ffffff",
					"name": "Background"
				},
				{
					"slug": "tertiary",
					"color": "#f0f0f0",
					"name": "Tertiary"
				}
			],
			"gradients": [
				{
					"slug": "primary-to-secondary",
					"gradient": "linear-gradient(135deg, var(--wp--preset--color--primary) 0%, var(--wp--preset--color--secondary) 100%)",
					"name": "Primary to Secondary"
				}
			],
			"duotone": [
				{
					"colors": ["#000000", "#ffffff"],
					"slug": "black-and-white",
					"name": "Black and White"
				}
			]
		},
		"typography": {
			"customFontSize": true,
			"dropCap": true,
			"fluid": true,
			"fontStyle": true,
			"fontWeight": true,
			"letterSpacing": true,
			"lineHeight": true,
			"textDecoration": true,
			"textTransform": true,
			"fontSizes": [
				{
					"slug": "small",
					"size": "0.875rem",
					"name": "Small",
					"fluid": {
						"min": "0.875rem",
						"max": "1rem"
					}
				},
				{
					"slug": "medium",
					"size": "1rem",
					"name": "Medium",
					"fluid": false
				},
				{
					"slug": "large",
					"size": "1.5rem",
					"name": "Large",
					"fluid": {
						"min": "1.25rem",
						"max": "1.75rem"
					}
				},
				{
					"slug": "x-large",
					"size": "2rem",
					"name": "Extra Large",
					"fluid": {
						"min": "1.75rem",
						"max": "2.5rem"
					}
				},
				{
					"slug": "huge",
					"size": "3rem",
					"name": "Huge",
					"fluid": {
						"min": "2.5rem",
						"max": "4rem"
					}
				}
			],
			"fontFamilies": [
				{
					"fontFamily": "-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen-Sans, Ubuntu, Cantarell, 'Helvetica Neue', sans-serif",
					"slug": "system",
					"name": "System Font"
				},
				{
					"fontFamily": "Georgia, serif",
					"slug": "serif",
					"name": "Serif"
				},
				{
					"fontFamily": "Consolas, Monaco, 'Andale Mono', 'Ubuntu Mono', monospace",
					"slug": "monospace",
					"name": "Monospace"
				}
			]
		},
		"spacing": {
			"customSpacingSize": true,
			"spacingScale": {
				"steps": 0
			},
			"spacingSizes": [
				{
					"slug": "30",
					"size": "0.5rem",
					"name": "Extra Small"
				},
				{
					"slug": "40",
					"size": "1rem",
					"name": "Small"
				},
				{
					"slug": "50",
					"size": "1.5rem",
					"name": "Medium"
				},
				{
					"slug": "60",
					"size": "2rem",
					"name": "Large"
				},
				{
					"slug": "70",
					"size": "3rem",
					"name": "Extra Large"
				},
				{
					"slug": "80",
					"size": "4rem",
					"name": "Huge"
				}
			],
			"units": ["px", "em", "rem", "vh", "vw", "%"]
		},
		"border": {
			"color": true,
			"radius": true,
			"style": true,
			"width": true
		},
		"shadow": {
			"defaultPresets": true,
			"presets": [
				{
					"slug": "natural",
					"shadow": "0 2px 8px rgba(0, 0, 0, 0.1)",
					"name": "Natural"
				},
				{
					"slug": "deep",
					"shadow": "0 4px 16px rgba(0, 0, 0, 0.2)",
					"name": "Deep"
				}
			]
		},
		"custom": {
			"spacing": {
				"gap": "1.5rem"
			},
			"typography": {
				"lineHeight": {
					"body": 1.7,
					"heading": 1.3
				}
			}
		},
		"blocks": {
			"core/button": {
				"border": {
					"radius": true
				},
				"color": {
					"palette": [
						{
							"slug": "primary",
							"color": "#0073aa",
							"name": "Primary"
						}
					]
				}
			},
			"core/heading": {
				"typography": {
					"fontSizes": [
						{
							"slug": "large",
							"size": "2rem",
							"name": "Large"
						}
					]
				}
			}
		}
	},
	"styles": {
		"color": {
			"background": "var(--wp--preset--color--background)",
			"text": "var(--wp--preset--color--foreground)"
		},
		"typography": {
			"fontFamily": "var(--wp--preset--font-family--system)",
			"fontSize": "var(--wp--preset--font-size--medium)",
			"lineHeight": "1.7"
		},
		"spacing": {
			"blockGap": "1.5rem",
			"padding": {
				"top": "0",
				"right": "1.5rem",
				"bottom": "0",
				"left": "1.5rem"
			}
		},
		"elements": {
			"link": {
				"color": {
					"text": "var(--wp--preset--color--primary)"
				},
				":hover": {
					"color": {
						"text": "var(--wp--preset--color--secondary)"
					}
				}
			},
			"h1": {
				"typography": {
					"fontSize": "var(--wp--preset--font-size--huge)",
					"lineHeight": "1.2",
					"fontWeight": "700"
				},
				"spacing": {
					"margin": {
						"top": "0",
						"bottom": "1rem"
					}
				}
			},
			"h2": {
				"typography": {
					"fontSize": "var(--wp--preset--font-size--x-large)",
					"lineHeight": "1.3",
					"fontWeight": "600"
				}
			},
			"h3": {
				"typography": {
					"fontSize": "var(--wp--preset--font-size--large)"
				}
			},
			"button": {
				"border": {
					"radius": "4px"
				},
				"color": {
					"background": "var(--wp--preset--color--primary)",
					"text": "var(--wp--preset--color--background)"
				},
				"spacing": {
					"padding": {
						"top": "0.75rem",
						"right": "1.5rem",
						"bottom": "0.75rem",
						"left": "1.5rem"
					}
				},
				"typography": {
					"fontWeight": "600"
				},
				":hover": {
					"color": {
						"background": "var(--wp--preset--color--secondary)"
					}
				}
			}
		},
		"blocks": {
			"core/site-title": {
				"typography": {
					"fontSize": "var(--wp--preset--font-size--large)",
					"fontWeight": "700"
				},
				"elements": {
					"link": {
						"color": {
							"text": "var(--wp--preset--color--foreground)"
						},
						":hover": {
							"color": {
								"text": "var(--wp--preset--color--primary)"
							}
						}
					}
				}
			},
			"core/navigation": {
				"typography": {
					"fontSize": "var(--wp--preset--font-size--small)"
				},
				"elements": {
					"link": {
						":hover": {
							"color": {
								"text": "var(--wp--preset--color--primary)"
							}
						}
					}
				}
			},
			"core/post-title": {
				"typography": {
					"fontSize": "var(--wp--preset--font-size--x-large)",
					"fontWeight": "700"
				}
			},
			"core/post-date": {
				"color": {
					"text": "#666666"
				},
				"typography": {
					"fontSize": "var(--wp--preset--font-size--small)"
				}
			},
			"core/pullquote": {
				"border": {
					"color": "var(--wp--preset--color--primary)",
					"width": "2px 0 2px 0"
				}
			}
		}
	},
	"customTemplates": [
		{
			"name": "blank",
			"title": "Blank",
			"postTypes": ["page"]
		},
		{
			"name": "full-width",
			"title": "Full Width",
			"postTypes": ["page", "post"]
		}
	],
	"templateParts": [
		{
			"name": "header",
			"title": "Header",
			"area": "header"
		},
		{
			"name": "footer",
			"title": "Footer",
			"area": "footer"
		},
		{
			"name": "sidebar",
			"title": "Sidebar",
			"area": "uncategorized"
		}
	]
}
```

---

## 4. BLOCK TEMPLATES (HTML)

### templates/index.html (Main Blog):

```html
<!-- wp:template-part {"slug":"header","tagName":"header"} /-->

<!-- wp:group {"tagName":"main","style":{"spacing":{"margin":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60"}}},"layout":{"type":"constrained"}} -->
<main class="wp-block-group" style="margin-top:var(--wp--preset--spacing--60);margin-bottom:var(--wp--preset--spacing--60)">
    
    <!-- wp:query {"queryId":0,"query":{"perPage":10,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":true}} -->
    <div class="wp-block-query">
        
        <!-- wp:post-template {"style":{"spacing":{"blockGap":"var:preset|spacing|60"}}} -->
            
            <!-- wp:group {"style":{"spacing":{"padding":{"top":"0","right":"0","bottom":"0","left":"0"}}},"layout":{"type":"default"}} -->
            <div class="wp-block-group" style="padding-top:0;padding-right:0;padding-bottom:0;padding-left:0">
                
                <!-- wp:post-featured-image {"isLink":true,"aspectRatio":"16/9"} /-->
                
                <!-- wp:post-title {"isLink":true,"fontSize":"x-large"} /-->
                
                <!-- wp:group {"style":{"spacing":{"blockGap":"0.5rem"}},"layout":{"type":"flex","flexWrap":"wrap"}} -->
                <div class="wp-block-group">
                    <!-- wp:post-date /-->
                    <!-- wp:paragraph -->
                    <p>•</p>
                    <!-- /wp:paragraph -->
                    <!-- wp:post-author {"showAvatar":false} /-->
                </div>
                <!-- /wp:group -->
                
                <!-- wp:post-excerpt {"moreText":"Read More →"} /-->
                
                <!-- wp:separator {"style":{"spacing":{"margin":{"top":"var:preset|spacing|60"}}}} -->
                <hr class="wp-block-separator has-alpha-channel-opacity" style="margin-top:var(--wp--preset--spacing--60)"/>
                <!-- /wp:separator -->
                
            </div>
            <!-- /wp:group -->
            
        <!-- /wp:post-template -->
        
        <!-- wp:query-pagination {"layout":{"type":"flex","justifyContent":"space-between"}} -->
            <!-- wp:query-pagination-previous /-->
            <!-- wp:query-pagination-numbers /-->
            <!-- wp:query-pagination-next /-->
        <!-- /wp:query-pagination -->
        
        <!-- wp:query-no-results -->
            <!-- wp:paragraph {"align":"center"} -->
            <p class="has-text-align-center">No posts found.</p>
            <!-- /wp:paragraph -->
        <!-- /wp:query-no-results -->
        
    </div>
    <!-- /wp:query -->
    
</main>
<!-- /wp:group -->

<!-- wp:template-part {"slug":"footer","tagName":"footer"} /-->
```

### templates/single.html (Single Post):

```html
<!-- wp:template-part {"slug":"header","tagName":"header"} /-->

<!-- wp:group {"tagName":"main","layout":{"type":"constrained"}} -->
<main class="wp-block-group">
    
    <!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60"}}},"layout":{"type":"constrained"}} -->
    <div class="wp-block-group" style="padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--60)">
        
        <!-- wp:post-title {"level":1} /-->
        
        <!-- wp:group {"style":{"spacing":{"blockGap":"0.5rem","margin":{"bottom":"var:preset|spacing|50"}}},"layout":{"type":"flex","flexWrap":"wrap"}} -->
        <div class="wp-block-group" style="margin-bottom:var(--wp--preset--spacing--50)">
            <!-- wp:post-date /-->
            <!-- wp:paragraph -->
            <p>•</p>
            <!-- /wp:paragraph -->
            <!-- wp:post-author {"showAvatar":true} /-->
            <!-- wp:paragraph -->
            <p>•</p>
            <!-- /wp:paragraph -->
            <!-- wp:post-terms {"term":"category"} /-->
        </div>
        <!-- /wp:group -->
        
        <!-- wp:post-featured-image /-->
        
        <!-- wp:post-content {"layout":{"type":"constrained"}} /-->
        
        <!-- wp:group {"style":{"spacing":{"margin":{"top":"var:preset|spacing|60"}}},"layout":{"type":"flex","flexWrap":"wrap"}} -->
        <div class="wp-block-group" style="margin-top:var(--wp--preset--spacing--60)">
            <!-- wp:paragraph -->
            <p><strong>Tags:</strong></p>
            <!-- /wp:paragraph -->
            <!-- wp:post-terms {"term":"post_tag"} /-->
        </div>
        <!-- /wp:group -->
        
        <!-- wp:separator {"style":{"spacing":{"margin":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60"}}}} -->
        <hr class="wp-block-separator has-alpha-channel-opacity" style="margin-top:var(--wp--preset--spacing--60);margin-bottom:var(--wp--preset--spacing--60)"/>
        <!-- /wp:separator -->
        
        <!-- wp:post-navigation-link {"type":"previous","label":"← Previous Post"} /-->
        <!-- wp:post-navigation-link {"label":"Next Post →"} /-->
        
        <!-- wp:comments /-->
        
    </div>
    <!-- /wp:group -->
    
</main>
<!-- /wp:group -->

<!-- wp:template-part {"slug":"footer","tagName":"footer"} /-->
```

### templates/page.html (Single Page):

```html
<!-- wp:template-part {"slug":"header","tagName":"header"} /-->

<!-- wp:group {"tagName":"main","layout":{"type":"constrained"}} -->
<main class="wp-block-group">
    
    <!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60"}}},"layout":{"type":"constrained"}} -->
    <div class="wp-block-group" style="padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--60)">
        
        <!-- wp:post-title {"level":1} /-->
        
        <!-- wp:post-content {"layout":{"type":"constrained"}} /-->
        
        <!-- wp:comments /-->
        
    </div>
    <!-- /wp:group -->
    
</main>
<!-- /wp:group -->

<!-- wp:template-part {"slug":"footer","tagName":"footer"} /-->
```

### templates/archive.html (Archive Pages):

```html
<!-- wp:template-part {"slug":"header","tagName":"header"} /-->

<!-- wp:group {"tagName":"main","layout":{"type":"constrained"}} -->
<main class="wp-block-group">
    
    <!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60"}}},"layout":{"type":"constrained"}} -->
    <div class="wp-block-group" style="padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--60)">
        
        <!-- wp:query-title {"type":"archive"} /-->
        
        <!-- wp:term-description /-->
        
        <!-- wp:query {"queryId":0,"query":{"perPage":12,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":true}} -->
        <div class="wp-block-query">
            
            <!-- wp:post-template {"style":{"spacing":{"blockGap":"var:preset|spacing|50"}},"layout":{"type":"grid","columnCount":3}} -->
                
                <!-- wp:group {"style":{"spacing":{"padding":{"top":"0","right":"0","bottom":"0","left":"0"}}},"layout":{"type":"default"}} -->
                <div class="wp-block-group">
                    
                    <!-- wp:post-featured-image {"isLink":true,"aspectRatio":"4/3"} /-->
                    
                    <!-- wp:post-title {"isLink":true,"fontSize":"large"} /-->
                    
                    <!-- wp:post-date {"fontSize":"small"} /-->
                    
                    <!-- wp:post-excerpt {"excerptLength":20} /-->
                    
                </div>
                <!-- /wp:group -->
                
            <!-- /wp:post-template -->
            
            <!-- wp:query-pagination {"layout":{"type":"flex","justifyContent":"center"}} -->
                <!-- wp:query-pagination-previous /-->
                <!-- wp:query-pagination-numbers /-->
                <!-- wp:query-pagination-next /-->
            <!-- /wp:query-pagination -->
            
        </div>
        <!-- /wp:query -->
        
    </div>
    <!-- /wp:group -->
    
</main>
<!-- /wp:group -->

<!-- wp:template-part {"slug":"footer","tagName":"footer"} /-->
```

### templates/404.html (Not Found):

```html
<!-- wp:template-part {"slug":"header","tagName":"header"} /-->

<!-- wp:group {"tagName":"main","layout":{"type":"constrained"}} -->
<main class="wp-block-group">
    
    <!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"}}},"layout":{"type":"constrained"}} -->
    <div class="wp-block-group" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70)">
        
        <!-- wp:heading {"textAlign":"center","level":1} -->
        <h1 class="wp-block-heading has-text-align-center">404</h1>
        <!-- /wp:heading -->
        
        <!-- wp:heading {"textAlign":"center","level":2} -->
        <h2 class="wp-block-heading has-text-align-center">Page Not Found</h2>
        <!-- /wp:heading -->
        
        <!-- wp:paragraph {"align":"center"} -->
        <p class="has-text-align-center">The page you're looking for doesn't exist. Try searching below:</p>
        <!-- /wp:paragraph -->
        
        <!-- wp:search {"label":"Search","showLabel":false,"placeholder":"Search...","width":50,"widthUnit":"%","buttonText":"Search","buttonPosition":"button-inside","buttonUseIcon":true,"align":"center"} /-->
        
    </div>
    <!-- /wp:group -->
    
</main>
<!-- /wp:group -->

<!-- wp:template-part {"slug":"footer","tagName":"footer"} /-->
```

### templates/blank.html (Blank Canvas):

```html
<!-- wp:post-content {"layout":{"type":"constrained"}} /-->
```

---

## 5. TEMPLATE PARTS

### parts/header.html:

```html
<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50","right":"var:preset|spacing|50","left":"var:preset|spacing|50"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group" style="padding-top:var(--wp--preset--spacing--50);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--50)">
    
    <!-- wp:group {"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between"}} -->
    <div class="wp-block-group">
        
        <!-- wp:group {"style":{"spacing":{"blockGap":"0.5rem"}},"layout":{"type":"flex"}} -->
        <div class="wp-block-group">
            
            <!-- wp:site-logo {"width":50} /-->
            
            <!-- wp:group {"layout":{"type":"flex","orientation":"vertical"}} -->
            <div class="wp-block-group">
                <!-- wp:site-title {"fontSize":"large"} /-->
                <!-- wp:site-tagline {"fontSize":"small"} /-->
            </div>
            <!-- /wp:group -->
            
        </div>
        <!-- /wp:group -->
        
        <!-- wp:navigation {"overlayMenu":"mobile","layout":{"type":"flex","justifyContent":"right"}} /-->
        
    </div>
    <!-- /wp:group -->
    
</div>
<!-- /wp:group -->
```

### parts/footer.html:

```html
<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60","right":"var:preset|spacing|50","left":"var:preset|spacing|50"}},"elements":{"link":{"color":{"text":"var:preset|color|background"}}}},"backgroundColor":"foreground","textColor":"background","layout":{"type":"constrained"}} -->
<div class="wp-block-group has-background-color has-foreground-background-color has-text-color has-background has-link-color" style="padding-top:var(--wp--preset--spacing--60);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--60);padding-left:var(--wp--preset--spacing--50)">
    
    <!-- wp:columns {"style":{"spacing":{"blockGap":{"top":"var:preset|spacing|50","left":"var:preset|spacing|60"}}}} -->
    <div class="wp-block-columns">
        
        <!-- wp:column -->
        <div class="wp-block-column">
            <!-- wp:heading {"level":3,"fontSize":"medium"} -->
            <h3 class="wp-block-heading has-medium-font-size">About Us</h3>
            <!-- /wp:heading -->
            
            <!-- wp:paragraph {"fontSize":"small"} -->
            <p class="has-small-font-size">A brief description of your site or company goes here.</p>
            <!-- /wp:paragraph -->
        </div>
        <!-- /wp:column -->
        
        <!-- wp:column -->
        <div class="wp-block-column">
            <!-- wp:heading {"level":3,"fontSize":"medium"} -->
            <h3 class="wp-block-heading has-medium-font-size">Quick Links</h3>
            <!-- /wp:heading -->
            
            <!-- wp:navigation {"overlayMenu":"never","layout":{"type":"flex","orientation":"