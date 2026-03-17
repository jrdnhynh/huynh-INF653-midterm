<?php
    // headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/Quote.php';

    // instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // instantiate quote
    $quote = new Quote($db);

    $quote->id = isset($_GET['id']) ? $_GET['id'] : null;

    if (empty($quote->id)) {
        echo json_encode(array('message' => 'Missing Required Parameters'));
        exit;
    }

    // get quote - return not found if it doesn't exist
    if ($quote->read_single()) {
        echo json_encode(array(
            'id'          => $quote->id,
            'quote'       => $quote->quote,
            'author'      => $quote->author_name,
            'category'    => $quote->category_name,
            'author_id'   => $quote->author_id,
            'category_id' => $quote->category_id
        ));
    } else {
        echo json_encode(array('message' => 'No Quotes Found'));
    }
?>