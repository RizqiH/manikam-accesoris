<?php
session_start();

// Check if user is logged in and is admin
if(!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Manikam Aksesoris | Payments Management</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../AdminLTE-3.2.0/plugins/fontawesome-free/css/all.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="../AdminLTE-3.2.0/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="../AdminLTE-3.2.0/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="../AdminLTE-3.2.0/plugins/sweetalert2/sweetalert2.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../AdminLTE-3.2.0/dist/css/adminlte.min.css">
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="dashboard.php" class="nav-link">Home</a>
      </li>
    </ul>
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <a class="nav-link" href="#" onclick="logout()">
          <i class="fas fa-sign-out-alt"></i> Logout
        </a>
      </li>
    </ul>
  </nav>

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="dashboard.php" class="brand-link">
      <img src="../AdminLTE-3.2.0/dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">Manikam Admin</span>
    </a>

    <div class="sidebar">
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="../AdminLTE-3.2.0/dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block"><?php echo $_SESSION['username']; ?></a>
          <small class="text-muted"><?php echo $_SESSION['role']; ?></small>
        </div>
      </div>

      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-item">
            <a href="dashboard.php" class="nav-link">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>Dashboard</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="products.php" class="nav-link">
              <i class="nav-icon fas fa-box"></i>
              <p>Products</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="orders.php" class="nav-link">
              <i class="nav-icon fas fa-shopping-cart"></i>
              <p>Orders</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="payments.php" class="nav-link active">
              <i class="nav-icon fas fa-credit-card"></i>
              <p>Payments</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="../manikam-aksesoris/index.php" class="nav-link" target="_blank">
              <i class="nav-icon fas fa-external-link-alt"></i>
              <p>View Frontend</p>
            </a>
          </li>
        </ul>
      </nav>
    </div>
  </aside>

  <!-- Content Wrapper -->
  <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Payments Management</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
              <li class="breadcrumb-item active">Payments</li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- Statistics Cards -->
        <div class="row">
          <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
              <div class="inner">
                <h3 id="totalRevenue">Rp 0</h3>
                <p>Total Revenue</p>
              </div>
              <div class="icon">
                <i class="fas fa-money-bill-wave"></i>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
              <div class="inner">
                <h3 id="totalPayments">0</h3>
                <p>Total Payments</p>
              </div>
              <div class="icon">
                <i class="fas fa-credit-card"></i>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
              <div class="inner">
                <h3 id="pendingPayments">0</h3>
                <p>Pending Payments</p>
              </div>
              <div class="icon">
                <i class="fas fa-clock"></i>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
              <div class="inner">
                <h3 id="completedPayments">0</h3>
                <p>Completed Payments</p>
              </div>
              <div class="icon">
                <i class="fas fa-check-circle"></i>
              </div>
            </div>
          </div>
        </div>
        
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Payment Records</h3>
                <div class="card-tools">
                  <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addPaymentModal">
                    <i class="fas fa-plus"></i> Add Payment
                  </button>
                  <button type="button" class="btn btn-success" onclick="loadPayments()">
                    <i class="fas fa-sync-alt"></i> Refresh
                  </button>
                </div>
              </div>
              <div class="card-body">
                <table id="paymentsTable" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>ID</th>
                    <th>Product Name</th>
                    <th>Amount</th>
                    <th>Payment Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                  </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

  <footer class="main-footer">
    <strong>Copyright &copy; 2025 <a href="#">Manikam Aksesoris</a>.</strong>
    All rights reserved.
  </footer>
</div>

<!-- Add Payment Modal -->
<div class="modal fade" id="addPaymentModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Add New Payment</h4>
        <button type="button" class="close" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>
      <form id="addPaymentForm">
        <div class="modal-body">
          <div class="form-group">
            <label for="addProductName">Product Name</label>
            <input type="text" class="form-control" id="addProductName" required>
          </div>
          <div class="form-group">
            <label for="addAmount">Payment Amount (Rp)</label>
            <input type="number" class="form-control" id="addAmount" required>
          </div>
          <div class="form-group">
            <label for="addDate">Payment Date</label>
            <input type="date" class="form-control" id="addDate" required>
          </div>
          <div class="form-group">
            <label for="addStatus">Status</label>
            <select class="form-control" id="addStatus" required>
              <option value="LUNAS">LUNAS</option>
              <option value="PENDING">PENDING</option>
              <option value="CANCELLED">CANCELLED</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Add Payment</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit Payment Modal -->
<div class="modal fade" id="editPaymentModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Edit Payment</h4>
        <button type="button" class="close" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>
      <form id="editPaymentForm">
        <div class="modal-body">
          <input type="hidden" id="editPaymentId">
          <div class="form-group">
            <label for="editProductName">Product Name</label>
            <input type="text" class="form-control" id="editProductName" required>
          </div>
          <div class="form-group">
            <label for="editAmount">Payment Amount (Rp)</label>
            <input type="number" class="form-control" id="editAmount" required>
          </div>
          <div class="form-group">
            <label for="editDate">Payment Date</label>
            <input type="date" class="form-control" id="editDate" required>
          </div>
          <div class="form-group">
            <label for="editStatus">Status</label>
            <select class="form-control" id="editStatus" required>
              <option value="LUNAS">LUNAS</option>
              <option value="PENDING">PENDING</option>
              <option value="CANCELLED">CANCELLED</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Update Payment</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- jQuery -->
<script src="../AdminLTE-3.2.0/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../AdminLTE-3.2.0/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- DataTables -->
<script src="../AdminLTE-3.2.0/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../AdminLTE-3.2.0/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="../AdminLTE-3.2.0/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="../AdminLTE-3.2.0/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<!-- AdminLTE App -->
<script src="../AdminLTE-3.2.0/dist/js/adminlte.min.js"></script>
<!-- SweetAlert2 -->
<script src="../AdminLTE-3.2.0/plugins/sweetalert2/sweetalert2.min.js"></script>

<script>
$(document).ready(function() {
    loadPayments();
    loadStatistics();
    
    // Set today's date as default
    $('#addDate').val(new Date().toISOString().split('T')[0]);
    
    // Add payment form submission
    $('#addPaymentForm').on('submit', function(e) {
        e.preventDefault();
        addPayment();
    });
    
    // Edit payment form submission
    $('#editPaymentForm').on('submit', function(e) {
        e.preventDefault();
        updatePayment();
    });
});

function loadPayments() {
    $.get('../backend/api/payments/read.php', function(data) {
        const table = $('#paymentsTable').DataTable({
            destroy: true,
            data: data.records || [],
            columns: [
                { data: 'id' },
                { data: 'nama_produk' },
                { 
                    data: 'pembayaran',
                    render: function(data) {
                        return 'Rp ' + parseInt(data).toLocaleString('id-ID');
                    }
                },
                { 
                    data: 'tgl_bayar',
                    render: function(data) {
                        return new Date(data).toLocaleDateString('id-ID');
                    }
                },
                {
                    data: 'keterangan',
                    render: function(data) {
                        let badgeClass = 'badge-secondary';
                        if (data === 'LUNAS') badgeClass = 'badge-success';
                        else if (data === 'PENDING') badgeClass = 'badge-warning';
                        else if (data === 'CANCELLED') badgeClass = 'badge-danger';
                        return `<span class="badge ${badgeClass}">${data}</span>`;
                    }
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        return `
                            <button class="btn btn-sm btn-warning" onclick="editPayment(${row.id}, '${row.nama_produk}', ${row.pembayaran}, '${row.tgl_bayar}', '${row.keterangan}')">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button class="btn btn-sm btn-danger" onclick="deletePayment(${row.id})">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        `;
                    }
                }
            ],
            responsive: true,
            order: [[0, 'desc']]
        });
    }).fail(function() {
        $('#paymentsTable').DataTable({
            destroy: true,
            data: [],
            columns: [
                { data: 'id' },
                { data: 'nama_produk' },
                { data: 'pembayaran' },
                { data: 'tgl_bayar' },
                { data: 'keterangan' },
                { data: null }
            ]
        });
    });
}

