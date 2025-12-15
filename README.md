# 🧠 Sistem Pendukung Keputusan Kebutuhan Kalori

Repository ini berisi project **Sistem Pendukung Keputusan (DSS) Kebutuhan Kalori** berbasis **PHP Native dan MySQL** yang dibuat untuk **tugas kuliah**. Sistem ini membantu pengguna menghitung kebutuhan kalori harian menggunakan metode **BMR (Basal Metabolic Rate)** dan **Logika Fuzzy**.

---

## 🎯 Tujuan Project

* Memenuhi tugas perkuliahan
* Menerapkan konsep **Decision Support System (DSS)**
* Mengimplementasikan perhitungan **BMR + Fuzzy Logic** dalam aplikasi web

---

## 🛠️ Teknologi yang Digunakan

### Frontend

* HTML5
* CSS3
* Bootstrap

### Backend

* PHP Native

### Database

* MySQL
* phpMyAdmin

### Deployment

* InfinityFree

---

## 📂 Struktur Folder Project

```
dss-kalori-app/
├── assets/            # CSS, JS, images
├── auth/              # Login & Register
├── admin/             # Dashboard admin
├── user/              # Halaman user
├── config/            # Koneksi database
├── index.php          # Halaman utama
├── login.php
├── register.php
└── README.md
```

---

## ⚙️ Cara Menjalankan Project (Localhost)

1. Clone repository:

```bash
git clone https://github.com/USERNAME/dss-kalori-app.git
```

2. Pindahkan folder ke:

```
htdocs/   (jika pakai XAMPP)
```

3. Buat database di phpMyAdmin:

```
Nama database: dss_kalori
```

4. Import file `.sql` ke database

5. Atur koneksi database di file:

```
config/koneksi.php
```

6. Jalankan di browser:

```
http://localhost/dss-kalori-app
```

---

## 🔐 Fitur Utama

* ✅ Login & Register
* 🔢 Perhitungan kebutuhan kalori (BMR + Fuzzy)
* 🕘 Riwayat perhitungan pengguna
* 🧑‍💼 Dashboard admin

---

## 🧮 Metode Perhitungan

* **BMR** digunakan untuk menghitung kebutuhan dasar energi
* **Logika Fuzzy** digunakan untuk menghasilkan rentang kebutuhan kalori yang lebih fleksibel dan realistis

---

## 🌐 Demo Aplikasi

🔗 **Live Demo:** [https://dssrk.infinityfreeapp.com/index.php](https://dssrk.infinityfreeapp.com/index.php)

---

## 📌 Catatan Penting

* Project ini menggunakan **PHP Native (tanpa framework)**
* Disarankan menjalankan di **XAMPP / Laragon** untuk local development
* Data pada demo bersifat simulasi

---

## 👤 Author

**Nama:** Rifki Figianto
**Project:** Tugas Kuliah – Sistem Pendukung Keputusan

---

## 📄 Lisensi

Project ini dibuat untuk keperluan **pendidikan dan non-komersial**.
