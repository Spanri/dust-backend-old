#!/bin/bash

# Настройка виртуального хоста (подходит для ubuntu, остальные хз)

# Запуск: sudo sh setVH.sh <название_сайта>
# Пример: sudo sh setVH.sh example.ru
# Важно: рядом со скриптом (в той же директории) должен лежать
# файл <название_сайта>.conf (например, example.ru.conf)

# Скрипт берет файл <название_сайта>.conf, этот файл помещается
# в апачевский конфиг, производятся все действия по настройке,
# в хосты ОС прописывается название сайта.

#  Если без sudo, не делаем скрипт
if [ "$(whoami)" != "root" ]; then
	echo "\e[32m× Root privileges are required to run this, try running with sudo\e[39m"
	exit 1;
fi

# Если параметр не введен, не делаем скрипт
if [ "$1" = "" ] ; then
  echo "\e[91m× Parameter with site name not found\e[39m"
  exit 1;
fi

# Берем название сайта, название конфига для сайта,
# проверяем существование файла конфига
site_name=$1
config_file="$site_name.conf"
if [ ! -f "$config_file" ]; then
  echo "\e[91m× File '$config_file' does not exist\e[39m";
  exit 1;
fi

# Перемещаем в sites-available файл <название_сайта>.conf
sites_available_path="/etc/apache2/sites-available/"
cp $config_file /$sites_available_path
echo "\e[32m✔ File '$config_file' was successfully copied to the folder '$sites_available_path'\e[39m"

# Проверяем существование файла конфига в sites-enabled и, если его нет,
# делаем так, чтобы был
sites_enabled_path="/etc/apache2/sites-enabled/"
if [ ! -f "$sites_enabled_path/$config_file" ]; then
  sudo a2ensite $config_file
  echo "\e[32m✔ File '$config_file' was successfully e2ensite to the folder '$sites_enabled_path'\e[39m"
else 
  echo "\e[32m✔ Operation 'e2ensite' is not necessary\e[39m"
fi

# Перезапускаем апаче
systemctl restart apache2
echo "\e[32m✔ Apache2 restarted\e[39m"

# Хз, так ли в других ОС
hosts_path="/etc/hosts"
if [ ! $(grep -Ril $site_name "$hosts_path") ]; then
  echo 127.0.0.1    $site_name >> $hosts_path
  echo "\e[32m✔ Hosts file updated\e[39m"
else
  echo "\e[32m✔ Updating the hosts file is not necessary\e[39m"
fi

# echo $site_name
# echo $config_file
# echo $sites_available_path
# sites_available_path=$(find / -xdev 2>/dev/null -name "sites-available")