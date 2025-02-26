<?php
$uri = parse_url($_SERVER["REQUEST_URI"])["path"];

$routes = require("routes.php");

foreach ($routes as $controller) {
    require_once "controllers/" . explode("@", $controller)[0] . ".php";
}


list($controller, $method) = explode('@', $routes[$uri]);
$instance = new $controller();
$instance->$method();