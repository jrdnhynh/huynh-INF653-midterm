<?php
    // headers
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/Quote.php';
    include_once '../../models/Author.php';
    include_once '../../models/Category.php';

    // instantiate db
    $database = new Database();
    $db = $database->connect();

    $author_id   = !empty($_GET['author_id'])   ? $_GET['author_id']   : null;
    $category_id = !empty($_GET['category_id']) ? $_GET['category_id'] : null;
    $random      = !empty($_GET['random']) && $_GET['random'] === 'true';

    // validate author_id if provided - only error if author doesn't exist at all
    if ($author_id) {
        $author = new Author($db);
        $author->id = $author_id;
        if (!$author->read_single()) {
            echo json_encode(array('message' => 'author_id Not Found'));
            exit;
        }
    }

    // validate category_id if provided
    if ($category_id) {
        $category = new Category($db);
        $category->id = $category_id;
        if (!$category->read_single()) {
            echo json_encode(array('message' => 'category_id Not Found'));
            exit;
        }
    }

    $query = 'SELECT c.category AS category_name, a.author AS author_name,
                q.id, q.quote, q.author_id, q.category_id
            FROM quotes q
            LEFT JOIN categories c ON q.category_id = c.id
            LEFT JOIN authors a ON q.author_id = a.id
            WHERE 1=1';

    $params = array();

    if ($author_id) {
        $query .= ' AND q.author_id = :author_id';
        $params[':author_id'] = $author_id;
    }
    if ($category_id) {
        $query .= ' AND q.category_id = :category_id';
        $params[':category_id'] = $category_id;
    }

    $query .= $random ? ' ORDER BY RANDOM() LIMIT 1' : ' ORDER BY q.id DESC';

    $stmt = $db->prepare($query);
    foreach ($params as $key => $val) {
        $stmt->bindValue($key, $val);
    }
    $stmt->execute();
    $num = $stmt->rowCount();

    if ($num > 0) {
        if ($random) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            echo json_encode(array(
                'id'          => $row['id'],
                'quote'       => $row['quote'],
                'author'      => $row['author_name'],
                'category'    => $row['category_name'],
                'author_id'   => $row['author_id'],
                'category_id' => $row['category_id']
            ));
        } else {
            $quotes_arr = array();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $quotes_arr[] = array(
                    'id'          => $row['id'],
                    'quote'       => $row['quote'],
                    'author'      => $row['author_name'],
                    'category'    => $row['category_name'],
                    'author_id'   => $row['author_id'],
                    'category_id' => $row['category_id']
                );
            }
            echo json_encode($quotes_arr);
        }
    } else {
        // filtering by author/category and no results, return empty array
        // return message when no filters are applied (no quotes at all)
        if ($author_id || $category_id) {
            echo json_encode(array());
        } else {
            echo json_encode(array('message' => 'No Quotes Found'));
        }
    }
?>