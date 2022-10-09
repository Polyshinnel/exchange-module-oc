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

    public function createOrderTable($orderData,$browserData,$storeName,$ipUser,$dateCreate) {
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
            'payment_postcode' => $orderData['postcode'],
            'payment_country' => 'Российская Федерация',
            'payment_country_id' => 176,
            'payment_zone' => $orderData['district'],
            'payment_zone_id' => $orderData['district_id'],
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
            'shipping_postcode' => $orderData['postcode'],
            'shipping_country' => 'Российская Федерация',
            'shipping_country_id' => 176,
            'shipping_zone' => $orderData['district'],
            'shipping_zone_id' => $orderData['district_id'],
            'shipping_address_format' => '',
            'shipping_custom_field' => '[]',
            'shipping_method' => $orderData['shipping_method'],
            'shipping_code' => 'free.free',
            'comment' => '',
            'total' => $orderData['total_all'],
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
            'date_added' => $dateCreate,
            'date_modified' => $dateCreate
        ];

        $query = "INSERT INTO oc_order SET
                            order_id = :order_id,
                            invoice_no = :invoice_no,
                            invoice_prefix = :invoice_prefix,
                            store_id = :store_id,
                            store_name = :store_name,
                            store_url = :store_url,
                            customer_id = :customer_id,
                            customer_group_id = :customer_group_id,
                            firstname = :firstname,
                            lastname = :lastname,
                            email = :email,
                            telephone = :telephone,
                            fax = :fax,
                            custom_field = :custom_field,
                            payment_firstname = :payment_firstname,
                            payment_lastname = :payment_lastname,
                            payment_company = :payment_company,
                            payment_address_1 = :payment_address_1,
                            payment_address_2 = :payment_address_2,
                            payment_city = :payment_city,
                            payment_postcode = :payment_postcode,
                            payment_country = :payment_country,
                            payment_country_id = :payment_country_id,
                            payment_zone = :payment_zone,
                            payment_zone_id = :payment_zone_id,
                            payment_address_format = :payment_address_format,
                            payment_custom_field = :payment_custom_field,
                            payment_method = :payment_method,
                            payment_code = :payment_code,
                            shipping_firstname = :shipping_firstname,
                            shipping_lastname = :shipping_lastname,
                            shipping_company = :shipping_company,
                            shipping_address_1 = :shipping_address_1,
                            shipping_address_2 = :shipping_address_2,
                            shipping_city = :shipping_city,
                            shipping_postcode = :shipping_postcode,
                            shipping_country = :shipping_country,
                            shipping_country_id = :shipping_country_id,
                            shipping_zone = :shipping_zone,
                            shipping_zone_id = :shipping_zone_id,
                            shipping_address_format = :shipping_address_format,
                            shipping_custom_field = :shipping_custom_field,
                            shipping_method = :shipping_method,
                            shipping_code = :shipping_code,
                            comment = :comment,
                            total = :total, 
                            order_status_id = :order_status_id,
                            affiliate_id = :affiliate_id,
                            commission = :commission,
                            marketing_id = :marketing_id,
                            tracking = :tracking,
                            language_id = :language_id,
                            currency_id = :currency_id,
                            currency_code = :currency_code,
                            currency_value = :currency_value,
                            ip = :ip,
                            forwarded_ip = :forwarded_ip,
                            user_agent = :user_agent,
                            accept_language = :accept_language,
                            date_added = :date_added,
                            date_modified = :date_modified";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($insertParams);
        return $this->pdo->lastInsertId();

    }

    public function createOrderHistory($orderId,$dateCreate) {
        $insertParams = [
            'order_history_id' => NULL,
            'order_id' => $orderId,
            'order_status_id' => 1,
            'notify' => 0,
            'comment' => '',
            'date_added' => $dateCreate
        ];

        $query = "INSERT INTO oc_order_history SET
                            order_history_id = :order_history_id,
                            order_id = :order_id,
                            order_status_id = :order_status_id,
                            notify = :notify,
                            comment = :comment,
                            date_added = :date_added";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($insertParams);
    }

    public function createOrderProduct($product,$orderId) {
        $insertParams = [
            'order_product_id' => NULL,
            'order_id' => $orderId,
            'product_id' => $product['product_id'],
            'name' => $product['name'],
            'model' => $product['model'],
            'quantity' => $product['quantity'],
            'price' => $product['price'],
            'total' => $product['total'],
            'tax' => 0,
            'reward' => 0
        ];

        $query = "INSERT INTO oc_order_product SET
                            order_product_id = :order_product_id,
                            order_id = :order_id,
                            product_id = :product_id,
                            name = :name,
                            model = :model,
                            quantity = :quantity,
                            price = :price,
                            total = :total,
                            tax = :tax,
                            reward = :reward";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($insertParams);
    }

    public function createOrderTotal($orderId,$orderData) {
        $insertArr = [
            [
                'order_total_id' => NULL,
                'order_id' => $orderId,
                'code' => 'sub_total',
                'title' => 'Предварительная стоимость',
                'value' => $orderData['total_products'],
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
                'value' => $orderData['total_all'],
                'sort_order' => 9
            ],
        ];

        foreach ($insertArr as $insertItem) {
            $query = "INSERT INTO oc_order_total SET
                            order_total_id = :order_total_id,
                            order_id = :order_id,
                            code = :code,
                            title = :title,
                            value = :value,
                            sort_order = :sort_order";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute($insertItem);
        }

    }
}

