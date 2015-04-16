#
. ../../config/shell.sh
if [ ! -d ${YIIDIR}/runtime/restore ]
then
  mkdir ${YIIDIR}/runtime/restore
fi

scp ${REMOTE_USER}@${REMOTE_HOST}:${REMOTE_YIIDIR}/runtime/backup/${REMOTE_DBNAME}.gz \
                           ${YIIDIR}/runtime/restore/${DBNAME}.gz
scp ${REMOTE_USER}@${REMOTE_HOST}:${REMOTE_YIIDIR}/runtime/backup/media.taz \
                           ${REMOTE_YIIDIR}/runtime/restore/media.taz
