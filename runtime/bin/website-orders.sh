:
# Fetch website orders and enters them in the system
BASEDIR=/Applications/mampstack/apps/prod
export PATH=/Applications/mampstack/php/bin:/Applications/mampstack/mysql/bin:$PATH
cd $BASEDIR
$BASEDIR/yii website/fetch-orders
