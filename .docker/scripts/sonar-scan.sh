#!/bin/bash

set -e

RED='\033[0;31m'
GREEN='\033[0;32m'
BLUE='\033[0;34m'
YELLOW='\033[0;93m'
NC='\033[0m'

source ".docker/.env"

APP_NAME=$(echo "$APP_NAME")
COMPOSE_PROJECT_NAME=$(echo "$COMPOSE_PROJECT_NAME")
SONAR_KEY=$(echo "$SONAR_KEY")
SONAR_TOKEN=$(echo "$SONAR_TOKEN")

docker run --rm \
    --platform linux/amd64/v8 \
    --link ${APP_NAME}-sonarqube \
    --net ${COMPOSE_PROJECT_NAME}_default \
    -e SONAR_HOST_URL="http://${APP_NAME}-sonarqube:9000" \
    -e SONAR_LOGIN=${SONAR_TOKEN} \
    -v "$PWD/web:/usr/src/web" \
    -v "$PWD/assets:/usr/src/assets" \
    -v "$PWD/.docker/sonarqube/sonar-project.properties:/usr/src/sonar-project.properties" \
    sonarsource/sonar-scanner-cli \
    -Dsonar.projectKey=${SONAR_KEY} \
    -Dsonar.projectName=${COMPOSE_PROJECT_NAME} \