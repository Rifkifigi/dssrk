<?php
session_start();
include "koneksi.php";
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }

$user = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM users WHERE id='{$_SESSION['user_id']}'"));

$low  = mysqli_query($koneksi, "SELECT * FROM data_makanan WHERE calories <= 400 ORDER BY RAND() LIMIT 10");
$high = mysqli_query($koneksi, "SELECT * FROM data_makanan WHERE calories > 400 ORDER BY RAND() LIMIT 10");

// Hitung kebutuhan per waktu makan
$kalori_user   = (float)$user['kalori'];
$kalori_sarapan = $kalori_user * 0.3;
$kalori_siang   = $kalori_user * 0.4;
$kalori_malam   = $kalori_user * 0.3;

// Ambil hasil query sebagai array agar bisa dipakai ulang
$sarapan = mysqli_fetch_all(mysqli_query($koneksi, "SELECT * FROM data_makanan WHERE calories BETWEEN ".($kalori_sarapan*0.8)." AND ".($kalori_sarapan*1.2)." ORDER BY RAND() LIMIT 5"), MYSQLI_ASSOC);
$siang   = mysqli_fetch_all(mysqli_query($koneksi, "SELECT * FROM data_makanan WHERE calories BETWEEN ".($kalori_siang*0.8)." AND ".($kalori_siang*1.2)." ORDER BY RAND() LIMIT 5"), MYSQLI_ASSOC);
$malam   = mysqli_fetch_all(mysqli_query($koneksi, "SELECT * FROM data_makanan WHERE calories BETWEEN ".($kalori_malam*0.8)." AND ".($kalori_malam*1.2)." ORDER BY RAND() LIMIT 5"), MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard - DSSRK</title>
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
  --card-bg: #1e1e1e;
  --navbar-bg: linear-gradient(90deg, #0d5234 0%, #0b4129 100%);
  --footer-bg: #0b4129;
}

/* Perbaikan dark mode */
body.dark .greeting-box h2 {
  color: #e6ffe6 !important;
}

body.dark .greeting-box p {
  color: #cccccc !important;
}

body.dark .greeting-box strong {
  color: #fff !important;
}

body.dark .badge {
  background-color: #198754 !important;
  color: #fff !important;
  box-shadow: 0 0 10px rgba(25, 135, 84, 0.5);
}

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
body.dark .modal-content { 
  background-color: #1f1f1f !important; 
  color: #f8f9fa !important; 
}
body.dark .modal-header { 
  background-color: #198754 !important; 
}
body.dark .btn-close { 
  filter: invert(1); 
}
body {
  background-color: var(--bg-color);
  color: var(--text-color);
  font-family: 'Poppins', sans-serif;
  transition: background 0.3s ease, color 0.3s ease;
}

/* NAVBAR RESPONSIVE */
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

/* GREETING BOX RESPONSIVE */
.greeting-box {
  background: linear-gradient(135deg, rgba(255,255,255,0.9), rgba(240,240,240,0.95));
  border-radius: 16px;
  padding: 20px;
  box-shadow: 0 4px 20px rgba(0,0,0,0.08);
  max-width: 900px;
  width: 100%;
}
body.dark .greeting-box { 
  background: linear-gradient(135deg, rgba(25,25,25,0.9), rgba(35,35,35,0.95)); 
}

.profile-img { 
  width: 90px; 
  height: 90px; 
  border-radius: 50%; 
  border: 3px solid #198754; 
  object-fit: cover; 
}

.greeting-box h2 {
  font-size: 1.5rem;
  margin-bottom: 0.5rem;
}

.greeting-box .badge {
  font-size: 0.9rem;
  padding: 0.5rem 1rem;
  display: inline-block;
}

/* ACTION CONTAINER RESPONSIVE */
.action-container {
  background: var(--card-bg);
  border-radius: 12px;
  padding: 25px 15px;
  margin-top: 25px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.08);
  display: flex; 
  justify-content: center; 
  gap: 15px; 
  flex-wrap: wrap;
  max-width: 900px;
  width: 100%;
}

