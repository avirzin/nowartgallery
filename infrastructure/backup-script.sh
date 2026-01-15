#!/bin/bash

# Now Art Gallery - Automated Backup Script
# Backs up WordPress database and wp-content to S3
#
# Usage:
#   ./backup-script.sh
#
# Setup:
#   1. Install AWS CLI: sudo apt-get install awscli
#   2. Configure AWS credentials: aws configure
#   3. Make script executable: chmod +x backup-script.sh
#   4. Add to crontab: crontab -e
#      Add: 0 2 * * * /path/to/backup-script.sh >> /var/log/wordpress-backup.log 2>&1

set -e

# Configuration
S3_BUCKET="nowartgallery-backups"
S3_PREFIX="wordpress"
BACKUP_DIR="/tmp/nowartgallery-backups"
RETENTION_DAYS=30
DATE=$(date +%Y%m%d_%H%M%S)

# WordPress paths (Bitnami default)
WP_ROOT="/opt/bitnami/wordpress"
WP_CONTENT="${WP_ROOT}/wp-content"
DB_NAME="bitnami_wordpress"
DB_USER="bn_wordpress"
DB_PASS=$(cat /opt/bitnami/wordpress/wp-config.php | grep DB_PASSWORD | cut -d \' -f 4)

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Functions
log_info() {
    echo -e "${GREEN}[INFO]${NC} $1"
}

log_warn() {
    echo -e "${YELLOW}[WARN]${NC} $1"
}

log_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Create backup directory
mkdir -p "${BACKUP_DIR}"

log_info "Starting backup process at $(date)"

# Backup database
log_info "Backing up database..."
DB_BACKUP_FILE="${BACKUP_DIR}/database_${DATE}.sql.gz"
mysqldump -u "${DB_USER}" -p"${DB_PASS}" "${DB_NAME}" | gzip > "${DB_BACKUP_FILE}"

if [ $? -eq 0 ]; then
    log_info "Database backup created: ${DB_BACKUP_FILE}"
else
    log_error "Database backup failed!"
    exit 1
fi

# Backup wp-content (excluding uploads if too large, or include them)
log_info "Backing up wp-content..."
WP_CONTENT_BACKUP_FILE="${BACKUP_DIR}/wp-content_${DATE}.tar.gz"
tar -czf "${WP_CONTENT_BACKUP_FILE}" \
    --exclude="${WP_CONTENT}/uploads" \
    --exclude="${WP_CONTENT}/cache" \
    -C "${WP_ROOT}" wp-content

if [ $? -eq 0 ]; then
    log_info "wp-content backup created: ${WP_CONTENT_BACKUP_FILE}"
else
    log_error "wp-content backup failed!"
    exit 1
fi

# Optional: Backup uploads separately (if needed)
# log_info "Backing up uploads..."
# UPLOADS_BACKUP_FILE="${BACKUP_DIR}/uploads_${DATE}.tar.gz"
# tar -czf "${UPLOADS_BACKUP_FILE}" -C "${WP_CONTENT}" uploads

# Upload to S3
log_info "Uploading backups to S3..."

aws s3 cp "${DB_BACKUP_FILE}" "s3://${S3_BUCKET}/${S3_PREFIX}/database/" || {
    log_error "Failed to upload database backup to S3"
    exit 1
}

aws s3 cp "${WP_CONTENT_BACKUP_FILE}" "s3://${S3_BUCKET}/${S3_PREFIX}/wp-content/" || {
    log_error "Failed to upload wp-content backup to S3"
    exit 1
}

log_info "Backups uploaded successfully to s3://${S3_BUCKET}/${S3_PREFIX}/"

# Clean up local backups
log_info "Cleaning up local backup files..."
rm -f "${BACKUP_DIR}"/*.sql.gz "${BACKUP_DIR}"/*.tar.gz

# Clean up old backups from S3 (older than retention period)
log_info "Cleaning up old backups from S3 (older than ${RETENTION_DAYS} days)..."
aws s3 ls "s3://${S3_BUCKET}/${S3_PREFIX}/database/" | while read -r line; do
    createDate=$(echo $line | awk {'print $1" "$2'})
    createDate=$(date -d "$createDate" +%s)
    olderThan=$(date -d "${RETENTION_DAYS} days ago" +%s)
    if [[ $createDate -lt $olderThan ]]; then
        fileName=$(echo $line | awk {'print $4'})
        if [[ $fileName != "" ]]; then
            aws s3 rm "s3://${S3_BUCKET}/${S3_PREFIX}/database/${fileName}"
            log_info "Deleted old backup: ${fileName}"
        fi
    fi
done

aws s3 ls "s3://${S3_BUCKET}/${S3_PREFIX}/wp-content/" | while read -r line; do
    createDate=$(echo $line | awk {'print $1" "$2'})
    createDate=$(date -d "$createDate" +%s)
    olderThan=$(date -d "${RETENTION_DAYS} days ago" +%s)
    if [[ $createDate -lt $olderThan ]]; then
        fileName=$(echo $line | awk {'print $4'})
        if [[ $fileName != "" ]]; then
            aws s3 rm "s3://${S3_BUCKET}/${S3_PREFIX}/wp-content/${fileName}"
            log_info "Deleted old backup: ${fileName}"
        fi
    fi
done

log_info "Backup process completed at $(date)"

# Optional: Send notification email
# echo "Backup completed successfully" | mail -s "WordPress Backup Success" admin@nowartgallery.art.br

exit 0
