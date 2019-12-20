#!/bin/bash
docker-compose up -d
url="http://localhost:5555"
#firefox -new-window --devtools $url &
firefox -new-tab $url &
exit 0 #Выход с кодом 0 (удачное завершение работы скрипта)
