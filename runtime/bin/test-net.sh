:
#http://stackoverflow.com/questions/687948/timeout-a-command-in-bash-without-unnecessary-delay

dir=`dirname $0`
. $dir/../../config/shell.sh

if [ -f ${YIIDIR}/runtime/bin/test-curl.sh ]
then
	( ${YIIDIR}/runtime/bin/test-curl.sh ) & pid=$!
	( sleep 35 && kill -HUP $pid ) 2>/dev/null & watcher=$!
	if wait $pid 2>/dev/null; then
	    echo "test-curl finished"
	    pkill -HUP -P $watcher
	    wait $watcher
	    exit 0
	else
	    echo "test-curl interrupted"
	fi
fi
exit 1 # something went wrong