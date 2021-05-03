<?php

class Products {
    public static $errors = array();
    
    public static function main() {
        include('productsArray.php');

        // self::checkQuery();

        $data = $products;
        $filteredDataCategory = array();
        $filteredDataLimit = array();
        $limit = self::getLimit();
        $category = self::getCategory();
       

        if (!$limit && !$category) {
            echo json_encode($data, JSON_UNESCAPED_UNICODE);
        }
        else if($limit && $category) {
            foreach ($data as $product) {
                if ($product['category'] == $category) {
                    array_push($filteredDataCategory, $product);
                }
            }

            if(count($filteredDataCategory) < $limit) {
                echo "There are only " . count($filteredDataCategory) . " items in that category.";
                die();
            }

            shuffle($filteredDataCategory);
            for ($i = 0; $i < $limit; $i++) {
                array_push($filteredDataLimit, $filteredDataCategory[$i]);
            }
            echo json_encode($filteredDataLimit, JSON_UNESCAPED_UNICODE);
        }
        else if (!$limit && $category) {
            foreach ($data as $product) {
                if ($product['category'] == $category) {
                    array_push($filteredDataCategory, $product);
                }
            }
            echo json_encode($filteredDataCategory, JSON_UNESCAPED_UNICODE);
        }
        else if ($limit && !$category) {
            shuffle($data);
            for($i = 0; $i < $limit; $i++) {
                array_push($filteredDataLimit, $data[$i]);
            }
            echo json_encode($filteredDataLimit, JSON_UNESCAPED_UNICODE);
        }

    }

 /*    public static function checkQuery() {

        if(count($_GET) == 2) {
            echo "Hallooo";
        }
        
        if (!array_key_exists('limit', $_GET) &&
            !array_key_exists('category', $_GET)) {
            echo "The key/keyes is not valid";
            die();
        }
        
    } */

    public static function getLimit() {
        $limit = isset($_GET['limit']) ? $_GET['limit'] : null;

        if($limit > 20) {
            self::$errors[] = "Can't show more than 20 products.";
            echo "Can't show more than 20 or less then 1 products.";
            die();
        }
        return $limit;
    }

    public static function getCategory() {
        $category = isset($_GET['category']) ? $_GET['category'] : null;

        if($category !== "men" &&
            $category !== "jewelery" &&
            $category !== "electronics" &&
            $category !== "women" &&
            $category !== null) 
            {
                self::$errors[] = 'The category does not exist';
                $category = null;
                echo 'The category does not exist.';
                die();
            }
        return $category;
    }

    // if (empty($_GET)) {
    // no data passed by get }
}

?>