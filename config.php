<?php

if (session_status() == PHP_SESSION_NONE) {
	session_set_cookie_params(46800);
	ini_set('session.gc_maxlifetime', 46800);
    session_start();
}

$domain = "good.city";
$auth_dn = "good";
$ldaprdn  = "$auth_dn\\DoorLock";     
$ldappass = "root";
$userDn = "DC=good,DC=city";  

$siteroot = 'https://mail.goodcity.com.ru:8094/missedlist/';
$siteroot2 = 'https://mail.goodcity.com.ru:8094/missedlist/test.php';
$loginroot = 'https://mail.goodcity.com.ru:8094/missedlist/login.php';
$cssroot = 'https://mail.goodcity.com.ru:8094/missedlist/css/bootstrap.css';

define("DBHOST", "127.0.0.1");
define("DBUSER", "oktell");
define("DBPASS", "root");
define("DBNAME", "oktell");
$db = @mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME) or die("Нет подключения к БД");
mysqli_set_charset($db, "utf8") or die("Не установлена кодировка соединения");
?>