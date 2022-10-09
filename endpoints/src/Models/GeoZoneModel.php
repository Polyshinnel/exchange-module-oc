<?php


namespace App\Models;


use PDO;

class GeoZoneModel
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getZoneByName($zoneName) {
        $selectArr = [
            'name' => $zoneName
        ];

        $query = "SELECT * FROM oc_zone WHERE name = :name";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($selectArr);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createGeoZone($name) {
        $insertParams = [
            'zone_id' => NULL,
            'country_id' => 176,
            'name' => $name,
            'code' => 'ADD',
            'status' => 1
        ];

        $query = "INSERT INTO oc_zone SET 
            zone_id = :zone_id, 
            country_id = :country_id,
            name = :name,
            code = :code,
            status = :status";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($insertParams);

        return $zoneId = $this->pdo->lastInsertId();
    }
}