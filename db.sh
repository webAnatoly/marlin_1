#!/bin/bash
#скрипт для подключения к базе mysql в запущенном докер контейнере
docker-compose up -d db
docker exec -it mysql_my_marlin_project_1 bash
