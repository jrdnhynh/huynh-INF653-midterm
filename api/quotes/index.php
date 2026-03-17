<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    $method = $_SERVER['REQUEST_METHOD'];

    // handle pre-flight OPTIONS request
    if ($method === 'OPTIONS') {
        die();
    }

    switch ($method) {
        case 'GET':
            // route to read_single if ?id= is present, otherwise read (handles all filters)
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
        default:
            echo json_encode(array('message' => 'Method Not Allowed'));
            break;
    }
?>