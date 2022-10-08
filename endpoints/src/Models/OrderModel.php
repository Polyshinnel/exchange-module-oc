<?php

namespace App\Models;

use PDO;

class OrderModel
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function createOrderTable($orderData,$browserData,$storeName,$postCode,$district,$paymentZoneId,$ipUser) {
        $insertParams = [
            'order_id' => NULL,
            'invoice_no' => 0,
            'invoice_prefix' => 'INV-2013-00',
            'store_id' => 0,
            'store_name' => $storeName,
            'store_url' => 'https://supply.profservice-kaluga.ru/',
            'customer_id' => 0,
            'customer_group_id' => 1,
            'firstname' => $orderData['name'],
            'lastname' => $orderData['lastName'],
            'email' => $orderData['email'],
            'telephone' => $orderData['phone'],
            'fax' => '',
            'custom_field' => '[]',
            'payment_firstname' => $orderData['name'],
            'payment_lastname' => $orderData['lastName'],
            'payment_company' => '',
            'payment_address_1' => $orderData['address'],
            'payment_address_2' => '',
            'payment_city' => $orderData['city'],
            'payment_postcode' => $postCode,
            'payment_country' => 'Российская Федерация',
            'payment_country_id' => 176,
            'payment_zone' => $district,
            'payment_zone_id' => $paymentZoneId,
            'payment_address_format' => '',
            'payment_custom_field' => '[]',
            'payment_method' => $orderData['payment_method'],
            'payment_code' => 'cod',
            'shipping_firstname' => $orderData['name'],
            'shipping_lastname' => $orderData['lastName'],
            'shipping_company' => '',
            'shipping_address_1' => $orderData['address'],
            'shipping_address_2' => '',
            'shipping_city' => $orderData['city'],
            'shipping_postcode' => $postCode,
            'shipping_country' => 'Российская Федерация',
            'shipping_country_id' => 176,
            'shipping_zone' => $district,
            'shipping_zone_id' => $paymentZoneId,
            'shipping_address_format' => '',
            'shipping_custom_field' => '[]',
            'shipping_method' => $orderData['shipping_method'],
            'shipping_code' => 'free.free',
            'comment' => '',
            'total' => $orderData['total'],
            'order_status_id' => 1,
            'affiliate_id' => 0,
            'commission' => 0,
            'marketing_id' => 0,
            'tracking' => '',
            'language_id' => 1,
            'currency_id' => 1,
            'currency_code' => 'RUB',
            'currency_value' => 1,
            'ip' => $ipUser,
            'forwarded_ip' => '',
            'user_agent' => $browserData,
            'accept_language' => 'ru-RU,ru;q=0.9,en-US;q=0.8,en;q=0.7',
            'date_added' => date("Y-m-d H:i:s"),
            'date_modified' => date("Y-m-d H:i:s")
        ];


    }

    public function createOrderHistory($orderId,$dateAdded) {
        $insertParams = [
            'order_history_id' => NULL,
            'order_id' => $orderId,
            'order_status_id' => 1,
            'notify' => 0,
            'comment' => '',
            'date_added' => $dateAdded
        ];
    }

    public function createOrderProduct($product,$orderId) {
        $insertParams = [
            'order_product_id' => NULL,
            'order_id' => $orderId,
            'product_id' => $product['id'],
            'name' => $product['name'],
            'model' => $product['model'],
            'quantity' => $product['quantity'],
            'price' => $product['price'],
            'total' => $product['total'],
            'tax' => 0,
            'reward' => 0
        ];
    }

    public function createOrderTotal($orderId,$orderData) {
        $insertArr = [
            [
                'order_total_id' => NULL,
                'order_id' => $orderId,
                'code' => 'sub_total',
                'title' => 'Предварительная стоимость',
                'value' => $orderData['total'],
                'sort_order' => 1
            ],
            [
                'order_total_id' => NULL,
                'order_id' => $orderId,
                'code' => 'shipping',
                'title' => $orderData['shipping_method'],
                'value' => $orderData['shipping_method_price'],
                'sort_order' => 3
            ],
            [
                'order_total_id' => NULL,
                'order_id' => $orderId,
                'code' => 'tax',
                'title' => 'НДС (20%)',
                'value' => 0,
                'sort_order' => 5
            ],
            [
                'order_total_id' => NULL,
                'order_id' => $orderId,
                'code' => 'total',
                'title' => 'Итого',
                'value' => $orderData['total'],
                'sort_order' => 9
            ],
        ];
    }
}

