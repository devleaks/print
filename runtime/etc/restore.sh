#
dir=`dirname $0`
. $dir/../../config/shell.sh

cd $dir

if [ -f $YIIDIR/runtime/restore/$DBNAME.gz -a -f $YIIDIR/runtime/restore/media.taz ]
then
  gunzip < $YIIDIR/runtime/restore/$DBNAME.gz > /tmp/$DBNAME.sql
  mysql -u$DBNAME -p$DBNAME $DBNAME < $YIIDIR/runtime/etc/drop_all_tables.sql
  mysql -u$DBNAME -p$DBNAME $DBNAME < /tmp/$DBNAME.sql
  rm /tmp/$DBNAME.sql
  ( cd $YIIDIR/web ; rm -rf pictures documents ; tar xzf $YIIDIR/runtime/restore/media.taz )
  # sudo -u daemon chown -R daemon:daemon $YIIDIR/web/documents $YIIDIR/web/pictures $YIIDIR/runtime
  echo "Restored."
else
  echo "Files not found."
fi
