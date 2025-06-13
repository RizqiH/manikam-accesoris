<?php
session_start();

// Proteksi halaman - hanya user yang sudah login yang bisa mengakses
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit();
}

include_once '../backend/config/database.php';
include_once '../backend/models/Product.php';

$database = new Database();
$db = $database->getConnection();

$product = new Product($db);
$stmt = $product->read();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Menu | Manikam Aksesoris</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,300;0,400;0,700;1,700&display=swap"
      rel="stylesheet"
    />

    <!-- Feather Icons -->
    <script src="https://unpkg.com/feather-icons"></script>

    <!-- My Style -->
    <link rel="stylesheet" href="css/style.css" />
  </head>

  <body>
    <!-- Navbar start -->
    <nav class="navbar">
      <div class="navbar-container">
        <!-- Logo Section -->
        <div class="navbar-brand">
          <a href="index.php" class="navbar-logo">
            <div class="logo-main">UMKM</div>
            <div class="logo-sub">Manikam<span class="logo-accent">Aksesoris</span>.</div>
          </a>
        </div>

        <!-- Navigation Links -->
        <div class="navbar-nav">
          <a href="index.php" class="nav-link">Home</a>
          <a href="menu.php" class="nav-link active">Menu</a>
          <a href="testimoni.php" class="nav-link">Testimoni</a>
          <a href="kontak.php" class="nav-link">Kontak</a>
        </div>

        <!-- Extra Actions -->
        <div class="navbar-extra">
          <!-- Shopping Cart -->
          <a href="shopping.php" class="cart-icon">
            <i data-feather="shopping-cart"></i>
            <span class="cart-badge">0</span>
          </a>

          <!-- User Info or Login Button -->
          <?php if (isset($_SESSION['user_id'])): ?>
            <div class="user-info">
              <div class="user-avatar">
                <?php echo strtoupper(substr($_SESSION['username'], 0, 1)); ?>
              </div>
              <div class="user-details">
                <span class="welcome-text">Halo, <?php echo htmlspecialchars($_SESSION['username']); ?>!</span>
                <a href="logout.php" class="logout-btn">
                  <i data-feather="log-out"></i>
                  Logout
                </a>
              </div>
            </div>
          <?php else: ?>
            <a href="login.php" class="login-button">
              <i data-feather="user"></i>
              Login
            </a>
          <?php endif; ?>

          <!-- Hamburger Menu -->
          <a href="#" class="hamburger-menu" id="hamburger-menu">
            <i data-feather="menu"></i>
          </a>
        </div>
      </div>
    </nav>
    <!-- Navbar end -->

    <!-- Menu Section start -->
    <section id="menu" class="menu">
      <h2><span>Menu</span> Kami</h2>
      <p>
        Temukan koleksi aksesoris terbaik kami dengan kualitas premium dan desain yang elegan. 
        Setiap produk dipilih khusus untuk melengkapi gaya Anda.
      </p>
      <div class="shopping-notice">
        <i data-feather="shopping-cart"></i>
        <span>Klik ikon keranjang belanja di atas untuk mulai berbelanja!</span>
      </div>
      <div class="row">
        <?php foreach($products as $product_item): ?>
        <div class="menu-card">
          <div class="card-image-container">
            <img
              src="<?php echo htmlspecialchars($product_item['foto']); ?>"
              alt="<?php echo htmlspecialchars($product_item['nama_produk']); ?>"
              class="menu-card-img"
            />
            <div class="card-overlay">
              <span class="view-details">Lihat Detail</span>
            </div>
          </div>
          <div class="card-content">
            <h3 class="menu-card-title"><?php echo htmlspecialchars($product_item['nama_produk']); ?></h3>
            <p class="menu-card-price">Rp <?php echo number_format($product_item['harga'], 0, ',', '.'); ?></p>
            <div class="card-actions">
              <button class="btn-add-cart" onclick="window.location.href='shopping.php'">
                <i data-feather="shopping-cart"></i>
                Tambah ke Keranjang
              </button>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </section>

    <footer>
      <div class="credit">
        <p>
          Created by <a href="#">M.Lutfi073_AntoninaS310</a>. | &copy; 2025.
        </p>
      </div>
    </footer>

    <script>
      feather.replace();
      
      // Hamburger menu functionality
      const hamburger = document.querySelector('#hamburger-menu');
      const navbarNav = document.querySelector('.navbar-nav');

      hamburger.addEventListener('click', function(e) {
        e.preventDefault();
        navbarNav.classList.toggle('active');
      });

      // Close mobile menu when clicking outside
      document.addEventListener('click', function(e) {
        if (!hamburger.contains(e.target) && !navbarNav.contains(e.target)) {
          navbarNav.classList.remove('active');
        }
      });

      // Close mobile menu when clicking on nav links
      document.querySelectorAll('.nav-link').forEach(link => {
        link.addEventListener('click', function() {
          navbarNav.classList.remove('active');
        });
      });
    </script>
    <script src="js/script.js"></script>
  </body>
</html>