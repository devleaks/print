#
dir=`dirname $0`
. $dir/../../config/shell.sh

cd $dir

if [ -f $1 -a -f $YIIDIR/runtime/restore/media.taz ]
then
  gunzip < $1 > $DBNAME.sql
  mysql -u$DBNAME -p$DBNAME $DBNAME < $YIIDIR/runtime/etc/drop_all_tables.sql
  mysql -u$DBNAME -p$DBNAME $DBNAME < $DBNAME.sql
  rm $DBNAME.sql
  echo "Restored database only."
  exit 0
else
  echo "Files not found ($1 or $YIIDIR/runtime/restore/media.taz)."
  exit 2
fi
