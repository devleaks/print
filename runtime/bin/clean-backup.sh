:
# Delete old database backup files
dir=`dirname $0`
. $dir/../../config/shell.sh
echo $dir/../../config/shell.sh
find $YIIDIR/runtime/backup -name "${DBNAME}*.gz" -type f -atime +14 -exec rm {} \;
