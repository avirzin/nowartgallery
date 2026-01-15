# AWS Lightsail Setup Guide

This guide walks you through setting up WordPress on AWS Lightsail for Now Art Gallery.

## Prerequisites

- AWS account with Lightsail access
- Domain name (nowartgallery.art.br) configured
- Basic familiarity with AWS console

## Step 1: Create Lightsail Instance

1. Log in to AWS Lightsail console
2. Click **Create instance**
3. Choose your instance location (closest to your users)
4. Select **WordPress** blueprint
5. Choose instance plan:
   - **Recommended**: 2 GB RAM / 1 vCPU / 40 GB SSD (or higher)
   - This meets the project requirements
6. Name your instance: `nowartgallery-wordpress`
7. Click **Create instance**

## Step 2: Get WordPress Credentials

1. Wait for instance to be running (green checkmark)
2. Click on your instance name
3. Go to **Connect using SSH** tab
4. Click **Connect using SSH** button (opens browser-based SSH)
5. Run the following command to get WordPress admin credentials:
   ```bash
   cat bitnami_credentials
   ```
6. **Save these credentials securely** - you'll need them to log in

## Step 3: Configure Static IP

1. In Lightsail console, go to **Networking** tab
2. Click **Create static IP**
3. Name it: `nowartgallery-static-ip`
4. Attach it to your WordPress instance
5. **Note the IP address** - you'll use this for DNS configuration

## Step 4: Configure DNS

### Option A: Using Route 53 (AWS)

1. Go to Route 53 in AWS console
2. Create a hosted zone for `nowartgallery.art.br`
3. Create an A record:
   - Name: `@` (or leave blank)
   - Type: A
   - Value: Your static IP address
   - TTL: 300

### Option B: Using External DNS Provider

1. Log in to your domain registrar
2. Update DNS records:
   - Type: A
   - Host: `@` (or root domain)
   - Points to: Your Lightsail static IP
   - TTL: 300
3. Wait for DNS propagation (can take up to 48 hours, usually faster)

## Step 5: Install SSL Certificate (Let's Encrypt)

See `ssl-setup.md` for detailed SSL certificate installation using Let's Encrypt.

## Step 6: Configure WordPress

1. Access WordPress admin:
   - URL: `http://your-static-ip` (or domain if DNS is configured)
   - Use credentials from Step 2
2. Complete WordPress setup wizard if prompted
3. Update WordPress settings:
   - Settings → General: Update site URL to your domain
   - Settings → Permalinks: Choose "Post name" structure

## Step 7: Install WooCommerce

1. In WordPress admin, go to **Plugins → Add New**
2. Search for "WooCommerce"
3. Click **Install Now**, then **Activate**
4. Follow WooCommerce setup wizard:
   - Configure store details
   - Set up payment methods (Pix will be added via custom plugin)
   - Configure shipping (if needed)
   - Install recommended plugins (optional)

## Step 8: Install Kiosko Theme

1. Go to **Appearance → Themes → Add New**
2. Search for "Kiosko"
3. Click **Install**, then **Activate**

## Step 9: Deploy Child Theme

1. Connect to your instance via SSH (see Step 2)
2. Navigate to themes directory:
   ```bash
   cd /opt/bitnami/wordpress/wp-content/themes
   ```
3. Clone or upload your child theme:
   ```bash
   # Option 1: Clone from Git (if using Git deployment)
   git clone <your-repo-url> nowartgallery-child
   
   # Option 2: Upload via SFTP (use FileZilla or similar)
   # Upload the theme/nowartgallery-child/ folder contents
   ```
4. Set proper permissions:
   ```bash
   sudo chown -R bitnami:daemon nowartgallery-child
   sudo chmod -R 755 nowartgallery-child
   ```
5. In WordPress admin: **Appearance → Themes**
6. Activate "Now Art Gallery Child"

## Step 10: Configure Amazon SES for Emails

1. Go to Amazon SES in AWS console
2. Verify your domain or email address
3. Create SMTP credentials:
   - Go to **SMTP Settings**
   - Click **Create SMTP Credentials**
   - Save the credentials
4. Install WP Mail SMTP plugin in WordPress:
   - Plugins → Add New → Search "WP Mail SMTP"
   - Install and activate
   - Configure with Amazon SES SMTP settings
5. Test email delivery

## Step 11: Configure Security

1. Update WordPress admin password (if using default)
2. Install security plugin (optional but recommended):
   - Wordfence Security
   - or iThemes Security
3. Configure firewall rules in Lightsail:
   - Go to **Networking** tab
   - Add firewall rules:
     - HTTP (port 80) - Allow
     - HTTPS (port 443) - Allow
     - SSH (port 22) - Restrict to your IP

## Step 12: Set Up Backups

See `backup-script.sh` for automated backup setup to S3.

## Maintenance

- Keep WordPress, plugins, and themes updated
- Monitor instance performance in Lightsail console
- Review backup logs regularly
- Monitor costs in AWS Billing console

## Troubleshooting

### Can't access WordPress admin
- Check firewall rules in Lightsail
- Verify instance is running
- Check credentials using `cat bitnami_credentials`

### SSL certificate issues
- See `ssl-setup.md` for troubleshooting
- Ensure DNS is properly configured
- Check certificate expiration

### Performance issues
- Consider upgrading instance size
- Enable WordPress caching plugin
- Optimize images before upload

## Cost Estimation

- Lightsail instance (2 GB): ~$10-12/month
- Static IP: Free (when attached to instance)
- S3 backups: ~$0.023/GB/month (minimal for small site)
- Data transfer: First 1 TB free, then $0.09/GB
- **Estimated total**: ~$10-15/month for small to medium traffic

## Next Steps

- Set up automated backups (see `backup-script.sh`)
- Configure SSL certificate (see `ssl-setup.md`)
- Deploy payment gateway plugin (separate repository)
- Test all functionality before going live
