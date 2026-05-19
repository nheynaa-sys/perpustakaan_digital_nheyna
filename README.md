# 📚 Digital Library Management System

Sistem Perpustakaan Digital berbasis web yang dirancang untuk memudahkan proses pengelolaan buku, anggota, peminjaman, pengembalian, dan denda secara digital.  
Project ini memiliki **2 role utama**, yaitu **Admin** dan **User/Anggota**.

---

# ✨ Features

## 👨‍💼 Admin Features
- Dashboard Admin
- Kelola Data Buku
  - Tambah buku
  - Edit buku
  - Hapus buku
  - Kategori buku
  - Stok buku
- Kelola Data Anggota
- Kelola Peminjaman Buku
- Kelola Pengembalian Buku
- Sistem Denda Keterlambatan
- Riwayat Transaksi
- Manajemen User & Role
- Search & Filter Data

## 👤 User Features
- Registrasi & Login
- Melihat daftar buku
- Detail buku
- Meminjam buku
- Melihat status peminjaman
- Riwayat peminjaman
- Notifikasi pengembalian

---

# 🛠️ Tech Stack

- **Backend:** Laravel
- **Frontend:** Blade / Bootstrap / TailwindCSS
- **Database:** MySQL
- **Authentication:** Laravel Authentication & Middleware
- **Server:** Apache / Nginx

---

# 📂 Project Structure

```bash
project-root/
│
├── app/
├── bootstrap/
├── config/
├── database/
├── public/
├── resources/
├── routes/
├── storage/
├── tests/
└── vendor/
```

---

# ⚙️ Installation

## 1. Clone Repository

```bash
git clone https://github.com/username/digital-library.git
```

## 2. Masuk ke Folder Project

```bash
cd digital-library
```

## 3. Install Dependency

```bash
composer install
```

## 4. Copy Environment File

```bash
cp .env.example .env
```

## 5. Generate Application Key

```bash
php artisan key:generate
```

## 6. Konfigurasi Database

Edit file `.env`

```env
DB_DATABASE=perpustakaan
DB_USERNAME=root
DB_PASSWORD=
```

## 7. Jalankan Migration

```bash
php artisan migrate
```

## 8. Jalankan Seeder (Optional)

```bash
php artisan db:seed
```

## 9. Jalankan Server

```bash
php artisan serve
```

---

# 🔐 Roles & Permissions

| Role | Access |
|------|---------|
| Admin | Full Access Management |
| User | Borrow Books & View History |

---

# 📖 Modules

## 📚 Book Management
Admin dapat mengelola seluruh data buku seperti:
- Judul buku
- Penulis
- Penerbit
- Tahun terbit
- Kategori
- Cover buku
- Jumlah stok

## 🔄 Borrowing System
User dapat:
- Mengajukan peminjaman
- Melihat status peminjaman
- Mengembalikan buku

Admin dapat:
- Menyetujui peminjaman
- Mengelola pengembalian
- Mengatur status transaksi

## 💸 Fine System
Sistem otomatis menghitung denda berdasarkan keterlambatan pengembalian buku.

---

# 🖼️ Screenshots

## Admin Dashboard
```bash
/assets/screenshots/admin-dashboard.png
```

## User Dashboard
```bash
/assets/screenshots/user-dashboard.png
```

---

# 🚀 Future Improvements

- Export laporan PDF
- Barcode scanner buku
- Email notification
- Dark mode
- API Integration
- Mobile responsive optimization

---

# 🤝 Contributing

Pull Request sangat terbuka untuk pengembangan project ini.

Langkah kontribusi:
1. Fork repository
2. Create new branch
3. Commit changes
4. Push branch
5. Open Pull Request

---

# 📄 License

Project ini menggunakan lisensi **MIT License**.

---

# 👨‍💻 Author

Developed with ❤️ by **Your Name**
