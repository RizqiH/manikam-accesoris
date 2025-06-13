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
    <title>Kontak - Manikam Aksesoris</title>
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
          <a href="index.php" class="nav-link">Home</a>
          <a href="menu.php" class="nav-link">Menu</a>
          <a href="testimoni.php" class="nav-link">Testimoni</a>
          <a href="kontak.php" class="nav-link active">Kontak</a>
        </div>

        <!-- Extra Actions -->
        <div class="navbar-extra">
          <!-- Cart Icon -->
          <a href="shopping.php" class="cart-icon">
            <i data-feather="shopping-cart"></i>
            <span class="cart-badge">0</span>
          </a>

          <!-- User Info (karena sudah login pasti ada session) -->
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

          <!-- Hamburger Menu -->
          <a href="#" class="hamburger-menu" id="hamburger-menu">
            <i data-feather="menu"></i>
          </a>
        </div>
      </div>
    </nav>
    <!-- Navbar End -->

    <!-- Contact Section Start -->
    <section class="contact">
      <h2><span>Kontak</span> Kami</h2>
      <p>
        Ada pertanyaan atau ingin tahu lebih banyak? Hubungi kami, dan kami siap
        membantu dengan senang hati!
      </p>
      <div class="row">
        <iframe
          src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3957.1795614693174!2d112.78832469999999!3d-7.333721200000005!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd7fab87edcad15%3A0xb26589947991eea1!2sUniversitas%20Pembangunan%20Nasional%20%E2%80%9CVeteran%E2%80%9D%20Jawa%20Timur!5e0!3m2!1sen!2sid!4v1744522905692!5m2!1sen!2sid"
          allowfullscreen=""
          loading="lazy"
          referrerpolicy="no-referrer-when-downgrade"
          class="map"
        ></iframe>

        <form action="#" method="POST">
          <div class="input-group">
            <i data-feather="user"></i>
            <input type="text" name="nama" placeholder="Nama Lengkap" required />
          </div>
          <div class="input-group">
            <i data-feather="mail"></i>
            <input type="email" name="email" placeholder="E-mail" required />
          </div>
          <div class="input-group">
            <i data-feather="phone"></i>
            <input type="tel" name="phone" placeholder="No HP" required />
          </div>
          <div class="input-group">
            <i data-feather="message-square"></i>
            <textarea name="pesan" placeholder="Tulis pesan Anda..." rows="5" style="resize: vertical; font-family: inherit; font-size: inherit; color: inherit; background: none; border: none; outline: none; width: 100%; padding: 2rem;"></textarea>
          </div>
          <button type="submit" class="btn">Kirim Pesan</button>
        </form>
      </div>
    </section>
    <!-- Contact Section End -->

    <!-- Footer Start -->
    <footer>
      <div class="credit">
        <p>
          Created by <a href="#">M.Lutfi073_AntoninaS310</a>. | &copy; 2025.
        </p>
      </div>
    </footer>
    <!-- Footer End -->

    <script>
      feather.replace();
    </script>
    <script src="js/script.js"></script>
  </body>
</html>