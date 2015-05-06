#!/bin/bash
dir=`dirname $0`
. $dir/../../config/shell.sh

cd $dir/../..

git stash

if [ -n "$1" ]
then
	echo reseting to $1
	git reset --hard $1
else
	echo getting latest
	git pull
fi

composer install --no-progress
