# ProfitBenefit Theme Installation Guide

## Requirements

### Server Requirements
- WordPress 6.0 or higher
- PHP 7.4 or higher
- MySQL 5.6+ or MariaDB 10.1+
- Apache or Nginx web server
- mod_rewrite enabled

### Recommended Hosting Specs
- Memory: 128MB minimum (256MB recommended)
- Disk Space: 50MB for theme files
- All standard WordPress requirements

## Installation Methods

### Method 1: Local Environment (XAMPP, MAMP, Local by Flywheel)

#### Step 1: Install WordPress Locally
1. Download and install XAMPP/MAMP or Local by Flywheel
2. Start Apache and MySQL servers
3. Create a new database for WordPress
4. Download WordPress from wordpress.org
5. Extract WordPress to your local server directory:
   - XAMPP: `C:\xampp\htdocs\your-site-name\`
   - MAMP: `/Applications/MAMP/htdocs/your-site-name/`
   - Local: Create new site in Local app
6. Run WordPress installation: `http://localhost/your-site-name/`
7. Complete the 5-minute WordPress installation

#### Step 2: Install the Theme
1. Navigate to your WordPress themes directory:
   ```
   /wp-content/themes/
   ```

2. **Option A: Manual Upload**
   - Copy the entire `profitbenefit-theme` folder
   - Paste into `/wp-content/themes/` directory
   - Result: `/wp-content/themes/profitbenefit-theme/`

3. **Option B: ZIP Upload**
   - Create a ZIP of `profitbenefit-theme` folder
   - Go to WordPress Dashboard → Appearance → Themes → Add New
   - Click "Upload Theme"
   - Select the ZIP file
   - Click "Install Now"

4. **Activate the Theme**
   - Go to Appearance → Themes
   - Find "ProfitBenefit Tools"
   - Click "Activate"

#### Step 3: Configure the Theme
1. **Set up Homepage**
   - Go to Pages → Add New
   - Create a page called "Home"
   - Go to Settings → Reading
   - Select "A static page"
   - Choose "Home" as Homepage
   - Create another page "Blog" and set as Posts page

2. **Configure Menus**
   - Go to Appearance → Menus
   - Create "Top Menu" and assign to "Top Menu" location
   - Create "Main Menu" and assign to "Main Menu" location
   - Create footer menus as needed

3. **Set Up Widgets**
   - Go to Appearance → Widgets
   - Drag widgets to "Main Sidebar":
     - Search
     - Categories
     - Recent Posts (or Popular Posts)
     - Tag Cloud

4. **Customize Hero Banner**
   - Go to Appearance → Customize → Hero Banner Settings
   - Customize text, button, and trust badges
   - Click "Publish"

5. **Upload Logo**
   - Go to Appearance → Customize → Site Identity
   - Click "Select Logo"
   - Upload your logo image

6. **Create Sample Content**
   - Add 5-10 blog posts with featured images
   - Assign categories to posts
   - Add tags to posts

### Method 2: Live Server Installation

#### Step 1: Prepare the Theme
1. Create a ZIP file of the `profitbenefit-theme` folder:
   ```bash
   zip -r profitbenefit-theme.zip profitbenefit-theme/
   ```

#### Step 2: Upload to Live Server

**Option A: Via WordPress Dashboard (Recommended)**
1. Log into your WordPress admin panel
2. Go to Appearance → Themes → Add New
3. Click "Upload Theme"
4. Choose the ZIP file
5. Click "Install Now"
6. Click "Activate"

**Option B: Via FTP/cPanel**
1. Connect to your server via FTP (FileZilla, Cyberduck, etc.)
2. Navigate to: `/public_html/wp-content/themes/`
3. Upload the entire `profitbenefit-theme` folder
4. Go to WordPress Dashboard → Appearance → Themes
5. Activate "ProfitBenefit Tools"

**Option C: Via SSH (Advanced)**
```bash
# Connect to your server
ssh user@yourserver.com

# Navigate to themes directory
cd /path/to/wordpress/wp-content/themes/

# Upload theme (if using SCP from local)
scp -r profitbenefit-theme user@yourserver.com:/path/to/wordpress/wp-content/themes/

# Set correct permissions
chmod -R 755 profitbenefit-theme/
```

#### Step 3: Post-Installation Configuration
Follow the same configuration steps as Local Environment (Step 3 above)

## Troubleshooting

### Common Issues

#### 1. Theme doesn't appear in themes list
**Solution:**
- Ensure `style.css` is in the root of the theme folder
- Check file permissions (755 for directories, 644 for files)
- Verify WordPress can read the directory

#### 2. Broken layout or missing styles
**Solution:**
- Go to Settings → Permalinks
- Click "Save Changes" (flushes rewrite rules)
- Clear browser cache
- Check if CSS file exists: `/wp-content/themes/profitbenefit-theme/assets/css/main.css`

#### 3. Hero banner not showing
**Solution:**
- Ensure you're viewing the homepage (front-page.php)
- Check Settings → Reading → Homepage is set to static page
- Verify CSS is loaded (check browser developer tools)

#### 4. Sidebar widgets not showing
**Solution:**
- Go to Appearance → Widgets
- Add widgets to "Main Sidebar" widget area
- Ensure you're on a page that displays sidebar (archive, single post)

