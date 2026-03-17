<?php
    // headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: PUT');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Quote.php';

    // instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // instantiate quote
    $quote = new Quote($db);

    // get raw posted data
    $data = json_decode(file_get_contents("php://input"));

    // check for required parameters (must have ID to update)
    if (empty($data->id) || empty($data->quote) || empty($data->author_id) || empty($data->category_id)) {
        echo json_encode(array('message' => 'Missing Required Parameters'));
        exit;
    }

    // check if quote exists
    $quote->id = $data->id;
    // if it fails it doesnt exist
    $quote->read_single();
    if (empty($quote->quote)) {
        echo json_encode(array('message' => 'No Quotes Found'));
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

    // update quote
    $quote->quote = $data->quote;
    $quote->author_id = $data->author_id;
    $quote->category_id = $data->category_id;

    if($quote->update()) {
        // fetch updated version
        $quote->read_single();
        echo json_encode(array(
            'id' => $quote->id,
            'quote' => $quote->quote,
            'author' => $quote->author_name,
            'category' => $quote->category_name
        ));
    } else {
        echo json_encode(array('message' => 'Quote Not Updated'));
    }
?>