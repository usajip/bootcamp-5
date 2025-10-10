<?php
require_once 'koneksi.php';

// Membuat tabel produk jika belum ada
$sql_create_table = "CREATE TABLE IF NOT EXISTS produk (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_produk VARCHAR(255) NOT NULL,
    kategori VARCHAR(100) NOT NULL,
    harga DECIMAL(10,2) NOT NULL,
    gambar VARCHAR(255),
    deskripsi TEXT,
    stok INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql_create_table) === TRUE) {
    echo "Tabel produk berhasil dibuat atau sudah ada.\n";
} else {
    echo "Error creating table: " . $conn->error;
}

// Menambahkan data sample produk
$sample_products = [
    ['Laptop Gaming ASUS ROG', 'Elektronik', 15000000, 'laptop-asus.jpg', 'Laptop gaming dengan spesifikasi tinggi untuk bermain game dan bekerja', 10],
    ['iPhone 15 Pro', 'Elektronik', 18000000, 'iphone-15.jpg', 'Smartphone terbaru dari Apple dengan teknologi canggih', 15],
    ['Samsung Galaxy S24', 'Elektronik', 12000000, 'samsung-s24.jpg', 'Android flagship dengan kamera berkualitas tinggi', 20],
    ['Kaos Polos Premium', 'Fashion', 150000, 'kaos-polos.jpg', 'Kaos polos berbahan cotton combed 30s yang nyaman dipakai', 50],
    ['Sepatu Sneakers Nike', 'Fashion', 2500000, 'nike-sneakers.jpg', 'Sepatu olahraga dengan desain modern dan comfortable', 30],
    ['Jaket Hoodie Unisex', 'Fashion', 350000, 'hoodie.jpg', 'Jaket hoodie dengan bahan fleece yang hangat', 25],
    ['Novel Laskar Pelangi', 'Buku', 85000, 'laskar-pelangi.jpg', 'Novel bestseller karya Andrea Hirata', 40],
    ['Buku Programming PHP', 'Buku', 125000, 'php-book.jpg', 'Panduan lengkap belajar pemrograman PHP dari dasar', 35],
    ['The Psychology of Money', 'Buku', 95000, 'psychology-money.jpg', 'Buku tentang psikologi dalam mengelola keuangan', 28],
    ['Kursi Gaming', 'Furniture', 2800000, 'gaming-chair.jpg', 'Kursi gaming ergonomis untuk kenyamanan bermain game', 12],
    ['Meja Kerja Minimalis', 'Furniture', 1500000, 'meja-kerja.jpg', 'Meja kerja dengan desain minimalis dan modern', 8],
    ['Lemari Pakaian 3 Pintu', 'Furniture', 3200000, 'lemari-pakaian.jpg', 'Lemari pakaian dengan kapasitas besar dan desain elegan', 6]
];

// Cek apakah sudah ada data
$check_data = "SELECT COUNT(*) as count FROM produk";
$result = $conn->query($check_data);
$row = $result->fetch_assoc();

if ($row['count'] == 0) {
    foreach ($sample_products as $product) {
        $sql_insert = "INSERT INTO produk (nama_produk, kategori, harga, gambar, deskripsi, stok) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql_insert);
        $stmt->bind_param("ssdssi", $product[0], $product[1], $product[2], $product[3], $product[4], $product[5]);
        
        if ($stmt->execute()) {
            echo "Produk '{$product[0]}' berhasil ditambahkan.\n";
        } else {
            echo "Error: " . $stmt->error;
        }
    }
} else {
    echo "Data produk sudah ada dalam database.\n";
}

$conn->close();
echo "Setup database produk selesai!\n";
?>