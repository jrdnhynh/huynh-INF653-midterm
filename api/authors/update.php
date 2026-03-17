<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: PUT');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Author.php';

    $database = new Database();
    $db = $database->connect();
    $author = new Author($db);

    $data = json_decode(file_get_contents("php://input"));

    if(!empty($data->id) && !empty($data->author)) {
        $author->id = $data->id;
        $author->author = $data->author;

        // check if author exists before updating
        if(!$author->read_single()) {
            echo json_encode(array('message' => 'author_id Not Found'));
            exit;
        }

        if($author->update()) {
            // return the updated object instead of just a success message
            echo json_encode(
                array(
                    'id' => $author->id,
                    'author' => $author->author
                )
            );
        } else {
            echo json_encode(array('message' => 'Author Not Updated'));
        }
    } else {
        echo json_encode(array('message' => 'Missing Required Parameters'));
    }
?>