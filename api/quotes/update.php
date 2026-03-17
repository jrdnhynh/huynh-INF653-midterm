<?php
    // headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: PUT');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    // imports
    include_once '../../config/Database.php';
    include_once '../../models/Quote.php';
    include_once '../../models/Author.php';
    include_once '../../models/Category.php';

    $database = new Database();
    $db = $database->connect();
    $quote    = new Quote($db);
    $author   = new Author($db);
    $category = new Category($db);

    // read input
    $data = json_decode(file_get_contents("php://input"));

    // check for id
    if (empty($data->id)) {
        echo json_encode(array('message' => 'Missing Required Parameters'));
        exit;
    }

    // validate fields
    if (empty($data->quote) || empty($data->author_id) || empty($data->category_id)) {
        echo json_encode(array('message' => 'Missing Required Parameters'));
        exit;
    }

    // check if quote exists
    $quote->id = $data->id;
    if (!$quote->read_single()) {
        echo json_encode(array('message' => 'No Quotes Found'));
        exit;
    }

    // check if author and category exists
    $author->id = $data->author_id;
    if (!$author->read_single()) {
        echo json_encode(array('message' => 'author_id Not Found'));
        exit;
    }

    $category->id = $data->category_id;
    if (!$category->read_single()) {
        echo json_encode(array('message' => 'category_id Not Found'));
        exit;
    }

    // load data into Quote object
    $quote->quote       = $data->quote;
    $quote->author_id   = $data->author_id;
    $quote->category_id = $data->category_id;

    // update and respond
    if ($quote->update()) {
        $quote->read_single();
        echo json_encode(array(
            'id'          => $quote->id,
            'quote'       => $quote->quote,
            'author'      => $quote->author_name,
            'author_id'   => $quote->author_id,
            'category'    => $quote->category_name,
            'category_id' => $quote->category_id
        ));
    } else {
        echo json_encode(array('message' => 'Quote Not Updated'));
    }
?>