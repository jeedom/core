# Jeedom by Loïc Gevrey #

(Version en français [ci-dessous](#french).)

## Prerequisites ##

* PHP 5.3
* MySQL
* PHP cURL
* nodeJS


To retrieve necessary packages:
```bash
apt-get update
apt-get install nginx-common  nginx-full
apt-get install mysql-client mysql-common mysql-server mysql-server-core
apt-get install nodejs php5-common php5-fpm php5-cli php5-curl php5-json
```
        
If you need LDAP:
```bash
apt-get install php5-ldap
```



## Installation ##

* Configure `core/config/common.config.sample.php` and rename it to `core/config/common.config.php`;
  * Set up database (DB) access
* Run `php install/install.php`
       
## Configuring nginx ##

### Simple ###

```bash
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
```

### Vhosts with SSL ###

```bash
server {
  listen       443;
  server_name  my.domain.fr;
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

# Re-routing http requests as https.
server {
  listen 80;
  server_name my.domain.bla www.my.domain.bla;
  rewrite     ^(.*)   https://$server_name$1 permanent;
}
```

## Configuring jeedom ##


* Go to `Admin` and retrieve the api key
* Add this line to the crontab :

```bash
* * * * * * su --shell=/bin/bash - www-data -c "/usr/bin/php /usr/share/nginx/www/jeedom/core/php/jeeCron.php" > /dev/null
```



# French #



## Pre-requis ##

* PHP 5.3
* MySQL
* PHP cURL
* nodeJS


Si vous n'avez pas encore installé les dépendances :
```bash
apt-get update
apt-get install nginx-common  nginx-full
apt-get install mysql-client mysql-common mysql-server mysql-server-core
apt-get install nodejs php5-common php5-fpm php5-cli php5-curl php5-json
```
        
Si vous avez besoin du LDAP :
```bash
apt-get install php5-ldap
```



## Installation ##

* Configurer `core/config/common.config.sample.php` et le renommer en `core/config/common.config.php`
  * Configurer l'accès à la BDD
* En ligne de commandes exécuter `php install/install.php`
       
## Configuration nginx ##

### Simple ###

```bash
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
```

### Vhosts avec SSL ###

```bash
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

# Redirection des requêtes http en https
server {
  listen 80;
  server_name mon.domaine.fr www.mon.domaine.fr;
  rewrite     ^(.*)   https://$server_name$1 permanent;
}
```

## Configuration de jeedom ##
* Aller dans `Admin` puis récupérer la clef api
* Ajouter cette ligne à crontab :

  ```bash
  * * * * * * su --shell=/bin/bash - www-data -c "/usr/bin/php /usr/share/nginx/www/jeedom/core/php/jeeCron.php" > /dev/null
  ```
