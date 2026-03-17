<?php
    // headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: GET');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    // imports
    include_once '../../config/Database.php';
    include_once '../../models/Quote.php';
    include_once '../../models/Author.php';
    include_once '../../models/Category.php';

    // instantiate
    $database = new Database();
    $db = $database->connect();

    // validate author_id filter if provided
    if (!empty($_GET['author_id'])) {
        $author = new Author($db);
        $author->id = $_GET['author_id'];
        if (!$author->read_single()) {
            echo json_encode(array('message' => 'author_id Not Found'));
            exit;
        }
    }

    // validate category_id filter if provided
    if (!empty($_GET['category_id'])) {
        $category = new Category($db);
        $category->id = $_GET['category_id'];
        if (!$category->read_single()) {
            echo json_encode(array('message' => 'category_id Not Found'));
            exit;
        }
    }

    // read query params
    $author_id   = !empty($_GET['author_id'])   ? $_GET['author_id']   : null;
    $category_id = !empty($_GET['category_id']) ? $_GET['category_id'] : null;
    $random      = !empty($_GET['random']) && $_GET['random'] === 'true';

    // build base query with joins to get author and category names
    $query = 'SELECT c.category AS category_name, a.author AS author_name,
                q.id, q.quote, q.author_id, q.category_id
              FROM quotes q
              LEFT JOIN categories c ON q.category_id = c.id
              LEFT JOIN authors a ON q.author_id = a.id
              WHERE 1=1';

    // append filters dynamically
    $params = array();
    if ($author_id) {
        $query .= ' AND q.author_id = :author_id';
        $params[':author_id'] = $author_id;
    }
    if ($category_id) {
        $query .= ' AND q.category_id = :category_id';
        $params[':category_id'] = $category_id;
    }

    // append random or default ordering
    $query .= $random ? ' ORDER BY RANDOM() LIMIT 1' : ' ORDER BY q.id DESC';

    // prepare and execute query
    $stmt = $db->prepare($query);
    foreach ($params as $key => $val) {
        $stmt->bindValue($key, $val);
    }
    $stmt->execute();
    $num = $stmt->rowCount();

    // respond with results
    if ($num > 0) {
        if ($random) {
            // return single object for random=true
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            // GET returns only id, quote, author, category (no _id fields)
            echo json_encode(array(
                'id'       => $row['id'],
                'quote'    => $row['quote'],
                'author'   => $row['author_name'],
                'category' => $row['category_name']
            ));
        } else {
            // return array of quotes
            $quotes_arr = array();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // GET returns only id, quote, author, category (no _id fields)
                $quotes_arr[] = array(
                    'id'       => $row['id'],
                    'quote'    => $row['quote'],
                    'author'   => $row['author_name'],
                    'category' => $row['category_name']
                );
            }
            echo json_encode($quotes_arr);
        }
    } else {
        echo json_encode(array('message' => 'No Quotes Found'));
    }
?>