<?php


namespace App\Models;


use PDO;

class ProductModel
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getProductById($productId)
    {
        $selectArr = [
            'product_id' => $productId
        ];

        $query = "SELECT * FROM oc_product WHERE product_id = :product_id";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($selectArr);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result[0];
    }
}