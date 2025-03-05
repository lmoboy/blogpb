<?php
$uri = parse_url($_SERVER["REQUEST_URI"])["path"];

$routes = require("routes.php");

foreach ($routes as $controller) {
    require_once "controllers/" . explode("@", $controller)[0] . ".php";
}

list($controller, $method) = explode('@', $routes[$uri]);
$instance = new $controller();

// Get request parameters
$params = [];
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $params = $_GET;
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $contentType = isset($_SERVER["CONTENT_TYPE"]) ? $_SERVER["CONTENT_TYPE"] : '';
    if (strpos($contentType, 'application/json') !== false) {
        $json = file_get_contents('php://input');
        $params = json_decode($json, true) ?? [];
    } else {
        $params = $_POST;
    }
}

// Add query parameters if they exist
if (isset($_GET['id'])) {
    $params['id'] = $_GET['id'];
}

// Call the method with parameters
call_user_func_array([$instance, $method], [$params]);