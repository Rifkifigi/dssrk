<?php session_start(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>📞 Contact Us - DSSRK</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
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

/* Contact Card */
.contact-card {
  background: var(--card-bg);
  max-width: 800px;
  margin: 60px auto;
  border-radius: 15px;
  padding: 40px;
  box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}
.contact-card h2 {
  color: #198754;
  font-weight: 700;
  margin-bottom: 20px;
  text-align: center;
}
.contact-info {
  text-align: center;
  margin-bottom: 25px;
}
.contact-info a {
  color: #198754;
  text-decoration: none;
  font-weight: 600;
}
.contact-info a:hover {
  text-decoration: underline;
}

/* Form Styling */
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
  padding: 12px 20px;
  font-size: 1rem;
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

/* Dark Mode Adjustments */
body.dark .contact-card h2, 
body.dark .contact-info p,
body.dark footer { 
  color: #fff !important; 
}
body.dark .form-control {
  background-color: #2a2a2a;
  color: #f8f9fa;
  border: 1px solid #555;
}
body.dark .form-control:focus {
  border-color: #2dc673;
  box-shadow: 0 0 0 0.2rem rgba(45, 198, 115, 0.25);
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
  
  .contact-card {
    margin: 40px 15px;
    padding: 30px;
  }
  
  .contact-card h2 {
    font-size: 1.5rem;
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
  
  .contact-card {
    margin: 30px 10px;
    padding: 25px;
  }
  
  .contact-card h2 {
    font-size: 1.3rem;
  }
  
  .btn-success {
    font-size: 0.9rem;
    padding: 10px 15px;
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
  
  .contact-card {
    margin: 20px 5px;
    padding: 20px;
  }
  
  .contact-card h2 {
    font-size: 1.2rem;
  }
  
  .btn-success {
    font-size: 0.85rem;
    padding: 9px 12px;
  }
}

/* Landscape mode untuk mobile */
@media (max-width: 768px) and (orientation: landscape) {
  .contact-card {
    margin: 20px 15px;
    padding: 20px;
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
  <div class="contact-card">
    <h2>📞 Hubungi Kami</h2>
    <div class="contact-info">
      <p>Ingin menyampaikan saran, pertanyaan, atau kerja sama?</p>
      <p>📧 Email: <a href="mailto:rifkifigianto1@gmail.com">rifkifigianto1@gmail.com</a></p>
      <p>📸 Instagram: <a href="https://instagram.com/ipifigi_" target="_blank">@ipifigi_</a></p>
      <p>💬 WhatsApp: <a href="https://wa.me/6287872715783" target="_blank">Klik untuk chat langsung</a></p>
    </div>

    <form id="waForm" onsubmit="sendToWhatsApp(event)">
      <div class="mb-3">
        <label class="form-label">Nama:</label>
        <input type="text" id="nama" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Nomor WhatsApp:</label>
        <input type="text" id="nowa" class="form-control" placeholder="contoh: 6281234567890" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Pesan:</label>
        <textarea id="pesan" class="form-control" rows="5" required></textarea>
      </div>

      <div class="text-center">
        <button type="submit" class="btn btn-success px-4">📨 Kirim via WhatsApp</button>
      </div>
    </form>
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

function sendToWhatsApp(e){
  e.preventDefault();

  const nama = document.getElementById("nama").value;
  const nowa = document.getElementById("nowa").value;
  const pesan = document.getElementById("pesan").value;

  // Nomor WA tujuan (punya kamu)
  const nomorTujuan = "6287872715783";

  const text =
  `Halo Rifki! 👋%0ASaya ingin menghubungi melalui DSSRK.%0A%0A` +
  `📛 Nama: ${nama}%0A` +
  `📱 Nomor WhatsApp: https://wa.me/${nowa}%0A` +
  `💬 Pesan:%0A${pesan}`;

  window.open(`https://wa.me/${nomorTujuan}?text=${text}`,"_blank");
}
</script>

</body>
</html>