# EduShop - Halaman Home E-commerce

## Deskripsi
Halaman home e-commerce yang dibuat menggunakan HTML, Bootstrap, dan PHP dengan fitur filter produk berdasarkan kategori dan pencarian.

## Fitur Utama
1. **Tampilan Responsive** - Menggunakan Bootstrap 5 untuk tampilan yang responsive di semua device
2. **Filter Kategori** - Filter produk berdasarkan kategori (Elektronik, Fashion, Buku, Furniture)
3. **Pencarian Produk** - Cari produk berdasarkan nama
4. **Database Integration** - Data produk diambil dari database MySQL
5. **Keranjang Belanja** - Fitur menambah produk ke keranjang (menggunakan session)
6. **Design Modern** - UI/UX yang menarik dengan animasi dan efek hover

## Struktur File
```
sesi-8/
├── home.php              # Halaman utama e-commerce
├── setup_products.php    # Script untuk setup database dan data sample
├── add_to_cart.php      # API untuk menambah produk ke keranjang
├── koneksi.php          # File koneksi database (sudah ada)
└── images/              # Folder untuk gambar produk
```

## Cara Penggunaan

### 1. Setup Database
Jalankan script setup untuk membuat tabel dan data sample:
```bash
php setup_products.php
```

### 2. Akses Halaman
Buka file `home.php` melalui web server atau langsung:
```
http://localhost/sesi-8/home.php
```

### 3. Fitur yang Tersedia
- **Filter Kategori**: Pilih dropdown kategori untuk filter produk
- **Pencarian**: Ketik nama produk di kotak pencarian
- **Reset Filter**: Klik tombol "Reset" untuk menghapus semua filter
- **Tambah ke Keranjang**: Klik tombol pada setiap produk (menggunakan AJAX)

## Struktur Database

### Tabel: produk
```sql
CREATE TABLE produk (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_produk VARCHAR(255) NOT NULL,
    kategori VARCHAR(100) NOT NULL,
    harga DECIMAL(10,2) NOT NULL,
    gambar VARCHAR(255),
    deskripsi TEXT,
    stok INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

## Kategori Produk yang Tersedia
1. **Elektronik** - Laptop, Smartphone, dll
2. **Fashion** - Pakaian, Sepatu, Aksesoris
3. **Buku** - Novel, Buku Pelajaran, dll
4. **Furniture** - Kursi, Meja, Lemari

## Teknologi yang Digunakan
- **Frontend**: HTML5, CSS3, JavaScript
- **Framework CSS**: Bootstrap 5.3.3
- **Icons**: Font Awesome 6.0.0
- **Backend**: PHP 8+
- **Database**: MySQL/MariaDB
- **AJAX**: Fetch API untuk keranjang belanja

## Fitur Responsive
- Desktop: 4 kolom produk per baris
- Tablet: 3 kolom produk per baris
- Mobile: 1-2 kolom produk per baris
- Navigation menu collapse pada mobile

## Customization
Anda dapat mengkustomisasi:
1. **Warna tema** - Edit variabel CSS di bagian `:root`
2. **Kategori produk** - Tambahkan kategori baru di database
3. **Layout produk** - Modifikasi struktur card di bagian products section
4. **Gambar produk** - Tambahkan gambar ke folder `images/`

## Notes
- Pastikan web server mendukung PHP 7.4+
- Pastikan database MySQL/MariaDB sudah running
- Update kredensial database di file `koneksi.php` sesuai setup Anda
- Untuk production, tambahkan validasi dan sanitasi input yang lebih ketat