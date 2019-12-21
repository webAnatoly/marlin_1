#!/bin/bash
url="http://localhost:5555"
phpMyAdmin="http://localhost:5556"
docker-compose up -d
firefox -new-tab $phpMyAdmin &
sleep 1
firefox -new-tab $url &
exit 0 #Выход с кодом 0 (удачное завершение работы скрипта)
