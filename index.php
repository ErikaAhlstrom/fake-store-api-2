<?php
include 'Products.php';

/**
 * Fake Store API
 */

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header("Referrer-Policy: no-referrer");

Products::main();


// $limit = isset($_GET["limit"]) ? $_GET["limit"] : 10;

// shuffle($names);


