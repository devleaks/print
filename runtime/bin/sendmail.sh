:
# Delete old database backup files
BASEDIR=/Applications/mampstack/apps/prod
export PATH=/Applications/mampstack/php/bin:/Applications/mampstack/mysql/bin:$PATH
cd $BASEDIR
$BASEDIR/yii mail/send
$BASEDIR/yii mail/notified
$BASEDIR/yii test/fix-payment-status
