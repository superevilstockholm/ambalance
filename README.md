# 🏦 Ambalance 😋  

**Ambalance** adalah platform berbasis website untuk **memanage dan memonitoring tabungan siswa** secara efisien dan transparan.  
Didesain untuk sekolah yang ingin melakukan digitalisasi sistem tabungan siswa dengan fitur autentikasi aman, laporan keuangan real-time, dan manajemen tabungan terpusat.

---

## 🧰 Tools

### ⚙️ Laravel
Digunakan sebagai **backend dan frontend utama** untuk mengelola seluruh logika aplikasi, autentikasi, integrasi database, dan tampilan view.  
Laravel menyediakan struktur yang kuat, aman, serta mudah untuk pengembangan fitur kompleks seperti manajemen tabungan dan otorisasi pengguna.

### 🗄️ MySQL
Digunakan sebagai **database utama** untuk menyimpan data siswa, guru, transaksi tabungan, dan aktivitas log dengan performa tinggi dan fleksibilitas.

### 🔒 Laravel Sanctum
Untuk **sistem autentikasi berbasis token (API Token)** yang aman dan efisien. Mendukung login multi-role seperti *student*, *teacher*, dan *admin*.


### 📦 Bootstrap
Digunakan sebagai **framework CSS** untuk membangun tampilan yang responsif dan elegan.

### 📊 Chart.js / ApexCharts
Menyajikan data tabungan siswa dalam bentuk **grafik dan statistik visual** agar lebih mudah dianalisis.

---

## 🚀 Fitur-Fitur

### 🔐 Authentication
- **Register** – Pendaftaran akun siswa/guru.  
- **Login** – Masuk ke sistem menggunakan NISN/NIP dan password.  
- **Change Password** – Ganti kata sandi dengan verifikasi keamanan.  
- **Reset Password** – Mengatur ulang kata sandi melalui email.  

### 💰 Tabungan
- **Monitoring Tabungan** – Menampilkan saldo dan riwayat transaksi tabungan siswa.  
- **Logs & Statistics** – Visualisasi pertumbuhan tabungan harian, mingguan, dan bulanan.  
- **Manage Tabungan (Teacher Role)** – Guru dapat mengelola tabungan siswa dalam kelas atau kelompoknya.  

---

## 🧑‍💻 Developer Note
Project ini dikembangkan dengan semangat **transparansi, efisiensi, dan kemudahan akses**.  
Setiap fitur dibuat dengan memperhatikan keamanan data serta kemudahan penggunaan bagi pengguna sekolah.

---

## 🧱 Tech Stack Overview
| Layer | Technology |
|-------|-------------|
| Backend | Laravel |
| Frontend | Blade Template Engine |
| CSS Framework | Bootstrap 5 |
| Consume API | Axios |
| Database | MySQL |
| Authentication | Laravel Sanctum |
| Chart Library | Chart.js / ApexCharts |
| Deployment | Docker + Nginx |

---

## 📜 License
Project ini bersifat **legal dan open for educational use**.  
Copyright © 2025 – Ambatocode. All rights reserved.
