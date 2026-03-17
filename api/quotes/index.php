<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'OPTIONS') {
    die();
}

// route to the correct file based on the method
switch($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            require 'read_single.php';
        } else {
            require 'read.php';
        }
        break;
    case 'POST':
        require 'create.php';
        break;
    case 'PUT':
        require 'update.php';
        break;
    case 'DELETE':
        require 'delete.php';
        break;
}