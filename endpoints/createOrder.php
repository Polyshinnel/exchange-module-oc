<?php 

include __DIR__.'/../../config.php';
include __DIR__.'/./vendor/autoload.php';

//Создаем экземляр подключения к БД
$host= DB_HOSTNAME;
$db= DB_DATABASE;
$user= DB_USERNAME;
$password= DB_PASSWORD;
$charset='utf8';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$pdo = new PDO($dsn, $user, $password);

