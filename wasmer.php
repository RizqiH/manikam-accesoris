<?php
if (isset($_GET['action']) && $_GET['action'] == 'liveconfig') {
    // Menampilkan konfigurasi atau data yang dibutuhkan
    echo "Live configuration data.";
} else {
    // Jika action tidak valid atau file tidak ditemukan
    echo "File or action not found.";
}
?>
