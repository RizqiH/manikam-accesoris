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
    <title>Shopping | Manikam Aksesoris</title>

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
          <a href="menu.php" class="nav-link">Menu</a>
          <a href="testimoni.php" class="nav-link">Testimoni</a>
          <a href="kontak.php" class="nav-link">Kontak</a>
        </div>

        <!-- Extra Actions -->
        <div class="navbar-extra">
          <!-- Cart Icon -->
          <a href="shopping.php" class="cart-icon" id="shopping-cart">
            <i data-feather="shopping-cart"></i>
            <span class="cart-badge">0</span>
          </a>

          <!-- User Info or Login -->
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
        Lorem ipsum dolor sit amet consectetur adipisicing elit. Expedita,
        repellendus?
      </p>
      
      <!-- Shopping Notice -->
      <div class="shopping-notice">
        <i data-feather="shopping-bag"></i>
        <span>SILAHKAN BERBELANJA !!!</span>
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
              <span class="view-details">View Details</span>
            </div>
          </div>
          
          <div class="card-content">
            <h3 class="menu-card-title"><?php echo htmlspecialchars($product_item['nama_produk']); ?></h3>
            <p class="menu-card-price">IDR <?php echo number_format($product_item['harga'], 0, ',', '.'); ?></p>
            
            <form class="form-produk" onsubmit="return kirimPesanan(event)">
              <input type="hidden" name="produk" value="<?php echo htmlspecialchars($product_item['nama_produk']); ?>" />
              <input type="hidden" name="harga" value="<?php echo $product_item['harga']; ?>" />
              <input
                type="text"
                name="nama"
                placeholder="Nama Lengkap"
                required
                style="width: 100%; padding: 0.8rem; margin: 0.5rem 0; border: 1px solid rgba(182, 137, 86, 0.3); border-radius: 8px; background: rgba(255, 255, 255, 0.05); color: #fff; font-family: 'Poppins', sans-serif;"
              />
              <input
                type="number"
                name="jumlah"
                placeholder="Jumlah"
                min="1"
                required
                style="width: 100%; padding: 0.8rem; margin: 0.5rem 0; border: 1px solid rgba(182, 137, 86, 0.3); border-radius: 8px; background: rgba(255, 255, 255, 0.05); color: #fff; font-family: 'Poppins', sans-serif;"
              />
              <textarea
                name="alamat"
                rows="2"
                placeholder="Alamat Pengiriman"
                required
                style="width: 100%; padding: 0.8rem; margin: 0.5rem 0; border: 1px solid rgba(182, 137, 86, 0.3); border-radius: 8px; background: rgba(255, 255, 255, 0.05); color: #fff; font-family: 'Poppins', sans-serif; resize: vertical;"
              ></textarea>
              
              <div class="card-actions">
                <button type="submit" class="btn-add-cart">
                  <i data-feather="shopping-cart"></i>
                  Beli Sekarang
                </button>
              </div>
            </form>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </section>

    <!-- Tabel Pesanan -->
    <section class="order-table" style="padding: 4rem 7% 2rem; background: linear-gradient(135deg, rgba(1, 1, 1, 0.9) 0%, rgba(30, 30, 30, 0.9) 100%);">
      <h2 style="text-align: center; font-size: 2.6rem; margin-bottom: 3rem; color: #fff;">
        <span style="color: var(--primary);">Daftar</span> Pesanan
      </h2>
      <div style="max-width: 1200px; margin: 0 auto; overflow-x: auto;">
        <table id="orderTable" style="width: 100%; border-collapse: collapse; background: linear-gradient(145deg, rgba(255, 255, 255, 0.08), rgba(255, 255, 255, 0.02)); border-radius: 15px; overflow: hidden; backdrop-filter: blur(10px); border: 1px solid rgba(182, 137, 86, 0.2); box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);">
          <thead style="background: linear-gradient(135deg, var(--primary), #d4af37);">
            <tr>
              <th style="padding: 1.5rem 1rem; font-size: 1.1rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #fff; border-bottom: 2px solid rgba(255, 255, 255, 0.2);">Nama</th>
              <th style="padding: 1.5rem 1rem; font-size: 1.1rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #fff; border-bottom: 2px solid rgba(255, 255, 255, 0.2);">Produk</th>
              <th style="padding: 1.5rem 1rem; font-size: 1.1rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #fff; border-bottom: 2px solid rgba(255, 255, 255, 0.2);">Jumlah</th>
              <th style="padding: 1.5rem 1rem; font-size: 1.1rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #fff; border-bottom: 2px solid rgba(255, 255, 255, 0.2);">Total Harga</th>
            </tr>
          </thead>
          <tbody>
            <!-- Data pesanan akan ditambahkan di sini -->
          </tbody>
        </table>
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
      
      // Mobile menu toggle
      const hamburgerMenu = document.getElementById('hamburger-menu');
      const navbarNav = document.querySelector('.navbar-nav');
      
      hamburgerMenu.addEventListener('click', function(e) {
        e.preventDefault();
        navbarNav.classList.toggle('active');
      });
      
      // Close mobile menu when clicking outside
      document.addEventListener('click', function(e) {
        if (!hamburgerMenu.contains(e.target) && !navbarNav.contains(e.target)) {
          navbarNav.classList.remove('active');
        }
      });
    </script>
    <script src="js/script.js"></script>
    
    <script>
      function kirimPesanan(event) {
        event.preventDefault();
        
        const form = event.target;
        const produk = form.produk.value;
        const harga = parseFloat(form.harga.value);
        const nama = form.nama.value;
        const jumlah = parseInt(form.jumlah.value);
        const alamat = form.alamat.value;
        
        const totalHarga = harga * jumlah;
        
        const orderData = {
          nama: nama,
          jumlah: jumlah,
          alamat: alamat,
          nama_produk: produk,
          total_harga: totalHarga,
          status_pesanan: 'pending',
          catatan: ''
        };
        
        fetch('../backend/api/orders/create.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify(orderData)
        })
        .then(response => response.json())
        .then(data => {
          if (data.message) {
            alert('Pesanan berhasil dikirim!');
            
            // Tambahkan ke tabel pesanan
            const table = document.getElementById('orderTable').getElementsByTagName('tbody')[0];
            const newRow = table.insertRow();
            newRow.innerHTML = `
              <td style="padding: 1.2rem 1rem; color: rgba(255, 255, 255, 0.9); border-bottom: 1px solid rgba(182, 137, 86, 0.1); font-size: 0.95rem; line-height: 1.6; vertical-align: top;">${nama}</td>
              <td style="padding: 1.2rem 1rem; color: rgba(255, 255, 255, 0.9); border-bottom: 1px solid rgba(182, 137, 86, 0.1); font-size: 0.95rem; line-height: 1.6; vertical-align: top;">${produk}</td>
              <td style="padding: 1.2rem 1rem; color: rgba(255, 255, 255, 0.9); border-bottom: 1px solid rgba(182, 137, 86, 0.1); font-size: 0.95rem; line-height: 1.6; vertical-align: top;">${jumlah}</td>
              <td style="padding: 1.2rem 1rem; color: rgba(255, 255, 255, 0.9); border-bottom: 1px solid rgba(182, 137, 86, 0.1); font-size: 0.95rem; line-height: 1.6; vertical-align: top;">IDR ${totalHarga.toLocaleString('id-ID')}</td>
            `;
            
            // Add hover effect to new row
            newRow.style.transition = 'all 0.3s ease';
            newRow.addEventListener('mouseenter', function() {
              this.style.background = 'rgba(182, 137, 86, 0.1)';
              this.style.transform = 'scale(1.01)';
            });
            newRow.addEventListener('mouseleave', function() {
              this.style.background = 'transparent';
              this.style.transform = 'scale(1)';
            });
            
            form.reset();
            
            // Update cart badge
            const cartBadge = document.querySelector('.cart-badge');
            if (cartBadge) {
              let currentCount = parseInt(cartBadge.textContent) || 0;
              cartBadge.textContent = currentCount + 1;
            }
          } else {
            alert('Terjadi kesalahan saat mengirim pesanan.');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          alert('Terjadi kesalahan saat mengirim pesanan.');
        });
        
        return false;
      }
    </script>
  </body>
</html>