<?php

class Products {
    public static $limit = null;
    public static $category = null;
    public static $data = null;

    public static function main() {
        // Hämtar data och sparar i $data variabeln
        self::setData();
                
        // Kolla att query string är valid
        self::checkQuery();

    }

    public static function setData() {
        include('productsArray.php');
        self::$data = $products;
    }

    public static function checkQuery() {

        // Kolla om det finns querystring, annars skriv ut alla produkter
        if (empty($_GET)) {
            echo json_encode(self::$data, JSON_UNESCAPED_UNICODE);
            die();
        }

        // Skicka error om fler än 2 queries
        if (count($_GET) > 2) {
            $error = "Only 'show' and 'category' filters are allowed.";
            echo json_encode($error, JSON_UNESCAPED_UNICODE);
            die();
        }

        // Skicka error om query key är ogiltig
        if (count($_GET) == 2 &&
            !array_key_exists('show', $_GET) &&
            !array_key_exists('category', $_GET)) {
            $error = "The key/keyes are not valid.";
            echo json_encode($error, JSON_UNESCAPED_UNICODE);
            die();
        } 

        // Körs om både category och show finns i query
        if (count($_GET) == 2 &&
        array_key_exists('show', $_GET) &&
        array_key_exists('category', $_GET)) {
            self::setLimit();
            self::setCategory();
            self::renderLimitCategory();
        }

        if (count($_GET) == 1) {

            if (array_key_exists('show', $_GET)) {
                self::setLimit();
                self::renderLimit();
            }
            else if(array_key_exists('category', $_GET)) {
                self::setCategory();
                self::renderCategory();
            }
            else {
                $error = "The key is not valid.";
                echo json_encode($error, JSON_UNESCAPED_UNICODE);
                die();
            } 
        }
    }

    // Renderar ut data med show filter
    public static function renderLimit() {
        
        $filteredDataLimit = array();

        shuffle(self::$data);

        for ($i = 0; $i < self::$limit; $i++) {
            array_push($filteredDataLimit, self::$data[$i]);
        }
        echo json_encode($filteredDataLimit, JSON_UNESCAPED_UNICODE);
    }

    // Renderar ut data med categoryfilter
    public static function renderCategory() {
        $filteredDataCategory = array();

        foreach (self::$data as $product) {
            if ($product['category'] == self::$category) {
                array_push($filteredDataCategory, $product);
            }
        }
        echo json_encode($filteredDataCategory, JSON_UNESCAPED_UNICODE);
    }

    // Renderar ut data med både show och category filter
    public static function renderLimitCategory() {
        $filteredDataCategory = array();
        $filteredDataLimit = array();
        
        foreach (self::$data as $product) {
            if ($product['category'] == self::$category) {
                array_push($filteredDataCategory, $product);
            }
        }

        if (count($filteredDataCategory) < self::$limit) {
            $error = "There are only " . count($filteredDataCategory) . " items in that category.";
            echo json_encode($error, JSON_UNESCAPED_UNICODE);
            die();
        }

        shuffle($filteredDataCategory);

        for ($i = 0; $i < self::$limit; $i++) {
            array_push($filteredDataLimit, $filteredDataCategory[$i]);
        }
        echo json_encode($filteredDataLimit, JSON_UNESCAPED_UNICODE);
    }

    // felsöker och sätter inkommande show query
    public static function setLimit() {
        $limit = htmlspecialchars($_GET['show']);

        // Skicka error om value är större än 20
        if (self::$limit > 20) {
            $error = "Can't show more than 20 products.";
            echo json_encode($error, JSON_UNESCAPED_UNICODE);
            die();
        }

        // Skicka error om show value inte är en siffra
        if (!is_numeric($limit)) {
            $error = "Value must be a number.";
            echo json_encode($error, JSON_UNESCAPED_UNICODE);
            die();
        }
        self::$limit = $limit;
    }
    // felsöker och sätter inkommande category query
    public static function setCategory() {
        // $category = isset($_GET['category']) ? $_GET['category'] : null;
        $category = htmlspecialchars($_GET['category']);

        // Skicka error om category är ogiltig
        if ($category !== "men" &&
            $category !== "jewelery" &&
            $category !== "electronics" &&
            $category !== "women") 
            {
                $error = 'The category does not exist.';
                $category = null;
                echo json_encode($error, JSON_UNESCAPED_UNICODE);
                die();
            }
        self::$category = $category;
    }

}

?>