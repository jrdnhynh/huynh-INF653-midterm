<?php
    class Author {
        private $conn;
        private $table = 'authors';

        public $id;
        public $author;

        public function __construct($db) {
            $this->conn = $db;
        }

        public function read() {
            $query = 'SELECT id, author FROM ' . $this->table . ' ORDER BY id ASC';
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt;
        }

        // Get single author
        public function read_single() {
            $query = 'SELECT id, author FROM ' . $this->table . ' WHERE id = ? LIMIT 1';
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $this->id);
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if($row) {
                $this->author = $row['author'];
                return true;
            }
            return false;
        }

        // Create author
        public function create() {
            $query = 'INSERT INTO ' . $this->table . ' (author) VALUES (:author)';
            $stmt = $this->conn->prepare($query);
            $this->author = htmlspecialchars(strip_tags($this->author));
            $stmt->bindParam(':author', $this->author);

            if($stmt->execute()) {
                return true;
            }
            return false;
        }

        // Update author
        public function update() {
            $query = 'UPDATE ' . $this->table . '
                    SET
                        author = :author
                    WHERE
                        id = :id';

            $stmt = $this->conn->prepare($query);

            // clean data
            $this->author = htmlspecialchars(strip_tags($this->author));
            $this->id = htmlspecialchars(strip_tags($this->id));

            // bind data
            $stmt->bindParam(':author', $this->author);
            $stmt->bindParam(':id', $this->id);

            if($stmt->execute()) {
                return true;
            }
            return false;
        }

        // Delete Author
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