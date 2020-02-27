# DUST games

## Настройка

1) Надо настроить apache.

a) с помощью моего скрипта
```bash
sudo sh ./scripts/setVH.sh dust.games
```

б) без моего скрипта
* настроить в `sites-available` (добавить <сайт>.conf)
* не забыть про `en2site`
``` bash
sudo a2ensite example.com.conf
```
* настроить хост в файле `hosts`
```bash
127.0.1.1   example.com
```
* перезапуск apache
```bash
sudo systemctl restart apache2
```
* возможно, убрать права у папок (можно попробовать 755 вместо 777)
```
sudo chmod 777 <путь_папки> 
```
