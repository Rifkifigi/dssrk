<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

include "../koneksi.php";

// ===== TAMBAH MAKANAN =====
if (isset($_POST['tambah_makanan'])) {
    $name = mysqli_real_escape_string($koneksi, $_POST['name']);
    $calories = floatval($_POST['calories']);
    $proteins = floatval($_POST['proteins']);
    $fat = floatval($_POST['fat']);
    $carbohydrate = floatval($_POST['carbohydrate']);
    $image = mysqli_real_escape_string($koneksi, $_POST['image']);

    mysqli_query($koneksi, "INSERT INTO data_makanan (name, calories, proteins, fat, carbohydrate, image)
                            VALUES ('$name', $calories, $proteins, $fat, $carbohydrate, '$image')");
    header("Location: data_makanan.php");
    exit;
}

// ===== EDIT MAKANAN =====
if (isset($_POST['edit_makanan'])) {
    $id = intval($_POST['id']);
    $name = mysqli_real_escape_string($koneksi, $_POST['name']);
    $calories = floatval($_POST['calories']);
    $proteins = floatval($_POST['proteins']);
    $fat = floatval($_POST['fat']);
    $carbohydrate = floatval($_POST['carbohydrate']);
    $image = mysqli_real_escape_string($koneksi, $_POST['image']);

    mysqli_query($koneksi, "UPDATE data_makanan SET 
        name='$name',
        calories=$calories,
        proteins=$proteins,
        fat=$fat,
        carbohydrate=$carbohydrate,
        image='$image'
        WHERE id=$id");
    header("Location: data_makanan.php");
    exit;
}

// ===== HAPUS MAKANAN =====
if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    mysqli_query($koneksi, "DELETE FROM data_makanan WHERE id=$id");
    header("Location: data_makanan.php");
    exit;
}

// ===== SEARCH DATA =====
 $cari = isset($_GET['cari']) ? mysqli_real_escape_string($koneksi, $_GET['cari']) : '';
if ($cari != '') {
    $data = mysqli_query($koneksi, "SELECT * FROM data_makanan WHERE name LIKE '%$cari%' ORDER BY id DESC");
} else {
    $data = mysqli_query($koneksi, "SELECT * FROM data_makanan ORDER BY id DESC");
}

// simpan data untuk modal
 $all_data = [];
while($d = mysqli_fetch_assoc($data)){ $all_data[] = $d; }
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Data Makanan - DSSRK</title>
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

/* Page Header */
.page-header {
  background: linear-gradient(135deg, var(--card-bg) 0%, rgba(25, 135, 84, 0.05) 100%);
  border-radius: 20px;
  padding: 30px;
  margin: 30px auto;
  max-width: 1200px;
  box-shadow: 0 8px 25px var(--shadow-color);
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-wrap: wrap;
  gap: 20px;
}
.page-header h2 {
  font-weight: 700;
  font-size: 1.8rem;
  margin: 0;
  color: var(--text-color);
  display: flex;
  align-items: center;
  gap: 10px;
}
.page-header .page-icon {
  font-size: 2rem;
  background: linear-gradient(135deg, #198754 0%, #157347 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}
.page-header .btn-add {
  background: linear-gradient(135deg, #198754 0%, #157347 100%);
  border: none;
  border-radius: 50px;
  padding: 12px 25px;
  font-weight: 600;
  font-size: 1rem;
  transition: all 0.3s ease;
  box-shadow: 0 4px 15px rgba(25, 135, 84, 0.3);
}
.page-header .btn-add:hover {
  transform: translateY(-3px);
  box-shadow: 0 8px 25px rgba(25, 135, 84, 0.4);
}

/* Search Form */
.search-container {
  background: var(--card-bg);
  border-radius: 20px;
  padding: 20px;
  margin: 0 auto 30px;
  max-width: 1200px;
  box-shadow: 0 8px 25px var(--shadow-color);
}
.search-form {
  display: flex;
  gap: 15px;
  align-items: center;
}
.search-input {
  flex: 1;
  position: relative;
}
.search-input input {
  border-radius: 50px;
  padding: 12px 20px 12px 50px;
  border: 1px solid rgba(0,0,0,0.1);
  transition: all 0.3s ease;
}
.search-input input:focus {
  border-color: #198754;
  box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.25);
}
.search-input i {
  position: absolute;
  left: 20px;
  top: 50%;
  transform: translateY(-50%);
  color: #6c757d;
}
.search-btn {
  background: linear-gradient(135deg, #198754 0%, #157347 100%);
  border: none;
  border-radius: 50px;
  padding: 12px 25px;
  font-weight: 600;
  transition: all 0.3s ease;
}
.search-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 20px rgba(25, 135, 84, 0.3);
}
.reset-btn {
  background-color: #6c757d;
  border: none;
  border-radius: 50px;
  padding: 12px 25px;
  font-weight: 600;
  transition: all 0.3s ease;
}
.reset-btn:hover {
  background-color: #5a6268;
  transform: translateY(-2px);
}

/* Table Container */
.table-container {
  background: var(--card-bg);
  border-radius: 20px;
  padding: 30px;
  margin: 0 auto 30px;
  max-width: 1200px;
  box-shadow: 0 8px 25px var(--shadow-color);
  overflow: hidden;
}
.table-container h3 {
  font-weight: 700;
  font-size: 1.4rem;
  margin-bottom: 20px;
  color: var(--text-color);
  display: flex;
  align-items: center;
  gap: 10px;
}

/* Table Styling */
.table-responsive {
  border-radius: 10px;
  overflow: hidden;
}
.table {
  margin-bottom: 0;
}
.table thead th {
  background: linear-gradient(135deg, #198754 0%, #157347 100%);
  color: #fff;
  border: none;
  font-weight: 600;
  text-transform: uppercase;
  font-size: 0.85rem;
  letter-spacing: 0.5px;
  padding: 15px 12px;
}
.table tbody tr {
  transition: all 0.2s ease;
  border-bottom: 1px solid rgba(0,0,0,0.05);
}
.table tbody tr:hover {
  background-color: rgba(25, 135, 84, 0.05);
  transform: scale(1.01);
}
.table tbody td {
  padding: 15px 12px;
  vertical-align: middle;
}

/* Food Image Preview */
.food-image {
  width: 60px;
  height: 60px;
  object-fit: cover;
  border-radius: 10px;
  box-shadow: 0 4px 8px rgba(0,0,0,0.1);
  transition: all 0.3s ease;
}
.food-image:hover {
  transform: scale(1.1);
  box-shadow: 0 8px 16px rgba(0,0,0,0.2);
}
.no-image {
  width: 60px;
  height: 60px;
  background-color: #f8f9fa;
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #6c757d;
  font-size: 0.8rem;
}

/* Nutrition Values */
.nutrition-value {
  font-weight: 600;
  display: inline-block;
  padding: 4px 8px;
  border-radius: 20px;
  font-size: 0.85rem;
}
.calories {
  background-color: rgba(25, 135, 84, 0.1);
  color: #198754;
}
.protein {
  background-color: rgba(13, 110, 253, 0.1);
  color: #0d6efd;
}
.fat {
  background-color: rgba(255, 193, 7, 0.1);
  color: #ffc107;
}
.carbs {
  background-color: rgba(220, 53, 69, 0.1);
  color: #dc3545;
}

/* Action Buttons */
.action-btns {
  display: flex;
  gap: 8px;
  justify-content: center;
}
.btn-action {
  padding: 8px 12px;
  border-radius: 8px;
  font-size: 0.85rem;
  font-weight: 600;
  transition: all 0.2s ease;
  display: flex;
  align-items: center;
  gap: 5px;
}
.btn-edit {
  background-color: rgba(255, 193, 7, 0.1);
  color: #ffc107;
  border: 1px solid rgba(255, 193, 7, 0.3);
}
.btn-edit:hover {
  background-color: #ffc107;
  color: #212529;
  transform: translateY(-2px);
}
.btn-delete {
  background-color: rgba(220, 53, 69, 0.1);
  color: #dc3545;
  border: 1px solid rgba(220, 53, 69, 0.3);
}
.btn-delete:hover {
  background-color: #dc3545;
  color: #fff;
  transform: translateY(-2px);
}

/* Highlight Search Result */
.highlight {
  background-color: rgba(255, 193, 7, 0.3);
  padding: 1px 3px;
  border-radius: 3px;
  font-weight: 700;
}

/* Modal Styling */
.modal-content {
  border-radius: 15px;
  border: none;
  box-shadow: 0 10px 30px rgba(0,0,0,0.2);
  overflow: hidden;
}
.modal-header {
  border-bottom: none;
  padding: 20px 25px;
}
.modal-title {
  font-weight: 700;
  font-size: 1.3rem;
  display: flex;
  align-items: center;
  gap: 10px;
}
.modal-body {
  padding: 25px;
}
.modal-footer {
  border-top: none;
  padding: 15px 25px 25px;
}
.form-label {
  font-weight: 600;
  margin-bottom: 8px;
  color: var(--text-color);
}
.form-control, .form-select {
  border-radius: 10px;
  border: 1px solid rgba(0,0,0,0.1);
  padding: 12px 15px;
  transition: all 0.2s ease;
}
.form-control:focus, .form-select:focus {
  border-color: #198754;
  box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.25);
}
.btn-modal {
  border-radius: 50px;
  padding: 10px 25px;
  font-weight: 600;
  transition: all 0.2s ease;
}
.btn-modal:hover {
  transform: translateY(-2px);
}

/* Dark Mode Adjustments */
body.dark .page-header {
  background: linear-gradient(135deg, var(--card-bg) 0%, rgba(25, 135, 84, 0.1) 100%);
}
body.dark .search-container {
  background: var(--card-bg);
}
body.dark .table-container {
  background: var(--card-bg);
}
body.dark .modal-content {
  background-color: #1f1f1f !important;
  color: #f8f9fa !important;
}
body.dark .modal-header {
  background-color: #198754 !important;
  color: #fff !important;
}
body.dark .form-control, 
body.dark .form-select {
  background-color: #2a2a2a;
  color: #f8f9fa;
  border: 1px solid #555;
}
body.dark .form-control:focus, 
body.dark .form-select:focus {
  border-color: #2dc673;
  box-shadow: 0 0 0 0.2rem rgba(45, 198, 115, 0.25);
}
body.dark .no-image {
  background-color: #2a2a2a;
  color: #aaa;
}
body.dark .btn-close {
  filter: invert(1);
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
  
  .page-header {
    margin: 20px 15px;
    padding: 20px;
    flex-direction: column;
    align-items: flex-start;
  }
  
  .page-header h2 {
    font-size: 1.5rem;
  }
  
  .page-header .btn-add {
    width: 100%;
    padding: 10px 20px;
  }
  
  .search-container {
    margin: 0 15px 20px;
    padding: 15px;
  }
  
  .search-form {
    flex-direction: column;
    gap: 10px;
  }
  
  .search-input {
    width: 100%;
  }
  
  .table-container {
    margin: 0 15px 20px;
    padding: 20px;
  }
  
  .table-container h3 {
    font-size: 1.2rem;
  }
  
  .table thead th {
    padding: 10px 8px;
    font-size: 0.8rem;
  }
  
  .table tbody td {
    padding: 12px 8px;
    font-size: 0.9rem;
  }
  
  .food-image {
    width: 50px;
    height: 50px;
  }
  
  .no-image {
    width: 50px;
    height: 50px;
    font-size: 0.7rem;
  }
  
  .action-btns {
    flex-direction: column;
    gap: 5px;
  }
  
  .btn-action {
    font-size: 0.8rem;
    padding: 6px 10px;
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
  
  .page-header {
    margin: 15px 10px;
    padding: 15px;
  }
  
  .page-header h2 {
    font-size: 1.3rem;
  }
  
  .search-container {
    margin: 0 10px 15px;
    padding: 15px;
  }
  
  .table-container {
    margin: 0 10px 15px;
    padding: 15px;
  }
  
  .table-container h3 {
    font-size: 1.1rem;
  }
  
  .table thead th {
    padding: 8px 6px;
    font-size: 0.75rem;
  }
  
  .table tbody td {
    padding: 10px 6px;
    font-size: 0.85rem;
  }
  
  .food-image {
    width: 40px;
    height: 40px;
  }
  
  .no-image {
    width: 40px;
    height: 40px;
    font-size: 0.6rem;
  }
  
  .nutrition-value {
    font-size: 0.75rem;
    padding: 2px 6px;
  }
  
  .modal-content {
    margin: 10px;
  }
  
  .modal-header {
    padding: 15px 20px;
  }
  
  .modal-body {
    padding: 20px;
  }
  
  .modal-footer {
    padding: 10px 20px 20px;
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
  
  .page-header h2 {
    font-size: 1.2rem;
  }
  
  .page-header .page-icon {
    font-size: 1.5rem;
  }
  
  .table thead th {
    font-size: 0.7rem;
  }
  
  .table tbody td {
    font-size: 0.8rem;
  }
  
  .modal-header {
    padding: 12px 15px;
  }
  
  .modal-body {
    padding: 15px;
  }
  
  .modal-footer {
    padding: 8px 15px 15px;
  }
}

/* Landscape mode untuk mobile */
@media (max-width: 768px) and (orientation: landscape) {
  .page-header {
    margin: 15px;
    padding: 15px;
    flex-direction: row;
  }
  
  .search-container {
    margin: 0 15px 15px;
    padding: 15px;
  }
  
  .search-form {
    flex-direction: row;
  }
  
  .table-container {
    margin: 0 15px 15px;
    padding: 15px;
  }
}
</style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark">
  <div class="container">
    <a class="navbar-brand" href="dashboardmin.php">
      <span class="logo-icon">🍱</span>
      <span class="logo-text"><span>DSS</span>RK</span>
    </a>
    <div class="d-flex align-items-center gap-2">
      <button id="themeToggle">🌙</button>
      <a href="dashboardmin.php" class="btn btn-outline-light btn-sm">🏠 Dashboard</a>
      <a href="../logout.php" class="btn btn-outline-light btn-sm">🚪 Logout</a>
    </div>
  </div>
</nav>

<main>
  <!-- Page Header -->
  <div class="page-header">
    <h2><span class="page-icon">🍱</span> Data Makanan</h2>
    <button class="btn btn-success btn-add" data-bs-toggle="modal" data-bs-target="#tambahModal">
      <i class="bi bi-plus-circle me-2"></i>Tambah Makanan
    </button>
  </div>

  <!-- Search Form -->
  <div class="search-container">
    <form method="GET" class="search-form">
      <div class="search-input">
        <i class="bi bi-search"></i>
        <input type="text" name="cari" class="form-control" placeholder="Cari makanan..." value="<?= htmlspecialchars($cari) ?>">
      </div>
      <button type="submit" class="btn btn-success search-btn">
        <i class="bi bi-search me-2"></i>Cari
      </button>
      <?php if($cari!=''): ?>
        <a href="data_makanan.php" class="btn btn-secondary reset-btn">
          <i class="bi bi-arrow-clockwise me-2"></i>Reset
        </a>
      <?php endif; ?>
    </form>
  </div>

  <!-- Table Container -->
  <div class="table-container">
    <h3><i class="bi bi-basket-fill me-2"></i>Daftar Makanan</h3>
    <div class="table-responsive">
      <table class="table table-hover align-middle">
        <thead>
          <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Kalori</th>
            <th>Protein</th>
            <th>Lemak</th>
            <th>Karbohidrat</th>
            <th>Gambar</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php $no=1; foreach($all_data as $m): 
                $nama = htmlspecialchars($m['name']);
                if($cari!='') $nama = preg_replace("/($cari)/i","<span class='highlight'>$1</span>",$nama);
          ?>
          <tr>
            <td><?= $no++ ?></td>
            <td><strong><?= $nama ?></strong></td>
            <td><span class="nutrition-value calories"><?= $m['calories'] ?> kkal</span></td>
            <td><span class="nutrition-value protein"><?= $m['proteins'] ?> g</span></td>
            <td><span class="nutrition-value fat"><?= $m['fat'] ?> g</span></td>
            <td><span class="nutrition-value carbs"><?= $m['carbohydrate'] ?> g</span></td>
            <td class="text-center">
              <?php if($m['image']!=''): ?>
                <img class="food-image" src="<?= htmlspecialchars($m['image']) ?>" alt="<?= htmlspecialchars($m['name']) ?>">
              <?php else: ?>
                <div class="no-image">No Image</div>
              <?php endif; ?>
            </td>
            <td>
              <div class="action-btns">
                <button class="btn-action btn-edit" data-bs-toggle="modal" data-bs-target="#editModal<?= $m['id'] ?>">
                  <i class="bi bi-pencil-fill"></i> Edit
                </button>
                <a href="?hapus=<?= $m['id'] ?>" onclick="return confirm('Yakin ingin menghapus makanan ini?')" class="btn-action btn-delete">
                  <i class="bi bi-trash-fill"></i> Hapus
                </a>
              </div>
            </td>
          </tr>

          <!-- Modal Edit -->
          <div class="modal fade" id="editModal<?= $m['id'] ?>" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
              <form method="POST" class="modal-content">
                <div class="modal-header bg-warning text-white">
                  <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Edit Makanan</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                  <input type="hidden" name="id" value="<?= $m['id'] ?>">
                  <div class="mb-3">
                    <label class="form-label">Nama Makanan</label>
                    <input type="text" name="name" value="<?= htmlspecialchars($m['name']) ?>" class="form-control" required>
                  </div>
                  <div class="row mb-3">
                    <div class="col-md-6 mb-2 mb-md-0">
                      <label class="form-label">Kalori (kkal)</label>
                      <input type="number" step="0.1" name="calories" value="<?= $m['calories'] ?>" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                      <label class="form-label">Protein (g)</label>
                      <input type="number" step="0.1" name="proteins" value="<?= $m['proteins'] ?>" class="form-control" required>
                    </div>
                  </div>
                  <div class="row mb-3">
                    <div class="col-md-6 mb-2 mb-md-0">
                      <label class="form-label">Lemak (g)</label>
                      <input type="number" step="0.1" name="fat" value="<?= $m['fat'] ?>" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                      <label class="form-label">Karbohidrat (g)</label>
                      <input type="number" step="0.1" name="carbohydrate" value="<?= $m['carbohydrate'] ?>" class="form-control" required>
                    </div>
                  </div>
                  <div class="mb-3">
                    <label class="form-label">URL Gambar</label>
                    <input type="text" name="image" value="<?= htmlspecialchars($m['image']) ?>" class="form-control">
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                  <button type="submit" name="edit_makanan" class="btn btn-warning btn-modal">Simpan Perubahan</button>
                </div>
              </form>
            </div>
          </div>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</main>

<!-- Modal Tambah -->
<div class="modal fade" id="tambahModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <form method="POST" class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title"><i class="bi bi-plus-circle me-2"></i>Tambah Makanan Baru</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label">Nama Makanan</label>
          <input type="text" name="name" class="form-control" required>
        </div>
        <div class="row mb-3">
          <div class="col-md-6 mb-2 mb-md-0">
            <label class="form-label">Kalori (kkal)</label>
            <input type="number" step="0.1" name="calories" class="form-control" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Protein (g)</label>
            <input type="number" step="0.1" name="proteins" class="form-control" required>
          </div>
        </div>
        <div class="row mb-3">
          <div class="col-md-6 mb-2 mb-md-0">
            <label class="form-label">Lemak (g)</label>
            <input type="number" step="0.1" name="fat" class="form-control" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Karbohidrat (g)</label>
            <input type="number" step="0.1" name="carbohydrate" class="form-control" required>
          </div>
        </div>
        <div class="mb-3">
          <label class="form-label">URL Gambar</label>
          <input type="text" name="image" class="form-control" placeholder="https://example.com/image.jpg">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" name="tambah_makanan" class="btn btn-success btn-modal">Tambah Makanan</button>
      </div>
    </form>
  </div>
</div>

<footer>
  <p>© <?= date("Y") ?> DSSRK — Sistem Rekomendasi Kalori. Admin Panel</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
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
  const pageHeader = document.querySelector('.page-header');
  const searchContainer = document.querySelector('.search-container');
  const tableContainer = document.querySelector('.table-container');
  
  pageHeader.style.opacity = '0';
  pageHeader.style.transform = 'translateY(-20px)';
  
  searchContainer.style.opacity = '0';
  searchContainer.style.transform = 'translateY(-10px)';
  
  tableContainer.style.opacity = '0';
  tableContainer.style.transform = 'translateY(20px)';
  
  setTimeout(() => {
    pageHeader.style.transition = 'all 0.6s ease';
    pageHeader.style.opacity = '1';
    pageHeader.style.transform = 'translateY(0)';
  }, 100);
  
  setTimeout(() => {
    searchContainer.style.transition = 'all 0.6s ease';
    searchContainer.style.opacity = '1';
    searchContainer.style.transform = 'translateY(0)';
  }, 200);
  
  setTimeout(() => {
    tableContainer.style.transition = 'all 0.6s ease';
    tableContainer.style.opacity = '1';
    tableContainer.style.transform = 'translateY(0)';
  }, 300);
});
</script>

</body>
</html>