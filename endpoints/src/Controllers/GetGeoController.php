<?php

namespace App\Controllers;

class GetGeoController
{
    public function getRegion(String $cityId) : array {
        $urlQuery = [
            'apiKey' => GEO_API_KEY,
            'locale[lang]' => 'ru',
            'filter[cityId]' => $cityId,
        ];
        $urlParams = http_build_query($urlQuery);
        $baseUrl = 'http://geohelper.info/api/v1/regions?';
        $url = $baseUrl.$urlParams;
        $response = file_get_contents($url);
        return json_decode($response,true);
    }

    public function getRegionByName(String $region) : array {
        $urlQuery = [
            'apiKey' => GEO_API_KEY,
            'locale[lang]' => 'ru',
            'filter[name]' => $region,
        ];
        $urlParams = http_build_query($urlQuery);
        $baseUrl = 'http://geohelper.info/api/v1/regions?';
        $url = $baseUrl.$urlParams;
        $response = file_get_contents($url);
        return json_decode($response,true);
    }

    private function getRegionById(String $regionId){
        $urlQuery = [
            'apiKey' => GEO_API_KEY,
            'locale[lang]' => 'ru',
            'filter[id]' => $regionId,
            'filter[countryIso]' => 'ru'
        ];
        $urlParams = http_build_query($urlQuery);
        $baseUrl = 'http://geohelper.info/api/v1/regions?';
        $url = $baseUrl.$urlParams;
        $response = file_get_contents($url);
        return json_decode($response,true);
    }

    public function getCity(String $cityName) {
        $urlQuery = [
            'apiKey' => GEO_API_KEY,
            'locale[lang]' => 'ru',
            'filter[name]' => $cityName,
            'filter[countryIso]' => 'ru'
        ];
        $urlParams = http_build_query($urlQuery);
        $baseUrl = 'http://geohelper.info/api/v1/cities?';
        $url = $baseUrl.$urlParams;
        $json = file_get_contents($url);
        $jsonArr = json_decode($json,true);

        $cityArr = [];
        if(!empty($jsonArr['result'])){
            $results = $jsonArr['result'];
            foreach ($results as $result) {
                $cityId = $result['id'];
                $cityName = $result['name'];
                $regionId = $result['regionId'];

                $regionData = $this->getRegionById($regionId);
                $regionName = $regionData['result'][0]['name'];

                $cityArr[] = [
                    'cityId' => $cityId,
                    'cityName' => $cityName,
                    'regionName' => $regionName
                ];
            }
        }

        return $cityArr;
    }

    public function getStreet(String $street,String $cityId) {
        $urlQuery = [
            'apiKey' => GEO_API_KEY,
            'locale[lang]' => 'ru',
            'filter[cityId]' => $cityId,
            'filter[name]' => $street
        ];
        $urlParams = http_build_query($urlQuery);
        $baseUrl = 'http://geohelper.info/api/v1/streets?';
        $url = $baseUrl.$urlParams;
        $json = file_get_contents($url);
        $jsonArr = json_decode($json,true);
        $streetArr = [];
        if(!empty($jsonArr['result'])){
            $results = $jsonArr['result'];
            foreach ($results as $result){
                $streetId = $result['id'];
                $streetLastName = $result['name'];
                $locationType = $result['localityType']['name'];
                $streetName = $locationType.' '.$streetLastName;
                $streetArr[] = [
                    'streetId' => $streetId,
                    'streetName' => $streetLastName,
                    'streetLocale' => $locationType
                ];
            }
        }

        return $streetArr;
    }

    public function getPostCode(String $cityId,String $streetId,String $houseNumber) {
        $urlQuery = [
            'apiKey' => GEO_API_KEY,
            'locale[lang]' => 'ru',
            'filter[cityId]' => $cityId,
            'filter[streetId]' => $streetId,
            'filter[houseNumber]' => $houseNumber
        ];
        $urlParams = http_build_query($urlQuery);
        $baseUrl = 'http://geohelper.info/api/v1/post-code?';
        $url = $baseUrl.$urlParams;
        $json = file_get_contents($url);
        $jsonArr = json_decode($json,true);
        return $jsonArr['result'];
    }
}