openssl genrsa -out jeedom.key 1024
openssl req \
    -new \
    -subj "/C=FR/ST=France/L=Paris/O=jeedom/OU=JE/CN=jeedom" \
    -key jeedom.key \
    -out jeedom.csr
openssl x509 -req -days 9999 -in jeedom.csr -signkey jeedom.key -out jeedom.crt
mkdir /etc/nginx/certs
cp jeedom.key /etc/nginx/certs
cp jeedom.crt /etc/nginx/certs
rm jeedom.key jeedom.crt
cp /usr/share/nginx/www/jeedom/install/nginx_default_ssl /etc/nginx/sites-available/default_ssl
ln -s /etc/nginx/sites-available/default_ssl /etc/nginx/sites-enabled/
service nginx reload