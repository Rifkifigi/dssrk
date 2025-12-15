<?php
session_start();
include "koneksi.php";
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }

 $user_id = $_SESSION['user_id'];
 $user = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM users WHERE id='$user_id'"));

// Upload foto profil
if (isset($_POST['update'])) {
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $kalori = $_POST['kalori'];
    $foto_lama = $user['foto'] ?? '';

    // Jika user upload foto baru
    if (!empty($_FILES['foto']['name'])) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) mkdir($target_dir);

        $file_name = basename($_FILES["foto"]["name"]);
        $target_file = $target_dir . time() . "_" . $file_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($imageFileType, $allowed)) {
            if (move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
                // hapus foto lama
                if (!empty($foto_lama) && file_exists($foto_lama)) unlink($foto_lama);
                $foto = $target_file;
                mysqli_query($koneksi, "UPDATE users SET foto='$foto' WHERE id='$user_id'");
                $user['foto'] = $foto;
            }
        }
    }

    mysqli_query($koneksi, "UPDATE users SET nama='$nama', kalori='$kalori' WHERE id='$user_id'");
    $_SESSION['nama'] = $nama;
    $user['nama'] = $nama;
    $user['kalori'] = $kalori;
    $pesan = "✅ Profil berhasil diperbarui!";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>👤 Profil Pengguna - DSSRK</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&family=Montserrat:wght@700&display=swap" rel="stylesheet">
<style>
:root {
  --bg-color: #f8f9fa;
  --text-color: #212529;
  --card-bg: #fff;
  --navbar-bg: linear-gradient(90deg, #198754 0%, #157347 100%);
  --footer-bg: #157347;
}
body.dark {
  --bg-color: #121212;
  --text-color: #f8f9fa;
  --card-bg: #1f1f1f;
  --navbar-bg: linear-gradient(90deg, #0d5234 0%, #0b4129 100%);
  --footer-bg: #0b4129;
}

/* === FIX FOOTER SELALU DI BAWAH === */
html, body {
  height: 100%;
  display: flex;
  flex-direction: column;
}
body {
  min-height: 100vh;
}
main {
  flex: 1;
}

body {
  background-color: var(--bg-color);
  color: var(--text-color);
  font-family: 'Poppins', sans-serif;
  transition: background 0.3s ease, color 0.3s ease;
}

/* === NAVBAR RESPONSIVE === */
.navbar {
  background: var(--navbar-bg);
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
  padding: 0.75rem 0;
}

/* LOGO VERTIKAL - Icon di atas, text di bawah */
.navbar-brand { 
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  text-align: center;
  font-weight: 800; 
  color: #fff !important; 
  font-family: 'Montserrat', sans-serif;
  line-height: 1;
  padding: 0.25rem 0.5rem;
  gap: 0;
}

.navbar-brand .logo-icon {
  font-size: 1.8rem;
  display: block;
  line-height: 1;
}

.navbar-brand .logo-text {
  font-size: 1rem;
  display: block;
  line-height: 1;
  margin-top: -0.1rem;
}

.navbar-brand .logo-text span { 
  color: #ffc107; 
}

/* Navbar buttons responsive */
.navbar .d-flex {
  flex-wrap: wrap;
  gap: 0.5rem;
}

.navbar .btn-sm {
  font-size: 0.85rem;
  padding: 0.4rem 0.8rem;
  white-space: nowrap;
}

#themeToggle {
  background: transparent;
  border: 1px solid #fff;
  border-radius: 50%;
  width: 35px;
  height: 35px;
  color: #fff;
  transition: 0.3s;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}
#themeToggle:hover {
  background: #fff;
  color: #198754;
}

/* Card */
.card {
  background: var(--card-bg);
  border: none;
  border-radius: 15px;
  box-shadow: 0 6px 18px rgba(0,0,0,0.08);
  max-width: 600px;
  margin: 0 auto;
}
body.dark .card { 
  background: #1f1f1f; 
  color: #f8f9fa;
}
body.dark label { color: #f8f9fa; }
body.dark .form-control {
  background-color: #2a2a2a;
  color: #f8f9fa;
  border: 1px solid #555;
}
body.dark .form-control:focus {
  border-color: #2dc673;
  box-shadow: 0 0 0 0.2rem rgba(45, 198, 115, 0.25);
}

/* Foto profil */
.profile-pic {
  width: 130px;
  height: 130px;
  border-radius: 50%;
  object-fit: cover;
  border: 4px solid #198754;
  margin-bottom: 15px;
  transition: transform 0.3s ease;
}
.profile-pic:hover {
  transform: scale(1.05);
}

/* Form styling */
.form-label {
  font-weight: 600;
  margin-bottom: 0.5rem;
}
.form-control {
  border-radius: 10px;
  padding: 10px;
  border: 1px solid #ccc;
  transition: all 0.2s ease;
}
.form-control:focus {
  border-color: #198754;
  box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.25);
}
.btn-success {
  font-weight: 600;
  border-radius: 10px;
  padding: 12px;
  font-size: 1.1rem;
}

/* Alert styling */
.alert {
  border-radius: 10px;
  border: none;
}

/* Footer */
footer {
  background: var(--footer-bg);
  color: #fff;
  text-align: center;
  padding: 15px 10px;
  font-size: 0.85rem;
  margin-top: auto;
}

