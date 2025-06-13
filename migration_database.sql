-- Database: `db_fpp`
-- Fixed version to handle existing tables and privilege issues

-- Drop existing tables if they exist (in correct order due to foreign keys)
SET FOREIGN_KEY_CHECKS = 0;

-- Drop views first
DROP VIEW IF EXISTS `v_order_stats`;
DROP VIEW IF EXISTS `v_payment_stats`;
DROP VIEW IF EXISTS `v_product_stats`;

-- Drop triggers
DROP TRIGGER IF EXISTS `tr_update_stock_after_order`;

-- Drop tables
DROP TABLE IF EXISTS `tb_pembayaran`;
DROP TABLE IF EXISTS `tb_pemesanan`;
DROP TABLE IF EXISTS `tb_kategori`;
DROP TABLE IF EXISTS `tb_produk`;

SET FOREIGN_KEY_CHECKS = 1;

-- --------------------------------------------------------

--
-- Table structure for table `tb_kategori`
--

CREATE TABLE `tb_kategori` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama_kategori` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `status` enum('aktif','nonaktif') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'aktif',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nama_kategori` (`nama_kategori`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_produk` (missing from original but referenced)
--

CREATE TABLE `tb_produk` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama_produk` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `harga` decimal(10,2) NOT NULL,
  `stok` int NOT NULL DEFAULT 0,
  `status` enum('aktif','nonaktif') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'aktif',
  `id_kategori` int DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_nama_produk` (`nama_produk`),
  KEY `idx_status` (`status`),
  KEY `fk_produk_kategori` (`id_kategori`),
  CONSTRAINT `fk_produk_kategori` FOREIGN KEY (`id_kategori`) REFERENCES `tb_kategori` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_pemesanan`
--

CREATE TABLE `tb_pemesanan` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jumlah` int NOT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_produk` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_harga` decimal(10,2) DEFAULT NULL,
  `status_pesanan` enum('pending','diproses','selesai','dibatalkan') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `tanggal_pesan` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `tanggal_selesai` timestamp NULL DEFAULT NULL,
  `catatan` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_status` (`status_pesanan`),
  KEY `idx_tanggal` (`tanggal_pesan`),
  KEY `idx_pemesanan_nama` (`nama`),
  KEY `idx_nama_produk` (`nama_produk`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_pembayaran`
--

CREATE TABLE `tb_pembayaran` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama_produk` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pembayaran` decimal(10,2) NOT NULL,
  `tgl_bayar` date NOT NULL,
  `keterangan` text COLLATE utf8mb4_unicode_ci,
  `metode_pembayaran` enum('cash','transfer','kartu_kredit','e_wallet') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'transfer',
  `status_pembayaran` enum('pending','berhasil','gagal','refund') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `nomor_referensi` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_pemesanan` int DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_status` (`status_pembayaran`),
  KEY `idx_tanggal` (`tgl_bayar`),
  KEY `idx_pemesanan` (`id_pemesanan`),
  KEY `idx_pembayaran_metode` (`metode_pembayaran`),
  CONSTRAINT `fk_pembayaran_pemesanan` FOREIGN KEY (`id_pemesanan`) REFERENCES `tb_pemesanan` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Trigger for updating stock after order completion
-- Note: This trigger requires SUPER privilege. If you don't have it, you can skip this or ask your database administrator to create it.
--

DELIMITER $$
CREATE TRIGGER `tr_update_stock_after_order` 
AFTER UPDATE ON `tb_pemesanan` 
FOR EACH ROW 
BEGIN
    IF NEW.status_pesanan = 'selesai' AND OLD.status_pesanan != 'selesai' THEN
        UPDATE tb_produk 
        SET stok = stok - NEW.jumlah 
        WHERE nama_produk = NEW.nama_produk AND stok >= NEW.jumlah;
    END IF;
END$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Views for statistics
-- Note: These views require SUPER privilege. If you don't have it, you can skip these or ask your database administrator to create them.
--

-- Order Statistics View
CREATE VIEW `v_order_stats` AS 
SELECT 
    COUNT(*) AS `total_orders`,
    COUNT(CASE WHEN `status_pesanan` = 'pending' THEN 1 END) AS `pending_orders`,
    COUNT(CASE WHEN `status_pesanan` = 'diproses' THEN 1 END) AS `processing_orders`,
    COUNT(CASE WHEN `status_pesanan` = 'selesai' THEN 1 END) AS `completed_orders`,
    COUNT(CASE WHEN `status_pesanan` = 'dibatalkan' THEN 1 END) AS `cancelled_orders`,
    SUM(`total_harga`) AS `total_revenue`
FROM `tb_pemesanan`;

-- Payment Statistics View
CREATE VIEW `v_payment_stats` AS 
SELECT 
    COUNT(*) AS `total_payments`,
    COUNT(CASE WHEN `status_pembayaran` = 'berhasil' THEN 1 END) AS `successful_payments`,
    COUNT(CASE WHEN `status_pembayaran` = 'pending' THEN 1 END) AS `pending_payments`,
    COUNT(CASE WHEN `status_pembayaran` = 'gagal' THEN 1 END) AS `failed_payments`,
    SUM(CASE WHEN `status_pembayaran` = 'berhasil' THEN `pembayaran` ELSE 0 END) AS `total_revenue`,
    AVG(CASE WHEN `status_pembayaran` = 'berhasil' THEN `pembayaran` ELSE NULL END) AS `average_payment`
FROM `tb_pembayaran`;

-- Product Statistics View
CREATE VIEW `v_product_stats` AS 
SELECT 
    COUNT(*) AS `total_products`,
    COUNT(CASE WHEN `status` = 'aktif' THEN 1 END) AS `active_products`,
    COUNT(CASE WHEN `status` = 'nonaktif' THEN 1 END) AS `inactive_products`,
    SUM(`stok`) AS `total_stock`,
    AVG(`harga`) AS `average_price`
FROM `tb_produk`;

-- --------------------------------------------------------

--
-- Sample data insertion (optional)
--

-- Insert sample categories
INSERT INTO `tb_kategori` (`nama_kategori`, `deskripsi`, `status`) VALUES
('Elektronik', 'Produk elektronik dan gadget', 'aktif'),
('Pakaian', 'Pakaian pria dan wanita', 'aktif'),
('Makanan', 'Makanan dan minuman', 'aktif');

-- Insert sample products
INSERT INTO `tb_produk` (`nama_produk`, `deskripsi`, `harga`, `stok`, `status`, `id_kategori`) VALUES
('Smartphone ABC', 'Smartphone dengan fitur terbaru', 2500000.00, 50, 'aktif', 1),
('Kemeja Putih', 'Kemeja putih berbahan katun', 150000.00, 100, 'aktif', 2),
('Kopi Arabica', 'Kopi arabica premium', 75000.00, 200, 'aktif', 3);

-- Note: If you encounter privilege errors for triggers and views, you have two options:
-- 1. Ask your database administrator to run those parts with SUPER privilege
-- 2. Skip the triggers and views - the basic tables will still work fine
-- 
-- To skip triggers and views, simply don't run those sections or comment them out.