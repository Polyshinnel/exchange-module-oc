<?php

use App\Controllers\GetGeoController;

include __DIR__.'/../../config.php';
include __DIR__.'/./vendor/autoload.php';


$geoClass = new GetGeoController();

$typeRequest = $_POST['type'];
//''
if($typeRequest == 'getCity'){
    $search = $_POST['search'];
    $getCity = $geoClass->getCity($search);
    $response = '';
    foreach ($getCity as $item){
        $response.= '<div class="checkout-contacts__line-block-result-line checkout-contacts__line-block-result-line_city"><p class="result-contact" data-id="'.$item['cityId'].'">'.$item['cityName'].'</p><p class="area-list">'.$item['regionName'].'</p></div>';
    }
    echo $response;
}

if($typeRequest == 'getStreet'){
    $search = $_POST['search'];
    $cityId = $_POST['cityId'];
    $getStreet = $geoClass->getStreet($search,$cityId);
    $response = '';
    foreach ($getStreet as $item){
        $response.= '<div class="checkout-contacts__line-block-result-line checkout-contacts__line-block-result-line_street"><p class="result-contact" data-id="'.$item['streetId'].'">'.$item['streetName'].'</p><p class="area-list">'.$item['streetLocale'].'</p></div>';
    }
    echo $response;
}

if($typeRequest == 'getPostCode'){
    $houseNum = $_POST['houseNum'];
    $cityId = $_POST['cityId'];
    $streetId = $_POST['streetId'];
    $getPostCode = $geoClass->getPostCode($cityId,$streetId,$houseNum);
    echo $getPostCode;
}

$getCity = $geoClass->getCity('Москва');
$getRegion = $geoClass->getRegion('4995');
$getRegionByName = $geoClass->getRegionByName('Москов');
$getStreet = $geoClass->getStreet('Кир','2818');
$getPostCode = $geoClass->getPostCode('2818','1347746','47');



//Поиск городов
//http://geohelper.info/api/v1/cities?apiKey=UrRDM9bPdRmwbQR4Bhyox4ImVxNFBd2h&locale[lang]=ru&filter[name]=калуга

//Поиск областей
//http://geohelper.info/api/v1/regions?apiKey=UrRDM9bPdRmwbQR4Bhyox4ImVxNFBd2h&locale[lang]=ru&filter[cityId]=2818&filter[name]=Калужская

//Поиск улиц
//http://geohelper.info/api/v1/streets?apiKey=UrRDM9bPdRmwbQR4Bhyox4ImVxNFBd2h&locale[lang]=ru&filter[cityId]=2818&filter[name]=Жукова

//Поиск почтового индекса
//http://geohelper.info/api/v1/post-code?apiKey=UrRDM9bPdRmwbQR4Bhyox4ImVxNFBd2h&locale[lang]=ru&filter[cityId]=2818&filter[streetId]=1347746&filter[houseNumber]=47
