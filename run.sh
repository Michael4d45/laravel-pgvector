#!/bin/bash

# Function to load environment variables from .env file
load_env() {
  if [ -f .env ]; then
    export $(grep -v '^#' .env | xargs)
  fi
}

# Function to start Docker containers based on the environment
start_containers() {
  cd docker

  local compose_file="docker-compose.$1.yml"
  if [ -f "$compose_file" ]; then
    docker compose -f docker-compose.yml -f "$compose_file" up -d --build
  else
    echo "Missing compose file: $compose_file"
    exit 1
  fi
}

# Main script execution
load_env

case "$APP_ENV" in
production)
  start_containers "production"
  ;;
local)
  start_containers "local"
  ;;
*)
  echo "Invalid APP_ENV value. Please set APP_ENV to 'production' or 'local'."
  exit 1
  ;;
esac
