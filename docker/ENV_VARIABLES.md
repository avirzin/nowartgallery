# Environment Variables

Create a `.env` file in the project root with the following variables:

```bash
# WordPress Configuration
WP_PORT=8080
WP_DEBUG=1
WP_DEBUG_LOG=1
WP_DEBUG_DISPLAY=0

# MySQL Configuration
MYSQL_DATABASE=wordpress
MYSQL_USER=wordpress
MYSQL_PASSWORD=wordpress
MYSQL_ROOT_PASSWORD=rootpassword

# phpMyAdmin Configuration
PHPMYADMIN_PORT=8081

# MailHog Configuration (Email Testing)
MAILHOG_PORT=8025
MAILHOG_SMTP_PORT=1025
```

## Setup Instructions

1. Copy this content to a file named `.env` in the project root
2. Update the values as needed for your environment
3. **Never commit `.env` to version control** (it's already in `.gitignore`)

## Default Values

The default values above are suitable for local development. For production:
- Use stronger passwords
- Disable debug mode (WP_DEBUG=0)
- Use environment-specific ports if needed
