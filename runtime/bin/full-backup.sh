:
# Delete old database backup files
. ../../config/shell.sh
$YIIDIR/yii backup/create false
now=`date "+%Y-%m-%d-%H-%M"`
(cd ${YIIDIR}/web ; tar czf ${YIIDIR}/runtime/backup/media.taz pictures documents)
