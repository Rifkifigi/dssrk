<?php
session_start();
include "koneksi.php";

 $id = $_GET['id'] ?? 0;
if (!$id) {
    die("<h3 style='text-align:center;margin-top:50px;color:red;'>⚠️ ID tidak ditemukan. Silakan isi data terlebih dahulu.</h3>");
}

// Ambil data user
 $q_user = mysqli_query($koneksi, "SELECT * FROM user_input WHERE id='$id'");
if (!$q_user || mysqli_num_rows($q_user) == 0) {
    die("<h3 style='text-align:center;margin-top:50px;color:red;'>❌ Data pengguna tidak ditemukan.</h3>");
}
 $user = mysqli_fetch_assoc($q_user);
 $kalori_total = round($user['crisp_value'], 2);
 $pantangan_raw = strtolower(trim($user['pantangan'] ?? ''));
 $pantangan_list = array_filter(array_map('trim', explode(',', $pantangan_raw)));

// Hitung target kalori per waktu makan
 $target_sarapan = round($kalori_total * 0.3, 2);
 $target_siang   = round($kalori_total * 0.4, 2);
 $target_malam   = round($kalori_total * 0.3, 2);

// Ambil semua makanan
 $menu_all = mysqli_query($koneksi, "SELECT * FROM data_makanan ORDER BY RAND()");

// ✅ Fungsi rekomendasi dengan multi-pantangan
function buatRekomendasi(&$menu_all, &$terpakai, $target_kalori, $pantangan_list = []) {
    $rekom = [];
    $total = 0;
    mysqli_data_seek($menu_all, 0);

    while ($row = mysqli_fetch_assoc($menu_all)) {
        $nama = strtolower($row['name']);
        $desc = strtolower($row['description'] ?? '');

        // Skip kalau sudah terpakai
        if (in_array($row['id'], $terpakai)) continue;

        // Skip kalau mengandung salah satu pantangan
        $skip = false;
        foreach ($pantangan_list as $p) {
            if ($p && (str_contains($nama, $p) || str_contains($desc, $p))) {
                $skip = true;
                break;
            }
        }
        if ($skip) continue;

        // Tambahkan makanan sampai cukup
        if ($total < $target_kalori) {
            $rekom[] = $row;
            $terpakai[] = $row['id'];
            $total += floatval($row['calories']);
        } else break;
    }
    return ['data'=>$rekom, 'total'=>$total];
}

 $terpakai = [];
 $rekom_sarapan = buatRekomendasi($menu_all, $terpakai, $target_sarapan, $pantangan_list);
 $rekom_siang   = buatRekomendasi($menu_all, $terpakai, $target_siang, $pantangan_list);
 $rekom_malam   = buatRekomendasi($menu_all, $terpakai, $target_malam, $pantangan_list);

