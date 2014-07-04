<?php
require dirname(__FILE__) . "/../config.php";

//grant all on collie.* to 'collie'@'localhost' identified by 'collie';
//drop user root@'localhost'
//create database collie;
$db = new PDO('mysql:host=' . MYSQL_HOST .';dbname=' . MYSQL_DB, MYSQL_USER, MYSQL_PSWD);
$lang = "UTF8";

$case = "
    create table testCase (
        case_id int NOT NULL AUTO_INCREMENT,
        title varchar(255) not null,
        content varchar(255) not null,
        descriptor blob not null,
        create_time datetime,
        Index(create_time),
        PRIMARY KEY (case_id)
    )ENGINE=InnoDB DEFAULT CHARSET=$lang;
";
try {
    $result = $db->query($case);
    print_r($result);
} catch (Exception $e) {
    print_r($e);
}

$category = "
    create table category (
        category_id int NOT NULL AUTO_INCREMENT,
        parent_id int null,
        name varchar(255) not null,
        create_time datetime,
        PRIMARY KEY (category_id)
    )ENGINE=InnoDB DEFAULT CHARSET=$lang;
";
try {
    $result = $db->query($category);
    print_r($result);
} catch (Exception $e) {
    print_r($e);
}

$config = "
    create table config (
        config_id int NOT NULL AUTO_INCREMENT,
        name varchar(255) not null,
        config blob,
        create_time datetime,
        PRIMARY KEY (config_id)
    )ENGINE=InnoDB DEFAULT CHARSET=$lang;
";
try {
    $result = $db->query($config);
    print_r($result);
} catch (Exception $e) {
    print_r($e);
}

/******insert test data**********/
/*$des = file_get_contents('criptor.json');
$sql = "insert into testCase(`title`, `content`,`descriptor`, `create_time`) values(
        'Test Case',
        'content ',
        '" . $des . "', 
        '". date("Y/m/d H:i:s") .
        "')";
*/
/*$result = $db->query($sql);
$result = $db->query($sql);
$result = $db->query($sql);
$result = $db->query($sql);
$result = $db->query($sql);
$result = $db->query($sql);
$result = $db->query($sql);
*/


//case_id or category_id
$config = "
    create table execute (
        execute_id int NOT NULL AUTO_INCREMENT,
        dirname varchar(20) not null,
        job_type enum('case', 'category'),
        job_id int not null, 
        name varchar(255) not null,
        create_time datetime,
        run_time int not null,
        passed_case_num int,
        failed_case_num int,
        PRIMARY KEY (execute_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=$lang;
";
try {
    $result = $db->query($config);
    print_r($result);
} catch (Exception $e) {
    print_r($e);
}

$config = "
    create table report (
        report_id int NOT NULL AUTO_INCREMENT,
        execute_id int not null,
        case_id int not null,
        dirname varchar(55) not null,
        name varchar(255) not null,
        create_time datetime,
        status enum('passed', 'failed', 'some_failed', 'none', 'running', 'standby'),
        passed_case_num int,
        failed_case_num int,
        config_id int,
        PRIMARY KEY (report_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=$lang;
";
try {
    $result = $db->query($config);
    print_r($result);
} catch (Exception $e) {
    print_r($e);
}


$config = "
    create table caseCategory (
        id int NOT NULL AUTO_INCREMENT,
        case_id int not null,
        category_id int not null,
        create_time datetime,
        PRIMARY KEY (id),
        UNIQUE(case_id, category_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=$lang;
";
try {
    $result = $db->query($config);
    print_r($result);
} catch (Exception $e) {
    print_r($e);
}


$config = "
    create table dataValue (
        key_name varchar(60) not null,
        value varchar(255) not null,
        create_time datetime,
        UNIQUE(key_name)
    ) ENGINE=InnoDB DEFAULT CHARSET=$lang;
";
try {
    $result = $db->query($config);
    print_r($result);
} catch (Exception $e) {
    print_r($e);
}
