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

    // quote query
    $result = $quote->read();
    // get row count
    $num = $result->rowCount();

    // check if any quotes
    if($num > 0) {
        // quote array
        $quotes_arr = array();
        $quotes_arr['data'] = array();

        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            $quote_item = array(
                'id' => $id,
                'quote' => $quote,
                'author' => $author_name,
                'category' => $category_name
            );

            // push to "data"
            array_push($quotes_arr['data'], $quote_item);
        }

        // turn to JSON & output
        echo json_encode($quotes_arr);
    } else {
        // no posts
        echo json_encode(
            array('message' => 'No Quotes Found')
        );
    }
?>