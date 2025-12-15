<?php
include "koneksi.php";
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM users WHERE id='{$_SESSION['user_id']}'"));
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>🧮 Input Data - DSSRK</title>
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

/* === PERBAIKAN TAMBAHAN UNTUK DARK MODE === */
body.dark label,
body.dark .form-label,
body.dark .card-header h3,
body.dark small,
body.dark .text-muted {
  color: #e9ecef !important; /* teks jadi terang */
}

body.dark .card {
  background-color: #1e1e1e !important;
  color: #f8f9fa !important;
}

body.dark .card-header {
  background-color: #198754 !important;
  color: #fff !important;
  border-bottom: 1px solid #2dc673 !important;
}

body.dark .btn-success {
  background-color: #2dc673 !important;
  color: #fff !important;
}
body.dark .btn-success:hover {
  background-color: #23a55b !important;
}

/* biar input placeholder tetap kebaca */
body.dark input::placeholder,
body.dark textarea::placeholder {
  color: #bdbdbd !important;
  opacity: 1;
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

/* === CARD INPUT RESPONSIVE === */
.card {
  border: none;
  border-radius: 15px;
  background: var(--card-bg);
  box-shadow: 0 4px 15px rgba(0,0,0,0.08);
  transition: background 0.3s ease, color 0.3s ease;
  max-width: 700px;
  margin: 0 auto;
}
.card-header {
  border-top-left-radius: 15px;
  border-top-right-radius: 15px;
  padding: 1.25rem;
}
.card-header h3 {
  font-weight: 700;
  font-size: 1.4rem;
  margin: 0;
}
.card-body {
  padding: 2rem;
}
.form-control, .form-select {
  border-radius: 10px;
  padding: 10px;
  border: 1px solid #ccc;
  transition: all 0.2s ease;
  font-size: 1rem;
}
.form-control:focus, .form-select:focus {
  border-color: #198754;
  box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.25);
}
.form-label {
  font-weight: 600;
  margin-bottom: 0.5rem;
  font-size: 0.95rem;
}
.btn-success {
  font-weight: 600;
  border-radius: 10px;
  padding: 12px;
  font-size: 1.1rem;
}
.btn-success:hover {
  background-color: #157347;
}

/* === FOOTER RESPONSIVE === */
footer {
  background: var(--footer-bg);
  color: #fff;
  text-align: center;
  padding: 15px 10px;
  font-size: 0.85rem;
  margin-top: auto;
}

/* === DARK MODE BUTTON & CARD === */
body.dark .form-control, body.dark .form-select {
  background-color: #2a2a2a;
  color: #f8f9fa;
  border: 1px solid #555;
}
body.dark .form-control:focus, body.dark .form-select:focus {
  border-color: #2dc673;
  box-shadow: 0 0 0 0.2rem rgba(45, 198, 115, 0.25);
}
body.dark .btn-success {
  background-color: #2dc673;
  border: none;
}
body.dark .btn-success:hover {
  background-color: #23a55b;
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
  
  .card-header h3 {
    font-size: 1.2rem;
  }
  
  .card-body {
    padding: 1.5rem;
  }
  
  .form-label {
    font-size: 0.9rem;
  }
  
  .form-control, .form-select {
    font-size: 0.95rem;
    padding: 9px;
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
    border-radius: 12px;
    margin: 0.5rem;
  }
  
  .card-header {
    padding: 1rem;
    border-top-left-radius: 12px;
    border-top-right-radius: 12px;
  }
  
  .card-header h3 {
    font-size: 1.1rem;
  }
  
  .card-body {
    padding: 1rem;
  }
  
  .form-label {
    font-size: 0.85rem;
    margin-bottom: 0.4rem;
  }
  
  .form-control, .form-select {
    font-size: 0.9rem;
    padding: 8px;
    border-radius: 8px;
  }
  
  .btn-success {
    font-size: 0.95rem;
    padding: 10px;
    border-radius: 8px;
  }
  
  .mb-3 {
    margin-bottom: 0.75rem !important;
  }
  
  small {
    font-size: 0.8rem;
  }
  
  footer {
    font-size: 0.75rem;
    padding: 12px 10px;
  }
  
  /* Biar row tetap rapi di mobile */
  .row {
    margin-left: -0.5rem;
    margin-right: -0.5rem;
  }
  
  .row > [class*='col-'] {
    padding-left: 0.5rem;
    padding-right: 0.5rem;
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
  }
  
  .card-header h3 {
    font-size: 1rem;
  }
  
  .card-body {
    padding: 0.75rem;
  }
  
  .form-label {
    font-size: 0.8rem;
  }
  
  .form-control, .form-select {
    font-size: 0.85rem;
    padding: 7px;
  }
  
  .btn-success {
    font-size: 0.9rem;
    padding: 9px;
  }
}

/* Landscape mode untuk mobile */
@media (max-width: 768px) and (orientation: landscape) {
  .card-body {
    padding: 1rem;
  }
  
  main {
    padding-top: 1rem;
    padding-bottom: 1rem;
  }
}
</style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark">
  <div class="container">
    <a class="navbar-brand" href="#">
      <span class="logo-icon">🍃</span>
      <span class="logo-text"><span>DSS</span>RK</span>
    </a>
    <div class="d-flex align-items-center gap-2">
      <button id="themeToggle">🌙</button>
      <a href="profile.php" class="btn btn-outline-light btn-sm">👤 Profile</a>
      <a href="logout.php" class="btn btn-outline-light btn-sm">🚪 Logout</a>
    </div>
  </div>
</nav>

<main>
  <!-- Input Card -->
  <div class="container mt-4 mt-md-5 mb-4 mb-md-5">
    <div class="card shadow-lg">
      <div class="card-header bg-success text-white text-center">
        <h3>🧾 Input Data Pengguna</h3>
      </div>
      <div class="card-body">
        <form action="proses.php" method="POST">
          <div class="mb-3">
            <label class="form-label">Nama:</label>
            <input type="text" class="form-control" name="nama" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Jenis Kelamin:</label>
            <select class="form-select" name="jk" required>
              <option value="pria">Pria</option>
              <option value="wanita">Wanita</option>
            </select>
          </div>

          <div class="row">
            <div class="col-md-4 mb-3">
              <label class="form-label">Usia:</label>
              <input type="number" class="form-control" name="usia" required>
            </div>
            <div class="col-md-4 mb-3">
              <label class="form-label">Berat Badan (kg):</label>
              <input type="number" step="0.1" class="form-control" name="berat" required>
            </div>
            <div class="col-md-4 mb-3">
              <label class="form-label">Tinggi Badan (cm):</label>
              <input type="number" step="0.1" class="form-control" name="tinggi" required>
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label">Aktivitas:</label>
            <select class="form-select" name="aktivitas" required>
              <option value="rendah">Rendah</option>
              <option value="sedang">Sedang</option>
              <option value="tinggi">Tinggi</option>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Pantangan Makanan (opsional):</label>
            <input type="text" name="pantangan" class="form-control" placeholder="Contoh: daging, pedas, gorengan">
            <small class="text-muted">Ketik kata kunci pantangan makanan (pisahkan dengan koma jika lebih dari satu).</small>
          </div>

          <button type="submit" class="btn btn-success w-100">⚙️ Hitung & Lanjut</button>
        </form>
      </div>
    </div>
  </div>
</main>

<!-- Footer -->
<footer>
  <p>© <?= date("Y") ?> DSSRK — Sistem Rekomendasi Kalori. Dibuat oleh Rifki Figianto</p>
</footer>

<!-- JS Dark Mode -->
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