#!/bin/bash
url="http://localhost:5555"
phpMyAdmin="http://localhost:5556"
docker-compose up -d
firefox $url $phpMyAdmin
exit 0 #Выход с кодом 0 (удачное завершение работы скрипта)
