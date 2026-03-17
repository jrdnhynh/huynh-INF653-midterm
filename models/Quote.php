<?php
    class Quote {
        // DB stuff
        private $conn;
        private $table = 'quotes';

        // Quote Properties
        public $id;
        public $quote;
        public $author_id;
        public $category_id;
        public $category_name;
        public $author_name;

        // Constructor with DB
        public function __construct($db) {
            $this->conn = $db;
        }

        // Get Quotes
        public function read() {
            // create query
            $query = 'SELECT
                    c.category as category_name,
                    a.author as author_name,
                    q.id,
                    q.quote,
                    q.author_id,
                    q.category_id
                FROM
                    ' . $this->table . ' q
                LEFT JOIN
                    categories c ON q.category_id = c.id
                LEFT JOIN
                    authors a ON q.author_id = a.id
                ORDER BY
                q.id DESC';

            // prepare statement
            $stmt = $this->conn->prepare($query);

            // execute query
            $stmt->execute();

            return $stmt;
        }

        // Get single quote
        public function read_single() {
            // create query
            $query = 'SELECT
                    c.category as category_name,
                    a.author as author_name,
                    q.id,
                    q.quote,
                    q.author_id,
                    q.category_id
                FROM
                    ' . $this->table . ' q
                LEFT JOIN
                    categories c ON q.category_id = c.id
                LEFT JOIN
                    authors a ON q.author_id = a.id
                WHERE
                    q.id = ?
                LIMIT 1';

            // prepare statement
            $stmt = $this->conn->prepare($query);

            // bind ID
            $stmt->bindParam(1, $this->id);

            // execute query
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // set properties
            $this->quote = $row['quote'];
            $this->author_id = $row['author_id'];
            $this->category_id = $row['category_id'];
            $this->author_name = $row['author_name'];
            $this->category_name = $row['category_name'];
        }

        // create Quote
        public function create() {
            // create query
            $query = 'INSERT INTO ' . $this->table . '
            SET
                quote = :quote,
                author_id = :author_id,
                category_id = :category_id';

            // prepare statement
            $stmt = $this->conn->prepare($query);

            // clean data
            $this->quote = htmlspecialchars(strip_tags($this->quote));
            $this->author_id = htmlspecialchars(strip_tags($this->author_id));
            $this->category_id = htmlspecialchars(strip_tags($this->category_id));

            // bind data
            $stmt->bindParam(':quote', $this->quote);
            $stmt->bindParam(':author_id', $this->author_id);
            $stmt->bindParam(':category_id', $this->category_id);

            // execute query
            if($stmt->execute()) {
                return true;
            }

            printf("Error: %s.\n", $stmt->error);
            
            return false;
        }

        // update Quote
        public function update() {
            // update query
            $query = 'UPDATE ' . $this->table . '
            SET
                quote = :quote,
                author_id = :author_id,
                category_id = :category_id
            WHERE
                id = :id';

            // prepare statement
            $stmt = $this->conn->prepare($query);

            // clean data
            $this->quote = htmlspecialchars(strip_tags($this->quote));
            $this->author_id = htmlspecialchars(strip_tags($this->author_id));
            $this->category_id = htmlspecialchars(strip_tags($this->category_id));
            $this->id = htmlspecialchars(strip_tags($this->id));

            // bind data
            $stmt->bindParam(':quote', $this->quote);
            $stmt->bindParam(':author_id', $this->author_id);
            $stmt->bindParam(':category_id', $this->category_id);
            $stmt->bindParam(':id', $this->id);

            // execute query
            if($stmt->execute()) {
                return true;
            }

            printf("Error: %s.\n", $stmt->error);
            
            return false;
        }

        // delete Quote
        public function delete() {
            // create query
            $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';

            // prepare statement
            $stmt = $this->conn->prepare($query);

            // clean data
            $this->id = htmlspecialchars(strip_tags($this->id));

            // bind data
            $stmt->bindParam(':id', $this->id);

            // execute query
            if($stmt->execute()) {
                return true;
            }

            printf("Error: %s.\n", $stmt->error);
            
            return false;
        }
    }
?>