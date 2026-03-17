<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/Author.php';

    $database = new Database();
    $db = $database->connect();
    $author = new Author($db);

    // check for ID in the GET request
    if(isset($_GET['id'])) {
        $author->id = $_GET['id'];

        // get Author
        if($author->read_single()) {
            $author_arr = array(
                'id' => $author->id,
                'author' => $author->author
            );
            echo json_encode($author_arr);
        } else {
            //specific message for non-existent ID
            echo json_encode(array('message' => 'author_id Not Found'));
        }
    } else {
        // ?id= is missing
        echo json_encode(array('message' => 'Missing Required Parameters'));
    }
?>