function loadStatistics() {
    $.get('../backend/api/payments/read.php', function(data) {
        if (data.records) {
            const payments = data.records;
            const totalRevenue = payments
                .filter(p => p.keterangan === 'LUNAS')
                .reduce((sum, payment) => sum + parseInt(payment.pembayaran), 0);
            const totalPayments = payments.length;
            const pendingPayments = payments.filter(p => p.keterangan === 'PENDING').length;
            const completedPayments = payments.filter(p => p.keterangan === 'LUNAS').length;
            
            $('#totalRevenue').text('Rp ' + totalRevenue.toLocaleString('id-ID'));
            $('#totalPayments').text(totalPayments);
            $('#pendingPayments').text(pendingPayments);
            $('#completedPayments').text(completedPayments);
        }
    });
}

function addPayment() {
    const paymentData = {
        nama_produk: $('#addProductName').val(),
        pembayaran: $('#addAmount').val(),
        tgl_bayar: $('#addDate').val(),
        keterangan: $('#addStatus').val()
    };
    
    $.ajax({
        url: '../backend/api/payments/create.php',
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify(paymentData),
        success: function(response) {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'Payment added successfully'
            });
            $('#addPaymentModal').modal('hide');
            $('#addPaymentForm')[0].reset();
            $('#addDate').val(new Date().toISOString().split('T')[0]);
            loadPayments();
            loadStatistics();
        },
        error: function(xhr) {
            const response = JSON.parse(xhr.responseText);
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: response.message
            });
        }
    });
}

function editPayment(id, productName, amount, date, status) {
    $('#editPaymentId').val(id);
    $('#editProductName').val(productName);
    $('#editAmount').val(amount);
    $('#editDate').val(date);
    $('#editStatus').val(status);
    $('#editPaymentModal').modal('show');
}

function updatePayment() {
    const paymentData = {
        id: $('#editPaymentId').val(),
        nama_produk: $('#editProductName').val(),
        pembayaran: $('#editAmount').val(),
        tgl_bayar: $('#editDate').val(),
        keterangan: $('#editStatus').val()
    };
    
    $.ajax({
        url: '../backend/api/payments/update.php',
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify(paymentData),
        success: function(response) {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'Payment updated successfully'
            });
            $('#editPaymentModal').modal('hide');
            loadPayments();
            loadStatistics();
        },
        error: function(xhr) {
            const response = JSON.parse(xhr.responseText);
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: response.message
            });
        }
    });
}

function deletePayment(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '../backend/api/payments/delete.php',
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({ id: id }),
                success: function(response) {
                    Swal.fire(
                        'Deleted!',
                        'Payment has been deleted.',
                        'success'
                    );
                    loadPayments();
                    loadStatistics();
                },
                error: function(xhr) {
                    const response = JSON.parse(xhr.responseText);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: response.message
                    });
                }
            });
        }
    });
}

function logout() {
    Swal.fire({
        title: 'Are you sure?',
        text: "You will be logged out of the admin panel",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, logout!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post('../backend/api/auth/logout.php', function() {
                window.location.href = '../index.php';
            });
        }
    });
}
</script>
</body>
</html>