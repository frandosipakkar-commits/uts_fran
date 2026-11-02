# My Store — UTS Praktik Pemrograman Web 2

## Deskripsi
My Store adalah aplikasi web sederhana untuk mengelola katalog produk. Aplikasi dibuat menggunakan PHP, MySQL, HTML, dan CSS.

## Fitur
- Login admin menggunakan session.
- Dashboard ringkasan total produk, produk aktif, dan discontinued.
- Create: menambah produk.
- Read: menampilkan dan mencari produk berdasarkan nama, SKU, atau kategori.
- Update: mengubah data produk.
- Discontinue: menandai produk yang tidak lagi diproduksi tanpa menghapus riwayatnya.
- Mengaktifkan kembali produk discontinued.
- Filter berdasarkan status.
- Menggunakan prepared statements PDO dan token CSRF pada form.

## Teknologi
- PHP 8 atau lebih baru
- MySQL/MariaDB
- HTML5 dan CSS3
- XAMPP (untuk server lokal)

## Cara menjalankan
1. Salin folder `my_store_uts` ke folder `htdocs` milik XAMPP.
2. Buka XAMPP, lalu aktifkan Apache dan MySQL.
3. Buka `http://localhost/phpmyadmin`.
4. Pilih menu **Import**, lalu impor file `database.sql` dari project ini. Alternatifnya, buka tab SQL dan jalankan isi file tersebut.
5. Pastikan pengaturan koneksi di `config/database.php` sesuai dengan MySQL lokal.
6. Buka `http://localhost/my_store_uts/`.
7. Login dengan akun awal:
   - Username: `admin`
   - Password: `admin123`
8. Buka **Kelola Produk** untuk menambah, mengedit, mencari, atau menandai produk discontinued.

## Struktur folder
```text
my_store_uts/
├── assets/
│   └── style.css
├── config/
│   ├── auth.php
│   └── database.php
├── screenshots/
│   └── .gitkeep
├── database.sql
├── dashboard.php
├── index.php
├── login.php
├── logout.php
├── products.php
└── README.md
```

## Database
Nama database: `my_store`

Tabel:
- `admins`: akun admin.
- `products`: data produk, harga, stok, kategori, dan status.

File `database.sql` menambahkan 25 data produk dummy.

## Screenshot
Simpan screenshot hasil pengerjaan ke folder `screenshots/`, misalnya:
- `01-login.png`
- `02-dashboard.png`
- `03-daftar-produk.png`
- `04-tambah-produk.png`
- `05-edit-produk.png`

## Catatan keamanan
Akun admin awal hanya untuk pengujian lokal. Ganti password sebelum aplikasi dipakai pada server sungguhan. Jangan mengunggah password pribadi atau konfigurasi rahasia ke repository publik.
