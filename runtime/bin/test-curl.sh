:
dir=`dirname $0`
. $dir/../../config/shell.sh

curl -s --connect-timeout 30 https://github.com/devleaks/print.git > /dev/null
saved=$?
date
echo $saved
exit $saved