
genList:
	php -dopen_basedir=/ ./bin/generateControllerList.php



#cron :  * * * * * root /usr/local/bin/php -dopen_basedir=/  /xxx/xx/web/cron/runCase.inc
cron:
	php -dopen_basedir=/  web/cron/runCase.inc

###########
#	Api   #
###########
checkStatus:
	curl -k http://host/collie/index.php?page=checkTestsStatus&reportGroupId=3
