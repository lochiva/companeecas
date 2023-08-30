#!/bin/bash

#cd in script dir
cd "$(dirname "$0")"

CONFIG=.env

if [ -f $CONFIG ]; then
        . ${CONFIG}
else
     echo "config file $CONFIG not found"
     exit;
fi


sqlFile=IRES_applicativocas.sql.gz
#sqlFile=IRES_applicativocas_demo.sql.gz
db='IRES_applicativocas'
docker-compose -f ../../../docker-compose.yml exec database bash -c "mysql -u root -p${MYSQL_ROOT_PASSWORD}  -e \"create database if not exists ${db}\""
docker-compose -f ../../../docker-compose.yml exec database bash -c "zcat /home/${MYSQL_SQL_DIR}/${sqlFile}  | mysql -u root -p${MYSQL_ROOT_PASSWORD} $db"

mysql -u root -p${MYSQL_ROOT_PASSWORD} -h ${MYSQL_IP} -e "create database if not exists ${db}"
zcat -f ../../$MYSQL_SQL_DIR/${sqlFile}  | mysql -u root -p${MYSQL_ROOT_PASSWORD}   -h ${MYSQL_IP} $db

