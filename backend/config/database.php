<?php
// Database configuration
class Database {
    private $host = 'db.be-mons1.bengt.wasmernet.com';
    private $db_name = 'db_fpp';
    private $username = 'c77c6e67721f8000d7d01f3f3262';
    private $password = '0684c77c-6e67-7588-8000-4183d24c18d7';
    private $port = '3306';  // Anda dapat mengganti nomor port ini sesuai dengan yang digunakan
    public $conn;

    public function getConnection() {
        $this->conn = null;
        
        // Debug log: Connection attempt
        error_log("[DEBUG] Attempting database connection...");
        error_log("[DEBUG] Host: " . $this->host);
        error_log("[DEBUG] Database: " . $this->db_name);
        error_log("[DEBUG] Username: " . $this->username);
        error_log("[DEBUG] Password: " . (empty($this->password) ? 'EMPTY' : 'SET'));
        error_log("[DEBUG] Port: " . $this->port);  // Log port

        try {
            // Tambahkan port ke DSN
            $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";port=" . $this->port;
            error_log("[DEBUG] DSN: " . $dsn);
            
            $this->conn = new PDO($dsn, $this->username, $this->password);
            $this->conn->exec("set names utf8");
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Debug log: Success
            error_log("[DEBUG] Database connection successful!");
            
        } catch(PDOException $exception) {
            // Log detailed error information
            error_log("[ERROR] Database connection failed!");
            error_log("[ERROR] PDO Error Code: " . $exception->getCode());
            error_log("[ERROR] PDO Error Message: " . $exception->getMessage());
            error_log("[ERROR] Connection string: mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";port=" . $this->port);
        }
        
        // Debug log: Final connection status
        if ($this->conn) {
            error_log("[DEBUG] Returning valid connection object");
        } else {
            error_log("[DEBUG] Returning NULL connection");
        }
        
        return $this->conn;
    }
}
?>
