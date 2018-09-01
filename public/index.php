<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: *");
header("Access-Control-Allow-Headers: *");

require_once __DIR__ . '/../src/functions.php';
require_once __DIR__ . '/../src/controller/authenticate.php';
require_once __DIR__ . '/../src/controller/products.php';

// Default index page
router('GET', '^/$', function() {
    echo '<a href="users">List users</a><br>';
});

// GET request to /users
router('GET', '^/users$', function() {
    echo '<a href="users/1000">Show user: 1000</a>';
});

// With named parameters
router('GET', '^/users/(?<id>\d+)$', function($params) {
    echo "You selected User-ID: ";
    var_dump($params);
});

// POST request to /users
router('POST', '^/users$', function() {
    header('Content-Type: application/json');
    $json = json_decode(file_get_contents('php://input'), true);
    echo json_encode(['result' => 1]);
});

// login 
router(['POST', 'OPTIONS'], '^/login$', 'login');

// logout 
router(['POST', 'OPTIONS'], '^/logout$', 'logout');

// register 
router(['POST', 'OPTIONS'], '^/register$', 'register');

// all product
router(['GET', 'OPTIONS'], '^/products/all$', 'all');

// single product
router(['GET', 'OPTIONS'], '^/products/(?<id>\d+)$', 'single');

// create product
router(['POST', 'OPTIONS'], '^/products/create$', 'create');

// my product
router(['GET', 'OPTIONS'], '^/products/mine$', 'index');


header("HTTP/1.0 404 Not Found");
echo '404 Not Found';