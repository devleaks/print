#
BASEDIR=/Applications/mampstack/apps/yii2/print
export PATH=/Applications/mampstack/php/bin:/Applications/mampstack/mysql/bin:$PATH
DATABASE=yii2print

cd $BASEDIR/runtime/etc

if [ -f $BASEDIR/runtime/restore/$DATABASE.gz -a -f $BASEDIR/runtime/restore/media.taz ]
then
  gunzip < $BASEDIR/runtime/restore/$DATABASE.gz > $DATABASE.sql
  mysql -u$DATABASE -p$DATABASE $DATABASE < drop_all_tables.sql
  mysql -u$DATABASE -p$DATABASE $DATABASE < $DATABASE.sql
  rm $DATABASE.sql
  ( cd $BASEDIR/web ; rm -rf pictures documents ; tar xzf $BASEDIR/runtime/restore/media.taz )
  # sudo -u daemon chown -R daemon:daemon $BASEDIR/web/documents $BASEDIR/web/pictures $BASEDIR/runtime
  echo "Restored."
else
  echo "Files not found."
fi
