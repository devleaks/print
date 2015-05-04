#

# L O C A L
# Configfile for paths
MAMPSTACK=/Applications/mampstack
YIIDIR=/Applications/mampstack/apps/yii/print
# DB
DBNAME=yii2print

# R E M O T E
# Info for failover host (to copy files)
REMOTE_YIIDIR=/Applications/mampstack/apps/yii/prod
REMOTE_HOST=192.168.9.105
REMOTE_USER=comptoir
REMOTE_DBNAME=prod

export PATH=${MAMPSTACK}/php/bin:${MAMPSTACK}/mysql/bin:$PATH
