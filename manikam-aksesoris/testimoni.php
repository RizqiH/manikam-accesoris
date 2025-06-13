<?php
session_start();

// Proteksi halaman - hanya user yang sudah login yang bisa mengakses
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Testimoni - Manikam Aksesoris</title>
    <script src="https://unpkg.com/feather-icons"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Open+Sans&family=Poppins:wght@400;600;700&display=swap"
      rel="stylesheet"
    />
    <!-- Style -->
    <link rel="stylesheet" href="css/style.css" />
  </head>
  <body>
    <!-- Navbar -->
    <nav class="navbar">
      <div class="navbar-container">
        <!-- Logo Section -->
        <div class="navbar-brand">
          <a href="index.php" class="navbar-logo">
            <div class="logo-main">UMKM<br/>Manikam</div>
            <div class="logo-sub"><span class="logo-accent">Aksesoris</span>.</div>
          </a>
        </div>

        <!-- Navigation Links -->
        <div class="navbar-nav">
          <a href="index.php" class="nav-link">Home</a>
          <a href="menu.php" class="nav-link">Menu</a>
          <a href="testimoni.php" class="nav-link active">Testimoni</a>
          <a href="kontak.php" class="nav-link">Kontak</a>
        </div>

        <!-- Extra Actions -->
        <div class="navbar-extra">
          <a href="shopping.php" class="cart-icon">
            <i data-feather="shopping-cart"></i>
            <span class="cart-badge">0</span>
          </a>
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
            <a href="login.php" class="login-button">
              <i data-feather="user"></i>
              Login
            </a>
          <?php endif; ?>
          <a href="#" class="hamburger-menu" id="hamburger-menu">
            <i data-feather="menu"></i>
          </a>
        </div>
      </div>
    </nav>

    <!-- Testimoni Section -->
    <section class="testimoni">
      <h2><span>Berikan</span> Ulasan Anda</h2>
      <p>Ulasan anda akan ditampilkan dalam bentuk Tabel Data Testimoni!</p>
      
      <form id="ulasanForm">
        <div class="input-group">
          <i data-feather="user"></i>
          <input type="text" id="nama" name="nama" placeholder="Nama Anda" required />
        </div>
        
        <div class="input-group">
          <i data-feather="briefcase"></i>
          <input type="text" id="pekerjaan" name="pekerjaan" placeholder="Pekerjaan" required />
        </div>
        
        <div class="input-group">
          <i data-feather="message-square"></i>
          <textarea
            id="pesan"
            name="pesan"
            placeholder="Tulis ulasan Anda..."
            rows="4"
            required
          ></textarea>
        </div>
        
        <button type="submit" class="btn">Kirim Ulasan</button>
      </form>

      <table>
        <thead>
          <tr>
            <th>Nama</th>
            <th>Pekerjaan</th>
            <th>Ulasan</th>
          </tr>
        </thead>
        <tbody id="hasilUlasan">
          <tr>
            <td>Antonina Septi Kristiana</td>
            <td>Mahasiswa</td>
            <td>
              Barangnya bagus dan keren sekali, saya suka menggunakan barang lokal
            </td>
          </tr>
          <tr>
            <td>Muhammad Lutfi</td>
            <td>Mahasiswa</td>
            <td>
              Asli karya lokal gaisss!!!
            </td>
          </tr>
        </tbody>
      </table>
    </section>

    <!-- Footer -->
    <footer>
      <div class="credit">
        <p>
          Created by
          <a href="#" style="color: var(--primary); font-weight: bold;">M.Lutfi073_AntoninaS310</a>. | &copy; 2025.
        </p>
      </div>
    </footer>

    <script>
      feather.replace();
      
      // Mobile menu toggle
      const hamburgerMenu = document.getElementById('hamburger-menu');
      const navbarNav = document.querySelector('.navbar-nav');
      
      hamburgerMenu.addEventListener('click', function(e) {
        e.preventDefault();
        navbarNav.classList.toggle('active');
      });
      
      // Close menu when clicking outside
      document.addEventListener('click', function(e) {
        if (!hamburgerMenu.contains(e.target) && !navbarNav.contains(e.target)) {
          navbarNav.classList.remove('active');
        }
      });
      
      // JavaScript untuk handling form testimoni
      document.getElementById('ulasanForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const nama = document.getElementById('nama').value;
        const pekerjaan = document.getElementById('pekerjaan').value;
        const pesan = document.getElementById('pesan').value;
        
        // Buat row baru
        const tbody = document.getElementById('hasilUlasan');
        const newRow = tbody.insertRow(0); // Insert di posisi pertama
        
        newRow.innerHTML = `
          <td>${nama}</td>
          <td>${pekerjaan}</td>
          <td>${pesan}</td>
        `;
        
        // Reset form
        this.reset();
        
        // Animasi untuk row baru
        newRow.style.backgroundColor = 'var(--primary)';
        newRow.style.color = 'white';
        setTimeout(() => {
          newRow.style.backgroundColor = '';
          newRow.style.color = '';
          newRow.style.transition = 'all 0.5s ease';
        }, 1000);
      });
    </script>
    <script src="js/script.js"></script>
  </body>
</html>