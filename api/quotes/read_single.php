<?php
    // headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    // imports
    include_once '../../config/Database.php';
    include_once '../../models/Quote.php';

    // instantiate
    $database = new Database();
    $db = $database->connect();
    $quote = new Quote($db);

    // get id from query param
    $quote->id = isset($_GET['id']) ? $_GET['id'] : null;

    // check id was provided
    if (empty($quote->id)) {
        echo json_encode(array('message' => 'Missing Required Parameters'));
        exit;
    }

    // fetch quote and respond
    if ($quote->read_single()) {
        // GET returns only id, quote, author, category (no _id fields)
        echo json_encode(array(
            'id'       => $quote->id,
            'quote'    => $quote->quote,
            'author'   => $quote->author_name,
            'category' => $quote->category_name
        ));
    } else {
        echo json_encode(array('message' => 'No Quotes Found'));
    }
?>