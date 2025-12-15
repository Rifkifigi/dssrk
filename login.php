<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include "koneksi.php";

if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $password = $_POST['password'];

    $query = mysqli_query($koneksi, "SELECT * FROM users WHERE email='$email'");
    $user = mysqli_fetch_assoc($query);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['nama'] = $user['nama'];
        $_SESSION['role'] = $user['role'];

        if ($user['role'] == 'admin') {
            header("Location: admin/dashboardmin.php");
        } elseif ($user['role'] == 'user') {
            header("Location: dashboard.php");
        } else {
            header("Location: login.php");
        }
        exit;
    } else {
        $error = "Email atau password salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login - DSSRK</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    background: linear-gradient(135deg, #56ab2f, #a8e063);
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 15px;
    font-family: 'Poppins', sans-serif;
}

/* CARD LOGIN */
.login-container {
    width: 100%;
    max-width: 450px;
    margin: 0 auto;
}

.card {
    width: 100%;
    border-radius: 1.2rem;
    box-shadow: 0 8px 30px rgba(0,0,0,0.2);
    overflow: hidden;
    animation: fadeIn 0.6s ease;
    border: none;
}

/* HEADER */
.card-header {
    padding: 30px 20px;
    position: relative;
    background: linear-gradient(135deg, #28a745, #20c997) !important;
}

/* TOMBOL BACK */
.btn-back {
    background: transparent;
    border: none;
    font-size: 1.5rem;
    position: absolute;
    left: 20px;
    top: 50%;
    transform: translateY(-50%);
    transition: all 0.3s ease;
    color: white;
    cursor: pointer;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    border-radius: 50%;
}

.btn-back:hover {
    background: rgba(255,255,255,0.2);
    color: #ffc107;
    transform: translateY(-50%) translateX(-3px);
}

/* TEXT HEADER */
.card-header h4 {
    animation: fadeIn 1s ease-in-out;
    font-weight: 700;
    font-size: 1.6rem;
    margin: 0;
    color: white;
}

/* CARD BODY */
.card-body {
    padding: 30px 25px;
}

/* ALERT */
.alert {
    border-radius: 10px;
    font-size: 0.9rem;
    padding: 12px 15px;
}

/* FORM LABEL */
.form-label {
    font-weight: 600;
    color: #333;
    margin-bottom: 8px;
    font-size: 0.95rem;
}

/* FORM INPUT */
.form-control {
    border-radius: 10px;
    padding: 13px 15px;
    font-size: 1rem;
    border: 2px solid #e0e0e0;
    transition: all 0.3s ease;
}

.form-control:focus {
    border-color: #28a745;
    box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.15);
}

/* BUTTON LOGIN */
.btn-success {
    padding: 13px;
    border-radius: 10px;
    font-weight: 600;
    font-size: 1.05rem;
    transition: all 0.3s ease;
    background: linear-gradient(135deg, #28a745, #20c997);
    border: none;
    box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
}

.btn-success:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(40, 167, 69, 0.4);
    background: linear-gradient(135deg, #20c997, #28a745);
}

.btn-success:active {
    transform: translateY(0);
}

/* FOOTER TEXT */
.footer-text {
    font-size: 0.95rem;
}

.footer-text a {
    color: #28a745;
    font-weight: 600;
    text-decoration: none;
    transition: color 0.3s ease;
}

.footer-text a:hover {
    color: #20c997;
    text-decoration: underline;
}

/* ANIMASI */
@keyframes fadeIn {
    from { 
        opacity: 0; 
        transform: translateY(-20px); 
    }
    to { 
        opacity: 1; 
        transform: translateY(0); 
    }
}

/* RESPONSIVE ================================================== */

/* Tablet ≤ 768px */
@media (max-width: 768px) {
    .card-header h4 {
        font-size: 1.4rem;
    }
    
    .card-body {
        padding: 25px 20px;
    }
}

/* HP sedang ≤ 576px */
@media (max-width: 576px) {
    body {
        padding: 10px;
    }
    
    .card {
        border-radius: 1rem;
    }
    
    .card-header {
        padding: 25px 15px;
    }
    
    .card-header h4 {
        font-size: 1.3rem;
        padding-right: 10px;
    }
    
    .btn-back {
        font-size: 1.3rem;
        left: 15px;
        width: 35px;
        height: 35px;
    }
    
    .card-body {
        padding: 20px 18px;
    }
    
    .form-label {
        font-size: 0.9rem;
    }
    
    .form-control {
        padding: 12px 14px;
        font-size: 0.95rem;
    }
    
    .btn-success {
        font-size: 1rem;
        padding: 12px;
    }
    
    .footer-text {
        font-size: 0.9rem;
    }
}

/* HP kecil ≤ 400px */
@media (max-width: 400px) {
    .card-header {
        padding: 22px 12px;
    }
    
    .card-header h4 {
        font-size: 1.2rem;
    }
    
    .btn-back {
        font-size: 1.2rem;
        left: 12px;
        width: 32px;
        height: 32px;
    }
    
    .card-body {
        padding: 18px 15px;
    }
    
    .form-control {
        padding: 11px 13px;
        font-size: 0.9rem;
    }
    
    .btn-success {
        font-size: 0.95rem;
        padding: 11px;
    }
}

/* HP super kecil ≤ 360px */
@media (max-width: 360px) {
    body {
        padding: 8px;
    }
    
    .card-header {
        padding: 20px 10px;
    }
    
    .card-header h4 {
        font-size: 1.1rem;
    }
    
    .btn-back {
        font-size: 1.1rem;
        left: 10px;
        width: 30px;
        height: 30px;
    }
    
    .card-body {
        padding: 16px 12px;
    }
    
    .form-label {
        font-size: 0.85rem;
        margin-bottom: 6px;
    }
    
    .form-control {
        padding: 10px 12px;
        font-size: 0.85rem;
    }
    
    .btn-success {
        font-size: 0.9rem;
        padding: 10px;
    }
    
    .footer-text {
        font-size: 0.85rem;
    }
    
    .alert {
        font-size: 0.85rem;
        padding: 10px 12px;
    }
}

/* Landscape mode untuk HP */
@media (max-height: 500px) and (orientation: landscape) {
    body {
        padding: 10px;
        align-items: flex-start;
        padding-top: 20px;
    }
    
    .card-header {
        padding: 20px 15px;
    }
    
    .card-body {
        padding: 20px;
    }
    
    .mb-3 {
        margin-bottom: 0.8rem !important;
    }
}

</style>
</head>
<body>

<div class="login-container">
    <div class="card">
        <div class="card-header text-center">
            <!-- Tombol kembali -->
            <a href="index.php" class="btn-back" title="Kembali">⬅️</a>
            <h4>🍃 DSSRK Login</h4>
        </div>
        <div class="card-body">
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" placeholder="Masukkan email Anda" required autofocus>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Masukkan password Anda" required>
                </div>
                <button type="submit" name="login" class="btn btn-success w-100">Login</button>
            </form>
            <p class="mt-3 text-center text-muted footer-text">Belum punya akun? <a href="register.php">Daftar</a></p>
        </div>
    </div>
</div>

</body>
</html>