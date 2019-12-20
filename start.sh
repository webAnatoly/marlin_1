#!/bin/bash
docker-compose up -d
url="http://localhost:5555"
phpMyAdmin="http://localhost:5556"
firefox -new-tab $phpMyAdmin &
sleep 1
firefox -new-tab $url &
exit 0 #Выход с кодом 0 (удачное завершение работы скрипта)
