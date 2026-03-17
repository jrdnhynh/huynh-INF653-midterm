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

    // instantiate
    $database = new Database();
    $db = $database->connect();
    $quote    = new Quote($db);
    $author   = new Author($db);
    $category = new Category($db);

    // read input
    $data = json_decode(file_get_contents("php://input"));

    // id is always required for update
    if (empty($data->id)) {
        echo json_encode(array('message' => 'Missing Required Parameters'));
        exit;
    }

    // check remaining required fields
    if (empty($data->quote) || empty($data->author_id) || empty($data->category_id)) {
        echo json_encode(array('message' => 'Missing Required Parameters'));
        exit;
    }

    // check quote exists
    $quote->id = $data->id;
    if (!$quote->read_single()) {
        echo json_encode(array('message' => 'No Quotes Found'));
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

    // set updated values
    $quote->quote       = $data->quote;
    $quote->author_id   = $data->author_id;
    $quote->category_id = $data->category_id;

    // update quote and respond
    if ($quote->update()) {
        $quote->read_single();
        // PUT returns id, quote, author_id, category_id (no name fields)
        echo json_encode(array(
            'id'          => $quote->id,
            'quote'       => $quote->quote,
            'author_id'   => $quote->author_id,
            'category_id' => $quote->category_id
        ));
    } else {
        echo json_encode(array('message' => 'Quote Not Updated'));
    }
?>