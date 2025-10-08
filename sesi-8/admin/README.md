# Admin Panel EduShop - CRUD Products

## Deskripsi
Panel admin untuk mengelola produk dengan fitur CRUD (Create, Read, Update, Delete) lengkap. Interface yang user-friendly dengan design modern menggunakan Bootstrap 5.

## Fitur Admin Panel

### 🏠 Dashboard
- **Statistik Real-time**: Total produk, kategori, stok menipis, stok habis
- **Chart Kategori**: Visualisasi distribusi produk per kategori
- **Produk Terbaru**: Daftar 5 produk yang baru ditambahkan
- **Quick Actions**: Tombol aksi cepat untuk navigasi
- **Auto Refresh**: Dashboard otomatis refresh setiap 30 detik

### 📦 Kelola Produk (`/admin/products/`)
1. **Halaman Utama (`index.php`)**
   - Tabel produk dengan DataTables
   - Filter berdasarkan kategori
   - Pencarian produk
   - Pagination otomatis
   - Aksi Edit & Hapus per produk
   - Konfirmasi hapus dengan modal

2. **Tambah Produk (`create.php`)**
   - Form lengkap dengan validasi
   - Upload gambar dengan drag & drop
   - Preview gambar real-time
   - Kategori baru atau pilih existing
   - Validasi client-side dan server-side

3. **Edit Produk (`edit.php`)**
   - Form pre-filled dengan data existing
   - Ubah gambar (opsional)
   - Preview gambar lama dan baru
   - Reset ke nilai original
   - Update data dengan konfirmasi

## Struktur File Admin

```
admin/
├── dashboard.php              # Dashboard utama dengan statistik
└── products/
    ├── index.php             # Daftar semua produk (Read)
    ├── create.php            # Form tambah produk (Create)
    └── edit.php              # Form edit produk (Update & Delete)
```

## Fitur Utama CRUD

### 1. CREATE (Tambah Produk)
- **Form Fields**: Nama, kategori, harga, stok, deskripsi, gambar
- **Upload Gambar**: Drag & drop atau click to select
- **Validasi**: Required fields, format gambar, ukuran file
- **Kategori Baru**: Bisa membuat kategori baru on-the-fly

### 2. READ (Lihat Produk)
- **Tabel Responsive**: Otomatis adjust di berbagai device
- **Filter & Search**: Berdasarkan kategori dan nama produk
- **Pagination**: Automatic dengan 10 produk per halaman
- **Sorting**: Klik header kolom untuk sort
- **Visual Indicators**: Badge untuk stok, harga format rupiah

### 3. UPDATE (Edit Produk)
- **Pre-filled Form**: Data existing otomatis terisi
- **Image Update**: Ganti gambar atau biarkan yang lama
- **Reset Function**: Kembalikan ke nilai original
- **Real-time Preview**: Lihat perubahan sebelum save

### 4. DELETE (Hapus Produk)
- **Konfirmasi Modal**: Popup konfirmasi sebelum hapus
- **Soft Validation**: Tampilkan nama produk yang akan dihapus
- **Image Cleanup**: Otomatis hapus file gambar dari server
- **Feedback**: Notifikasi sukses/error setelah aksi

## Teknologi yang Digunakan

### Frontend
- **Bootstrap 5.3.3**: Framework CSS responsive
- **Font Awesome 6.0**: Icon library
- **DataTables**: Enhanced table functionality
- **Chart.js**: Untuk dashboard charts
- **Custom CSS**: Styling khusus dengan CSS variables

### Backend
- **PHP 8+**: Server-side logic
- **MySQLi**: Database connection dan queries
- **Prepared Statements**: Keamanan SQL injection
- **File Upload**: Handle image upload dengan validasi

### JavaScript Features
- **AJAX**: Form submission tanpa reload
- **Drag & Drop**: Upload gambar modern
- **Form Validation**: Client-side validation
- **Image Preview**: Real-time preview gambar
- **Modal Confirmation**: Konfirmasi aksi berbahaya

## Keamanan & Validasi

### Server-side
- **SQL Injection Prevention**: Prepared statements
- **XSS Protection**: htmlspecialchars() untuk output
- **File Upload Security**: Validasi ekstensi dan ukuran
- **Data Sanitization**: Trim dan validasi input

### Client-side
- **Form Validation**: JavaScript validation sebelum submit
- **File Type Check**: Hanya terima format gambar
- **Required Fields**: Validasi field wajib
- **Number Validation**: Format harga dan stok

## Responsive Design

### Desktop (≥992px)
- Sidebar fixed di kiri
- Tabel full-width dengan semua kolom
- 4 stats card per row
- Modal center screen

### Tablet (768px - 991px)
- Sidebar collapse
- 2 stats card per row
- Horizontal scroll untuk tabel
- Adjusted button sizes

### Mobile (≤767px)
- Sidebar full-width di atas
- 1 stats card per row
- Card layout untuk produk
- Touch-friendly buttons

## Setup & Installation

### 1. Database Setup
```bash
# Jalankan script setup (jika belum)
php setup_products.php
```

### 2. Folder Permissions
```bash
# Pastikan folder images writable
chmod 755 images/
```

### 3. Access Admin Panel
```
# Dashboard
http://localhost/sesi-8/admin/dashboard.php

# Kelola Produk
http://localhost/sesi-8/admin/products/index.php
```

## Browser Support
- **Chrome** 90+
- **Firefox** 88+
- **Safari** 14+
- **Edge** 90+

## Performance Features
- **Lazy Loading**: DataTables load data on demand
- **Image Compression**: Auto resize untuk upload
- **CSS/JS Minification**: CDN untuk dependencies
- **Caching**: Browser caching untuk static assets

## Future Enhancements
1. **Role-based Access**: Multi-level admin access
2. **Bulk Actions**: Select multiple products untuk batch operations
3. **Export/Import**: CSV/Excel export/import produk
4. **Image Gallery**: Multiple images per product
5. **Advanced Analytics**: More detailed statistics
6. **Audit Log**: Track semua perubahan data