<?php


namespace App\Controllers;

use App\Models\GeoZoneModel;

class LocationController
{
    private $zoneModel;

    public function __construct(GeoZoneModel $zoneModel)
    {
        $this->zoneModel = $zoneModel;
    }

    public function getZoneId($name) {
        $zoneData = $this->zoneModel->getZoneByName($name);
        if(empty($zoneData)){
            return $this->zoneModel->createGeoZone($name);
        }else{
            return $zoneData[0]['zone_id'];
        }
    }
}