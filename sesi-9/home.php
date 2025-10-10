<?php
require_once 'koneksi.php';

// Ambil semua category untuk filter
$sql_categories = "SELECT DISTINCT category FROM product ORDER BY category";
$categories_result = $conn->query($sql_categories);

// Ambil data produk berdasarkan filter category jika ada
$category_filter = isset($_GET['category']) ? $_GET['category'] : '';
$search_filter = isset($_GET['search']) ? $_GET['search'] : '';

$sql_products = "SELECT * FROM product WHERE 1=1";
$params = [];
$types = "";

if (!empty($category_filter)) {
    $sql_products .= " AND category = ?";
    $params[] = $category_filter;
    $types .= "s";
}

if (!empty($search_filter)) {
    $sql_products .= " AND name LIKE ?";
    $params[] = "%$search_filter%";
    $types .= "s";
}

$sql_products .= " ORDER BY id DESC";

$stmt = $conn->prepare($sql_products);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$products_result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduShop - Online Store</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
<link href="style.css" rel="stylesheet">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="#"><i class="fas fa-shopping-bag me-2"></i>EduShop</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Produk</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Kategori</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Kontak</a>
                    </li>
                </ul>
                <div class="d-flex">
                    <a href="cart.php" class="btn btn-outline-light me-2">
                        <i class="fas fa-shopping-cart"></i> Keranjang
                    </a>
                    <a href="#" class="btn btn-light">
                        <i class="fas fa-user"></i> Login
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold mb-4">Selamat Datang di EduShop</h1>
                    <p class="lead mb-4">Temukan berbagai produk berkualitas dengan harga terbaik. Dari elektronik hingga fashion, semua ada di sini!</p>
                    <a href="#products" class="btn btn-light btn-lg">
                        <i class="fas fa-shopping-bag me-2"></i>Mulai Belanja
                    </a>
                </div>
                <div class="col-lg-6">
                    <div class="text-center">
                        <i class="fas fa-store display-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Filter Section -->
    <section class="filter-section" id="products">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h2 class="text-center mb-4">Produk Kami</h2>
                    
                    <!-- Filter Form -->
                    <form method="GET" class="row g-3 justify-content-center">
                        <div class="col-md-4">
                            <select name="category" class="form-select" onchange="this.form.submit()">
                                <option value="">Semua Kategori</option>
                                <?php while($category = $categories_result->fetch_assoc()): ?>
                                    <option value="<?= htmlspecialchars($category['category']) ?>" 
                                            <?= $category_filter === $category['category'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($category['category']) ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <input type="text" 
                                   name="search" 
                                   class="form-control" 
                                   placeholder="Cari produk..."
                                   value="<?= htmlspecialchars($search_filter) ?>">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-custom w-100">
                                <i class="fas fa-search me-1"></i>Cari
                            </button>
                        </div>
                        <?php if ($category_filter || $search_filter): ?>
                        <div class="col-md-2">
                            <a href="?" class="btn btn-outline-secondary w-100">
                                <i class="fas fa-times me-1"></i>Reset
                            </a>
                        </div>
                        <?php endif; ?>
                    </form>
                    
                    <!-- Filter Info -->
                    <?php if ($category_filter || $search_filter): ?>
                    <div class="mt-3 text-center">
                        <span class="badge bg-info me-2">
                            Filter aktif: 
                            <?php if ($category_filter): ?>
                                Kategori: <?= htmlspecialchars($category_filter) ?>
                            <?php endif; ?>
                            <?php if ($search_filter): ?>
                                Pencarian: "<?= htmlspecialchars($search_filter) ?>"
                            <?php endif; ?>
                        </span>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Products Section -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <?php if ($products_result->num_rows > 0): ?>
                    <?php while($product = $products_result->fetch_assoc()): ?>
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                        <div class="card product-card h-100">
                            <div class="product-image">
                                <?php if (!empty($product['gambar'])): ?>
                                    <img src="images/<?= htmlspecialchars($product['gambar']) ?>" 
                                         class="card-img-top" 
                                         alt="<?= htmlspecialchars($product['name']) ?>"
                                         style="height: 250px; object-fit: cover;">
                                <?php else: ?>
                                    <i class="fas fa-image"></i>
                                <?php endif; ?>
                            </div>
                            <div class="card-body d-flex flex-column">
                                <div class="mb-2">
                                    <span class="category-badge"><?= htmlspecialchars($product['category']) ?></span>
                                </div>
                                <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
                                <p class="card-text flex-grow-1"><?= htmlspecialchars(substr($product['description'], 0, 100)) ?>...</p>
                                <div class="mt-auto">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="price-tag">Rp <?= number_format($product['price'], 0, ',', '.') ?></span>
                                        <small class="text-muted">Stok: <?= $product['stock'] ?></small>
                                    </div>
                                    <div class="d-grid">
                                        <button class="btn btn-custom" onclick="addToCart(<?= $product['id'] ?>)">
                                            <i class="fas fa-cart-plus me-2"></i>Tambah ke Keranjang
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="col-12">
                        <div class="loading-placeholder">
                            <i class="fas fa-search display-4 mb-3"></i>
                            <h4>Produk tidak ditemukan</h4>
                            <p>Coba ubah filter atau kata kunci pencarian Anda.</p>
                            <a href="?" class="btn btn-custom">Lihat Semua Produk</a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5><i class="fas fa-shopping-bag me-2"></i>EduShop</h5>
                    <p>Platform e-commerce terpercaya dengan berbagai pilihan produk berkualitas untuk semua kebutuhan Anda.</p>
                </div>
                <div class="col-md-2 mb-4">
                    <h6>Kategori</h6>
                    <ul class="list-unstyled">
                        <li><a href="?kategori=Elektronik" class="text-light text-decoration-none">Elektronik</a></li>
                        <li><a href="?kategori=Fashion" class="text-light text-decoration-none">Fashion</a></li>
                        <li><a href="?kategori=Buku" class="text-light text-decoration-none">Buku</a></li>
                        <li><a href="?kategori=Furniture" class="text-light text-decoration-none">Furniture</a></li>
                    </ul>
                </div>
                <div class="col-md-3 mb-4">
                    <h6>Bantuan</h6>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-light text-decoration-none">Cara Berbelanja</a></li>
                        <li><a href="#" class="text-light text-decoration-none">Pembayaran</a></li>
                        <li><a href="#" class="text-light text-decoration-none">Pengiriman</a></li>
                        <li><a href="#" class="text-light text-decoration-none">FAQ</a></li>
                    </ul>
                </div>
                <div class="col-md-3 mb-4">
                    <h6>Hubungi Kami</h6>
                    <p><i class="fas fa-phone me-2"></i>+62 812-3456-7890</p>
                    <p><i class="fas fa-envelope me-2"></i>info@edushop.com</p>
                    <div>
                        <a href="#" class="text-light me-3"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-light me-3"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-light me-3"><i class="fab fa-twitter"></i></a>
                    </div>
                </div>
            </div>
            <hr>
            <div class="text-center">
                <p>&copy; 2024 EduShop. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JavaScript -->
    <script>
        // Fungsi untuk menambah produk ke keranjang
        function addToCart(productId) {
            // Implementasi AJAX untuk menambah ke keranjang
            fetch('add_to_cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    product_id: productId,
                    quantity: 1
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Tampilkan notifikasi sukses
                    showNotification('Produk berhasil ditambahkan ke keranjang!', 'success');
                } else {
                    showNotification('Gagal menambahkan produk ke keranjang!', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Terjadi kesalahan!', 'error');
            });
        }

        // Fungsi untuk menampilkan notifikasi
        function showNotification(message, type) {
            const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
            const notification = `
                <div class="alert ${alertClass} alert-dismissible fade show position-fixed" 
                     style="top: 20px; right: 20px; z-index: 9999; min-width: 300px;" role="alert">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;
            document.body.insertAdjacentHTML('beforeend', notification);
            
            // Auto remove after 3 seconds
            setTimeout(() => {
                const alert = document.querySelector('.alert');
                if (alert) alert.remove();
            }, 3000);
        }

        // Smooth scrolling for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            });
        });

        // Loading animation for filter changes
        document.querySelector('select[name="kategori"]').addEventListener('change', function() {
            const container = document.querySelector('.container .row');
            container.style.opacity = '0.6';
        });
    </script>
</body>
</html>

<?php
$conn->close();
?>