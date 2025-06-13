<?php
require_once __DIR__ . '/../config/database.php';

class Payment {
    private $conn;
    private $table_name = "tb_pembayaran";

    public $id;
    public $nama_produk;
    public $pembayaran;
    public $tgl_bayar;
    public $keterangan;
    public $metode_pembayaran;
    public $status_pembayaran;
    public $nomor_referensi;
    public $id_pemesanan;
    public $created_at;
    public $updated_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Create payment
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " SET nama_produk=:nama_produk, pembayaran=:pembayaran, tgl_bayar=:tgl_bayar, keterangan=:keterangan, metode_pembayaran=:metode_pembayaran, status_pembayaran=:status_pembayaran, nomor_referensi=:nomor_referensi, id_pemesanan=:id_pemesanan";
        $stmt = $this->conn->prepare($query);

        // Sanitize
        $this->nama_produk = htmlspecialchars(strip_tags($this->nama_produk));
        $this->pembayaran = htmlspecialchars(strip_tags($this->pembayaran));
        $this->tgl_bayar = htmlspecialchars(strip_tags($this->tgl_bayar));
        $this->keterangan = htmlspecialchars(strip_tags($this->keterangan ?? ''));
        $this->metode_pembayaran = $this->metode_pembayaran ?? 'transfer';
        $this->status_pembayaran = $this->status_pembayaran ?? 'pending';
        $this->nomor_referensi = htmlspecialchars(strip_tags($this->nomor_referensi ?? ''));
        $this->id_pemesanan = $this->id_pemesanan ?? null;

        // Bind values
        $stmt->bindParam(":nama_produk", $this->nama_produk);
        $stmt->bindParam(":pembayaran", $this->pembayaran);
        $stmt->bindParam(":tgl_bayar", $this->tgl_bayar);
        $stmt->bindParam(":keterangan", $this->keterangan);
        $stmt->bindParam(":metode_pembayaran", $this->metode_pembayaran);
        $stmt->bindParam(":status_pembayaran", $this->status_pembayaran);
        $stmt->bindParam(":nomor_referensi", $this->nomor_referensi);
        $stmt->bindParam(":id_pemesanan", $this->id_pemesanan);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Read all payments
    public function read() {
        $query = "SELECT id, nama_produk, pembayaran, tgl_bayar, keterangan, metode_pembayaran, status_pembayaran, nomor_referensi, id_pemesanan, created_at, updated_at FROM " . $this->table_name . " ORDER BY id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Read single payment
    public function readOne() {
        $query = "SELECT id, nama_produk, pembayaran, tgl_bayar, keterangan, metode_pembayaran, status_pembayaran, nomor_referensi, id_pemesanan, created_at, updated_at FROM " . $this->table_name . " WHERE id = :id LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row) {
            $this->nama_produk = $row['nama_produk'];
            $this->pembayaran = $row['pembayaran'];
            $this->tgl_bayar = $row['tgl_bayar'];
            $this->keterangan = $row['keterangan'];
            $this->metode_pembayaran = $row['metode_pembayaran'];
            $this->status_pembayaran = $row['status_pembayaran'];
            $this->nomor_referensi = $row['nomor_referensi'];
            $this->id_pemesanan = $row['id_pemesanan'];
            $this->created_at = $row['created_at'];
            $this->updated_at = $row['updated_at'];
            return true;
        }
        return false;
    }

    // Update payment
    public function update() {
        $query = "UPDATE " . $this->table_name . " SET nama_produk=:nama_produk, pembayaran=:pembayaran, tgl_bayar=:tgl_bayar, keterangan=:keterangan, metode_pembayaran=:metode_pembayaran, status_pembayaran=:status_pembayaran, nomor_referensi=:nomor_referensi, id_pemesanan=:id_pemesanan WHERE id=:id";
        $stmt = $this->conn->prepare($query);

        // Sanitize
        $this->nama_produk = htmlspecialchars(strip_tags($this->nama_produk));
        $this->pembayaran = htmlspecialchars(strip_tags($this->pembayaran));
        $this->tgl_bayar = htmlspecialchars(strip_tags($this->tgl_bayar));
        $this->keterangan = htmlspecialchars(strip_tags($this->keterangan ?? ''));
        $this->metode_pembayaran = $this->metode_pembayaran ?? 'transfer';
        $this->status_pembayaran = $this->status_pembayaran ?? 'pending';
        $this->nomor_referensi = htmlspecialchars(strip_tags($this->nomor_referensi ?? ''));
        $this->id_pemesanan = $this->id_pemesanan ?? null;
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Bind values
        $stmt->bindParam(":nama_produk", $this->nama_produk);
        $stmt->bindParam(":pembayaran", $this->pembayaran);
        $stmt->bindParam(":tgl_bayar", $this->tgl_bayar);
        $stmt->bindParam(":keterangan", $this->keterangan);
        $stmt->bindParam(":metode_pembayaran", $this->metode_pembayaran);
        $stmt->bindParam(":status_pembayaran", $this->status_pembayaran);
        $stmt->bindParam(":nomor_referensi", $this->nomor_referensi);
        $stmt->bindParam(":id_pemesanan", $this->id_pemesanan);
        $stmt->bindParam(":id", $this->id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Delete payment
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

    // Get payment statistics
    public function getStats() {
        $query = "SELECT 
                    COUNT(*) as total_transactions,
                    SUM(pembayaran) as total_revenue,
                    AVG(pembayaran) as avg_payment
                  FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>