:
# Delete old database backup files
BASEDIR=/Applications/mampstack/apps/prod
export PATH=/Applications/mampstack/php/bin:/Applications/mampstack/mysql/bin:$PATH
cd $BASEDIR
$BASEDIR/yii mail/send
