<?php
    // headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: DELETE');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Category.php';

    // instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // instantiate category
    $category = new Category($db);

    // get raw posted data
    $data = json_decode(file_get_contents("php://input"));

    // check for required parameters
    if (!empty($data->id)) {
        // set ID to delete
        $category->id = $data->id;

        // delete category
        if($category->delete()) {
            echo json_encode(
                array('id' => $category->id)
            );
        } else {
            echo json_encode(
                array('message' => 'Category Not Deleted')
            );
        }
    } else {
        // missing parameters
        echo json_encode(
            array('message' => 'Missing Required Parameters')
        );
    }
?>