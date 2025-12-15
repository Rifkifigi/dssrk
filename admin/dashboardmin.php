<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Panel - DSSRK</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Montserrat:wght@700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
<style>
:root {
  --bg-color: #f8f9fa;
  --text-color: #212529;
  --card-bg: #fff;
  --navbar-bg: linear-gradient(90deg, #198754 0%, #157347 100%);
  --footer-bg: #157347;
  --accent-color: #ffc107;
  --shadow-color: rgba(0,0,0,0.08);
}
body.dark {
  --bg-color: #121212;
  --text-color: #f8f9fa;
  --card-bg: #1e1e1e;
  --navbar-bg: linear-gradient(90deg, #0d5234 0%, #0b4129 100%);
  --footer-bg: #0b4129;
  --accent-color: #ffc107;
  --shadow-color: rgba(0,0,0,0.3);
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
  transition: all 0.3s ease;
}

/* === NAVBAR RESPONSIVE === */
.navbar {
  background: var(--navbar-bg);
  box-shadow: 0 4px 12px var(--shadow-color);
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
  color: var(--accent-color); 
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
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}
#themeToggle:hover {
  background: #fff;
  color: #198754;
  transform: rotate(20deg);
}

/* Welcome Section */
.welcome-section {
  background: linear-gradient(135deg, var(--card-bg) 0%, rgba(25, 135, 84, 0.05) 100%);
  border-radius: 20px;
  padding: 40px;
  margin: 40px auto;
  max-width: 800px;
  box-shadow: 0 8px 25px var(--shadow-color);
  text-align: center;
  position: relative;
  overflow: hidden;
}
.welcome-section::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 4px;
  background: linear-gradient(90deg, #198754 0%, var(--accent-color) 100%);
}
.welcome-section h2 {
  font-weight: 700;
  font-size: 2rem;
  margin-bottom: 15px;
  background: linear-gradient(135deg, #198754 0%, #157347 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}
.welcome-section p {
  font-size: 1.1rem;
  opacity: 0.8;
}

/* Admin Cards */
.admin-card {
  background: var(--card-bg);
  border-radius: 20px;
  padding: 35px;
  box-shadow: 0 8px 25px var(--shadow-color);
  transition: all 0.4s ease;
  border: 1px solid transparent;
  position: relative;
  overflow: hidden;
  height: 100%;
}
.admin-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 4px;
  background: linear-gradient(90deg, #198754 0%, var(--accent-color) 100%);
  transform: scaleX(0);
  transition: transform 0.4s ease;
}
.admin-card:hover {
  transform: translateY(-8px);
  box-shadow: 0 15px 35px var(--shadow-color);
  border-color: rgba(25, 135, 84, 0.2);
}
.admin-card:hover::before {
  transform: scaleX(1);
}
.admin-card .card-icon {
  font-size: 3rem;
  margin-bottom: 20px;
  display: block;
  background: linear-gradient(135deg, #198754 0%, var(--accent-color) 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}
.admin-card h5 {
  font-weight: 700;
  font-size: 1.3rem;
  margin-bottom: 15px;
  color: var(--text-color);
}
.admin-card p {
  font-size: 1rem;
  opacity: 0.8;
  margin-bottom: 25px;
  color: var(--text-color);
}
.admin-card .btn {
  font-weight: 600;
  padding: 12px 30px;
  border-radius: 50px;
  font-size: 1rem;
  transition: all 0.3s ease;
  border: none;
}
.admin-card .btn-success {
  background: linear-gradient(135deg, #198754 0%, #157347 100%);
}
.admin-card .btn-success:hover {
  background: linear-gradient(135deg, #157347 0%, #125a36 100%);
  transform: translateY(-2px);
  box-shadow: 0 8px 20px rgba(25, 135, 84, 0.3);
}

/* Dark Mode Adjustments */
body.dark .welcome-section {
  background: linear-gradient(135deg, var(--card-bg) 0%, rgba(25, 135, 84, 0.1) 100%);
}
body.dark .admin-card {
  background: var(--card-bg);
  border-color: rgba(255, 255, 255, 0.1);
}
body.dark .admin-card .card-icon {
  background: linear-gradient(135deg, #2dc673 0%, var(--accent-color) 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
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
  
  .welcome-section {
    margin: 30px 15px;
    padding: 30px 20px;
  }
  
  .welcome-section h2 {
    font-size: 1.6rem;
  }
  
  .welcome-section p {
    font-size: 1rem;
  }
  
  .admin-card {
    margin-bottom: 20px;
    padding: 25px;
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
  
  .welcome-section {
    margin: 20px 10px;
    padding: 25px 15px;
  }
  
  .welcome-section h2 {
    font-size: 1.4rem;
  }
  
  .admin-card {
    padding: 20px;
  }
  
  .admin-card .card-icon {
    font-size: 2.5rem;
  }
  
  .admin-card h5 {
    font-size: 1.2rem;
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
  
  .welcome-section {
    padding: 20px 10px;
  }
  
  .admin-card {
    padding: 15px;
  }
  
  .admin-card .card-icon {
    font-size: 2rem;
  }
  
  .admin-card .btn {
    padding: 10px 20px;
    font-size: 0.9rem;
  }
}

/* Landscape mode untuk mobile */
@media (max-width: 768px) and (orientation: landscape) {
  .welcome-section {
    margin: 20px 15px;
    padding: 20px;
  }
  
  .admin-card {
    padding: 20px;
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
      <a href="../logout.php" class="btn btn-outline-light btn-sm">🚪 Logout</a>
    </div>
  </div>
</nav>

<main>
  <!-- Welcome Section -->
  <div class="welcome-section">
    <h2>Selamat Datang, <?= htmlspecialchars($_SESSION['nama']); ?> 👋</h2>
    <p>Administrator Dashboard - Kelola sistem dengan mudah dan efisien</p>
  </div>

  <!-- Admin Cards -->
  <div class="container mb-5">
    <div class="row justify-content-center g-4">
      <div class="col-md-5 col-lg-4">
        <div class="admin-card text-center">
          <span class="card-icon">👤</span>
          <h5>Kelola Data User</h5>
          <p>Tambah, ubah, atau hapus data pengguna sistem dengan kontrol penuh</p>
          <a href="data_user.php" class="btn btn-success">
            <i class="bi bi-people-fill me-2"></i>Kelola User
          </a>
        </div>
      </div>

      <div class="col-md-5 col-lg-4">
        <div class="admin-card text-center">
          <span class="card-icon">🍱</span>
          <h5>Kelola Data Makanan</h5>
          <p>Atur database makanan dan rekomendasi gizi untuk sistem</p>
          <a href="data_makanan.php" class="btn btn-success">
            <i class="bi bi-basket-fill me-2"></i>Kelola Makanan
          </a>
        </div>
      </div>
    </div>
  </div>
</main>

<footer>
  <p>© <?= date("Y") ?> DSSRK — Sistem Rekomendasi Kalori. Admin Panel</p>
</footer>

<script>
// Dark Mode Toggle
const toggleBtn = document.getElementById('themeToggle');
toggleBtn.addEventListener('click', () => {
  document.body.classList.toggle('dark');
  toggleBtn.textContent = document.body.classList.contains('dark') ? '☀️' : '🌙';
  localStorage.setItem('theme', document.body.classList.contains('dark') ? 'dark' : 'light');
});

// Load saved theme
if (localStorage.getItem('theme') === 'dark') {
  document.body.classList.add('dark');
  toggleBtn.textContent = '☀️';
}

// Add entrance animations
document.addEventListener('DOMContentLoaded', function() {
  const cards = document.querySelectorAll('.admin-card');
  cards.forEach((card, index) => {
    card.style.opacity = '0';
    card.style.transform = 'translateY(30px)';
    
    setTimeout(() => {
      card.style.transition = 'all 0.6s ease';
      card.style.opacity = '1';
      card.style.transform = 'translateY(0)';
    }, 200 * (index + 1));
  });
  
  // Animate welcome section
  const welcomeSection = document.querySelector('.welcome-section');
  welcomeSection.style.opacity = '0';
  welcomeSection.style.transform = 'translateY(-20px)';
  
  setTimeout(() => {
    welcomeSection.style.transition = 'all 0.8s ease';
    welcomeSection.style.opacity = '1';
    welcomeSection.style.transform = 'translateY(0)';
  }, 100);
});
</script>

</body>
</html>