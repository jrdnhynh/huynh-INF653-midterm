<?php
    class Database {
        private $host;
        private $port;
        private $db_name;
        private $username;
        private $password;
        private $conn;

        public function __construct() {
            // look for env variables first
            // if dont exist -> look for hardcoded values
            $this->host = getenv('DB_HOST') ?: 'dpg-d6seg3kr85hc73er6idg-a.oregon-postgres.render.com';
            $this->port = getenv('DB_PORT') ?: '5432';
            $this->db_name = getenv('DB_NAME') ?: 'quotesdb_kszq';
            $this->username = getenv('DB_USER') ?: 'quotesdb_kszq_user';
            $this->password = getenv('DB_PASS') ?: 'Qoeb4nnSPMdFfrZh9PXXg5rjrowKgzQL';
        }

        public function connect() {
            $this->conn = null;

            try {
                $dsn = "pgsql:host={$this->host};port={$this->port};dbname={$this->db_name}";
                
                $this->conn = new PDO($dsn, $this->username, $this->password);
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
            } catch(PDOException $e) {
                echo 'Connection Error: ' . $e->getMessage();
            }

            return $this->conn;
        }
    }
?>