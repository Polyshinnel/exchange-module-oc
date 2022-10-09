<?php

use App\Controllers\LocationController;
use App\Controllers\OrderController;
use App\Models\GeoZoneModel;
use App\Models\OrderModel;
use App\Models\ProductModel;

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
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$data = file_get_contents('php://input');
$data = json_decode($data, true);


//$orderData = [
//    'name' => 'Андрей',
//    'lastName' => 'Нестеров',
//    'email' => 'polyshinnel@gmail.com',
//    'phone' => '+79030264456',
//    'address' => 'улица Жукова д.47',
//    'city' => 'Калуга',
//    'postcode' => '248018',
//    'payment_method' => 'Перевод',
//    'shipping_method' => 'До точки СДЭК',
//    'shipping_method_price' => '300',
//    'district' => 'Калужская область',
//    'products' => [
//        [
//            'product_id' => 28,
//            'quantity' => 5
//        ],
//        [
//            'product_id' => 29,
//            'quantity' => 5
//        ]
//    ]
//];

//$geoZoneModel = new GeoZoneModel($pdo);
//$geoZoneController = new LocationController($geoZoneModel);
//$orderModel = new OrderModel($pdo);
//$productModel = new ProductModel($pdo);
//$orderController = new OrderController($orderData,$orderModel,$productModel,$geoZoneController);
//$orderController->createOrder();



