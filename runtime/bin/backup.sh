:
# Delete old database backup files
dir=`dirname $0`
. $dir/../../config/shell.sh
$YIIDIR/yii backup/create true
