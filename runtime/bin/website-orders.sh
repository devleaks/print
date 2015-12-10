:
# Fetch website orders and enters them in the system
dir=`dirname $0`
. $dir/../../config/shell.sh
$YIIDIR/yii website/fetch-orders
