# Overview
Collie is a automated test framework, it is designed to improve a project to be more stable.

Collie use PHP and selenium to implement End-to-End test and support a easy UI to controller your tests.

Beyond the unit test, collie try to test every feature in the real page of website. Collie won't test the fake data, we test the real world.

And it is free.

## What is the special of Collie.
Collie develop many controller for you to use, you can arrange the sequence of controllers, and create your test procedure.
No matter to be afraid of program, Collie give you a nice UI to edit your tests.

### Demo Video
https://www.youtube.com/watch?v=8SJ29Ljw-NE

### The demo website
http://collie.puritys.me/

### Switch Environment
Do you have the problem to test alpha,beta,production environments. Collie is a good solution for you.
Collie support switching system setting to test different environment.

### Test Reports and Logs


### Auto Run Tests.



## The following Library and tool are required.
* Selenium
* PHP
* Apache


## Install GuideLine
mysql -h localhost -u xxx -p xxx

create database collie;

grant all on collie.* to 'collie'@'localhost' identified by 'collie';

git clone --recursive git@github.com:puritys/collie.git
<pre>
cd collie 
php   web/sql/create.php
</pre>

You will need to generate the controller list. Execute the following command to generate it.

<pre>gmake genList</pre>

To create a directory to save the test log.
<pre>
cd collie/web
mkdir runTmp
chmod -R 777 runTmp (or change the owner to be apache runner)
</pre>

#### 中文 ####

### Collie 是一個自動化測試工具，使用圖形化介面來管理、執行你的 functional test.






