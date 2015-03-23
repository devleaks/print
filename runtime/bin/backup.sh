:
# Delete old database backup files
BASEDIR=/Applications/mampstack/apps/yii2/print
export PATH=/Applications/mampstack/php/bin:/Applications/mampstack/mysql/bin:$PATH
cd $BASEDIR
$BASEDIR/yii backup/create true