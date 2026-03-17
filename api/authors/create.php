<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Author.php';

    $database = new Database();
    $db = $database->connect();
    $author = new Author($db);

    $data = json_decode(file_get_contents("php://input"));

    if (!empty($data->author)) {
        $author->author = $data->author;

        if ($author->create()) {
            // PostgreSQL requires the sequence name for lastInsertId
            echo json_encode(array(
                'id'     => $db->lastInsertId('authors_id_seq'),
                'author' => $author->author
            ));
        } else {
            echo json_encode(array('message' => 'Author Not Created'));
        }
    } else {
        echo json_encode(array('message' => 'Missing Required Parameters'));
    }
?>