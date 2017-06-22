#!/bin/bash

#echo "--DEBUG--"
#echo "TRAVIS_REPO_SLUG: $TRAVIS_REPO_SLUG"
#echo "TRAVIS_PHP_VERSION: $TRAVIS_PHP_VERSION"
#echo "TRAVIS_PULL_REQUEST: $TRAVIS_PULL_REQUEST"

if [ "$TRAVIS_REPO_SLUG" == "jeedom/core" ] && [ "$TRAVIS_PULL_REQUEST" == "false" ] && [ "$TRAVIS_PHP_VERSION" == "5.6" ] && [ "$TRAVIS_BRANCH" == "beta" ]; then
  echo -e "Publishing PHPDoc...\n"
  cp -R build/docs $HOME/docs-latest
  git config --global user.email "travis@travis-ci.org"
  git config --global user.name "travis-ci"
  mkdir -p /usr/jeedom
  cd /usr/jeedom
  git clone --branch=gh-pages https://${GH_TOKEN}@github.com/jeedom/documentation.git
  if [ ! -f /usr/jeedom/documentation/phpdoc ]; then
    mkdir -p /usr/jeedom/documentation/phpdoc
  fi
  cd /usr/jeedom/documentation/phpdoc
  rm -rf /usr/jeedom/documentation/phpdoc/*
  cp -Rf $HOME/docs-latest/* /usr/jeedom/documentation/phpdoc/
  cd /usr/jeedom/documentation
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