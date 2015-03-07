#!/bin/bash
if [ -f /tmp/watchdog-mysql ]; then
    exit 0
else
    echo "" > /tmp/watchdog-mysql
    sudo /etc/init.d/mysql status > /dev/null 2>&1
    if [ $? -eq 3 ]; then
        echo "Mysql arreté je le redemarre"
        sudo /etc/init.d/mysql stop
        sudo /etc/init.d/mysql start 
    fi
    sudo rm -f /tmp/watchdog-mysql
fi