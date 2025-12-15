<?php
include "koneksi.php";
// Ambil sample data makanan untuk preview
$sample_foods = mysqli_query($koneksi, "SELECT * FROM data_makanan ORDER BY RAND() LIMIT 5");
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>DSSRK - Sistem Rekomendasi Kalori Harian</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
<style>
:root {
  --primary-green: #045a29;
  --light-green: #4ebf75;
  --accent-yellow: #f8f404;
  --dark-green: #0d4e1c;
  --text-dark: #2c3e50;
  --orange: #ff6b35;
}

* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  font-family: 'Poppins', sans-serif;
  color: var(--text-dark);
  overflow-x: hidden;
}

/* ====== HERO SECTION ====== */
.hero-section {
  min-height: 100vh;
  background: linear-gradient(135deg, var(--primary-green) 0%, var(--light-green) 100%);
  color: white;
  position: relative;
  overflow: hidden;
  display: flex;
  align-items: center;
  padding: 100px 0 150px;
}

.hero-section::before {
  content: '';
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  height: 150px;
  background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120"><path fill="%23ffffff" d="M0 120 Q300 80 600 100 T1200 100 V120 H0z"/></svg>');
  background-size: cover;
}

.hero-decoration {
  position: absolute;
  opacity: 0.1;
  font-size: 200px;
}

.deco-1 { top: 10%; left: 5%; animation: float 8s ease-in-out infinite; }
.deco-2 { bottom: 20%; right: 5%; animation: float 6s ease-in-out infinite reverse; }

.hero-content {
  position: relative;
  z-index: 2;
  animation: fadeInUp 1s ease;
}

.hero-badge {
  display: inline-block;
  background: rgba(255, 255, 255, 0.2);
  backdrop-filter: blur(10px);
  padding: 10px 25px;
  border-radius: 50px;
  font-size: 0.9rem;
  font-weight: 600;
  margin-bottom: 25px;
  border: 2px solid rgba(255, 255, 255, 0.3);
}

.hero-title {
  font-size: 3.5rem;
  font-weight: 800;
  line-height: 1.2;
  margin-bottom: 20px;
  text-shadow: 2px 4px 10px rgba(0, 0, 0, 0.2);
}

.hero-subtitle {
  font-size: 1.3rem;
  opacity: 0.95;
  margin-bottom: 40px;
  line-height: 1.6;
}

.hero-buttons {
  display: flex;
  gap: 20px;
  flex-wrap: wrap;
}

.btn-primary-custom {
  background: white;
  color: var(--primary-green);
  padding: 15px 40px;
  border-radius: 50px;
  font-weight: 600;
  font-size: 1.1rem;
  border: none;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
  transition: all 0.3s ease;
  text-decoration: none;
  display: inline-block;
}

.btn-primary-custom:hover {
  background: var(--accent-yellow);
  transform: translateY(-3px);
  box-shadow: 0 6px 25px rgba(0, 0, 0, 0.3);
}

.btn-outline-custom {
  background: transparent;
  color: white;
  padding: 15px 40px;
  border-radius: 50px;
  font-weight: 600;
  font-size: 1.1rem;
  border: 2px solid white;
  transition: all 0.3s ease;
  text-decoration: none;
  display: inline-block;
}

.btn-outline-custom:hover {
  background: white;
  color: var(--primary-green);
  transform: translateY(-3px);
}

.hero-illustration {
  text-align: center;
  animation: fadeInUp 1s ease 0.3s both;
}

.hero-illustration i {
  font-size: 300px;
  opacity: 0.9;
  filter: drop-shadow(0 10px 30px rgba(0, 0, 0, 0.2));
}

/* ====== FEATURES SECTION ====== */
.features-section {
  padding: 100px 0;
  background: #ffffff;
}

.section-title {
  font-size: 2.5rem;
  font-weight: 700;
  color: var(--primary-green);
  text-align: center;
  margin-bottom: 20px;
}

.section-subtitle {
  text-align: center;
  color: #666;
  font-size: 1.1rem;
  margin-bottom: 60px;
}

.feature-card {
  background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
  border-radius: 20px;
  padding: 40px 30px;
  text-align: center;
  transition: all 0.4s ease;
  height: 100%;
  border: 2px solid transparent;
  position: relative;
  overflow: hidden;
}

