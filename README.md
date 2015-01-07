# Jeedom by Loïc Gevrey #

## Prerequisites ##
* PHP 5.3
* MySQL
* PHP cURL
* nodeJS

        Si vous n'avez pas encore installé les dépendances :
            apt-get update
            apt-get install nginx-common  nginx-full
            apt-get install mysql-client mysql-common mysql-server mysql-server-core
            apt-get install nodejs php5-common php5-fpm php5-cli php5-curl php5-json
        Si vous avez besoin du LDAP : 
            apt-get install php5-ldap
    2) Installation
        a) Configurer core/config/common.config.sample.php et le renommer en core/config/common.config.php
            - Configurer l'accès à la BDD
        b) En ligne de commandes exécuter php install/install.php 
       
    3) Configuration nginx 
        - Simple
            #######################################################################
            location / {
                    try_files $uri $uri/ /index.html /index.php;
            }

            location /nodeJS/ {
                    proxy_set_header X-NginX-Proxy true;
                    proxy_pass http://127.0.0.1:8070/;
                    proxy_http_version 1.1;
                    proxy_set_header Upgrade $http_upgrade;
                    proxy_set_header Connection "upgrade";
                    proxy_set_header Host $host;
                    proxy_redirect off;
            }

            location /socket.io/ {
                    proxy_pass http://127.0.0.1:8070/socket.io/;
                    proxy_http_version 1.1;
                    proxy_set_header Upgrade $http_upgrade;
                    proxy_set_header Connection "upgrade";
                    proxy_set_header Host $host;
                    proxy_redirect off;
            }

            location ~ \.php$ {
                    try_files $uri =404;
                    fastcgi_pass unix:/var/run/php5-fpm.sock;
                    fastcgi_index index.php;
                    include fastcgi_params;
            }
            #######################################################################

        - Vhosts avec SSL
            #######################################################################
            server {
                    listen       443;
                    server_name mon.domain.fr;
                    ssl          on;
                    ssl_certificate      /etc/nginx/certs/jeedom.crt;
                    ssl_certificate_key  /etc/nginx/certs/jeedom.key;

                    client_max_body_size 20M;

                    error_page  497  https://$host$request_uri;

                    proxy_set_header   Host $http_host;
                    proxy_set_header   X-Real-IP $remote_addr;
                    proxy_set_header   X-Forwarded-For $proxy_add_x_forwarded_for;
                    proxy_set_header   X-Forwarded-Proto $scheme;

                    location /nodeJS/ {
                            proxy_set_header X-NginX-Proxy true;
                            proxy_pass http://serverIP:NodeJsPort/;
                            proxy_http_version 1.1;
                            proxy_set_header Upgrade $http_upgrade;
                            proxy_set_header Connection "upgrade";
                            proxy_set_header Host $host;
                            proxy_redirect off;
                    }      

                    location /socket.io/ {
                            proxy_pass http://serverIP:NodeJsPort/socket.io/;
                            proxy_http_version 1.1;
                            proxy_set_header Upgrade $http_upgrade;
                            proxy_set_header Connection "upgrade";
                            proxy_set_header Host $host;
                            proxy_redirect off;
                    }      


                    location ~ \.php$ {
                            try_files $uri =404;
                            fastcgi_pass unix:/var/run/php5-fpm.sock;
                            fastcgi_index index.php;
                            include fastcgi_params;
                    }

            }

            #Redirection des requêtes http en https
            server {
               listen 80;
               server_name mon.domaine.fr www.mon.domaine.fr;
               rewrite     ^(.*)   https://$server_name$1 permanent;
            }
            #######################################################################

    4) Configuration de jeedom
        a) Aller dans Admin puis récupérer la clef api
        b) Ajouter cette ligne à crontab :  "* * * * * * su --shell=/bin/bash - www-data -c "/usr/bin/php /usr/share/nginx/www/jeedom/core/php/jeeCron.php" >> /dev/null "