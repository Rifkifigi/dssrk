<?php
session_start();
include "koneksi.php";

// Ambil data dari form
 $nama      = $_POST['nama'] ?? '';
 $jk        = $_POST['jk'] ?? '';
 $usia      = floatval($_POST['usia'] ?? 0);
 $berat     = floatval($_POST['berat'] ?? 0);
 $tinggi    = floatval($_POST['tinggi'] ?? 0);
 $aktivitas = $_POST['aktivitas'] ?? '';
 $pantangan = trim($_POST['pantangan'] ?? '');

// --- 1️⃣ HITUNG BMR ---
if ($jk === 'pria') {
    $bmr = 66 + (13.7 * $berat) + (5 * $tinggi) - (6.8 * $usia);
} else {
    $bmr = 655 + (9.6 * $berat) + (1.8 * $tinggi) - (4.7 * $usia);
}

// --- 2️⃣ HITUNG TDEE (Total Daily Energy Expenditure) ---
switch ($aktivitas) {
    case "rendah": $tdee = $bmr * 1.2; break;
    case "sedang": $tdee = $bmr * 1.55; break;
    case "tinggi": $tdee = $bmr * 1.9; break;
    default: $tdee = $bmr * 1.2; break;
}

// --- 3️⃣ FUZZYFIKASI RELATIF BERBASIS TDEE ---
// Fuzzy Set Dinamis:
// Rendah  : ≤ 0.9 × TDEE
// Sedang  : 0.9 – 1.1 × TDEE
// Tinggi  : ≥ 1.1 × TDEE

$batas_rendah_max = 0.8 * $tdee;   // μ=1 di sini
$batas_rendah_nol = 0.9 * $tdee;   // μ=0 di sini

$batas_sedang_mulai = 0.9 * $tdee; // μ=0 → naik
$batas_sedang_puncak = 1.0 * $tdee; // μ=1
$batas_sedang_akhir = 1.1 * $tdee;  // μ=0

$batas_tinggi_mulai = 1.1 * $tdee;  // μ=0 → naik
$batas_tinggi_puncak = 1.2 * $tdee; // μ=1

// Fungsi membership dinamis
function mu_rendah($x, $max, $nol) {
    if ($x <= $max) return 1;
    if ($x >= $nol) return 0;
    return ($nol - $x) / ($nol - $max);
}

function mu_sedang($x, $mulai, $puncak, $akhir) {
    if ($x <= $mulai || $x >= $akhir) return 0;
    if ($x == $puncak) return 1;
    if ($x < $puncak) return ($x - $mulai) / ($puncak - $mulai);
    return ($akhir - $x) / ($akhir - $puncak);
}

function mu_tinggi($x, $mulai, $puncak) {
    if ($x <= $mulai) return 0;
    if ($x >= $puncak) return 1;
    return ($x - $mulai) / ($puncak - $mulai);
}

$rendah = mu_rendah($tdee, $batas_rendah_max, $batas_rendah_nol);
$sedang = mu_sedang($tdee, $batas_sedang_mulai, $batas_sedang_puncak, $batas_sedang_akhir);
$tinggi_val = mu_tinggi($tdee, $batas_tinggi_mulai, $batas_tinggi_puncak);

 $kategori_fuzzy = 'rendah';
 $max_mu = $rendah;
if ($sedang > $max_mu) { $max_mu = $sedang; $kategori_fuzzy = 'sedang'; }
if ($tinggi_val > $max_mu) { $max_mu = $tinggi_val; $kategori_fuzzy = 'tinggi'; }

// --- 4️⃣ NILAI DEFUZZYFIKASI (Weighted Average Method) ---
$nilai_crisp = (
    ($rendah * $batas_rendah_max) + 
    ($sedang * $batas_sedang_puncak) + 
    ($tinggi_val * $batas_tinggi_puncak)
) / (($rendah + $sedang + $tinggi_val) ?: 1);

// --- 5️⃣ SIMPAN KE DATABASE ---
 $pantangan_sql = $pantangan !== '' ? "'$pantangan'" : "'tidak ada'";
 $query = "
INSERT INTO user_input 
(nama, jk, usia, berat, tinggi, aktivitas, kalori, kategori_fuzzy, tanggal_input, pantangan, crisp_value)
VALUES
('$nama', '$jk', '$usia', '$berat', '$tinggi', '$aktivitas', '$tdee', '$kategori_fuzzy', NOW(), $pantangan_sql, '$nilai_crisp')
";

