#
if [ ! -d /Applications/mampstack/apps/yii2/print/runtime/restore ]
then
  mkdir /Applications/mampstack/secours/secours/runtime/restore
fi

scp comptoir@192.168.9.123:/Applications/mampstack/apps/prod/runtime/backup/prod.gz \
                           /Applications/mampstack/apps/yii2/print/runtime/restore/prod.gz
scp comptoir@192.168.9.123:/Applications/mampstack/apps/prod/runtime/backup/media.taz \
                           /Applications/mampstack/apps/yii2/print/runtime/restore/media.taz
