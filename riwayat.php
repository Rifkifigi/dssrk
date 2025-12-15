<?php
include "koneksi.php";
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

 $user_id = $_SESSION['user_id'];
 $user = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM users WHERE id='$user_id'"));

// Hapus riwayat
if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    mysqli_query($koneksi, "DELETE FROM riwayat WHERE id='$id' AND user_id='$user_id'");
    header("Location: riwayat.php");
    exit;
}

 $riwayat = mysqli_query($koneksi, "SELECT * FROM riwayat WHERE user_id='$user_id' ORDER BY tanggal DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>📜 Riwayat Rekomendasi - DSSRK</title>
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

/* ======== FIX KONTRAS WARNA DARK MODE ======== */
body.dark .card {
  background-color: #181818 !important;
  color: #ffffff !important;
  box-shadow: 0 4px 15px rgba(255,255,255,0.05);
}
body.dark .table {
  background-color: #202020 !important;
  color: #f8f9fa !important;
}
body.dark .table th {
  background-color: #198754 !important;
  color: #ffffff !important;
}
body.dark .table tr {
  border-bottom: 1px solid #2a2a2a !important;
}
body.dark .table td {
  color: #e6e6e6 !important;
  background-color: #1b1b1b !important;
}
body.dark .table-hover tbody tr:hover {
  background-color: rgba(25, 135, 84, 0.3) !important;
}
body.dark .text-muted { color: #d0d0d0 !important; }
body.dark .btn-outline-success {
  color: #a2f5b5 !important;
  border-color: #2dc673 !important;
}
body.dark .btn-outline-success:hover {
  background-color: #2dc673 !important;
  color: #121212 !important;
}

/* === GLOBAL === */
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

/* === CARD & TABLE === */
.card {
  border: none;
  border-radius: 15px;
  background: var(--card-bg);
  box-shadow: 0 4px 10px rgba(0,0,0,0.08);
  transition: background 0.3s ease, color 0.3s ease;
  margin-bottom: 1.5rem;
}
.table th {
  background-color: #198754;
  color: #fff;
  border: none;
}
.table-hover tbody tr:hover {
  background-color: rgba(25, 135, 84, 0.1);
}

/* === ICON BUTTON === */
.icon-btn {
  background: transparent;
  border: none;
  font-size: 1.2rem;
  padding: 3px 6px;
  transition: transform 0.2s ease;
}
.icon-btn:hover { transform: scale(1.2); }
.icon-view { color: #198754; }
.icon-delete { color: #dc3545; }

/* === MODAL === */
body.dark .modal-content {
  background-color: #1f1f1f !important;
  color: #f8f9fa !important;
}
body.dark .modal-header {
  background-color: #198754 !important;
  color: #fff !important;
}
body.dark .btn-close { filter: invert(1); }

/* === FOOTER === */
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
    margin: 0 1rem 1.5rem;
  }
  
  .table-responsive {
    font-size: 0.9rem;
  }
  
  .icon-btn {
    font-size: 1rem;
    padding: 2px 4px;
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
    margin: 0 0.5rem 1rem;
    padding: 1rem;
  }
  
  .table-responsive {
    font-size: 0.8rem;
  }
  
  .icon-btn {
    font-size: 0.9rem;
    padding: 1px 3px;
  }
  
  .modal-dialog {
    margin: 1rem;
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
    margin: 0 0.25rem 0.75rem;
    padding: 0.75rem;
  }
  
  .table-responsive {
    font-size: 0.75rem;
  }
  
  .icon-btn {
    font-size: 0.8rem;
  }
  
  .modal-dialog {
    margin: 0.5rem;
  }
}

/* Landscape mode untuk mobile */
@media (max-width: 768px) and (orientation: landscape) {
  .card {
    margin: 0 1rem 1rem;
    padding: 1rem;
  }
  
  .table-responsive {
    font-size: 0.85rem;
  }
}

/* === PRINT MODE === */
@media print {
  body * { visibility: hidden; }
  .modal-content, .modal-content * { visibility: visible; }
  .modal-content { position: absolute; left: 0; top: 0; width: 100%; }
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
      <a href="profile.php" class="btn btn-outline-light btn-sm">👤 Profile</a>
      <a href="logout.php" class="btn btn-outline-light btn-sm">🚪 Logout</a>
    </div>
  </div>
</nav>

<main>
  <!-- Header Container -->
  <div class="container mt-4 mt-md-5">
    <div class="d-flex justify-content-between align-items-center flex-column flex-md-row gap-2 px-3 py-2 rounded-3 shadow-sm"
         style="background: var(--card-bg); border:1px solid rgba(0,0,0,0.05);">
      <h4 class="text-success fw-bold mb-0">Riwayat Rekomendasi</h4>
      <a href="dashboard.php" class="btn btn-outline-success btn-sm">🏠 Kembali ke Dashboard</a>
    </div>
  </div>

  <!-- Konten Utama -->
  <div class="container mb-5 mt-4">
    <div class="card p-4">
      <p class="text-muted">Berikut daftar rekomendasi menu yang pernah kamu simpan.</p>

      <?php if (mysqli_num_rows($riwayat) == 0): ?>
        <div class="alert alert-warning text-center">Belum ada riwayat yang tersimpan.</div>
      <?php else: ?>
        <div class="table-responsive">
          <table class="table table-hover align-middle">
            <thead>
              <tr>
                <th>No</th>
                <th>Waktu</th>
                <th>Total Kalori</th>
                <th>Menu Rekomendasi</th>
                <th class="text-center">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php $no=1; while($row = mysqli_fetch_assoc($riwayat)):
                $menu = json_decode($row['menu_json'], true);
                $tanggal = date('d M Y, H:i', strtotime($row['tanggal']));
              ?>
              <tr>
                <td><?= $no++ ?></td>
                <td><?= $tanggal ?></td>
                <td><strong><?= round($row['total_kalori'],2) ?> kkal</strong></td>
                <td>
                  🍳 <?= $menu['sarapan'][0] ?? '-' ?>,
                  🍱 <?= $menu['siang'][0] ?? '-' ?>,
                  🌙 <?= $menu['malam'][0] ?? '-' ?>
                </td>
                <td class="text-center">
                  <button class="icon-btn icon-view" title="Lihat Detail" data-bs-toggle="modal" data-bs-target="#detail<?= $row['id'] ?>">👁️</button>
                  <a href="?hapus=<?= $row['id'] ?>" onclick="return confirm('Yakin ingin menghapus riwayat ini?')" class="icon-btn icon-delete" title="Hapus">🗑️</a>
                </td>
              </tr>

              <!-- Modal Detail -->
              <div class="modal fade" id="detail<?= $row['id'] ?>" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                  <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                      <h5 class="modal-title">Detail Rekomendasi - <?= $tanggal ?></h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                      <div class="d-flex justify-content-between align-items-center mb-3">
                        <p><strong>Total Kalori:</strong> <?= round($row['total_kalori'],2) ?> kkal</p>
                        <button class="btn btn-outline-success btn-sm" onclick="window.print()">🖨️ Print</button>
                      </div>
                      <hr>
                      <h6 class="text-primary">🍳 Sarapan</h6>
                      <ul>
                        <?php foreach ($menu['sarapan'] ?? [] as $item): ?>
                          <li><?= htmlspecialchars($item) ?></li>
                        <?php endforeach; ?>
                      </ul>

                      <h6 class="text-danger mt-3">🍱 Makan Siang</h6>
                      <ul>
                        <?php foreach ($menu['siang'] ?? [] as $item): ?>
                          <li><?= htmlspecialchars($item) ?></li>
                        <?php endforeach; ?>
                      </ul>

                      <h6 class="text-success mt-3">🌙 Makan Malam</h6>
                      <ul>
                        <?php foreach ($menu['malam'] ?? [] as $item): ?>
                          <li><?= htmlspecialchars($item) ?></li>
                        <?php endforeach; ?>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </div>
  </div>
</main>

<!-- Footer -->
<footer>
  <p>© <?= date("Y") ?> DSSRK — Sistem Rekomendasi Kalori. Dibuat oleh Rifki Figianto</p>
</footer>

<!-- Dark Mode Script -->
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