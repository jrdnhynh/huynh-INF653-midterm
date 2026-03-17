<?php
    class Category {
        private $conn;
        private $table = 'categories';

        public $id;
        public $category;

        public function __construct($db) {
            $this->conn = $db;
        }

        public function read() {
            $query = 'SELECT id, category FROM ' . $this->table . ' ORDER BY id ASC';
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt;
        }

        // Get single category
        public function read_single() {
            $query = 'SELECT id, category FROM ' . $this->table . ' WHERE id = ? LIMIT 1';
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $this->id);
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if($row) {
                $this->category = $row['category'];
                return true;
            }
            return false;
        }

        // Create category
        public function create() {
            $query = 'INSERT INTO ' . $this->table . ' SET category = :category';
            $stmt = $this->conn->prepare($query);

            // clean data
            $this->category = htmlspecialchars(strip_tags($this->category));

            // bind data
            $stmt->bindParam(':category', $this->category);

            if($stmt->execute()) {
                return true;
            }
            return false;
        }

        // Update Category
        public function update() {
            $query = 'UPDATE ' . $this->table . '
                    SET
                        category = :category
                    WHERE
                        id = :id';

            $stmt = $this->conn->prepare($query);

            // clean data
            $this->category = htmlspecialchars(strip_tags($this->category));
            $this->id = htmlspecialchars(strip_tags($this->id));

            // bind data
            $stmt->bindParam(':category', $this->category);
            $stmt->bindParam(':id', $this->id);

            if($stmt->execute()) {
                return true;
            }
            return false;
        }

        // Delete Category
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