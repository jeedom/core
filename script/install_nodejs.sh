#!/bin/bash

installVer='14' 	#NodeJS major version to be installed
minVer='14'	      #min NodeJS major version to be accepted

# vérifier si toujours nécessaire, cette source traine encore sur certaines smart et si une source est invalide -> nodejs ne s'installera pas
if [ -f /etc/apt/sources.list.d/deb-multimedia.list* ]; then
  echo "Vérification si la source deb-multimedia existe (bug lors du apt-get update si c'est le cas)"
  echo "deb-multimedia existe !"
  if [ -f /etc/apt/sources.list.d/deb-multimedia.list.disabled ]; then
    echo "mais on l'a déjà désactivé..."
  else
    if [ -f /etc/apt/sources.list.d/deb-multimedia.list ]; then
      echo "Désactivation de la source deb-multimedia !"
      sudo mv /etc/apt/sources.list.d/deb-multimedia.list /etc/apt/sources.list.d/deb-multimedia.list.disabled &>/dev/null
    else
      if [ -f /etc/apt/sources.list.d/deb-multimedia.list.disabled ]; then
        echo "mais il est déjà désactivé..."
      else
        echo "mais n'est pas 'disabled' ... il sera normalement ignoré donc ca devrait passer..."
      fi
    fi
  fi
fi

# sur smart, je désactive le repo.jeedom car toujours un risque à l'heure actuel que nodejs s'install pas bien
toReAddRepo=0
if [ -f /media/boot/multiboot/meson64_odroidc2.dtb.linux ]; then
  hasRepo=$(grep "repo.jeedom.com" /etc/apt/sources.list | wc -l)
  if [ "$hasRepo" -ne "0" ]; then
    echo "Désactivation de la source repo.jeedom.com !"
    toReAddRepo=1
    sudo apt-add-repository -r "deb http://repo.jeedom.com/odroid/ stable main"
  fi
fi

#prioritize nodesource nodejs : just in case
sudo bash -c "cat >> /etc/apt/preferences.d/nodesource" << EOL
Package: nodejs
Pin: origin deb.nodesource.com
Pin-Priority: 600
EOL

# Mise à jour APT et installation des packages nécessaires
sudo apt-get update
sudo DEBIAN_FRONTEND=noninteractive apt-get install -y lsb-release build-essential apt-utils git

# Vérification du système
arch=`arch`;

#jessie as libstdc++ > 4.9 needed for nodejs 12
lsb_release -c | grep jessie
if [ $? -eq 0 ]
then
  today=$(date +%Y%m%d)
  if [[ "$today" > "20200630" ]]; 
  then 
  echo "== ATTENTION Debian 8 Jessie n'est officiellement plus supportée depuis le 30 juin 2020, merci de mettre à jour votre distribution !!!"
  exit 1
fi
fi

#x86 32 bits not supported by nodesource anymore
bits=$(getconf LONG_BIT)
if { [ "$arch" = "i386" ] || [ "$arch" = "i686" ]; } && [ "$bits" -eq "32" ]
then 
echo "== ATTENTION Votre système est x86 en 32bits et NodeJS 12 n'y est pas supporté, merci de passer en 64bits !!!"
exit 1 
fi


type nodejs &>/dev/null
if [ $? -eq 0 ]; then actual=`nodejs -v`; else actual='Aucune'; fi
echo -n "[Check Version NodeJS actuelle : ${actual} : "
testVer=$(php -r "echo version_compare('${actual}','v${minVer}','>=');")
if [[ $testVer == "1" ]]
then
  echo "[  OK  ]";
  new=$actual
else
  echo "[  KO  ]";
  echo "Installation de NodeJS $installVer"
  
  #if npm exists
  type npm &>/dev/null
  if [ $? -eq 0 ]; then
    npmPrefix=`npm prefix -g`
  else
    npmPrefix="/usr"
  fi
  
  sudo DEBIAN_FRONTEND=noninteractive apt-get -y --purge autoremove npm &>/dev/null
  sudo DEBIAN_FRONTEND=noninteractive apt-get -y --purge autoremove nodejs &>/dev/null
  
  if [[ $arch == "armv6l" ]]
  then
    #version to install for armv6 (to check on https://unofficial-builds.nodejs.org)
    if [[ $installVer == "12" ]]
    then
      armVer="12.21.0"
    fi
    if [[ $installVer == "13" ]]
    then
      armVer="13.14.0"
    fi
    if [[ $installVer == "14" ]]
    then
      armVer="14.16.0"
    fi
    if [[ $installVer == "15" ]]
    then
      armVer="15.9.0"
    fi
    echo "Jeedom Mini ou Raspberry 1, 2 ou zéro détecté, non supporté mais on essaye l'utilisation du paquet non-officiel ${armVer} pour armv6l"
    wget https://unofficial-builds.nodejs.org/download/release/${armvVer}/node-v${armVer}-linux-armv6l.tar.gz
    tar -xvf node-v${armVer}-linux-armv6l.tar.gz
    cd node-v${armVer}-linux-armv6l
    sudo cp -f -R * /usr/local/
    cd ..
    rm -fR node-v${armVer}-linux-armv6l* &>/dev/null
    ln -s /usr/local/bin/node /usr/bin/node &>/dev/null
    ln -s /usr/local/bin/node /usr/bin/nodejs &>/dev/null
    #upgrade to recent npm
    sudo npm install -g npm
  else
    echo "Utilisation du dépot officiel"
    curl -sL https://deb.nodesource.com/setup_${installVer}.x | sudo -E bash -
    sudo DEBIAN_FRONTEND=noninteractive apt-get install -y nodejs  
  fi
  
  npm config set prefix ${npmPrefix} &>/dev/null

  if [ $(which node | wc -l) -ne 0 ] && [ $(which nodejs | wc -l) -eq 0 ]; then 
    ln -s $(which node) $(which node)js
  fi
  
  new=`nodejs -v`;
  echo -n "[Check Version NodeJS après install : ${new} : "
  testVerAfter=$(php -r "echo version_compare('${new}','v${minVer}','>=');")
  if [[ $testVerAfter != "1" ]]
  then
    echo "[  KO  ] -> relancez les dépendances"
  else
    echo "[  OK  ]"
  fi