// Simpan ke riwayat
if (isset($_POST['simpan'])) {
    $menu_json = json_encode([
        'sarapan'=>array_column($rekom_sarapan['data'],'name'),
        'siang'=>array_column($rekom_siang['data'],'name'),
        'malam'=>array_column($rekom_malam['data'],'name')
    ]);
    $user_id = $_SESSION['user_id'] ?? 1;
    $total = $rekom_sarapan['total']+$rekom_siang['total']+$rekom_malam['total'];
    mysqli_query($koneksi,"INSERT INTO riwayat (user_id,total_kalori,menu_json)
        VALUES('$user_id','$total','".mysqli_real_escape_string($koneksi,$menu_json)."')");
    $pesan="✅ Rekomendasi berhasil disimpan ke riwayat!";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>🍽️ Rekomendasi Menu Harian - DSSRK</title>
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

/* Info Bar */
.info-bar {
  display: flex;
  justify-content: space-between;
  align-items: stretch;
  flex-wrap: wrap;
  gap: 1.5rem;
  margin: 40px auto;
  max-width: 1100px;
}
.info-box {
  flex: 1 1 65%;
  background: var(--card-bg);
  padding: 25px 30px;
  border-radius: 12px;
  box-shadow: 0 4px 10px rgba(0,0,0,0.08);
}
.info-box h4 { color: #198754; font-weight: 700; }

/* Action buttons */
.action-box {
  flex: 1 1 30%;
  background: var(--card-bg);
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 15px;
  box-shadow: 0 4px 10px rgba(0,0,0,0.08);
  flex-wrap: wrap;
  padding: 15px;
}
.action-box .btn {
  border-radius: 10px;
  font-weight: 600;
  width: 120px;
  padding: 10px;
}

/* Rekomendasi Container */
.rekom-row {
  display: flex;
  justify-content: center;
  flex-wrap: wrap;
  gap: 30px;
  margin: 50px auto;
  max-width: 1200px;
}
.container-section {
  background: var(--card-bg);
  flex: 1 1 300px;
  text-align: center;
  border-radius: 15px;
  padding: 30px;
  box-shadow: 0 6px 15px rgba(0,0,0,0.08);
  transition: 0.3s;
  cursor: pointer;
}
.container-section:hover {
  transform: scale(1.05);
  box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}

/* Carousel */
.carousel-item img {
  width: 100%;
  height: 320px;
  object-fit: cover;
  border-radius: 10px;
}
.carousel-caption {
  background: rgba(0,0,0,0.6);
  border-radius: 10px;
  padding: 10px;
}

/* Footer */
footer {
  background: var(--footer-bg);
  color: #fff;
  text-align: center;
  padding: 15px 10px;
  margin-top: auto;
  font-size: 0.85rem;
}

/* Alert dark mode */
body.dark .alert-success {
  background-color: #1f4037;
  color: #b8f7c2;
  border: 1px solid #2dc673;
}
body.dark .info-box h4, 
body.dark .info-box p,
body.dark .container-section h3,
body.dark .container-section p,
body.dark footer { color: #fff !important; }

/* Dark mode adjustments */
body.dark .modal-content {
  background-color: #1e1e1e !important;
  color: #f8f9fa !important;
}
body.dark .modal-header {
  background-color: #198754 !important;
  color: #fff !important;
}
body.dark .btn-close {
  filter: invert(1);
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
  
  .info-bar {
    margin: 30px 15px;
    gap: 1rem;
  }
  
  .info-box {
    padding: 20px;
  }
  
  .action-box {
    padding: 15px;
    gap: 10px;
  }
  
  .action-box .btn {
    width: 100px;
    font-size: 0.9rem;
    padding: 8px;
  }
  
  .rekom-row {
    gap: 20px;
    margin: 30px 15px;
  }
  
  .container-section {
    padding: 25px;
  }
  
  .carousel-item img {
    height: 250px;
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
  
  .info-bar {
    flex-direction: column;
    gap: 1rem;
    margin: 20px 10px;
  }
  
  .info-box, .action-box {
    flex: 1 1 100%;
  }
  
  .action-box {
    padding: 15px;
    gap: 10px;
  }
  
  .action-box .btn {
    width: 100px;
    font-size: 0.85rem;
    padding: 8px;
  }
  
  .rekom-row {
    gap: 15px;
    margin: 20px 10px;
  }
  
  .container-section {
    padding: 20px;
    flex: 1 1 100%;
  }
  
  .carousel-item img {
    height: 200px;
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
  
  .info-box {
    padding: 15px;
  }
  
  .action-box {
    padding: 10px;
  }
  
  .action-box .btn {
    width: 90px;
    font-size: 0.8rem;
    padding: 7px;
  }
  
  .container-section {
    padding: 15px;
  }
  
  .carousel-item img {
    height: 180px;
  }
}

/* Landscape mode untuk mobile */
@media (max-width: 768px) and (orientation: landscape) {
  .info-bar {
    margin: 15px 10px;
  }
  
  .info-box, .action-box {
    padding: 15px;
  }
  
  .rekom-row {
    margin: 20px 10px;
  }
  
  .container-section {
    padding: 15px;
  }
  
  .carousel-item img {
    height: 200px;
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
      <a href="profile.php" class="btn btn-outline-light btn-sm">👤 Profile</a>
      <a href="logout.php" class="btn btn-outline-light btn-sm">🚪 Logout</a>
    </div>
  </div>
</nav>

<main>
  <div class="info-bar">
    <div class="info-box">
      <h4>🍽️ Rekomendasi Menu Harian untuk <?= strtoupper($user['nama']) ?></h4>
      <p>🔥 Kebutuhan Kalori: <strong><?= $kalori_total ?> kkal</strong></p>
      <p>🚫 Pantangan: <strong><?= $pantangan_raw ?: 'Tidak ada' ?></strong></p>
    </div>
    <div class="action-box">
      <a href="dashboard.php" class="btn btn-outline-success">🏠 Dashboard</a>
      <form method="POST" class="d-inline">
        <button type="submit" name="simpan" class="btn btn-success">💾 Simpan</button>
      </form>
    </div>
  </div>

  <?php if(!empty($pesan)): ?>
    <div class="alert alert-success text-center mx-auto" style="max-width:600px;"><?= $pesan ?></div>
  <?php endif; ?>

  <div class="rekom-row">
    <div class="container-section" data-bs-toggle="modal" data-bs-target="#sarapanModal">
      <h3 class="text-primary">🍳 Sarapan</h3>
      <p>Total Kalori: <?= round($rekom_sarapan['total'],2) ?> / <?= $target_sarapan ?> kkal</p>
    </div>
    <div class="container-section" data-bs-toggle="modal" data-bs-target="#siangModal">
      <h3 class="text-danger">🥗 Makan Siang</h3>
      <p>Total Kalori: <?= round($rekom_siang['total'],2) ?> / <?= $target_siang ?> kkal</p>
    </div>
    <div class="container-section" data-bs-toggle="modal" data-bs-target="#malamModal">
      <h3 class="text-success">🌙 Makan Malam</h3>
      <p>Total Kalori: <?= round($rekom_malam['total'],2) ?> / <?= $target_malam ?> kkal</p>
    </div>
  </div>

  <?php
  $modals = [
    'sarapan' => ['title'=>'🍳 Menu Sarapan','class'=>'bg-primary','data'=>$rekom_sarapan['data']],
    'siang'   => ['title'=>'🥗 Menu Makan Siang','class'=>'bg-danger','data'=>$rekom_siang['data']],
    'malam'   => ['title'=>'🌙 Menu Makan Malam','class'=>'bg-success','data'=>$rekom_malam['data']]
  ];
  foreach($modals as $key=>$m):
  ?>
  <div class="modal fade" id="<?= $key ?>Modal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header <?= $m['class'] ?> text-white">
          <h5 class="modal-title"><?= $m['title'] ?></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <?php if(empty($m['data'])): ?>
            <p class="text-center text-muted">Tidak ada rekomendasi karena pantangan: <strong><?= htmlspecialchars($pantangan_raw) ?></strong></p>
          <?php else: ?>
          <div id="carousel<?= ucfirst($key) ?>" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
              <?php $i=0; foreach($m['data'] as $item):
                $img = !empty($item['image']) ? $item['image'] : 'https://via.placeholder.com/600x400?text=Gambar+Tidak+Tersedia'; ?>
                <div class="carousel-item <?= $i==0?'active':'' ?>">
                  <img src="<?= $img ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                  <div class="carousel-caption">
                    <h5><?= htmlspecialchars($item['name']) ?></h5>
                    <p>Kalori: <?= $item['calories'] ?> | Protein: <?= $item['proteins'] ?>g | Lemak: <?= $item['fat'] ?>g | Karbo: <?= $item['carbohydrate'] ?>g</p>
                  </div>
                </div>
              <?php $i++; endforeach; ?>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carousel<?= ucfirst($key) ?>" data-bs-slide="prev">
              <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carousel<?= ucfirst($key) ?>" data-bs-slide="next">
              <span class="carousel-control-next-icon"></span>
            </button>
          </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
  <?php endforeach; ?>
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