.feature-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 4px;
  background: linear-gradient(90deg, var(--primary-green), var(--light-green));
  transform: scaleX(0);
  transition: transform 0.4s ease;
}

.feature-card:hover {
  transform: translateY(-10px);
  box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
  border-color: var(--light-green);
}

.feature-card:hover::before {
  transform: scaleX(1);
}

.feature-icon {
  font-size: 4rem;
  color: var(--light-green);
  margin-bottom: 25px;
  display: inline-block;
  transition: transform 0.3s ease;
}

.feature-card:hover .feature-icon {
  transform: scale(1.1) rotate(5deg);
}

.feature-title {
  font-size: 1.4rem;
  font-weight: 700;
  color: var(--primary-green);
  margin-bottom: 15px;
}

.feature-text {
  color: #666;
  font-size: 1rem;
  line-height: 1.7;
}

/* ====== DASHBOARD PREVIEW SECTION ====== */
.preview-section {
  padding: 100px 0;
  background: linear-gradient(180deg, #f8f9fa 0%, #ffffff 100%);
}

.dashboard-preview {
  background: white;
  border-radius: 20px;
  padding: 40px;
  box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
  position: relative;
  overflow: hidden;
}

.preview-blur {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  backdrop-filter: blur(8px);
  background: rgba(255, 255, 255, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 10;
}

.blur-content {
  text-align: center;
  background: white;
  padding: 40px;
  border-radius: 20px;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
}

.blur-content h3 {
  font-size: 1.8rem;
  font-weight: 700;
  color: var(--primary-green);
  margin-bottom: 15px;
}

.blur-content p {
  color: #666;
  margin-bottom: 25px;
}

.preview-table {
  margin-top: 30px;
}

.table-custom {
  background: white;
  border-radius: 15px;
  overflow: hidden;
}

.table-custom thead {
  background: linear-gradient(135deg, var(--primary-green), var(--light-green));
  color: white;
}

.table-custom th {
  padding: 15px;
  font-weight: 600;
}

.table-custom td {
  padding: 15px;
  vertical-align: middle;
}

.food-img-preview {
  width: 50px;
  height: 50px;
  object-fit: cover;
  border-radius: 10px;
}

/* ====== HOW IT WORKS SECTION ====== */
.how-it-works {
  padding: 100px 0;
  background: white;
}

.step-card {
  text-align: center;
  padding: 30px 20px;
  position: relative;
}

.step-number {
  width: 80px;
  height: 80px;
  background: linear-gradient(135deg, var(--primary-green), var(--light-green));
  color: white;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 2rem;
  font-weight: 700;
  margin: 0 auto 25px;
  box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
}

.step-icon {
  font-size: 3rem;
  color: var(--light-green);
  margin-bottom: 20px;
}

.step-title {
  font-size: 1.3rem;
  font-weight: 700;
  color: var(--primary-green);
  margin-bottom: 15px;
}

.step-text {
  color: #666;
  line-height: 1.6;
}

.step-arrow {
  position: absolute;
  top: 40px;
  right: -30px;
  font-size: 3rem;
  color: var(--light-green);
  opacity: 0.3;
}

/* ====== ADVANTAGES SECTION ====== */
.advantages-section {
  padding: 100px 0;
  background: linear-gradient(135deg, var(--primary-green) 0%, var(--light-green) 100%);
  color: white;
  position: relative;
  overflow: hidden;
}

.advantages-section::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 100px;
  background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120"><path fill="%23ffffff" d="M0 0 Q300 40 600 20 T1200 20 V0 H0z"/></svg>');
  background-size: cover;
}

.advantage-item {
  display: flex;
  align-items: start;
  gap: 20px;
  margin-bottom: 30px;
  padding: 25px;
  background: rgba(255, 255, 255, 0.1);
  backdrop-filter: blur(10px);
  border-radius: 15px;
  border: 2px solid rgba(255, 255, 255, 0.2);
  transition: all 0.3s ease;
}

.advantage-item:hover {
  background: rgba(255, 255, 255, 0.2);
  transform: translateX(10px);
}

.advantage-icon {
  font-size: 2.5rem;
  color: var(--accent-yellow);
}

.advantage-content h4 {
  font-size: 1.2rem;
  font-weight: 700;
  margin-bottom: 8px;
}

.advantage-content p {
  opacity: 0.9;
  margin: 0;
}

/* ====== TESTIMONIAL SECTION ====== */
.testimonial-section {
  padding: 100px 0;
  background: #f8f9fa;
}

.testimonial-card {
  background: white;
  border-radius: 20px;
  padding: 35px;
  box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
  height: 100%;
  position: relative;
  transition: all 0.3s ease;
}

.testimonial-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
}

