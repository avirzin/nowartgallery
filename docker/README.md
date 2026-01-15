# Docker Development Environment

This directory contains the Docker configuration for local WordPress development.

## Quick Start

1. Copy the example environment file:
   ```bash
   cp docker/.env.example .env
   ```

2. Start the containers:
   ```bash
   docker-compose up -d
   ```

3. Access WordPress:
   - WordPress: http://localhost:8080
   - phpMyAdmin: http://localhost:8081
   - MailHog (Email Testing): http://localhost:8025

4. Complete WordPress installation:
   - Follow the WordPress installation wizard at http://localhost:8080
   - Remember your admin credentials

5. Install WooCommerce:
   - Go to Plugins → Add New
   - Search for "WooCommerce"
   - Install and activate

6. Install Kiosko theme:
   - Go to Appearance → Themes → Add New
   - Search for "Kiosko"
   - Install and activate

7. Activate child theme:
   - Go to Appearance → Themes
   - Activate "Now Art Gallery Child"

## Services

- **wordpress**: WordPress application (port 8080)
- **db**: MySQL 8.0 database
- **phpmyadmin**: Database management (port 8081)
- **mailhog**: Email testing tool (port 8025)

## Volume Mounts

The child theme is mounted from `theme/nowartgallery-child/` to the WordPress themes directory, so changes are reflected immediately.

## Useful Commands

```bash
# Start containers
docker-compose up -d

# Stop containers
docker-compose down

# View logs
docker-compose logs -f

# Access WordPress container shell
docker exec -it nowartgallery-wp bash

# Access database
docker exec -it nowartgallery-db mysql -u wordpress -p wordpress

# Reset everything (WARNING: deletes all data)
docker-compose down -v
```

## Troubleshooting

- If port conflicts occur, change ports in `.env` file
- WordPress debug mode is enabled by default (check logs in container)
- Database persists in Docker volume `db_data`
- WordPress files persist in Docker volume `wordpress_data`
