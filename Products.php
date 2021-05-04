<?php

class Products {
    public static $limit = null;
    public static $category = null;
    
    public static function main() {
        include('productsArray.php');

        self::checkQuery();

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
                $error = "There are only " . count($filteredDataCategory) . " items in that category.";
                echo json_encode($error, JSON_UNESCAPED_UNICODE);
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

    public static function checkQuery() {

        // Skicka error om fler än 2 queries
        if(count($_GET) > 2) {
            $error = "Only 'show' and 'category' filters are allowed.";
            echo json_encode($error, JSON_UNESCAPED_UNICODE);
            die();
        }
        // Skicka error om query key är ogiltigt
        if (count($_GET) == 2 &&
            !array_key_exists('limit', $_GET) &&
            !array_key_exists('category', $_GET)) {
            $error = "The key/keyes is not valid.";
            echo json_encode($error, JSON_UNESCAPED_UNICODE);
            die();
        }

        
        
    }

    public static function getLimit() {
        $limit = isset($_GET['show']) ? $_GET['show'] : null;

        // Skicka error om value är större än 20
        if($limit > 20) {
            $error = "Can't show more than 20 products.";
            echo json_encode($error, JSON_UNESCAPED_UNICODE);
            die();
        }
        // Skicka error om show value inte är en siffra
        if(!is_numeric($limit)) {
            $error = "Value must be a number.";
            echo json_encode($error, JSON_UNESCAPED_UNICODE);
            die();
        }
        return $limit;
    }

    public static function getCategory() {
        $category = isset($_GET['category']) ? $_GET['category'] : null;

        // Skicka error om category är ogiltig
        if($category !== "men" &&
            $category !== "jewelery" &&
            $category !== "electronics" &&
            $category !== "women" &&
            $category !== null) 
            {
                $error = 'The category does not exist.';
                $category = null;
                echo json_encode($error, JSON_UNESCAPED_UNICODE);
                die();
            }
        return $category;
    }

    // if (empty($_GET)) {
    // no data passed by get }
}

?>