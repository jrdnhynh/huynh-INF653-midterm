<?php
    // headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Quote.php';
    include_once '../../models/Author.php';
    include_once '../../models/Category.php';

    $database = new Database();
    $db = $database->connect();

    $quote = new Quote($db);
    $author = new Author($db);
    $category = new Category($db);

    $data = json_decode(file_get_contents("php://input"));

    // check for required parameters
    if (empty($data->quote) || empty($data->author_id) || empty($data->category_id)) {
        echo json_encode(array('message' => 'Missing Required Parameters'));
        exit;
    }

    // check if author exists
    $author->id = $data->author_id;
    if (!$author->read_single()) {
        echo json_encode(array('message' => 'author_id Not Found'));
        exit;
    }

    // check if category exists
    $category->id = $data->category_id;
    if (!$category->read_single()) {
        echo json_encode(array('message' => 'category_id Not Found'));
        exit;
    }

    // create quote
    $quote->quote = $data->quote;
    $quote->author_id = $data->author_id;
    $quote->category_id = $data->category_id;

    if($quote->create()) {
        // fetch newly created quote
        $quote->id = $db->lastInsertId();
        $quote->read_single();

        echo json_encode(array(
            'id' => $quote->id,
            'quote' => $quote->quote,
            'author' => $quote->author_name,
            'category' => $quote->category_name
        ));
    } else {
        echo json_encode(array('message' => 'Quote Not Created'));
    }
?>