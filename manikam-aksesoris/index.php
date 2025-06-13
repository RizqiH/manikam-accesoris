<?php
session_start();

// Proteksi halaman - hanya user yang sudah login yang bisa mengakses
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Manikam Aksesoris</title>
    <link rel="stylesheet" href="css/style.css" />
    <script src="https://unpkg.com/feather-icons"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
      rel="stylesheet"
    />
  </head>
  <body>
    <!-- Navbar Start -->
    <nav class="navbar">
      <div class="navbar-container">
        <!-- Logo Section -->
        <div class="navbar-brand">
          <a href="index.php" class="navbar-logo">
            <div class="logo-main">UMKM</div>
            <div class="logo-sub">Manikam <span class="logo-accent">Aksesoris</span>.</div>
          </a>
        </div>

        <!-- Navigation Links -->
        <div class="navbar-nav">
          <a href="index.php" class="nav-link active">Home</a>
          <a href="menu.php" class="nav-link">Menu</a>
          <a href="testimoni.php" class="nav-link">Testimoni</a>
          <a href="kontak.php" class="nav-link">Kontak</a>
        </div>

        <!-- Extra Actions -->
        <div class="navbar-extra">
          <!-- Cart Icon -->
          <a href="shopping.php" class="cart-icon">
            <i data-feather="shopping-cart"></i>
            <span class="cart-badge">0</span>
          </a>

          <!-- User Info or Login Button -->
          <?php if (isset($_SESSION['user_id'])): ?>
            <div class="user-info">
              <div class="user-avatar"><?php echo strtoupper(substr($_SESSION['username'], 0, 1)); ?></div>
              <div class="user-details">
                <span class="welcome-text">Halo, <?php echo htmlspecialchars($_SESSION['username']); ?>!</span>
                <a href="logout.php" class="logout-btn">
                  <i data-feather="log-out"></i>
                  Logout
                </a>
              </div>
            </div>
          <?php else: ?>
            <a href="../index.php" class="login-button">
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
    <!-- Navbar End -->

    <!-- Hero Section Start -->
    <section class="hero">
      <div class="content">
        <h1>
          Karya tangan lokal,<br />
          <span>Gaya internasional</span><br />
        </h1>
        <p>
          Sebagai UMKM lokal yang berasal dari Sidoarjo, kami bangga
          mempersembahkan aksesoris handmade berkualitas yang mengangkat
          kearifan lokal dengan sentuhan gaya masa kini. Setiap karya adalah
          hasil dedikasi pengrajin kami, membawa semangat Indonesia ke panggung
          internasional.
        </p>
        <a href="menu.php" class="cta">Beli Sekarang</a>
      </div>
    </section>
    <!-- Hero Section End -->

    <!-- Footer start -->
    <footer>
      <div class="credit">
        <p>
          Created by <a href="#">M.Lutfi073_AntoninaS310</a>. | &copy; 2025.
        </p>
      </div>
    </footer>

    <script>
      feather.replace();
    </script>
    <script src="js/script.js"></script>
  </body>
</html>