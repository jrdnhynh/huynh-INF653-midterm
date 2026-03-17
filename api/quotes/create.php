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

    // validate author
    $author->id = $data->author_id;
    if (!$author->read_single()) {
        echo json_encode(array('message' => 'author_id Not Found'));
        exit;
    }

    // validate category
    $category->id = $data->category_id;
    if (!$category->read_single()) {
        echo json_encode(array('message' => 'category_id Not Found'));
        exit;
    }

    // clean data
    $quote->quote       = $data->quote;
    $quote->author_id   = $data->author_id;
    $quote->category_id = $data->category_id;

    // create & respond quote data
    if ($quote->create()) {
        $quote->id = $db->lastInsertId('quotes_id_seq');
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
        echo json_encode(array('message' => 'Quote Not Created'));
    }
?>