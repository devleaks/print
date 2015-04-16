:
# Delete old database backup files
. ../../config/shell.sh
find $YIIDIR/runtime/backup -name 'backup-*.gz' -type f -atime +14 -exec rm {} \;
