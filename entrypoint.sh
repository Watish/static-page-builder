#!/usr/bin/env sh
printf "Start Script \r\n";
printf "Show PHP Version \r\n";
php -v;
cd /opt/app/ && composer install;
printf "Start Watching \r\n";
nohup cd /opt/app/ && php cli.php --watch > /dev/null ;
cd /opt/app/ && php cli.php --serve;


