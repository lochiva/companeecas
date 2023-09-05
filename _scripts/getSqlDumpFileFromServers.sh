#!/bin/bash

#when used from a trusteless network you should use che command version with "ProxyJump"
#Be sure to have access to lochiva@bastion.lochiva.net without password
#To achieve this run just one time: 
# ssh-copy-id lochiva@bastion.lochiva.net
#insert the password and thats' all


#downloads SQL dump file from production servers 
#uncomment the row you are interested in and run the script
#cd in script dir
cd "$(dirname "$0")"

CONFIG=.env

if [ -f $CONFIG ]; then
        . ${CONFIG}
else
     echo "config file $CONFIG not found"
     exit;
fi

cd ../../../../$MYSQL_SQL_DIR


#ires
#set language to english in order to  generate the name of day of week as on the remote server
export LANG=${LANG/it_IT/en_US}
DOW=$(date +"%a")
#scp iresgest@www.iresgestionaleprogetti.it:/home/iresgest/SQL/iresgest_testapplicativocas_${DOW}.sql.gz IRES_applicativocas.sql.gz
scp iresgest@www.iresgestionaleprogetti.it:/home/iresgest/SQL/iresgest_applicativocas_${DOW}.sql.gz IRES_applicativocas.sql.gz