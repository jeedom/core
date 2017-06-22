#!/bin/sh
sed -i '/# The named pipe \/dev\/xconsole/,$d' /etc/rsyslog.conf
systemctl restart rsyslog.service