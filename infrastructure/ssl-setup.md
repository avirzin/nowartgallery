# SSL Certificate Setup with Let's Encrypt

This guide explains how to set up SSL certificates for Now Art Gallery using Let's Encrypt on AWS Lightsail (Bitnami WordPress).

## Prerequisites

- Domain name (nowartgallery.art.br) pointing to your Lightsail instance
- SSH access to your Lightsail instance
- Ports 80 and 443 open in Lightsail firewall

## Method 1: Using Bitnami bncert-tool (Recommended)

Bitnami provides a tool specifically for managing SSL certificates on their WordPress installations.

### Step 1: Connect to Your Instance

1. In Lightsail console, click on your instance
2. Go to **Connect using SSH** tab
3. Click **Connect using SSH**

### Step 2: Run bncert-tool

```bash
sudo /opt/bitnami/bncert-tool
```

### Step 3: Follow the Interactive Setup

1. Enter your domain name: `nowartgallery.art.br`
2. Enter additional domains (optional): `www.nowartgallery.art.br`
3. The tool will:
   - Install Certbot if needed
   - Request certificates from Let's Encrypt
   - Configure Apache/Nginx
   - Set up auto-renewal

### Step 4: Verify SSL

1. Visit `https://nowartgallery.art.br`
2. Check that the padlock icon appears in browser
3. Test SSL: https://www.ssllabs.com/ssltest/

### Step 5: Configure Auto-Renewal

The bncert-tool sets up auto-renewal automatically. Verify it's working:

```bash
sudo certbot renew --dry-run
```

## Method 2: Manual Certbot Installation

If bncert-tool is not available, use Certbot directly.

### Step 1: Install Certbot

```bash
sudo apt-get update
sudo apt-get install certbot python3-certbot-apache
```

### Step 2: Stop Apache

```bash
sudo /opt/bitnami/ctlscript.sh stop apache
```

### Step 3: Request Certificate

```bash
sudo certbot certonly --standalone -d nowartgallery.art.br -d www.nowartgallery.art.br
```

### Step 4: Configure Apache

Edit Apache configuration:

```bash
sudo nano /opt/bitnami/apache2/conf/bitnami/bitnami.conf
```

Add SSL configuration (or modify existing):

```apache
<VirtualHost _default_:443>
  ServerName nowartgallery.art.br
  DocumentRoot /opt/bitnami/wordpress
  
  SSLEngine on
  SSLCertificateFile /etc/letsencrypt/live/nowartgallery.art.br/fullchain.pem
  SSLCertificateKeyFile /etc/letsencrypt/live/nowartgallery.art.br/privkey.pem
  
  # Redirect HTTP to HTTPS
  RewriteEngine On
  RewriteCond %{HTTPS} off
  RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
</VirtualHost>
```

### Step 5: Start Apache

```bash
sudo /opt/bitnami/ctlscript.sh start apache
```

### Step 6: Set Up Auto-Renewal

Create a renewal script:

```bash
sudo nano /opt/bitnami/letsencrypt-renew.sh
```

Add:

```bash
#!/bin/bash
sudo /opt/bitnami/ctlscript.sh stop apache
sudo certbot renew
sudo /opt/bitnami/ctlscript.sh start apache
```

Make executable:

```bash
sudo chmod +x /opt/bitnami/letsencrypt-renew.sh
```

Add to crontab:

```bash
sudo crontab -e
```

Add line (runs twice daily):

```
0 0,12 * * * /opt/bitnami/letsencrypt-renew.sh >> /var/log/letsencrypt-renew.log 2>&1
```

## Method 3: Using Lightsail Load Balancer (Advanced)

For production with high traffic, consider using a Lightsail load balancer with SSL certificate.

1. Create a Lightsail load balancer
2. Attach SSL certificate (Let's Encrypt or AWS Certificate Manager)
3. Point load balancer to your WordPress instance
4. Update DNS to point to load balancer

## Troubleshooting

### Certificate Request Fails

- **DNS not propagated**: Wait for DNS to fully propagate (can take up to 48 hours)
- **Port 80 blocked**: Ensure port 80 is open in Lightsail firewall
- **Domain not pointing to instance**: Verify DNS A record is correct

### Certificate Expired

- Check renewal cron job is running
- Manually renew: `sudo certbot renew`
- Verify auto-renewal: `sudo certbot renew --dry-run`

### Mixed Content Warnings

- Update WordPress site URL to HTTPS:
  - Settings → General → WordPress Address (URL)
  - Settings → General → Site Address (URL)
- Use a plugin like "Really Simple SSL" to force HTTPS

### Apache Won't Start After SSL

- Check certificate paths are correct
- Verify certificate files exist:
  ```bash
  sudo ls -la /etc/letsencrypt/live/nowartgallery.art.br/
  ```
- Check Apache error logs:
  ```bash
  sudo tail -f /opt/bitnami/apache2/logs/error_log
  ```

## Security Best Practices

1. **Force HTTPS**: Redirect all HTTP traffic to HTTPS
2. **HSTS**: Enable HTTP Strict Transport Security headers
3. **Certificate Monitoring**: Set up alerts for certificate expiration
4. **Regular Updates**: Keep Certbot and system packages updated

## Testing SSL Configuration

- SSL Labs: https://www.ssllabs.com/ssltest/
- SSL Checker: https://www.sslshopper.com/ssl-checker.html
- Browser DevTools: Check Security tab for certificate details

## Additional Resources

- Let's Encrypt Documentation: https://letsencrypt.org/docs/
- Bitnami SSL Guide: https://docs.bitnami.com/aws/apps/wordpress/administration/enable-https/
- Certbot Documentation: https://certbot.eff.org/docs/
