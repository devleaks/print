:
# Delete old database backup files
BASEDIR=/Applications/mampstack/apps/yii2/print/runtime
find $BASEDIR -name 'backup-*.gz' -type f -atime +14 -exec rm {} \;
