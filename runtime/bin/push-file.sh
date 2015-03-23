#
target=192.168.9.105

count=`ping -c 1 $target | grep icmp* | wc -l `

if [ $count -eq 0 ]
then
    echo "Host is not Alive! Try again later.."
else
scp /Applications/mampstack/apps/prod/runtime/backup/prod.gz \
                           jj@${target}:/Applications/mampstack/apps/yii2/print/runtime/restore/prod.gz
scp /Applications/mampstack/apps/prod/runtime/backup/media.taz \
                           jj@${target}:/Applications/mampstack/apps/yii2/print/runtime/restore/media.taz
scp /Applications/mampstack/apps/prod/runtime/backup/prod.gz \
                           jj@${target}:/Applications/mampstack/apps/devl/runtime/restore/devl.gz
scp /Applications/mampstack/apps/prod/runtime/backup/media.taz \
                           jj@${target}:/Applications/mampstack/apps/devl/runtime/restore/media.taz
fi

