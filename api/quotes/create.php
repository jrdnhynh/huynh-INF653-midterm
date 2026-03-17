<?php
    // headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    // imports
    include_once '../../config/Database.php';
    include_once '../../models/Quote.php';
    include_once '../../models/Author.php';
    include_once '../../models/Category.php';

    // instantiate
    $database = new Database();
    $db = $database->connect();
    $quote    = new Quote($db);
    $author   = new Author($db);
    $category = new Category($db);

    // read input
    $data = json_decode(file_get_contents("php://input"));

    // check if quote, author_id, or category_id are missing
    if (empty($data->quote) || empty($data->author_id) || empty($data->category_id)) {
        echo json_encode(array('message' => 'Missing Required Parameters'));
        exit;
    }

    // validate author exists
    $author->id = $data->author_id;
    if (!$author->read_single()) {
        echo json_encode(array('message' => 'author_id Not Found'));
        exit;
    }

    // validate category exists
    $category->id = $data->category_id;
    if (!$category->read_single()) {
        echo json_encode(array('message' => 'category_id Not Found'));
        exit;
    }

    // set quote properties
    $quote->quote       = $data->quote;
    $quote->author_id   = $data->author_id;
    $quote->category_id = $data->category_id;

    // create quote and respond
    if ($quote->create()) {
        // get the new id from the postgres sequence
        $quote->id = $db->lastInsertId('quotes_id_seq');
        $quote->read_single();
        // POST returns id, quote, author_id, category_id (no name fields)
        echo json_encode(array(
            'id'          => $quote->id,
            'quote'       => $quote->quote,
            'author_id'   => $quote->author_id,
            'category_id' => $quote->category_id
        ));
    } else {
        echo json_encode(array('message' => 'Quote Not Created'));
    }
?>