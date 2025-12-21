#!/bin/bash

# this script is used by CI/CD Github Workflow
JEEDOM_VERSION=$(cat core/config/version)
# get current jeedom version number, 2 digits e.g. 4.5
JEEDOM_SHORT_VERSION="$(echo "$JEEDOM_VERSION" | awk -F. '{print $1"."$2}')"
# Docker hub repository may be overriden
REPO=${DOCKER_HUB_REPO:-"jeedom"}

if [[ "${GITHUB_REF_NAME}" == "master" ]]; then
  # select the only image for 'latest' tag
  if [[ "${DEBIAN}" == "bookworm" && "${DATABASE}" == "0" ]]; then
    JEEDOM_TAGS="${REPO}/jeedom:latest,${REPO}/jeedom:${JEEDOM_SHORT_VERSION}-${TAG_SUFFIX}";
  else
    JEEDOM_TAGS="${REPO}/jeedom:${JEEDOM_SHORT_VERSION}-${TAG_SUFFIX}"
  fi
  GITHUB_BRANCH=${GITHUB_REF_NAME};
elif [[ "${GITHUB_REF_NAME}" == "beta" ]]; then
  JEEDOM_TAGS="${REPO}/jeedom:${JEEDOM_SHORT_VERSION}-${TAG_SUFFIX}-beta"
  GITHUB_BRANCH=${GITHUB_REF_NAME};
else
  JEEDOM_TAGS="${REPO}/jeedom:${JEEDOM_SHORT_VERSION}-${TAG_SUFFIX}-alpha"
  GITHUB_BRANCH=alpha;
fi

# GITHUB_OUTPUT is the output filename
# https://docs.github.com/en/actions/reference/workflows-and-actions/workflow-commands#setting-an-output-parameter
echo "JEEDOM_TAGS=$JEEDOM_TAGS" >> "$GITHUB_OUTPUT"
echo "GITHUB_BRANCH=$GITHUB_BRANCH" >> "$GITHUB_OUTPUT"
echo "JEEDOM_REPO=$REPO" >> "$GITHUB_OUTPUT"

# GITHUB_STEP_SUMMARY is the workflow summary
# https://docs.github.com/en/actions/using-workflows/workflow-commands-for-github-actions#adding-a-job-summary
echo "### The Current Branch is: ${GITHUB_REF_NAME} :rocket:" >> $GITHUB_STEP_SUMMARY
echo "# Jeedom Version is: $JEEDOM_VERSION" >> $GITHUB_STEP_SUMMARY
echo "### Short Version is: $JEEDOM_SHORT_VERSION" >> $GITHUB_STEP_SUMMARY
echo "### generated Docker tags:\n$JEEDOM_TAGS" >> $GITHUB_STEP_SUMMARY
