<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: PUT');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Category.php';

    $database = new Database();
    $db = $database->connect();
    $category = new Category($db);

    $data = json_decode(file_get_contents("php://input"));

    if(!empty($data->id) && !empty($data->category)) {
        $category->id = $data->id;
        $category->category = $data->category;

        // verify category exists before updating
        if(!$category->read_single()) {
            echo json_encode(array('message' => 'category_id Not Found'));
            exit;
        }

        if($category->update()) {
            // return updated object
            echo json_encode(
                array(
                    'id' => $category->id,
                    'category' => $category->category
                )
            );
        } else {
            echo json_encode(array('message' => 'Category Not Updated'));
        }
    } else {
        echo json_encode(array('message' => 'Missing Required Parameters'));
    }
?>