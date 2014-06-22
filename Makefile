
genList:
	php -dopen_basedir=/ ./bin/generateControllerList.php


cron:
	php -dopen_basedir=/  web/cron/runCase.inc
