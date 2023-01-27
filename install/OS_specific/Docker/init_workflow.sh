#!/bin/bash

# this script is used by CI/CD Github Workflow
JEEDOM_VERSION=$(cat core/config/version)
JEEDOM_SHORT_VERSION="$(echo "$JEEDOM_VERSION" | awk -F. '{print $1"."$2}')"

if [[ "${GITHUB_REF_NAME}" == "V4-Stable" ]]; then
  JEEDOM_TAGS="${DOCKER_HUB_USERNAME}/jeedom:latest,${DOCKER_HUB_USERNAME}/jeedom:$JEEDOM_SHORT_VERSION";
elif [[ "${GITHUB_REF_NAME}" == "beta" ]]; then
  JEEDOM_TAGS="${DOCKER_HUB_USERNAME}/jeedom:beta,${DOCKER_HUB_USERNAME}/jeedom:$JEEDOM_SHORT_VERSION";
else
  JEEDOM_TAGS="${DOCKER_HUB_USERNAME}/jeedom:$JEEDOM_SHORT_VERSION";
fi

# GITHUB_ENV is the environment variables filename
# https://docs.github.com/en/actions/using-workflows/workflow-commands-for-github-actions#environment-files
echo "JEEDOM_VERSION=$JEEDOM_VERSION" >> $GITHUB_ENV
echo "JEEDOM_SHORT_VERSION=$JEEDOM_SHORT_VERSION" >> $GITHUB_ENV
echo "JEEDOM_TAGS=$JEEDOM_TAGS" >> $GITHUB_ENV

# GITHUB_STEP_SUMMARY is the workflow summary
# https://docs.github.com/en/actions/using-workflows/workflow-commands-for-github-actions#adding-a-job-summary
echo "### The Current Branch is: ${GITHUB_REF_NAME} :rocket:" >> $GITHUB_STEP_SUMMARY
echo "# Jeedom Version is: $JEEDOM_VERSION" >> $GITHUB_STEP_SUMMARY
echo "# Short Version is: $JEEDOM_SHORT_VERSION" >> $GITHUB_STEP_SUMMARY
echo "# Jeedom Tags: $JEEDOM_TAGS" >> $GITHUB_STEP_SUMMARY