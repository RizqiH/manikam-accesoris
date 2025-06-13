<?php
require_once __DIR__ . '/../config/database.php';

class Product {
    private $conn;
    private $table_name = "tb_produk";

    public $id;
    public $nama_produk;
    public $harga;
    public $foto;
    public $deskripsi;
    public $stok;
    public $kategori;
    public $status;
    public $created_at;
    public $updated_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Create product
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " SET nama_produk=:nama_produk, harga=:harga, foto=:foto, deskripsi=:deskripsi, stok=:stok, kategori=:kategori, status=:status";
        $stmt = $this->conn->prepare($query);

        // Sanitize
        $this->nama_produk = htmlspecialchars(strip_tags($this->nama_produk));
        $this->harga = htmlspecialchars(strip_tags($this->harga));
        $this->foto = htmlspecialchars(strip_tags($this->foto ?? ''));
        $this->deskripsi = htmlspecialchars(strip_tags($this->deskripsi ?? ''));
        $this->stok = $this->stok ?? 0;
        $this->kategori = htmlspecialchars(strip_tags($this->kategori ?? ''));
        $this->status = $this->status ?? 'aktif';

        // Bind values
        $stmt->bindParam(":nama_produk", $this->nama_produk);
        $stmt->bindParam(":harga", $this->harga);
        $stmt->bindParam(":foto", $this->foto);
        $stmt->bindParam(":deskripsi", $this->deskripsi);
        $stmt->bindParam(":stok", $this->stok);
        $stmt->bindParam(":kategori", $this->kategori);
        $stmt->bindParam(":status", $this->status);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Read all products
    public function read() {
        $query = "SELECT id, nama_produk, harga, foto, deskripsi, stok, kategori, status, created_at, updated_at FROM " . $this->table_name . " ORDER BY id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Read single product
    public function readOne() {
        $query = "SELECT id, nama_produk, harga, foto, deskripsi, stok, kategori, status, created_at, updated_at FROM " . $this->table_name . " WHERE id = :id LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row) {
            $this->nama_produk = $row['nama_produk'];
            $this->harga = $row['harga'];
            $this->foto = $row['foto'];
            $this->deskripsi = $row['deskripsi'];
            $this->stok = $row['stok'];
            $this->kategori = $row['kategori'];
            $this->status = $row['status'];
            $this->created_at = $row['created_at'];
            $this->updated_at = $row['updated_at'];
            return true;
        }
        return false;
    }

    // Update product
    public function update() {
        $query = "UPDATE " . $this->table_name . " SET nama_produk=:nama_produk, harga=:harga, foto=:foto, deskripsi=:deskripsi, stok=:stok, kategori=:kategori, status=:status WHERE id=:id";
        $stmt = $this->conn->prepare($query);

        // Sanitize
        $this->nama_produk = htmlspecialchars(strip_tags($this->nama_produk));
        $this->harga = htmlspecialchars(strip_tags($this->harga));
        $this->foto = htmlspecialchars(strip_tags($this->foto ?? ''));
        $this->deskripsi = htmlspecialchars(strip_tags($this->deskripsi ?? ''));
        $this->stok = $this->stok ?? 0;
        $this->kategori = htmlspecialchars(strip_tags($this->kategori ?? ''));
        $this->status = $this->status ?? 'aktif';
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Bind values
        $stmt->bindParam(":nama_produk", $this->nama_produk);
        $stmt->bindParam(":harga", $this->harga);
        $stmt->bindParam(":foto", $this->foto);
        $stmt->bindParam(":deskripsi", $this->deskripsi);
        $stmt->bindParam(":stok", $this->stok);
        $stmt->bindParam(":kategori", $this->kategori);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":id", $this->id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Delete product
    public function delete() {
        error_log("[PRODUCT DELETE] Starting delete for ID: " . $this->id);
        
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(':id', $this->id);
        
        error_log("[PRODUCT DELETE] Executing query: " . $query . " with ID: " . $this->id);
        
        if($stmt->execute()) {
            $rowCount = $stmt->rowCount();
            error_log("[PRODUCT DELETE] Query executed successfully. Rows affected: " . $rowCount);
            
            if($rowCount > 0) {
                error_log("[PRODUCT DELETE] Product deleted successfully");
                return true;
            } else {
                error_log("[PRODUCT DELETE] No rows affected - product may not exist");
                return false;
            }
        } else {
            $errorInfo = $stmt->errorInfo();
            error_log("[PRODUCT DELETE] Query execution failed: " . print_r($errorInfo, true));
            return false;
        }
    }

    // Search products
    public function search($keywords) {
        $query = "SELECT id, nama_produk, harga, foto FROM " . $this->table_name . " WHERE nama_produk LIKE :keywords ORDER BY id DESC";
        $stmt = $this->conn->prepare($query);
        
        $keywords = htmlspecialchars(strip_tags($keywords));
        $keywords = "%{$keywords}%";
        
        $stmt->bindParam(':keywords', $keywords);
        $stmt->execute();
        
        return $stmt;
    }
}
?>