#
. ../../config/shell.sh

count=`ping -c 1 ${REMOTE_HOST} | grep icmp* | wc -l`

if [ $count -gt 0 ]
then
scp ${YIIDIR}/runtime/backup/${DBNAME}.gz \
                           ${REMOTE_USER}@${REMOTE_HOST}:${REMOTE_YIIDIR}/runtime/restore/${REMOTE_DBNAME}.gz
scp ${YIIDIR}/runtime/backup/media.taz \
                           ${REMOTE_USER}@${REMOTE_HOST}:${REMOTE_YIIDIR}/runtime/restore/media.taz

## Added to test only: Note dbname hardcoded
#scp ${YIIDIR}/runtime/backup/prod.gz \
#                           ${REMOTE_USER}@${REMOTE_HOST}:${REMOTE_YIIDIR}/runtime/restore/devl.gz
#scp ${YIIDIR}/runtime/backup/media.taz \
#                           ${REMOTE_USER}@${REMOTE_HOST}:${REMOTE_YIIDIR}/runtime/restore/media.taz
else
    echo "Host unreachable. Try again later."
fi
