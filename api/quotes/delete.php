<?php
    // headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: DELETE');
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

    // check for required parameters
    if (!empty($data->id)) {
        
        // assign the ID from the data to the quote object
        $quote->id = $data->id;

        // delete quote
        if($quote->delete()) {
            echo json_encode(
                array('id' => $quote->id)
            );
        } else {
            echo json_encode(
                array('message' => 'Quote Not Deleted')
            );
        }
    } else {
        // missing parameters
        echo json_encode(
            array('message' => 'Missing Required Parameters')
        );
    }
?>