/* === MEDIA QUERIES === */

/* Tablet & Mobile Large */
@media (max-width: 768px) {
  .navbar-brand .logo-icon {
    font-size: 1.5rem;
  }
  
  .navbar-brand .logo-text {
    font-size: 0.9rem;
  }
  
  .navbar .btn-sm {
    font-size: 0.8rem;
    padding: 0.35rem 0.7rem;
  }
  
  #themeToggle {
    width: 32px;
    height: 32px;
    font-size: 0.9rem;
  }
  
  .card {
    margin: 1rem;
  }
  
  .profile-pic {
    width: 110px;
    height: 110px;
  }
  
  .btn-success {
    font-size: 1rem;
    padding: 11px;
  }
}

/* Mobile Small */
@media (max-width: 576px) {
  .navbar {
    padding: 0.5rem 0;
  }
  
  .navbar-brand {
    padding: 0.2rem 0.4rem;
  }
  
  .navbar-brand .logo-icon {
    font-size: 1.3rem;
  }
  
  .navbar-brand .logo-text {
    font-size: 0.75rem;
    margin-top: -0.15rem;
  }
  
  .navbar .d-flex {
    gap: 0.35rem;
  }
  
  .navbar .btn-sm {
    font-size: 0.7rem;
    padding: 0.3rem 0.5rem;
  }
  
  #themeToggle {
    width: 30px;
    height: 30px;
    font-size: 0.85rem;
  }
  
  .container {
    padding-left: 10px;
    padding-right: 10px;
  }
  
  .card {
    margin: 0.5rem;
    padding: 1.5rem;
  }
  
  .profile-pic {
    width: 100px;
    height: 100px;
  }
  
  .btn-success {
    font-size: 0.95rem;
    padding: 10px;
  }
  
  footer {
    font-size: 0.75rem;
    padding: 12px 10px;
  }
}

/* Extra Small Mobile */
@media (max-width: 380px) {
  .navbar-brand .logo-icon {
    font-size: 1.2rem;
  }
  
  .navbar-brand .logo-text {
    font-size: 0.7rem;
  }
  
  .navbar .btn-sm {
    font-size: 0.65rem;
    padding: 0.25rem 0.45rem;
  }
  
  .card {
    margin: 0.25rem;
    padding: 1rem;
  }
  
  .profile-pic {
    width: 90px;
    height: 90px;
  }
  
  .btn-success {
    font-size: 0.9rem;
    padding: 9px;
  }
}

/* Landscape mode untuk mobile */
@media (max-width: 768px) and (orientation: landscape) {
  .card {
    margin: 0.5rem;
    padding: 1.5rem;
  }
  
  .profile-pic {
    width: 100px;
    height: 100px;
  }
}
</style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark">
  <div class="container">
    <a class="navbar-brand" href="dashboard.php">
      <span class="logo-icon">🍃</span>
      <span class="logo-text"><span>DSS</span>RK</span>
    </a>
    <div class="d-flex align-items-center gap-2">
      <button id="themeToggle">🌙</button>
      <a href="dashboard.php" class="btn btn-outline-light btn-sm">🏠 Dashboard</a>
      <a href="logout.php" class="btn btn-outline-light btn-sm">🚪 Logout</a>
    </div>
  </div>
</nav>

<main>
<div class="container mt-4 mt-md-5 mb-4 mb-md-5">
  <div class="card p-4">
    <h4 class="text-center text-success fw-bold mb-3">👤 Profil Pengguna</h4>
    <div class="text-center">
      <img src="<?= !empty($user['foto']) ? $user['foto'] : 'assets/default.png' ?>" 
           alt="Foto Profil" class="profile-pic">
    </div>

    <?php if (!empty($pesan)): ?>
      <div class="alert alert-success text-center mt-3"><?= $pesan ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" class="mt-3">
      <div class="mb-3">
        <label class="form-label">Ganti Foto Profil</label>
        <input type="file" name="foto" class="form-control" accept="image/*">
      </div>
      <div class="mb-3">
        <label class="form-label">Nama Lengkap</label>
        <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($user['nama']) ?>" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" readonly>
      </div>
      <div class="mb-3">
        <label class="form-label">Kebutuhan Kalori Harian</label>
        <input type="number" name="kalori" class="form-control" value="<?= htmlspecialchars($user['kalori']) ?>" required>
      </div>
      <button type="submit" name="update" class="btn btn-success w-100">💾 Simpan Perubahan</button>
    </form>
  </div>
</div>
</main>

<footer>
  <p>© <?= date("Y") ?> DSSRK — Sistem Rekomendasi Kalori. Dibuat oleh Rifki Figianto</p>
</footer>

<script>
const toggleBtn = document.getElementById('themeToggle');
toggleBtn.addEventListener('click', () => {
  document.body.classList.toggle('dark');
  toggleBtn.textContent = document.body.classList.contains('dark') ? '☀️' : '🌙';
  localStorage.setItem('theme', document.body.classList.contains('dark') ? 'dark' : 'light');
});
if (localStorage.getItem('theme') === 'dark') {
  document.body.classList.add('dark');
  toggleBtn.textContent = '☀️';
}
</script>
</body>
</html>