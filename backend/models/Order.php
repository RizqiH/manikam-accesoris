<?php
require_once __DIR__ . '/../config/database.php';

class Order {
    private $conn;
    private $table_name = "tb_pemesanan";

    public $id;
    public $nama;
    public $jumlah;
    public $alamat;
    public $nama_produk;
    public $total_harga;
    public $status_pesanan;
    public $tanggal_pesan;
    public $tanggal_selesai;
    public $catatan;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Create order
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " SET nama=:nama, jumlah=:jumlah, alamat=:alamat, nama_produk=:nama_produk, total_harga=:total_harga, status_pesanan=:status_pesanan, catatan=:catatan";
        $stmt = $this->conn->prepare($query);

        // Sanitize
        $this->nama = htmlspecialchars(strip_tags($this->nama));
        $this->jumlah = htmlspecialchars(strip_tags($this->jumlah));
        $this->alamat = htmlspecialchars(strip_tags($this->alamat));
        $this->nama_produk = htmlspecialchars(strip_tags($this->nama_produk ?? ''));
        $this->total_harga = $this->total_harga ?? 0;
        $this->status_pesanan = $this->status_pesanan ?? 'pending';
        $this->catatan = htmlspecialchars(strip_tags($this->catatan ?? ''));

        // Bind values
        $stmt->bindParam(":nama", $this->nama);
        $stmt->bindParam(":jumlah", $this->jumlah);
        $stmt->bindParam(":alamat", $this->alamat);
        $stmt->bindParam(":nama_produk", $this->nama_produk);
        $stmt->bindParam(":total_harga", $this->total_harga);
        $stmt->bindParam(":status_pesanan", $this->status_pesanan);
        $stmt->bindParam(":catatan", $this->catatan);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Read all orders
    public function read() {
        $query = "SELECT id, nama, jumlah, alamat, nama_produk, total_harga, status_pesanan, tanggal_pesan, tanggal_selesai, catatan FROM " . $this->table_name . " ORDER BY id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Read single order
    public function readOne() {
        $query = "SELECT id, nama, jumlah, alamat, nama_produk, total_harga, status_pesanan, tanggal_pesan, tanggal_selesai, catatan FROM " . $this->table_name . " WHERE id = :id LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row) {
            $this->nama = $row['nama'];
            $this->jumlah = $row['jumlah'];
            $this->alamat = $row['alamat'];
            $this->nama_produk = $row['nama_produk'];
            $this->total_harga = $row['total_harga'];
            $this->status_pesanan = $row['status_pesanan'];
            $this->tanggal_pesan = $row['tanggal_pesan'];
            $this->tanggal_selesai = $row['tanggal_selesai'];
            $this->catatan = $row['catatan'];
            return true;
        }
        return false;
    }

    // Update order
    public function update() {
        $query = "UPDATE " . $this->table_name . " SET nama=:nama, jumlah=:jumlah, alamat=:alamat, nama_produk=:nama_produk, total_harga=:total_harga, status_pesanan=:status_pesanan, catatan=:catatan WHERE id=:id";
        $stmt = $this->conn->prepare($query);

        // Sanitize
        $this->nama = htmlspecialchars(strip_tags($this->nama));
        $this->jumlah = htmlspecialchars(strip_tags($this->jumlah));
        $this->alamat = htmlspecialchars(strip_tags($this->alamat));
        $this->nama_produk = htmlspecialchars(strip_tags($this->nama_produk ?? ''));
        $this->total_harga = $this->total_harga ?? 0;
        $this->status_pesanan = $this->status_pesanan ?? 'pending';
        $this->catatan = htmlspecialchars(strip_tags($this->catatan ?? ''));
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Bind values
        $stmt->bindParam(":nama", $this->nama);
        $stmt->bindParam(":jumlah", $this->jumlah);
        $stmt->bindParam(":alamat", $this->alamat);
        $stmt->bindParam(":nama_produk", $this->nama_produk);
        $stmt->bindParam(":total_harga", $this->total_harga);
        $stmt->bindParam(":status_pesanan", $this->status_pesanan);
        $stmt->bindParam(":catatan", $this->catatan);
        $stmt->bindParam(":id", $this->id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Delete order
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(':id', $this->id);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>