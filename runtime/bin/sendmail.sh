:
dir=`dirname $0`
. $dir/../../config/shell.sh
BASEDIR=/Applications/mampstack/apps/prod
cd $YIIDIR

if [ -f ${YIIDIR}/runtime/bin/test-curl.sh ]
then
	${YIIDIR}/runtime/bin/test-curl.sh
	if [ $? -eq 0 ]
	then
		$YIIDIR/yii mail/send
	fi
fi

$YIIDIR/yii mail/notified
$YIIDIR/yii test/fix-payment-status
