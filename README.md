# Now Art Gallery - Art E-commerce Platform

WordPress + WooCommerce e-commerce platform for Now Art Gallery (nowartgallery.art.br), featuring a custom Kiosko child theme and AWS Lightsail infrastructure.

## Project Overview

This repository contains:
- **Docker development environment** for local WordPress development
- **Kiosko child theme** (`theme/nowartgallery-child/`) with art gallery customizations
- **Infrastructure documentation** for AWS Lightsail deployment
- **Deployment guides** and setup instructions

**Note**: The payment gateway plugin (Orkestra Pix integration) is developed in a [separate repository](https://github.com/your-org/nowartgallery-payment-plugin).

## Quick Start

### Local Development

1. **Set up environment**:
   ```bash
   cp docker/.env.example .env
   # Edit .env with your preferred settings
   ```

2. **Start Docker containers**:
   ```bash
   docker-compose up -d
   ```

3. **Access WordPress**:
   - WordPress: http://localhost:8080
   - phpMyAdmin: http://localhost:8081
   - MailHog: http://localhost:8025

4. **Install dependencies**:
   - Complete WordPress installation wizard
   - Install WooCommerce plugin
   - Install Kiosko theme (Appearance → Themes → Add New)
   - Activate "Now Art Gallery Child" theme

See [docker/README.md](docker/README.md) for detailed setup instructions.

### Production Deployment

1. **Set up AWS Lightsail**: See [infrastructure/aws-lightsail-setup.md](infrastructure/aws-lightsail-setup.md)
2. **Configure SSL**: See [infrastructure/ssl-setup.md](infrastructure/ssl-setup.md)
3. **Deploy theme**: See [docs/DEPLOYMENT.md](docs/DEPLOYMENT.md)
4. **Set up backups**: See [infrastructure/backup-script.sh](infrastructure/backup-script.sh)

## Project Structure

```
nowartgallery/
├── docker/                    # Docker development environment
│   ├── docker-compose.yml     # Docker Compose configuration
│   ├── .env.example          # Environment variables template
│   └── README.md             # Docker setup instructions
├── theme/
│   └── nowartgallery-child/   # Kiosko child theme
│       ├── style.css         # Child theme header
│       ├── functions.php     # Theme functions and hooks
│       ├── assets/           # Custom CSS and JavaScript
│       ├── templates/        # WooCommerce template overrides
│       └── README.md         # Theme documentation
├── infrastructure/
│   ├── aws-lightsail-setup.md # AWS Lightsail setup guide
│   ├── backup-script.sh      # Automated backup script
│   └── ssl-setup.md          # SSL certificate setup
├── docs/
│   ├── DEPLOYMENT.md         # Deployment procedures
│   └── THEME_SETUP.md        # Kiosko theme installation guide
├── docker-compose.yml        # Root-level Docker Compose (convenience)
├── .gitignore                # Git ignore rules
└── README.md                 # This file
```

## Features

### Child Theme Features

- Custom product meta fields:
  - Artist name
  - Artwork dimensions
  - Limited edition support with edition numbers
- Enhanced product display with artwork details
- Limited edition badges on products
- Custom WooCommerce template overrides
- Mobile-first responsive design

### Infrastructure Features

- Docker-based local development
- AWS Lightsail hosting
- Automated S3 backups
- Let's Encrypt SSL certificates
- Amazon SES email configuration

## Documentation

- **[Docker Setup](docker/README.md)**: Local development environment
- **[AWS Lightsail Setup](infrastructure/aws-lightsail-setup.md)**: Production server setup
- **[SSL Setup](infrastructure/ssl-setup.md)**: Let's Encrypt certificate installation
- **[Deployment Guide](docs/DEPLOYMENT.md)**: Theme deployment procedures
- **[Theme Setup](docs/THEME_SETUP.md)**: Kiosko parent theme installation

## Requirements

- Docker & Docker Compose
- WordPress 6.0+ (with Full Site Editing support)
- WooCommerce 8.0+
- Kiosko theme (installed via WordPress admin)
- PHP 8.0+
- MySQL 8.0+
- AWS account (for production)

## Development Workflow

1. **Local Development**:
   - Make changes to child theme in `theme/nowartgallery-child/`
   - Test in Docker environment
   - Commit changes to Git

2. **Deployment**:
   - Push to repository
   - Deploy to production (see [DEPLOYMENT.md](docs/DEPLOYMENT.md))
   - Verify functionality

3. **Maintenance**:
   - Regular backups (automated via script)
   - Update WordPress, plugins, and themes
   - Monitor performance

## Version Control

This repository contains:
- ✅ Child theme customizations
- ✅ Docker development environment
- ✅ Infrastructure scripts and documentation
- ❌ WordPress core (installed separately)
- ❌ Kiosko parent theme (installed via WordPress admin)
- ❌ Payment plugin (separate repository)

See [.gitignore](.gitignore) for complete exclusion list.

## Support

For issues or questions:
- Check documentation in `docs/` and `infrastructure/`
- Review theme README: `theme/nowartgallery-child/README.md`
- Check Docker setup: `docker/README.md`

## License

[Add your license information here]

## Contributing

[Add contribution guidelines if applicable]
