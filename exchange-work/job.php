<?php

include __DIR__.'/../../config.php';
include __DIR__.'/src/ExchangeModel.php';
include __DIR__.'/src/ExchangeParser.php';

//Создаем SimpleXMLObjects
$xmlCategories = simplexml_load_file(__DIR__ . '/../categories.xml');
$xmlPictures = simplexml_load_file(__DIR__ . '/../pictures.xml');
$xmlProducts = simplexml_load_file(__DIR__ . '/../products.xml');

//Создаем экземляр подключения к БД
$host= DB_HOSTNAME;
$db= DB_DATABASE;
$user= DB_USERNAME;
$password= DB_PASSWORD;
$charset='utf8';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$pdo = new PDO($dsn, $user, $password);

$rootPath = __DIR__.'/../pictures';
$destinationPath = __DIR__.'/../../image/exchange-img';

$fileList = scandir($rootPath);

unset($fileList[0]);
unset($fileList[1]);

foreach ($fileList as $item)
{
    $filePathFrom = $rootPath.'/'.$item;
    $filePathTo = $destinationPath.'/'.$item;
    copy($filePathFrom,$filePathTo);
}


$parser = new ExchangeParser($xmlCategories,$xmlPictures,$xmlProducts);

$products = $parser->parseProducts();

$dbModel = new ExchangeModel($pdo,$products);

$resultProductProcessing = $dbModel->productProcessing();

echo "<pre>";
print_r($resultProductProcessing);