if (!mysqli_query($koneksi, $query)) {
    die('❌ Gagal menyimpan data: ' . mysqli_error($koneksi));
}

 $id = mysqli_insert_id($koneksi);
if ($id == 0) {
    die('⚠️ ID tidak terbentuk. Pastikan kolom `id` di tabel user_input memiliki AUTO_INCREMENT.');
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>📊 Fuzzyfikasi - DSSRK</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

body.dark .info-card h4, 
body.dark .info-card p, 
body.dark .chart-box h5 {
  color: #fff !important;
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

/* === CONTENT === */
h3.title {
  text-align: center;
  font-weight: 700;
  margin-top: 40px;
  margin-bottom: 30px;
  color: #198754;
  font-size: 1.8rem;
}

/* flex container untuk keterangan + tombol */
.info-wrapper {
  display: flex;
  justify-content: center;
  align-items: stretch;
  flex-wrap: wrap;
  gap: 20px;
  margin: 0 auto 40px;
  max-width: 1100px;
}
.info-card {
  flex: 1 1 300px;
  background: var(--card-bg);
  border-radius: 20px;
  padding: 25px;
  box-shadow: 0 6px 15px rgba(0,0,0,0.08);
}
.action-card {
  flex: 1 1 200px;
  background: var(--card-bg);
  border-radius: 20px;
  padding: 25px;
  box-shadow: 0 6px 15px rgba(0,0,0,0.08);
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
}
.action-card .btn {
  font-size: 1.1rem;
  font-weight: 600;
  border-radius: 10px;
  padding: 12px 20px;
  width: 100%;
}

/* Chart */
.chart-box {
  background: var(--card-bg);
  border-radius: 20px;
  padding: 20px;
  box-shadow: 0 6px 15px rgba(0,0,0,0.08);
  max-width: 1000px;
  margin: 0 auto 30px;
}
.chart-container {
  position: relative;
  height: 300px;
  width: 100%;
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
  
  h3.title {
    font-size: 1.5rem;
    margin-top: 30px;
    margin-bottom: 20px;
  }
  
  .info-card, .action-card {
    padding: 20px;
  }
  
  .action-card .btn {
    font-size: 1rem;
    padding: 10px 15px;
  }
  
  .chart-container {
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
  
  h3.title {
    font-size: 1.3rem;
    margin-top: 20px;
    margin-bottom: 15px;
  }
  
  .info-wrapper {
    gap: 15px;
  }
  
  .info-card, .action-card {
    padding: 15px;
  }
  
  .chart-container {
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
  
  h3.title {
    font-size: 1.2rem;
  }
  
  .info-card, .action-card {
    padding: 12px;
  }
  
  .action-card .btn {
    font-size: 0.9rem;
    padding: 9px 12px;
  }
}

/* Landscape mode untuk mobile */
@media (max-width: 768px) and (orientation: landscape) {
  .info-card, .action-card {
    padding: 15px;
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
  <div class="container">
    <h3 class="title">📊 Fuzzyfikasi Kebutuhan Kalori (TDEE-Based)</h3>

    <div class="info-wrapper">
      <div class="info-card">
        <h4 class="text-center fw-bold mb-3 text-success">Identitas Pengguna</h4>
        <p>👤 Nama: <strong><?= strtoupper(htmlspecialchars($nama)) ?></strong></p>
        <p>⚡ BMR (Basal Metabolic Rate): <strong><?= round($bmr, 1) ?> kkal</strong></p>
        <p>🔥 TDEE (Total Daily Energy Expenditure): <strong><?= round($tdee, 1) ?> kkal</strong></p>
        <p>🧠 Kategori Fuzzy: <strong><?= ucfirst($kategori_fuzzy) ?></strong> (μ=<?= round($max_mu, 3) ?>)</p>
        <p>📈 Nilai Defuzzyfikasi (Crisp): <strong><?= round($nilai_crisp, 2) ?> kkal</strong></p>
        <p>🚫 Pantangan: <strong><?= $pantangan ? htmlspecialchars($pantangan) : 'Tidak ada' ?></strong></p>
        <hr>
        <small class="text-muted">
          ✅ <strong>Fuzzy Set Relatif (Berbasis TDEE):</strong><br>
          🔹 Rendah: ≤ <?= round(0.9 * $tdee, 0) ?> kkal (≤0.9×TDEE)<br>
          🔸 Sedang: <?= round(0.9 * $tdee, 0) ?>–<?= round(1.1 * $tdee, 0) ?> kkal (0.9–1.1×TDEE)<br>
          🔺 Tinggi: ≥ <?= round(1.1 * $tdee, 0) ?> kkal (≥1.1×TDEE)
        </small>
      </div>

      <div class="action-card">
        <a href="hasil.php?id=<?= $id ?>" class="btn btn-success btn-lg shadow-sm">
          🍽️ Lihat Rekomendasi Menu
        </a>
      </div>
    </div>

    <div class="chart-box">
      <h5 class="text-center fw-bold mb-4">Visualisasi Derajat Keanggotaan (μ)</h5>
      <div class="chart-container">
        <canvas id="fuzzyChart"></canvas>
      </div>
    </div>
  </div>
</main>

<footer>
  <p>© <?= date("Y") ?> DSSRK — Sistem Rekomendasi Kalori | Rifki Figianto</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// TDEE dan batas fuzzy dari PHP
const tdee = <?= round($tdee, 2) ?>;
const batasRendahMax = <?= round($batas_rendah_max, 2) ?>;
const batasRendahNol = <?= round($batas_rendah_nol, 2) ?>;
const batasSedangMulai = <?= round($batas_sedang_mulai, 2) ?>;
const batasSedangPuncak = <?= round($batas_sedang_puncak, 2) ?>;
const batasSedangAkhir = <?= round($batas_sedang_akhir, 2) ?>;
const batasTinggiMulai = <?= round($batas_tinggi_mulai, 2) ?>;
const batasTinggiPuncak = <?= round($batas_tinggi_puncak, 2) ?>;

// Generate xVals dinamis: dari 0.7×TDEE sampai 1.3×TDEE
const xMin = Math.floor(0.7 * tdee);
const xMax = Math.ceil(1.3 * tdee);
const xVals = [];
for(let i = xMin; i <= xMax; i += 50) xVals.push(i);

// Fungsi membership
function muRendah(x) {
  if (x <= batasRendahMax) return 1;
  if (x >= batasRendahNol) return 0;
  return (batasRendahNol - x) / (batasRendahNol - batasRendahMax);
}

function muSedang(x) {
  if (x <= batasSedangMulai || x >= batasSedangAkhir) return 0;
  if (x === batasSedangPuncak) return 1;
  if (x < batasSedangPuncak) return (x - batasSedangMulai) / (batasSedangPuncak - batasSedangMulai);
  return (batasSedangAkhir - x) / (batasSedangAkhir - batasSedangPuncak);
}

function muTinggi(x) {
  if (x <= batasTinggiMulai) return 0;
  if (x >= batasTinggiPuncak) return 1;
  return (x - batasTinggiMulai) / (batasTinggiPuncak - batasTinggiMulai);
}

const rendahData = xVals.map(x => muRendah(x));
const sedangData = xVals.map(x => muSedang(x));
const tinggiData = xVals.map(x => muTinggi(x));

// Chart
const ctx = document.getElementById('fuzzyChart').getContext('2d');
new Chart(ctx, {
  type: 'line',
  data: {
    labels: xVals,
    datasets: [
      { label:'Rendah', data:rendahData, borderColor:'#36A2EB', tension:0.3, fill:false, borderWidth: 2 },
      { label:'Sedang', data:sedangData, borderColor:'#FFCE56', tension:0.3, fill:false, borderWidth: 2 },
      { label:'Tinggi', data:tinggiData, borderColor:'#FF6384', tension:0.3, fill:false, borderWidth: 2 },
      { 
        label:`TDEE Anda (${Math.round(tdee)} kkal)`, 
        data: xVals.map(x => Math.abs(x - tdee) < 25 ? 1 : null),
        pointRadius: 8, borderWidth: 0, backgroundColor: '#4CAF50', type: 'scatter'
      }
    ]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    scales: {
      y: { 
        beginAtZero: true, 
        max: 1.1, 
        title: { display:true, text:'μ (Derajat Keanggotaan)', font: {size: 14, weight: 'bold'} },
        ticks: { stepSize: 0.1 }
      },
      x: { 
        title: { display:true, text:'Kebutuhan Kalori (kkal)', font: {size: 14, weight: 'bold'} } 
      }
    },
    plugins: { 
      legend: { 
        position:'top', 
        labels: {font: {size: 13}} 
      },
      tooltip: {
        mode: 'index',
        intersect: false,
        callbacks: {
          label: function(context) {
            let label = context.dataset.label || '';
            if (label) label += ': ';
            if (context.parsed.y !== null) {
              label += context.parsed.y.toFixed(3);
            }
            return label;
          }
        }
      }
    }
  }
});

// Dark mode toggle
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