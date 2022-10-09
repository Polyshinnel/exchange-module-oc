<?php


namespace App\Controllers;


use App\Models\OrderModel;
use App\Models\ProductModel;

class OrderController
{
    private $orderData;
    private $orderModel;
    private $productModel;
    private $geoZoneController;

    public function __construct(array $orderData,OrderModel $orderModel,ProductModel $productModel,LocationController $geoZoneController)
    {
        $this->orderData = $orderData;
        $this->orderModel = $orderModel;
        $this->productModel = $productModel;
        $this->geoZoneController = $geoZoneController;
    }

    public function createOrder() {
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        $orderData = $this->orderData;

        $userIp = $_SERVER['REMOTE_ADDR'];
        $dateCreate = date("Y-m-d H:i:s");
        $products = $this->getProducts();
        $orderData['total_products'] = $this->getProductsTotals($products);

        $orderData['total_all'] = $this->getAllTotals($orderData);
        $orderData['district_id'] = $this->geoZoneController->getZoneId($orderData['district']);


        $orderId = $this->orderModel->createOrderTable($orderData,$userAgent,'Профсервис',$userIp,$dateCreate);
        echo "<pre>";
        print_r($orderId);

        $this->orderModel->createOrderHistory($orderId,$dateCreate);

        foreach ($products as $product) {
            $this->orderModel->createOrderProduct($product,$orderId);
        }
        $this->orderModel->createOrderTotal($orderId,$orderData);
    }

    public function getProducts() {
        $orderData = $this->orderData;
        $productsRaw = $orderData['products'];
        $products = [];
        foreach ($productsRaw as $item) {
            $productId = $item['product_id'];
            $productInfo = $this->productModel->getProductById($productId);
            $productTotal = $item['quantity'] * $productInfo['price'];
            $products[] = [
                'product_id' => $item['product_id'],
                'name' => $productInfo['model'],
                'model' => $productInfo['model'],
                'quantity' => $item['quantity'],
                'price' => $productInfo['price'],
                'total' => $productTotal
            ];
        }

        return $products;
    }

    public function getProductsTotals($products) {
        $total = 0;
        foreach ($products as $product) {
            $subTotal = $product['price'] * $product['quantity'];
            $total += $subTotal;
        }

        return $total;
    }

    public function getAllTotals($orderData) {
       return $orderData['shipping_method_price'] + $orderData['total_products'];
    }
}