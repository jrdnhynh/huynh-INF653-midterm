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

    // GET id, if no id set to null
    $quote->id = isset($_GET['id']) ? $_GET['id'] : null;

    // validate if user provided id in url
    if (empty($quote->id)) {
        echo json_encode(array('message' => 'Missing Required Parameters'));
        exit;
    }

    // fetch and respond
    if ($quote->read_single()) {
        echo json_encode(array(
            'id'          => $quote->id,
            'quote'       => $quote->quote,
            'author'      => $quote->author_name,
            'author_id'   => $quote->author_id,
            'category'    => $quote->category_name,
            'category_id' => $quote->category_id
        ));
    } else {
        echo json_encode(array('message' => 'No Quotes Found'));
    }
?>