fi

type npm &>/dev/null
if [ $? -ne 0 ]; then
  # Installation de npm car non présent (par sécu)
  sudo DEBIAN_FRONTEND=noninteractive apt-get install -y npm  
  sudo npm install -g npm
fi

type npm &>/dev/null
if [ $? -eq 0 ]; then
  npmPrefix=`npm prefix -g`
  npmPrefixSudo=`sudo npm prefix -g`
  npmPrefixwwwData=`sudo -u www-data npm prefix -g`
  echo -n "[Check Prefix : $npmPrefix and sudo prefix : $npmPrefixSudo and www-data prefix : $npmPrefixwwwData : "
  if [[ "$npmPrefixSudo" != "/usr" ]] && [[ "$npmPrefixSudo" != "/usr/local" ]]; then 
  echo "[  KO  ]"
  if [[ "$npmPrefixwwwData" == "/usr" ]] || [[ "$npmPrefixwwwData" == "/usr/local" ]]; then
    echo "Reset prefix ($npmPrefixwwwData) pour npm `sudo whoami`"
    sudo npm config set prefix $npmPrefixwwwData
  else
    if [[ "$npmPrefix" == "/usr" ]] || [[ "$npmPrefix" == "/usr/local" ]]; then
      echo "Reset prefix ($npmPrefix) pour npm `sudo whoami`"
      sudo npm config set prefix $npmPrefix
    else
      [ -f /usr/bin/raspi-config ] && { rpi="1"; } || { rpi="0"; }
      if [[ "$rpi" == "1" ]]; then
        echo "Reset prefix (/usr) pour npm `sudo whoami`"
        sudo npm config set prefix /usr
      else
        echo "Reset prefix (/usr/local) pour npm `sudo whoami`"
        sudo npm config set prefix /usr/local
      fi
    fi
  fi  
else
  if [[ "$npmPrefixwwwData" == "/usr" ]] || [[ "$npmPrefixwwwData" == "/usr/local" ]]; then
    if [[ "$npmPrefixwwwData" == "$npmPrefixSudo" ]]; then
      echo "[  OK  ]"
    else
      echo "[  KO  ]"
      echo "Reset prefix ($npmPrefixwwwData) pour npm `sudo whoami`"
      sudo npm config set prefix $npmPrefixwwwData
    fi
  else
    echo "[  KO  ]"
    if [[ "$npmPrefix" == "/usr" ]] || [[ "$npmPrefix" == "/usr/local" ]]; then
      echo "Reset prefix ($npmPrefix) pour npm `sudo whoami`"
      sudo npm config set prefix $npmPrefix
    else
      [ -f /usr/bin/raspi-config ] && { rpi="1"; } || { rpi="0"; }
      if [[ "$rpi" == "1" ]]; then
        echo "Reset prefix (/usr) pour npm `sudo whoami`"
        sudo npm config set prefix /usr
      else
        echo "Reset prefix (/usr/local) pour npm `sudo whoami`"
        sudo npm config set prefix /usr/local
      fi
    fi
  fi
fi
fi

# on nettoie la priorité nodesource
sudo rm -f /etc/apt/preferences.d/nodesource &>/dev/null

# on remet deb-multimedia si on l'a désactivé avant
if [ -f /etc/apt/sources.list.d/deb-multimedia.list.disabled ]; then
  echo "Réactivation de la source deb-multimedia qu'on avait désactivé !"
  sudo mv /etc/apt/sources.list.d/deb-multimedia.list.disabled /etc/apt/sources.list.d/deb-multimedia.list &>/dev/null
fi

# on remet le repo.jeedom si on l'a désactivé avant + refresh de la clé
if [ "$toReAddRepo" -ne "0" ]; then
  echo "Réactivation de la source repo.jeedom.com qu'on avait désactivé !"
  toReAddRepo=0
  sudo wget --quiet -O - http://repo.jeedom.com/odroid/conf/jeedom.gpg.key | sudo apt-key add - 
  sudo apt-add-repository "deb http://repo.jeedom.com/odroid/ stable main" &>/dev/null
fi

