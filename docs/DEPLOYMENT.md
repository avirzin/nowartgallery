# Deployment Guide

This guide covers deploying the Now Art Gallery child theme and managing the WordPress installation.

## Prerequisites

- AWS Lightsail instance running WordPress
- SSH access to the instance
- Git installed on local machine (for Git-based deployment)
- SFTP client (for manual deployment)

## Deployment Methods

### Method 1: Git-Based Deployment (Recommended for Production)

This method allows you to deploy directly from your Git repository.

#### Step 1: Set Up Git on Server

1. Connect to your Lightsail instance via SSH
2. Install Git (if not already installed):
   ```bash
   sudo apt-get update
   sudo apt-get install git
   ```

#### Step 2: Clone Repository

```bash
cd /opt/bitnami/wordpress/wp-content/themes
sudo git clone <your-repo-url> nowartgallery-child
sudo chown -R bitnami:daemon nowartgallery-child
sudo chmod -R 755 nowartgallery-child
```

#### Step 3: Set Up Deployment Script

Create a deployment script:

```bash
sudo nano /opt/bitnami/deploy-theme.sh
```

Add:

```bash
#!/bin/bash
cd /opt/bitnami/wordpress/wp-content/themes/nowartgallery-child
sudo git pull origin main
sudo chown -R bitnami:daemon .
sudo chmod -R 755 .
echo "Theme deployed successfully"
```

Make executable:

```bash
sudo chmod +x /opt/bitnami/deploy-theme.sh
```

#### Step 4: Deploy Updates

When you push changes to your repository:

```bash
ssh user@your-instance-ip
sudo /opt/bitnami/deploy-theme.sh
```

Or set up a webhook for automatic deployment (advanced).

### Method 2: Manual ZIP Upload

For quick deployments or when Git is not available.

#### Step 1: Create ZIP File

On your local machine:

```bash
cd theme
zip -r nowartgallery-child.zip nowartgallery-child/
```

#### Step 2: Upload via WordPress Admin

1. Log in to WordPress admin
2. Go to **Appearance → Themes → Add New**
3. Click **Upload Theme**
4. Choose the ZIP file
5. Click **Install Now**
6. Activate the theme

#### Step 3: Upload via SFTP

1. Connect to your server via SFTP (FileZilla, WinSCP, etc.)
2. Navigate to `/opt/bitnami/wordpress/wp-content/themes/`
3. Upload the `nowartgallery-child` folder
4. Set permissions:
   ```bash
   sudo chown -R bitnami:daemon nowartgallery-child
   sudo chmod -R 755 nowartgallery-child
   ```

### Method 3: Direct File Transfer (SSH)

For quick file updates.

```bash
# From local machine
scp -r theme/nowartgallery-child/* user@your-instance-ip:/opt/bitnami/wordpress/wp-content/themes/nowartgallery-child/

# Then set permissions on server
ssh user@your-instance-ip
sudo chown -R bitnami:daemon /opt/bitnami/wordpress/wp-content/themes/nowartgallery-child
sudo chmod -R 755 /opt/bitnami/wordpress/wp-content/themes/nowartgallery-child
```

## Pre-Deployment Checklist

- [ ] Test theme locally in Docker environment
- [ ] Ensure Kiosko parent theme is installed on production
- [ ] Backup current production site
- [ ] Verify all customizations are in child theme (not parent)
- [ ] Check for any hardcoded URLs or paths
- [ ] Test WooCommerce functionality locally

## Post-Deployment Steps

1. **Activate Child Theme**
   - Go to **Appearance → Themes**
   - Activate "Now Art Gallery Child"

2. **Clear Caches**
   - If using caching plugin, clear cache
   - Clear browser cache

3. **Verify Functionality**
   - Test homepage
   - Test product pages
   - Test cart and checkout
   - Test mobile responsiveness
   - Check custom meta fields (artist, dimensions, etc.)

4. **Update Permalinks**
   - Go to **Settings → Permalinks**
   - Click **Save Changes** (refreshes rewrite rules)

## Database Migration

If you need to migrate database content:

### Export from Local

```bash
# From Docker container
docker exec nowartgallery-db mysqldump -u wordpress -pwordpress wordpress > backup.sql
```

### Import to Production

```bash
# On production server
mysql -u bitnami_wordpress -p bitnami_wordpress < backup.sql
```

**Note**: Be careful with database migrations. Test in staging first.

## Environment Variables

If your theme uses environment-specific configurations:

1. Create `wp-config-local.php` for local development
2. Use WordPress constants in `wp-config.php` for production
3. Avoid hardcoding URLs or API keys in theme files

## Rollback Procedure

If deployment causes issues:

1. **Via WordPress Admin**
   - Go to **Appearance → Themes**
   - Activate a different theme (Kiosko parent or default)

2. **Via SSH**
   ```bash
   cd /opt/bitnami/wordpress/wp-content/themes
   sudo rm -rf nowartgallery-child
   # Restore from backup if needed
   ```

3. **Restore from Backup**
   - Use backup script to restore from S3
   - See `infrastructure/backup-script.sh`

## Continuous Deployment (Advanced)

For automated deployments, consider:

1. **GitHub Actions**: Automate deployment on push
2. **Webhooks**: Trigger deployment script on Git push
3. **CI/CD Pipeline**: Full automated testing and deployment

Example GitHub Actions workflow (`.github/workflows/deploy.yml`):

```yaml
name: Deploy to Production

on:
  push:
    branches: [ main ]

jobs:
  deploy:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v2
      - name: Deploy via SSH
        uses: appleboy/ssh-action@master
        with:
          host: ${{ secrets.HOST }}
          username: ${{ secrets.USERNAME }}
          key: ${{ secrets.SSH_KEY }}
          script: |
            cd /opt/bitnami/wordpress/wp-content/themes/nowartgallery-child
            sudo git pull origin main
            sudo chown -R bitnami:daemon .
            sudo chmod -R 755 .
```

## Troubleshooting

### Theme Not Appearing

- Check file permissions
- Verify theme folder is in correct location
- Check `style.css` has proper child theme header
- Ensure Kiosko parent theme is installed

### Styles Not Loading

- Clear browser cache
- Clear WordPress cache
- Check file paths in `functions.php`
- Verify parent theme styles are enqueued

### Functions Not Working

- Check PHP error logs
- Enable WordPress debug mode
- Verify WooCommerce is active
- Check for PHP syntax errors

## Security Considerations

- Never commit sensitive data (API keys, passwords)
- Use environment variables for configuration
- Keep WordPress, plugins, and themes updated
- Regularly review file permissions
- Use SFTP/SSH keys instead of passwords when possible

## Maintenance

- Regularly update child theme from repository
- Monitor for conflicts with parent theme updates
- Test after WordPress core updates
- Review and update custom code as needed
