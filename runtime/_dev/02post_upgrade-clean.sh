### clean
cd /Applications/mampstack/apps/devl
rm runtime/backup/*
rm runtime/debug/mail/*
rm runtime/debug/*
rm runtime/logs/*
rm runtime/document/*
rm runtime/document/account/*
rm runtime/document/late-bills/*
rm runtime/extraction/*
#
rm -r web/pictures/*
find web/assets -type l -exec rm {} \;