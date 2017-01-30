#!/bin/bash

#echo "--DEBUG--"
#echo "TRAVIS_REPO_SLUG: $TRAVIS_REPO_SLUG"
#echo "TRAVIS_PHP_VERSION: $TRAVIS_PHP_VERSION"
#echo "TRAVIS_PULL_REQUEST: $TRAVIS_PULL_REQUEST"

if [ "$TRAVIS_REPO_SLUG" == "jeedom/core" ] && [ "$TRAVIS_PULL_REQUEST" == "false" ] && [ "$TRAVIS_PHP_VERSION" == "5.6" ] && [ "$TRAVIS_BRANCH" == "beta" ]; then
  echo -e "Publishing PHPDoc...\n"
  git config --global user.email "travis@travis-ci.org"
  git config --global user.name "travis-ci"
  mkdir /usr/jeedom
  cd /usr/jeedom
  git clone --quiet --branch=gh-pages https://github.com/jeedom/documentation.git
  if [ -f /usr/jeedom/documenation/phpdoc ]; then
    mkdir phpdoc
  fi
  cd /usr/jeedom/documenation/phpdoc
  rm -rf /usr/jeedom/documenation/phpdoc/*
  phpdoc -d /var/www/html/core/class -t /usr/jeedom/documenation/phpdoc/
  cd /usr/jeedom/documenation/phpdoc
  git add -f .
  git commit -m "PHPDocumentor (Travis Build : $TRAVIS_BUILD_NUMBER  - Branch : $TRAVIS_BRANCH)"
  git push -fq origin gh-pages
  echo -e "Published PHPDoc.\n"
else
  echo "No doc to generate : "
  echo "TRAVIS_REPO_SLUG : $TRAVIS_REPO_SLUG"
  echo "TRAVIS_PULL_REQUEST : $TRAVIS_PULL_REQUEST"
  echo "TRAVIS_PHP_VERSION : $TRAVIS_PHP_VERSION"
  echo "TRAVIS_BRANCH : $TRAVIS_BRANCH"
fi