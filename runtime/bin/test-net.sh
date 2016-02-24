:
#http://stackoverflow.com/questions/687948/timeout-a-command-in-bash-without-unnecessary-delay

dir=`dirname $0`
. $dir/../../config/shell.sh

TIMEOUT=25
TIMEOUT_INT=$(($TIMEOUT + 5))
TESTFILE=https://github.com/devleaks/print.git

( curl -s --connect-timeout $TIMEOUT $TESTFILE > /dev/null ) & pid=$!
( sleep $TIMEOUT_INT && kill -HUP $pid ) 2>/dev/null & watcher=$!
if wait $pid 2>/dev/null; then
    echo "curl finished ($?)"
    pkill -HUP -P $watcher
    wait $watcher
    exit 0 # only way to exit correctly
else
	curl_status=$?
	date
    echo "curl interrupted ($curl_status)"
	exit $curl_status # problem with curl, see curl(1) for exit statuses
fi
exit 1 # something went wrong