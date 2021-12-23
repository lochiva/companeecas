#!/bin/bash
HTTPDUSER=`ps aux | grep -E '[a]pache|[h]ttpd|[_]www|[w]ww-data|[n]ginx' | grep -v root | head -1 | cut -d\  -f1`

if  [  ! -d tmp  ] ; then

	mkdir tmp
fi


if  [  ! -d logs ] ; then

mkdir logs
fi

if  [  ! -d src/files ] ; then

mkdir src/files

fi

if  [  ! -d webroot/img/user ] ; then

mkdir webroot/img/user

fi

if  [  ! -f config/app.php ] ; then
cp config/app.default.php config/app.php
fi

setfacl -R -m u:${HTTPDUSER}:rwx tmp
setfacl -R -d -m u:${HTTPDUSER}:rwx tmp

setfacl -R -m u:${HTTPDUSER}:rwx logs
setfacl -R -d -m u:${HTTPDUSER}:rwx logs

setfacl -R -m u:${HTTPDUSER}:rwx webroot/img
setfacl -R -d -m u:${HTTPDUSER}:rwx webroot/img

setfacl -R -m u:${HTTPDUSER}:rwx src/files
setfacl -R -d -m u:${HTTPDUSER}:rwx src/files



