<?php

class ExchangeParser
{
    private $xmlCategories;
    private $xmlPictures;
    private $xmlProducts;

    public function __construct(SimpleXMLElement $xmlCategories,SimpleXMLElement $xmlPictures,SimpleXMLElement $xmlProducts)
    {
        $this->xmlCategories = $xmlCategories;
        $this->xmlPictures = $xmlPictures;
        $this->xmlProducts = $xmlProducts;
    }

    public function parseCategory() : array
    {
        $categoriesData = $this->xmlCategories->category;

        $categories = [];

        $categoriesAllowList = [
            'Вакуумное оборудование',
            'Весовое',
            'Газовое оборудование',
            'Кофейное оборудование',
            'Механическое оборудование',
            'Посудомоечное оборудование',
            'Прачечное оборудование',
            'Тепловое оборудование',
            'Электрика',
            'Прочее',
            'Холодильное оборудование'
        ];

        foreach($categoriesData as $categoriesItem)
        {
            $attributes = $categoriesItem->attributes();
            $link = (string)$attributes->Ссылка;
            $name = (string)$attributes->Наименование;

            if(in_array($name,$categoriesAllowList))
            {
                $categories[$link] = [
                    'name' => $name,
                ];
            }
        }

        return $categories;
    }

    public function parseImg() : array
    {
        $pictures = [];
        $pictureData = $this->xmlPictures->picture;

        foreach ($pictureData as $pictureItem)
        {
            $attributes = $pictureItem->attributes();
            $owner = (string)$attributes->Владелец;
            $filename = (string)$attributes->ИмяФайла;

            $pictures[$owner] = [$filename];
        }

        return $pictures;
    }

    public function parseProducts() : array
    {
        $productsData = $this->xmlProducts->product;

        $products = [];

        $categories = $this->parseCategory();

        $pictures = $this->parseImg();

        foreach($productsData as $productItem) {
            $attributes = $productItem->attributes();

            $c1Id = (string)$attributes->Ссылка;
            $name = (string)$attributes->Наименование;
            $categoryId = (string)$attributes->Родитель;
            if(!empty($categories[$categoryId]['name']))
            {
                $description = (string)$attributes->Описание;
                $sku = (string)$attributes->Артикул;
                $price = (string)$attributes->Цена;
                $priceArr = explode(' ', $price);
                $priceNew = '';
                foreach ($priceArr as $item)
                {
                    $priceNew .= $item;
                }
                $quantity = (string)$attributes->Остаток;

                $categoryName = $categories[$categoryId]['name'];

                if($priceNew == '')
                {
                    $priceNew = 0;
                    $quantity = 0;
                }

                if($quantity == '')
                {
                    $quantity = 0;
                }

                $img = 'exchange-img/no-image.png';

                if(!empty($pictures[$c1Id]))
                {
                    $img = 'exchange-img/'.$pictures[$c1Id][0];
                }

                if(!empty($categoryName))
                {
                    $products[$c1Id] = [
                        'name' => $name,
                        'c1Id' => $c1Id,
                        'categoryId' => $categoryId,
                        'categoryName' => $categoryName,
                        'description' => $description,
                        'sku' => $sku,
                        'price' => $priceNew,
                        'quantity' => $quantity,
                        'img' => $img,
                        'alias' => $this->translit($name)
                    ];
                }
            }
        }

        return $products;
    }

    private function translit($value)
    {
        $converter = array(
            'а' => 'a',    'б' => 'b',    'в' => 'v',    'г' => 'g',    'д' => 'd',
            'е' => 'e',    'ё' => 'e',    'ж' => 'zh',   'з' => 'z',    'и' => 'i',
            'й' => 'y',    'к' => 'k',    'л' => 'l',    'м' => 'm',    'н' => 'n',
            'о' => 'o',    'п' => 'p',    'р' => 'r',    'с' => 's',    'т' => 't',
            'у' => 'u',    'ф' => 'f',    'х' => 'h',    'ц' => 'c',    'ч' => 'ch',
            'ш' => 'sh',   'щ' => 'sch',  'ь' => '',     'ы' => 'y',    'ъ' => '',
            'э' => 'e',    'ю' => 'yu',   'я' => 'ya',
        );

        $value = mb_strtolower($value);
        $value = strtr($value, $converter);
        $value = mb_ereg_replace('[^-0-9a-z]', '-', $value);
        $value = mb_ereg_replace('[-]+', '-', $value);
        $value = trim($value, '-');

        return $value;
    }
}









