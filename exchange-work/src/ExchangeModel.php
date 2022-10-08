<?php


class ExchangeModel
{
    private $pdo;
    private $products;

    public function __construct(PDO $pdo,array $products)
    {
        $this->pdo = $pdo;
        $this->products = $products;
    }

    private function getProductsAll()
    {
        $query = "SELECT c1id FROM oc_product";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    private function getCategoryById($c1id)
    {
        $selectArr = [
            'c1_id' => $c1id
        ];

        $query = "SELECT * FROM oc_category WHERE c1_id = :c1_id";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($selectArr);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function createProduct($product)
    {
        $sortOrder = 1;
        if($product['quantity'] < 1)
        {
            $sortOrder = 2;
        }
        //Добавляем данные в таблицу oc_products
        $createArr = [
            'product_id' => NULL,
            'model' => $product['name'],
            'sku' => $product['sku'],
            'upc' => '',
            'ean' => '',
            'jan' => '',
            'isbn' => '',
            'mpn' => '',
            'location' => '',
            'quantity' => $product['quantity'],
            'stock_status_id' => 7,
            'image' => $product['img'],
            'manufacturer_id' => 0,
            'shipping' => 1,
            'price' => $product['price'],
            'points' => 0,
            'tax_class_id' => 0,
            'date_available' => date("Y-m-d"),
            'weight' => 0,
            'weight_class_id' => 1,
            'length' => 0,
            'width' => 0,
            'height' => 0,
            'length_class_id' => 1,
            'subtract' => 1,
            'minimum' => 1,
            'sort_order' => $sortOrder,
            'status' => 1,
            'viewed' => 0,
            'c1id' => $product['c1Id'],
            'date_added' => date("Y-m-d"),
            'date_modified' => date("Y-m-d")
        ];
        $query = "INSERT INTO oc_product SET
                    product_id = :product_id,
                    model = :model,
                    sku = :sku,
                    upc = :upc,
                    ean = :ean,
                    jan = :jan,
                    isbn = :isbn,
                    mpn = :mpn,
                    location = :location,
                    quantity = :quantity,
                    stock_status_id = :stock_status_id,
                    image = :image,
                    manufacturer_id = :manufacturer_id,
                    shipping = :shipping,
                    price = :price,
                    points = :points,
                    tax_class_id = :tax_class_id,
                    date_available = :date_available,
                    weight = :weight,
                    weight_class_id = :weight_class_id,
                    length = :length,
                    width = :width,
                    height = :height,
                    length_class_id = :length_class_id,
                    subtract = :subtract,
                    minimum = :minimum,
                    sort_order = :sort_order,
                    status = :status,
                    viewed = :viewed,
                    c1id = :c1id,
                    date_added = :date_added,
                    date_modified = :date_modified";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($createArr);

        $product_id = $this->pdo->lastInsertId();
        $categoryData = $this->getCategoryById($product['categoryId']);

        $categoryId = $categoryData[0]['category_id'];


        //Добавляем товар в таблицу oc_product_description
        $createArr = [
            'product_id' => $product_id,
            'language_id' => 1,
            'name' => $product['name'],
            'description' => $product['description'],
            'tag' => '',
            'meta_title' => $product['name'],
            'meta_h1' => $product['name'],
            'meta_description' => $product['description'],
            'meta_keyword' => '',
        ];
        $query = "INSERT INTO oc_product_description SET
                            product_id = :product_id,
                            language_id = :language_id,
                            name = :name,
                            description = :description,
                            tag = :tag,
                            meta_title = :meta_title,
                            meta_h1 = :meta_h1,
                            meta_description = :meta_description,
                            meta_keyword = :meta_keyword";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($createArr);

        //Добавляем товар в таблицу oc_product_to_category
        $createArr = [
            'product_id' => $product_id,
            'category_id' => $categoryId,
            'main_category' => 1
        ];

        $query = "INSERT INTO oc_product_to_category SET
                            product_id = :product_id,
                            category_id = :category_id,
                            main_category = :main_category";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($createArr);

        //Добавляем товар в таблицу oc_product_to_layout
        $createArr = [
            'product_id' => $product_id,
            'store_id' => 0,
            'layout_id' => 0
        ];
        $query = "INSERT INTO oc_product_to_layout SET
                    product_id = :product_id,
                    store_id = :store_id,
                    layout_id = :layout_id";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($createArr);

        //Добавляем товар в таблицу oc_product_to_store
        $createArr = [
            'product_id' => $product_id,
            'store_id' => 0
        ];
        $query = "INSERT INTO oc_product_to_store SET product_id = :product_id, store_id = :store_id";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($createArr);

        //Добавляем чпу для товара
        $createArr = [
            'url_alias_id' => NULL,
            'query' => 'product_id='.$product_id,
            'keyword' => $product['alias']
        ];
        $query = "INSERT INTO oc_url_alias SET
                    url_alias_id = :url_alias_id,
                    query = :query,
                    keyword = :keyword";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($createArr);
    }

    private function updateProduct($product)
    {
        //Обновляем данные в таблице oc_product
        $createArr = [
            'quantity' => $product['quantity'],
            'c1id' => $product['c1Id'],
        ];
        $query = "UPDATE oc_product SET quantity = :quantity WHERE c1id = :c1id";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($createArr);

        $createArr = [
            'price' => $product['price'],
            'c1id' => $product['c1Id'],
        ];
        $query = "UPDATE oc_product SET price = :price WHERE c1id = :c1id";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($createArr);

        $sortOrder = 1;
        if($product['quantity'] < 1)
        {
            $sortOrder = 2;
        }
        $createArr = [
            'sort_order' => $sortOrder,
            'c1id' => $product['c1Id'],
        ];
        $query = "UPDATE oc_product SET sort_order = :sort_order WHERE c1id = :c1id";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($createArr);

        $createArr = [
            'date_modified' => date("Y-m-d"),
            'c1id' => $product['c1Id'],
        ];
        $query = "UPDATE oc_product SET date_modified = :date_modified WHERE c1id = :c1id";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($createArr);

        $createArr = [
            'image' => $product['img'],
            'c1id' => $product['c1Id'],
        ];
        $query = "UPDATE oc_product SET image = :image WHERE c1id = :c1id";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($createArr);

        $productData = $this->getProductById($product['c1Id']);
        $productId = $productData[0]['product_id'];

        //Обновляем данные в таблице oc_product_description
        $createArr = [
            'description' => $product['description'],
            'product_id' => $productId,
        ];
        $query = "UPDATE oc_product_description SET description = :description WHERE product_id = :product_id";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($createArr);

        $createArr = [
            'meta_description' => $product['description'],
            'product_id' => $productId,
        ];
        $query = "UPDATE oc_product_description SET meta_description = :meta_description WHERE product_id = :product_id";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($createArr);
    }

    private function getProductById($c1id)
    {
        $selectArr = [
            'c1id' => $c1id
        ];

        $query = "SELECT * FROM oc_product WHERE c1id = :c1id";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($selectArr);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function productProcessing(){
        $productsInBase = $this->getProductsAll();
        $products = $this->products;

        $productCreated = 0;
        $productUpdated = 0;

        foreach ($products as $product)
        {
            print_r($product);
            if(in_array($product['c1Id'],$productsInBase))
            {
                $this->updateProduct($product);
                $productUpdated++;
            }
            else
            {
                $this->createProduct($product);
                $productCreated++;
            }
        }

        return "Обработка завершена, создано $productCreated, обновлено $productUpdated";
    }
}