#### 5. Images not displaying
**Solution:**
- Check image URLs in posts
- Regenerate thumbnails using plugin like "Regenerate Thumbnails"
- Verify uploads directory is writable: `/wp-content/uploads/`

#### 6. "Parse error" or "Fatal error"
**Solution:**
- Check PHP version (must be 7.4+)
- Upload theme files again (may be corrupted)
- Check error logs in cPanel or server logs

#### 7. Slow loading on live site
**Solution:**
- Install caching plugin (WP Super Cache, W3 Total Cache)
- Optimize images before uploading
- Enable GZIP compression on server
- Use CDN for Google Fonts

## File Permissions

### Recommended Permissions
```
Directories: 755
Files: 644
wp-config.php: 440 or 400 (more secure)
```

### Setting Permissions via SSH
```bash
# Navigate to theme directory
cd /path/to/wordpress/wp-content/themes/profitbenefit-theme/

# Set directory permissions
find . -type d -exec chmod 755 {} \;

# Set file permissions
find . -type f -exec chmod 644 {} \;
```

## Performance Optimization

### After Installation
1. **Install Caching Plugin**
   - WP Super Cache (free)
   - WP Rocket (premium)
   - W3 Total Cache (free)

2. **Optimize Images**
   - Use Smush or ShortPixel
   - Compress images before upload
   - Use WebP format when possible

3. **Enable GZIP Compression**
   - Add to `.htaccess` or server config
   - Most hosting providers enable by default

4. **Minify CSS/JS** (Optional)
   - Use Autoptimize plugin
   - Already optimized in theme

## Security Recommendations

1. **Keep WordPress Updated**
   - Update WordPress core regularly
   - Update theme when new version available

2. **Install Security Plugin**
   - Wordfence Security (free)
   - iThemes Security (free)
   - Sucuri Security (free)

3. **Use Strong Passwords**
   - Admin account
   - Database user
   - FTP/SSH accounts

4. **Enable SSL Certificate**
   - Get free SSL from Let's Encrypt
   - Most hosting providers offer free SSL

5. **Regular Backups**
   - UpdraftPlus (free)
   - BackWPup (free)
   - VaultPress (premium)

## Testing Checklist

After installation, test the following:

### Frontend
- [ ] Homepage loads with hero banner
- [ ] DON'T MISS section displays
- [ ] Trending posts carousel works
- [ ] Category boxes show (6 boxes in 2x3 grid)
- [ ] Sidebar widgets display
- [ ] Blog archive page works
- [ ] Single post page works
- [ ] Comments display (if enabled)
- [ ] Navigation menus work
- [ ] Mobile responsive (test on phone)
- [ ] Hero banner animations work

### Backend
- [ ] Theme appears in Appearance → Themes
- [ ] Customizer loads all options
- [ ] Hero Banner Settings section appears
- [ ] Widgets can be added to sidebar
- [ ] Menus can be created and assigned
- [ ] Featured images can be set
- [ ] No PHP errors in error logs

### Performance
- [ ] Page loads in under 3 seconds
- [ ] Images load properly
- [ ] CSS and JS load correctly
- [ ] No 404 errors in browser console
- [ ] Google Fonts load

## Migration from Local to Live

### Step 1: Export Local Database
```bash
# Using phpMyAdmin
1. Go to phpMyAdmin
2. Select your database
3. Click "Export"
4. Choose "Quick" method
5. Click "Go" to download .sql file
```

### Step 2: Update URLs in Database
```sql
-- Replace local URLs with live URLs
UPDATE wp_options SET option_value = replace(option_value, 'http://localhost/your-site', 'https://yourdomain.com') WHERE option_name = 'home' OR option_name = 'siteurl';

UPDATE wp_posts SET guid = replace(guid, 'http://localhost/your-site','https://yourdomain.com');

UPDATE wp_posts SET post_content = replace(post_content, 'http://localhost/your-site', 'https://yourdomain.com');

UPDATE wp_postmeta SET meta_value = replace(meta_value,'http://localhost/your-site','https://yourdomain.com');
```

### Step 3: Upload Files
1. Upload all WordPress files via FTP
2. Upload database to live server
3. Update wp-config.php with live database credentials

### Step 4: Verify
- Test all pages
- Check image paths
- Test forms and functionality
- Verify SSL certificate

## Support

If you encounter issues:

1. **Check WordPress.org Support Forums**
   - https://wordpress.org/support/

2. **Check Theme Documentation**
   - Read `profitbenefit-theme/README.md`

3. **Enable Debug Mode** (temporarily)
   ```php
   // In wp-config.php
   define('WP_DEBUG', true);
   define('WP_DEBUG_LOG', true);
   define('WP_DEBUG_DISPLAY', false);
   ```

4. **Check Error Logs**
   - Look in `/wp-content/debug.log`
   - Check server error logs in cPanel

## Additional Resources

- WordPress Codex: https://codex.wordpress.org/
- Theme Development: https://developer.wordpress.org/themes/
- Local Development: https://localwp.com/
- WordPress Forums: https://wordpress.org/support/forums/

---

**Version**: 1.0.0
**Last Updated**: December 2024
**Compatibility**: WordPress 6.0+, PHP 7.4+