.action-container .btn { 
  min-width: 160px;
  font-weight: 600; 
  font-size: 1rem; 
  border-radius: 10px; 
  padding: 10px 20px; 
}

/* SCROLL MENU RESPONSIVE */
.scroll-menu { 
  display: flex; 
  overflow-x: auto; 
  gap: 15px; 
  scroll-behavior: smooth; 
  padding: 10px 15px 15px; 
  justify-content: flex-start;
}
.scroll-menu::-webkit-scrollbar { 
  height: 8px; 
}
.scroll-menu::-webkit-scrollbar-thumb { 
  background: #ccc; 
  border-radius: 10px; 
}

/* CARD RESPONSIVE */
.card {
  min-width: 180px; 
  flex: 0 0 auto; 
  border: none;
  border-radius: 12px; 
  overflow: hidden;
  background: var(--card-bg); 
  color: var(--text-color);
  box-shadow: 0 4px 10px rgba(0,0,0,0.08);
  transition: transform 0.25s ease;
  cursor: pointer;
}
.card:hover { 
  transform: scale(1.05); 
}
.card-img-top { 
  width: 100%; 
  height: 130px; 
  object-fit: cover; 
}

.card-body h6 {
  font-size: 0.95rem;
  margin: 0;
}

/* SECTION TITLE RESPONSIVE */
.section-title {
  position: relative;
  font-weight: 700;
  margin-top: 40px;
  margin-bottom: 30px;
  display: block;
  text-align: center;
  width: fit-content;
  margin-left: auto;
  margin-right: auto;
  padding: 0 15px 12px;
  font-size: 1.2rem;
}
.section-title::after {
  content: "";
  position: absolute;
  width: 80%;
  height: 4px;
  left: 50%;
  bottom: 0;
  transform: translateX(-50%);
  background: #198754;
  border-radius: 10px;
}

/* FOOTER RESPONSIVE */
footer {
  background: var(--footer-bg);
  color: #fff;
  text-align: center;
  padding: 15px 10px;
  font-size: 0.85rem;
  margin-top: auto;
}