.quote-icon {
  font-size: 3rem;
  color: var(--light-green);
  opacity: 0.3;
  margin-bottom: 15px;
}

.testimonial-text {
  font-style: italic;
  color: #555;
  margin-bottom: 20px;
  line-height: 1.7;
}

.testimonial-author {
  display: flex;
  align-items: center;
  gap: 15px;
}

.author-avatar {
  width: 50px;
  height: 50px;
  background: linear-gradient(135deg, var(--primary-green), var(--light-green));
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-weight: 700;
  font-size: 1.2rem;
}

.author-info h5 {
  font-weight: 600;
  color: var(--primary-green);
  margin: 0;
  font-size: 1rem;
}

.author-info p {
  margin: 0;
  color: #666;
  font-size: 0.9rem;
}

/* ====== CTA SECTION ====== */
.cta-section {
  padding: 100px 0;
  background: linear-gradient(135deg, var(--orange) 0%, #ff8c5a 100%);
  color: white;
  text-align: center;
  position: relative;
  overflow: hidden;
}

.cta-section::before {
  content: '🎯';
  position: absolute;
  font-size: 300px;
  opacity: 0.1;
  top: -50px;
  right: -50px;
  animation: rotate 20s linear infinite;
}

.cta-title {
  font-size: 3rem;
  font-weight: 800;
  margin-bottom: 20px;
  text-shadow: 2px 4px 10px rgba(0, 0, 0, 0.2);
}

.cta-text {
  font-size: 1.2rem;
  margin-bottom: 40px;
  opacity: 0.95;
}

.cta-buttons {
  display: flex;
  gap: 20px;
  justify-content: center;
  flex-wrap: wrap;
}

/* ====== FOOTER ====== */
footer {
  background: linear-gradient(135deg, var(--dark-green) 0%, var(--primary-green) 100%);
  color: white;
  padding: 60px 0 30px;
}

.footer-section {
  margin-bottom: 30px;
}

.footer-title {
  font-size: 1.3rem;
  font-weight: 700;
  margin-bottom: 20px;
  color: var(--accent-yellow);
}

.footer-links {
  list-style: none;
  padding: 0;
}

.footer-links li {
  margin-bottom: 12px;
}

.footer-links a {
  color: rgba(255, 255, 255, 0.8);
  text-decoration: none;
  transition: all 0.3s ease;
}

.footer-links a:hover {
  color: white;
  padding-left: 5px;
}

.footer-bottom {
  border-top: 1px solid rgba(255, 255, 255, 0.2);
  padding-top: 30px;
  margin-top: 40px;
  text-align: center;
}

.social-icons {
  display: flex;
  gap: 15px;
  justify-content: center;
  margin-top: 20px;
}

.social-icons a {
  width: 40px;
  height: 40px;
  background: rgba(255, 255, 255, 0.1);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  text-decoration: none;
  transition: all 0.3s ease;
}

.social-icons a:hover {
  background: var(--accent-yellow);
  transform: translateY(-3px);
}

/* ====== ANIMATIONS ====== */
@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes float {
  0%, 100% { transform: translateY(0); }
  50% { transform: translateY(-20px); }
}

@keyframes rotate {
  from { transform: rotate(0deg); }
  to { transform: rotate(360deg); }
}

/* ====== RESPONSIVE ====== */
@media (max-width: 992px) {
  .hero-title { font-size: 2.5rem; }
  .section-title { font-size: 2rem; }
  .cta-title { font-size: 2.2rem; }
  .step-arrow { display: none; }
  .hero-illustration i { font-size: 200px; }
}

@media (max-width: 768px) {
  .hero-section { padding: 80px 0 120px; }
  .hero-title { font-size: 2rem; }
  .hero-subtitle { font-size: 1.1rem; }
  .hero-buttons { justify-content: center; }
  .btn-primary-custom, .btn-outline-custom { padding: 12px 30px; font-size: 1rem; }
  .features-section, .preview-section, .how-it-works, .advantages-section, .testimonial-section, .cta-section { padding: 60px 0; }
  .section-title { font-size: 1.8rem; }
  .cta-title { font-size: 1.8rem; }
  .hero-illustration { display: none; }
  .advantage-item { flex-direction: column; text-align: center; }
}

@media (max-width: 576px) {
  .hero-buttons, .cta-buttons { flex-direction: column; width: 100%; }
  .btn-primary-custom, .btn-outline-custom { width: 100%; text-align: center; }
  .table-custom { font-size: 0.85rem; }
  .food-img-preview { width: 40px; height: 40px; }
}
</style>
</head>
<body>

<!-- 1️⃣ HERO SECTION -->
<section class="hero-section">
  <div class="hero-decoration deco-1">🥗</div>
  <div class="hero-decoration deco-2">🍎</div>
  
  <div class="container">
    <div class="row align-items-center">
      <div class="col-lg-7">
        <div class="hero-content">
             <h1 class="hero-title">🍃DSSRK</h1>
          <p class="hero-subtitle">Hitung kebutuhan kalori berdasarkan BMR, aktivitas harian, dan dapatkan rekomendasi makanan yang tepat untuk gaya hidup sehat Anda.</p>
          <div class="hero-buttons">
            <a href="login.php" class="btn-primary-custom">🚀 Coba Sekarang</a>
            <a href="register.php" class="btn-outline-custom">📝 Daftar Gratis</a>
          </div>
        </div>
      </div>
      <div class="col-lg-5">
        <div class="hero-illustration">
          <i class="bi bi-heart-pulse-fill"></i>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- 2️⃣ FEATURES SECTION -->
<section class="features-section">
  <div class="container">
    <h2 class="section-title">Fitur Unggulan DSSRK</h2>
    <p class="section-subtitle">Sistem lengkap untuk membantu Anda mencapai target kesehatan</p>
    
    <div class="row g-4">
      <div class="col-md-6 col-lg-3">
        <div class="feature-card">
          <i class="bi bi-calculator-fill feature-icon"></i>
          <h3 class="feature-title">Perhitungan BMR Otomatis</h3>
          <p class="feature-text">Hitung kebutuhan kalori berdasarkan gender, usia, tinggi, berat, dan tingkat aktivitas harian Anda.</p>
        </div>
      </div>
      <div class="col-md-6 col-lg-3">
        <div class="feature-card">
          <i class="bi bi-bowl-hot-fill feature-icon"></i>
          <h3 class="feature-title">Rekomendasi Makanan</h3>
          <p class="feature-text">Sistem menampilkan rekomendasi makanan harian sesuai dengan kebutuhan kalori ideal Anda.</p>
        </div>
      </div>
      <div class="col-md-6 col-lg-3">
        <div class="feature-card">
          <i class="bi bi-clock-history feature-icon"></i>
          <h3 class="feature-title">Riwayat & Monitoring</h3>
          <p class="feature-text">Simpan dan pantau history perhitungan kalori untuk tracking progress kesehatan Anda.</p>
        </div>
      </div>
      <div class="col-md-6 col-lg-3">
        <div class="feature-card">
          <i class="bi bi-graph-up-arrow feature-icon"></i>
          <h3 class="feature-title">Fuzzy Logic</h3>
          <p class="feature-text">Menggunakan metode ilmiah fuzzy logic untuk hasil yang lebih akurat dan personal.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- 3️⃣ DASHBOARD PREVIEW -->
<section class="preview-section">
  <div class="container">
    <h2 class="section-title">Lihat Dashboard Anda</h2>
    <p class="section-subtitle">Preview fitur lengkap yang akan Anda dapatkan</p>
    
    <div class="dashboard-preview">
      <h3 class="mb-4"><i class="bi bi-table"></i> Preview Rekomendasi Makanan</h3>
      <div class="preview-table">
        <table class="table table-custom">
          <thead>
            <tr>
              <th>Gambar</th>
              <th>Nama Makanan</th>
              <th>Kalori</th>
              <th>Kategori</th>
            </tr>
          </thead>
          <tbody>
            <?php while($food = mysqli_fetch_assoc($sample_foods)): ?>
            <tr>
              <td><img src="<?= $food['image'] ?>" alt="<?= htmlspecialchars($food['name']) ?>" class="food-img-preview"></td>
              <td><?= htmlspecialchars($food['name']) ?></td>
              <td><strong><?= $food['calories'] ?></strong> kcal</td>
              <td>
                <?php if($food['calories'] <= 400): ?>
                  <span class="badge bg-success">Low Calorie</span>
                <?php else: ?>
                  <span class="badge bg-danger">High Calorie</span>
                <?php endif; ?>
              </td>
            </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
      
      <!-- Blur Overlay -->
      <div class="preview-blur">
        <div class="blur-content">
          <h3>🔒 Fitur Lengkap Tersedia Setelah Login</h3>
          <p>Daftar sekarang untuk mengakses dashboard lengkap, grafik kalori, dan rekomendasi personal!</p>
          <div class="d-flex gap-3 justify-content-center">
            <a href="login.php" class="btn btn-success btn-lg">Login Sekarang</a>
            <a href="register.php" class="btn btn-outline-success btn-lg">Daftar Gratis</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- 4️⃣ HOW IT WORKS -->
<section class="how-it-works">
  <div class="container">
    <h2 class="section-title">Cara Kerja Sistem</h2>
    <p class="section-subtitle">3 langkah mudah untuk mendapatkan rekomendasi kalori personal</p>
    
    <div class="row g-4">
      <div class="col-md-4">
        <div class="step-card">
          <div class="step-number">1</div>
          <i class="bi bi-person-fill-add step-icon"></i>
          <h3 class="step-title">Isi Data Diri</h3>
          <p class="step-text">Masukkan data seperti usia, tinggi badan, berat badan, gender, dan tingkat aktivitas harian Anda.</p>
          <span class="step-arrow d-none d-md-block">→</span>
        </div>
      </div>
      <div class="col-md-4">
        <div class="step-card">
          <div class="step-number">2</div>
          <i class="bi bi-cpu-fill step-icon"></i>
          <h3 class="step-title">Algoritma Mengolah Data</h3>
          <p class="step-text">Sistem menghitung BMR dan menggunakan fuzzy logic untuk menentukan kategori serta kalori ideal Anda.</p>
          <span class="step-arrow d-none d-md-block">→</span>
        </div>
      </div>
      <div class="col-md-4">
        <div class="step-card">
          <div class="step-number">3</div>
          <i class="bi bi-check-circle-fill step-icon"></i>
          <h3 class="step-title">Dapatkan Rekomendasi</h3>
          <p class="step-text">Terima daftar makanan dan angka kalori aman per hari yang sesuai dengan kebutuhan Anda.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- 5️⃣ TESTIMONIALS -->
<section class="testimonial-section">
  <div class="container">
    <h2 class="section-title">Apa Kata Mereka?</h2>
    <p class="section-subtitle">Pengalaman pengguna dengan sistem DSSRK</p>
    
    <div class="row g-4">
      <div class="col-md-4">
        <div class="testimonial-card">
          <div class="quote-icon">"</div>
          <p class="testimonial-text">Aplikasi sederhana dan membantu sekali untuk kontrol diet. Rekomendasi makanannya sangat praktis!</p>
          <div class="testimonial-author">
            <div class="author-avatar">A</div>
            <div class="author-info">
              <h5>Andi Setiawan</h5>
              <p>Mahasiswa</p>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="testimonial-card">
          <div class="quote-icon">"</div>
          <p class="testimonial-text">Akurasi rekomendasinya cocok untuk kebutuhan harian saya. Sistem berbasis ilmiah yang terpercaya.</p>
          <div class="testimonial-author">
            <div class="author-avatar">B</div>
            <div class="author-info">
              <h5>Budi Prakoso</h5>
              <p>K