<?php
session_start();
include_once '../backend/config/database.php';
include_once '../backend/models/User.php';

$database = new Database();
$db = $database->getConnection();

$user = new User($db);

$register_error = '';
$register_success = '';

if ($_POST) {
    $user->username = $_POST['username'];
    $user->email = $_POST['email'];
    $user->password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $user->role = 'user'; // Default role
    
    // Validation
    if ($user->password !== $confirm_password) {
        $register_error = 'Password dan konfirmasi password tidak cocok!';
    } elseif (strlen($user->password) < 6) {
        $register_error = 'Password minimal 6 karakter!';
    } elseif ($user->create()) {
        $register_success = 'Registrasi berhasil! Silakan login.';
    } else {
        $register_error = 'Username atau email sudah digunakan!';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | Manikam Aksesoris</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,300;0,400;0,700;1,700&display=swap" rel="stylesheet">
    
    <!-- Feather Icons -->
    <script src="https://unpkg.com/feather-icons"></script>
    
    <!-- My Style -->
    <link rel="stylesheet" href="css/style.css">
    
    <style>
        .register-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
            padding: 2rem;
        }
        
        .register-box {
            background-color: rgba(255, 255, 255, 0.05);
            padding: 3rem;
            border-radius: 20px;
            border: 1px solid rgba(182, 137, 86, 0.3);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 450px;
            backdrop-filter: blur(10px);
        }
        
        .register-box h2 {
            text-align: center;
            color: var(--primary);
            margin-bottom: 2rem;
            font-size: 2.5rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group label {
            display: block;
            color: #fff;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }
        
        .form-group input {
            width: 100%;
            padding: 1rem;
            border: 1px solid rgba(182, 137, 86, 0.5);
            border-radius: 10px;
            background-color: rgba(255, 255, 255, 0.1);
            color: #fff;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .form-group input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 10px rgba(182, 137, 86, 0.3);
            outline: none;
            background-color: rgba(255, 255, 255, 0.15);
        }
        
        .form-group input::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }
        
        .register-btn {
            width: 100%;
            padding: 1rem;
            background-color: var(--primary);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .register-btn:hover {
            background-color: #9d7548;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(182, 137, 86, 0.3);
        }
        
        .error-message {
            background-color: rgba(220, 53, 69, 0.2);
            color: #ff6b6b;
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1rem;
            text-align: center;
            border: 1px solid rgba(220, 53, 69, 0.3);
        }
        
        .success-message {
            background-color: rgba(40, 167, 69, 0.2);
            color: #28a745;
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1rem;
            text-align: center;
            border: 1px solid rgba(40, 167, 69, 0.3);
        }
        
        .login-link {
            text-align: center;
            margin-top: 2rem;
            color: #fff;
        }
        
        .login-link a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }
        
        .login-link a:hover {
            color: #9d7548;
        }
        
        .back-home {
            text-align: center;
            margin-top: 1rem;
        }
        
        .back-home a {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.3s ease;
        }
        
        .back-home a:hover {
            color: #fff;
        }
        
        .password-requirements {
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.6);
            margin-top: 0.3rem;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-box">
            <h2>Daftar</h2>
            
            <?php if ($register_error): ?>
                <div class="error-message">
                    <?php echo $register_error; ?>
                </div>
            <?php endif; ?>
            
            <?php if ($register_success): ?>
                <div class="success-message">
                    <?php echo $register_success; ?>
                </div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" placeholder="Masukkan username" required value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="Masukkan email" required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Masukkan password" required>
                    <div class="password-requirements">Minimal 6 karakter</div>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">Konfirmasi Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" placeholder="Konfirmasi password" required>
                </div>
                
                <button type="submit" class="register-btn">Daftar</button>
            </form>
            
            <div class="login-link">
                Sudah punya akun? <a href="login.php">Login di sini</a>
            </div>
            
            <div class="back-home">
                <a href="index.php">← Kembali ke Beranda</a>
            </div>
        </div>
    </div>
    
    <script>
        feather.replace();
        
        // Password confirmation validation
        document.getElementById('confirm_password').addEventListener('input', function() {
            const password = document.getElementById('password').value;
            const confirmPassword = this.value;
            
            if (password !== confirmPassword) {
                this.setCustomValidity('Password tidak cocok');
            } else {
                this.setCustomValidity('');
            }
        });
    </script>
</body>
</html>