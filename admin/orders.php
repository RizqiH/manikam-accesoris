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
  <title>Manikam Aksesoris | Orders Management</title>

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
            <a href="orders.php" class="nav-link active">
              <i class="nav-icon fas fa-shopping-cart"></i>
              <p>Orders</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="payments.php" class="nav-link">
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
            <h1 class="m-0">Orders Management</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
              <li class="breadcrumb-item active">Orders</li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Customer Orders</h3>
                <div class="card-tools">
                  <button type="button" class="btn btn-success" onclick="loadOrders()">
                    <i class="fas fa-sync-alt"></i> Refresh
                  </button>
                </div>
              </div>
              <div class="card-body">
                <table id="ordersTable" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>Order ID</th>
                    <th>Customer Name</th>
                    <th>Quantity</th>
                    <th>Address</th>
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

<!-- View Order Details Modal -->
<div class="modal fade" id="orderDetailsModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Order Details</h4>
        <button type="button" class="close" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-6">
            <h5>Customer Information</h5>
            <p><strong>Name:</strong> <span id="customerName"></span></p>
            <p><strong>Address:</strong> <span id="customerAddress"></span></p>
          </div>
          <div class="col-md-6">
            <h5>Order Information</h5>
            <p><strong>Order ID:</strong> <span id="orderId"></span></p>
            <p><strong>Quantity:</strong> <span id="orderQuantity"></span></p>
          </div>
        </div>
        <hr>
        <div class="row">
          <div class="col-12">
            <h5>Actions</h5>
            <button class="btn btn-success" onclick="processOrder()">Mark as Processed</button>
            <button class="btn btn-info" onclick="createPayment()">Create Payment Record</button>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Create Payment Modal -->
<div class="modal fade" id="createPaymentModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Create Payment Record</h4>
        <button type="button" class="close" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>
      <form id="createPaymentForm">
        <div class="modal-body">
          <input type="hidden" id="paymentOrderId">
          <div class="form-group">
            <label for="paymentProductName">Product Name</label>
            <input type="text" class="form-control" id="paymentProductName" required>
          </div>
          <div class="form-group">
            <label for="paymentAmount">Payment Amount (Rp)</label>
            <input type="number" class="form-control" id="paymentAmount" required>
          </div>
          <div class="form-group">
            <label for="paymentDate">Payment Date</label>
            <input type="date" class="form-control" id="paymentDate" required>
          </div>
          <div class="form-group">
            <label for="paymentStatus">Status</label>
            <select class="form-control" id="paymentStatus" required>
              <option value="LUNAS">LUNAS</option>
              <option value="PENDING">PENDING</option>
              <option value="CANCELLED">CANCELLED</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Create Payment</button>
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
let currentOrderData = null;

$(document).ready(function() {
    loadOrders();
    
    // Set today's date as default
    $('#paymentDate').val(new Date().toISOString().split('T')[0]);
    
    // Create payment form submission
    $('#createPaymentForm').on('submit', function(e) {
        e.preventDefault();
        submitPayment();
    });
});

function loadOrders() {
    $.get('../backend/api/orders/read.php', function(data) {
        const table = $('#ordersTable').DataTable({
            destroy: true,
            data: data.records || [],
            columns: [
                { data: 'id' },
                { data: 'nama' },
                { data: 'jumlah' },
                { 
                    data: 'alamat',
                    render: function(data) {
                        return data.length > 50 ? data.substring(0, 50) + '...' : data;
                    }
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        return `
                            <button class="btn btn-sm btn-info" onclick="viewOrderDetails(${row.id}, '${row.nama}', ${row.jumlah}, '${row.alamat.replace(/'/g, "\\'")}', '${row.nama_produk || ''}', ${row.total_harga || 0})">  
                                <i class="fas fa-eye"></i> View
                            </button>
                            <button class="btn btn-sm btn-danger" onclick="deleteOrder(${row.id})">
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
        $('#ordersTable').DataTable({
            destroy: true,
            data: [],
            columns: [
                { data: 'id' },
                { data: 'nama' },
                { data: 'jumlah' },
                { data: 'alamat' },
                { data: null }
            ]
        });
    });
}

function viewOrderDetails(id, name, quantity, address, productName, totalPrice) {
    console.log('Order Details:', { id, name, quantity, address, productName, totalPrice });
    currentOrderData = { id, name, quantity, address, productName, totalPrice };
    
    $('#orderId').text(id);
    $('#customerName').text(name);
    $('#customerAddress').text(address);
    $('#orderQuantity').text(quantity);
    
    $('#orderDetailsModal').modal('show');
}

function processOrder() {
    Swal.fire({
        title: 'Process Order',
        text: 'Mark this order as processed?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, process it!'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire(
                'Processed!',
                'Order has been marked as processed.',
                'success'
            );
            $('#orderDetailsModal').modal('hide');
        }
    });
}

function createPayment() {
    if (currentOrderData) {
        console.log('Current Order Data:', currentOrderData);
        console.log('Product Name:', currentOrderData.productName);
        console.log('Total Price:', currentOrderData.totalPrice);
        
        $('#paymentOrderId').val(currentOrderData.id);
        $('#paymentProductName').val(currentOrderData.productName || ''); // Auto-populate product name
        $('#paymentAmount').val(currentOrderData.totalPrice || ''); // Auto-populate price
        $('#orderDetailsModal').modal('hide');
        $('#createPaymentModal').modal('show');
    }
}

function submitPayment() {
    const paymentData = {
        nama_produk: $('#paymentProductName').val(),
        pembayaran: $('#paymentAmount').val(),
        tgl_bayar: $('#paymentDate').val(),
        keterangan: $('#paymentStatus').val()
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
                text: 'Payment record created successfully'
            });
            $('#createPaymentModal').modal('hide');
            $('#createPaymentForm')[0].reset();
            $('#paymentDate').val(new Date().toISOString().split('T')[0]);
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

function deleteOrder(id) {
    console.log('Delete order called with ID:', id);
    
    // Check if SweetAlert2 is loaded
    if (typeof Swal === 'undefined') {
        alert('SweetAlert2 library not loaded!');
        return;
    }
    
    // Convert ID to integer to ensure proper type
    id = parseInt(id);
    console.log('Converted ID to integer:', id);
    
    console.log('About to show SweetAlert confirmation dialog');
    
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Data yang dihapus tidak dapat dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        console.log('SweetAlert result:', result);
        if (result.isConfirmed) {
            console.log('User confirmed deletion, sending AJAX request');
            
            $.ajax({
                url: '../backend/api/orders/delete.php',
                type: 'DELETE',
                contentType: 'application/json',
                data: JSON.stringify({id: id}),
                beforeSend: function() {
                    console.log('Sending DELETE request with data:', {id: id});
                },
                success: function(response) {
                    console.log('Delete success response:', response);
                    Swal.fire(
                        'Berhasil!',
                        'Order telah dihapus.',
                        'success'
                    );
                    loadOrders();
                },
                error: function(xhr) {
                    console.log('Delete error - Status:', xhr.status);
                    console.log('Delete error - Response:', xhr.responseText);
                    console.log('Delete error - Status Text:', xhr.statusText);
                    
                    let errorMessage = 'An error occurred';
                    try {
                        const response = JSON.parse(xhr.responseText);
                        errorMessage = response.message || 'An error occurred';
                    } catch (e) {
                        // If response is not JSON, use status text or default message
                        if (xhr.status === 0) {
                            errorMessage = 'Network error - Unable to connect to server';
                        } else {
                            errorMessage = xhr.statusText || 'Server error occurred (Status: ' + xhr.status + ')';
                        }
                    }
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: errorMessage
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