/* MEDIA QUERIES */

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
  
  .greeting-box {
    padding: 20px 15px;
  }
  
  .greeting-box h2 {
    font-size: 1.3rem;
  }
  
  .profile-img {
    width: 80px;
    height: 80px;
  }
  
  .greeting-box .badge {
    font-size: 0.85rem;
    padding: 0.4rem 0.8rem;
  }
  
  .action-container {
    padding: 20px 10px;
    gap: 12px;
  }
  
  .action-container .btn {
    min-width: 140px;
    font-size: 0.95rem;
    padding: 9px 15px;
  }
  
  .section-title {
    font-size: 1.1rem;
    margin-top: 35px;
    margin-bottom: 25px;
  }
  
  .card {
    min-width: 160px;
  }
  
  .card-img-top {
    height: 120px;
  }
  
  .card-body h6 {
    font-size: 0.9rem;
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
  
  .greeting-box {
    padding: 15px 10px;
    border-radius: 12px;
  }
  
  .greeting-box h2 {
    font-size: 1.1rem;
  }
  
  .greeting-box p {
    font-size: 0.9rem;
  }
  
  .profile-img {
    width: 70px;
    height: 70px;
    border: 2px solid #198754;
  }
  
  .greeting-box .badge {
    font-size: 0.8rem;
    padding: 0.35rem 0.7rem;
  }
  
  .action-container {
    padding: 15px 10px;
    gap: 10px;
  }
  
  .action-container .btn {
    min-width: 120px;
    font-size: 0.9rem;
    padding: 8px 12px;
  }
  
  .section-title {
    font-size: 1rem;
    margin-top: 30px;
    margin-bottom: 20px;
    padding: 0 10px 10px;
  }
  
  .scroll-menu {
    gap: 12px;
    padding: 10px 10px 12px;
  }
  
  .card {
    min-width: 140px;
  }
  
  .card-img-top {
    height: 100px;
  }
  
  .card-body {
    padding: 0.75rem;
  }
  
  .card-body h6 {
    font-size: 0.85rem;
  }
  
  footer {
    font-size: 0.75rem;
    padding: 12px 10px;
  }
  
  .modal-body img {
    border-radius: 8px;
  }
  
  .modal-body p {
    font-size: 0.9rem;
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
  
  .greeting-box h2 {
    font-size: 1rem;
  }
  
  .action-container .btn {
    min-width: 100px;
    font-size: 0.85rem;
  }
  
  .card {
    min-width: 130px;
  }
  
  .section-title {
    font-size: 0.95rem;
  }
}

/* Container responsiveness */
.container {
  padding-left: 15px;
  padding-right: 15px;
}

@media (max-width: 576px) {
  .container {
    padding-left: 10px;
    padding-right: 10px;
  }
}
</style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark">
  <div class="container">
    <a class="navbar-brand" href="#">
      <span class="logo-icon">🍃</span>
      <span class="logo-text"><span>DSS</span>RK</span>
    </a>
    <div class="d-flex align-items-center gap-2">
      <button id="themeToggle" title="Ganti Tema 🌙">🌙</button>
      <a href="profile.php" class="btn btn-outline-light btn-sm">👤 Profile</a>
      <a href="contact.php" class="btn btn-outline-light btn-sm">📞 Contact</a>
      <a href="index.php" class="btn btn-outline-light btn-sm">🚪 Logout</a>
    </div>
  </div>
</nav>

<main class="text-center">
  <div class="container mt-4 d-flex justify-content-center">
    <div class="greeting-box">
      <div class="d-flex align-items-center flex-column flex-md-row text-center text-md-start gap-3">
        <img src="<?= $user['foto'] ?: 'https://cdn-icons-png.flaticon.com/512/3135/3135715.png' ?>" alt="Foto Profil" class="profile-img shadow-sm">
        <div>
          <h2>Selamat Datang, <span class="text-success"><?= strtoupper(htmlspecialchars($user['nama'])) ?></span> 👋</h2>
          <p class="text-muted mb-2">Senang melihatmu kembali di <strong>DSSRK</strong>!</p>
          <span class="badge bg-success bg-opacity-75">🔥 Kebutuhan Kalori: <strong><?= $user['kalori'] ?> kkal</strong></span>
        </div>
      </div>
    </div>
  </div>

  <div class="container d-flex justify-content-center">
    <div class="action-container">
      <a href="input.php" class="btn btn-outline-success">📝 Input Data</a>
      <a href="riwayat.php" class="btn btn-outline-success">📜 Riwayat</a>
    </div>
  </div>

  <div class="container">
    <h5 class="section-title text-success">🌿 Rekomendasi Low Calorie Hari Ini</h5>
    <div class="scroll-menu">
      <?php while($row = mysqli_fetch_assoc($low)): ?>
        <div class="card" data-bs-toggle="modal" data-bs-target="#modalLow<?= $row['id'] ?>">
          <img src="<?= $row['image'] ?>" class="card-img-top" alt="<?= $row['name'] ?>">
          <div class="card-body text-center"><h6><?= $row['name'] ?></h6></div>
        </div>
        <div class="modal fade" id="modalLow<?= $row['id'] ?>" tabindex="-1">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header bg-success text-white"><h5 class="modal-title"><?= $row['name'] ?></h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
              <div class="modal-body">
                <img src="<?= $row['image'] ?>" class="img-fluid mb-3 rounded">
                <p><strong>Kalori:</strong> <?= $row['calories'] ?> kkal</p>
                <p><strong>Protein:</strong> <?= $row['proteins'] ?> g</p>
                <p><strong>Lemak:</strong> <?= $row['fat'] ?> g</p>
                <p><strong>Karbohidrat:</strong> <?= $row['carbohydrate'] ?> g</p>
              </div>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    </div>

    <h5 class="section-title text-danger">🔥 Rekomendasi High Calorie Hari Ini</h5>
    <div class="scroll-menu">
      <?php while($row = mysqli_fetch_assoc($high)): ?>
        <div class="card" data-bs-toggle="modal" data-bs-target="#modalHigh<?= $row['id'] ?>">
          <img src="<?= $row['image'] ?>" class="card-img-top" alt="<?= $row['name'] ?>">
          <div class="card-body text-center"><h6><?= $row['name'] ?></h6></div>
        </div>
        <div class="modal fade" id="modalHigh<?= $row['id'] ?>" tabindex="-1">
          <div class="modal-dialog modal-dialog-centered"><div class="modal-content">
            <div class="modal-header bg-danger text-white"><h5><?= $row['name'] ?></h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body"><img src="<?= $row['image'] ?>" class="img-fluid mb-3 rounded">
              <p><strong>Kalori:</strong> <?= $row['calories'] ?> kkal</p>
              <p><strong>Protein:</strong> <?= $row['proteins'] ?> g</p>
              <p><strong>Lemak:</strong> <?= $row['fat'] ?> g</p>
              <p><strong>Karbohidrat:</strong> <?= $row['carbohydrate'] ?> g</p>
            </div></div></div>
        </div>
      <?php endwhile; ?>
    </div>

    <h4 class="section-title text-success">🍽️ Rekomendasi Menu Sesuai Kebutuhan Kalori</h4>
    <?php
    $kategori = [
      'Sarapan' => ['data' => $sarapan, 'color' => 'success', 'emoji' => '🍳', 'kalori' => $kalori_sarapan],
      'Makan Siang' => ['data' => $siang, 'color' => 'warning', 'emoji' => '🍱', 'kalori' => $kalori_siang],
      'Makan Malam' => ['data' => $malam, 'color' => 'primary', 'emoji' => '🌙', 'kalori' => $kalori_malam]
    ];
    foreach($kategori as $judul => $val):
      $idBase = str_replace(' ', '_', $judul);
    ?>
      <h5 class="section-title text-<?= $val['color'] ?>"><?= $val['emoji'] ?> <?= $judul ?> (± <?= round($val['kalori']) ?> kkal)</h5>
      <div class="scroll-menu">
        <?php foreach($val['data'] as $row): ?>
          <div class="card" data-bs-toggle="modal" data-bs-target="#modal_<?= $idBase ?>_<?= $row['id'] ?>">
            <img src="<?= $row['image'] ?>" class="card-img-top" alt="<?= $row['name'] ?>">
            <div class="card-body text-center"><h6><?= $row['name'] ?></h6></div>
          </div>
          <div class="modal fade" id="modal_<?= $idBase ?>_<?= $row['id'] ?>" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered"><div class="modal-content">
              <div class="modal-header bg-<?= $val['color'] ?> text-white">
                <h5><?= $row['name'] ?></h5><button class="btn-close" data-bs-dismiss="modal"></button>
              </div>
              <div class="modal-body">
                <img src="<?= $row['image'] ?>" class="img-fluid mb-3 rounded">
                <p><strong>Kalori:</strong> <?= $row['calories'] ?> kkal</p>
                <p><strong>Protein:</strong> <?= $row['proteins'] ?> g</p>
                <p><strong>Lemak:</strong> <?= $row['fat'] ?> g</p>
                <p><strong>Karbohidrat:</strong> <?= $row['carbohydrate'] ?> g</p>
              </div>
            </div></div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endforeach; ?>
  </div>
</main>

<footer><p>© <?= date("Y") ?> DSSRK — Sistem Rekomendasi Menu. Dibuat oleh Rifki Figianto</p></footer>

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