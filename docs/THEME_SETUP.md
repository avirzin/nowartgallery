# Kiosko Theme Setup Guide

This guide explains how to install and configure the Kiosko parent theme and activate the Now Art Gallery child theme.

## About Kiosko Theme

Kiosko is a free, minimalist block theme by Automattic designed specifically for WooCommerce stores. It features:
- Clean black and white design
- Full Site Editing (FSE) support
- Mobile-first responsive layout
- Optimized for art and print shops

## Installation Methods

### Method 1: Via WordPress Admin (Recommended)

This is the easiest and recommended method for both local and production.

#### Local Development (Docker)

1. Start your Docker environment:
   ```bash
   docker-compose up -d
   ```

2. Access WordPress: http://localhost:8080

3. Complete WordPress installation if not already done

4. Install Kiosko:
   - Go to **Appearance → Themes → Add New**
   - Search for "Kiosko"
   - Click **Install**
   - Click **Activate**

5. Install WooCommerce (if not already installed):
   - Go to **Plugins → Add New**
   - Search for "WooCommerce"
   - Install and activate
   - Complete WooCommerce setup wizard

6. Activate child theme:
   - Go to **Appearance → Themes**
   - Find "Now Art Gallery Child"
   - Click **Activate**

#### Production (AWS Lightsail)

1. Log in to WordPress admin on your production site

2. Install Kiosko:
   - Go to **Appearance → Themes → Add New**
   - Search for "Kiosko"
   - Click **Install**
   - **Do NOT activate yet** (activate child theme instead)

3. Install WooCommerce (if not already installed):
   - Go to **Plugins → Add New**
   - Search for "WooCommerce"
   - Install and activate

4. Deploy child theme (see `DEPLOYMENT.md`)

5. Activate child theme:
   - Go to **Appearance → Themes**
   - Find "Now Art Gallery Child"
   - Click **Activate**

### Method 2: Manual Installation (Advanced)

If you need to install Kiosko manually:

#### Step 1: Download Kiosko

1. Visit: https://wordpress.org/themes/kiosko/
2. Click **Download** button
3. Save the ZIP file

#### Step 2: Upload to WordPress

**Via WordPress Admin:**
1. Go to **Appearance → Themes → Add New**
2. Click **Upload Theme**
3. Choose the Kiosko ZIP file
4. Click **Install Now**
5. Click **Activate** (or activate child theme instead)

**Via SFTP/SSH:**
1. Extract the ZIP file
2. Upload `kiosko` folder to `/wp-content/themes/`
3. Set permissions:
   ```bash
   sudo chown -R bitnami:daemon /opt/bitnami/wordpress/wp-content/themes/kiosko
   sudo chmod -R 755 /opt/bitnami/wordpress/wp-content/themes/kiosko
   ```
4. Activate via WordPress admin

## Child Theme Activation

### Prerequisites

- Kiosko parent theme must be installed (but not necessarily activated)
- Child theme files must be in `/wp-content/themes/nowartgallery-child/`

### Activation Steps

1. Log in to WordPress admin
2. Go to **Appearance → Themes**
3. Find "Now Art Gallery Child"
4. Hover over it and click **Activate**

### Verification

After activation, verify:

1. **Theme is Active**
   - Appearance → Themes should show "Now Art Gallery Child" as active
   - Parent theme "Kiosko" should be listed but not active

2. **Site Appearance**
   - Visit your site frontend
   - Should see Kiosko's minimalist design
   - Custom styles from child theme should be applied

3. **Custom Features**
   - Go to a product page
   - Check for custom meta fields (Artist, Dimensions, Limited Edition)
   - Verify limited edition badges appear on products

## Configuration

### WooCommerce Setup

1. **General Settings**
   - Go to **WooCommerce → Settings**
   - Configure store address, currency, etc.

2. **Products**
   - Add your first product
   - Fill in custom fields:
     - Artist Name
     - Dimensions
     - Limited Edition (if applicable)
     - Edition Number (if limited edition)

3. **Payment Methods**
   - Configure payment gateways
   - Pix payment will be added via custom plugin (separate repository)

### Theme Customization

Kiosko uses Full Site Editing (FSE), so customization is done via:

1. **Site Editor**
   - Go to **Appearance → Editor**
   - Customize templates, patterns, and styles
   - Changes are saved to database

2. **Custom CSS**
   - Add custom styles in child theme's `assets/css/custom.css`
   - Or use **Appearance → Customize → Additional CSS**

3. **Template Overrides**
   - Custom WooCommerce templates in `templates/woocommerce/`
   - Modify as needed for your requirements

## Local vs Production Differences

### Local Development

- Theme installed via WordPress admin
- Child theme mounted as volume in Docker
- Changes reflect immediately
- Debug mode enabled

### Production

- Theme installed via WordPress admin
- Child theme deployed from repository
- Changes require deployment
- Debug mode disabled

## Updating Kiosko Theme

### Automatic Updates

WordPress will notify you when Kiosko updates are available:

1. Go to **Dashboard → Updates**
2. If Kiosko update is available, click **Update Now**
3. **Important**: Updates won't affect your child theme customizations

### Manual Updates

1. Download latest version from WordPress.org
2. Deactivate child theme temporarily
3. Delete old Kiosko theme folder
4. Upload new version
5. Reactivate child theme

## Troubleshooting

### Child Theme Not Appearing

- **Check file location**: Should be in `/wp-content/themes/nowartgallery-child/`
- **Check style.css**: Must have proper child theme header with `Template: kiosko`
- **Check permissions**: Files should be readable by web server
- **Clear cache**: Clear browser and WordPress cache

### Parent Theme Not Found Error

- **Kiosko not installed**: Install Kiosko parent theme first
- **Wrong template name**: Verify `Template: kiosko` in child theme's `style.css`
- **Case sensitivity**: Template name must match exactly (lowercase "kiosko")

### Styles Not Loading

- **Parent styles**: Check that parent styles are enqueued in `functions.php`
- **Cache**: Clear browser and WordPress cache
- **File paths**: Verify CSS file paths are correct
- **Permissions**: Check file permissions

### Custom Fields Not Showing

- **WooCommerce active**: Ensure WooCommerce plugin is installed and active
- **Product type**: Custom fields appear on product edit page
- **Permissions**: Check user has permission to edit products
- **Functions.php**: Verify custom field functions are in child theme's `functions.php`

## Best Practices

1. **Always use child theme**: Never modify Kiosko parent theme directly
2. **Test updates**: Test Kiosko updates in staging before production
3. **Backup before updates**: Always backup before updating themes
4. **Version control**: Keep child theme in Git repository
5. **Document customizations**: Document any custom code or overrides

## Additional Resources

- Kiosko Theme Page: https://wordpress.org/themes/kiosko/
- WooCommerce Documentation: https://woocommerce.com/documentation/
- WordPress Child Themes: https://developer.wordpress.org/themes/advanced-topics/child-themes/
