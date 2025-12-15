<?php
include "../koneksi.php";
// gunakan path relatif ke folder atas

$nama = "Admin DSSRK";
$email = "admin@gmail.com";
$password = password_hash("admin123", PASSWORD_DEFAULT);
$role = "admin";

$query = "INSERT INTO users (nama, email, password, role) VALUES ('$nama', '$email', '$password', '$role')";

if (mysqli_query($koneksi, $query)) {
    echo "✅ Admin berhasil dibuat!<br>";
    echo "Email: $email<br>Password: admin123";
} else {
    echo "❌ Gagal membuat admin: " . mysqli_error($koneksi);
}
?>
