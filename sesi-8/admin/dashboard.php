<?php
require_once '../koneksi.php';

// Get statistics
$stats = [];

// Total products
$sql = "SELECT COUNT(*) as total FROM produk";
$result = $conn->query($sql);
$stats['total_products'] = $result->fetch_assoc()['total'];

// Total categories
$sql = "SELECT COUNT(DISTINCT kategori) as total FROM produk";
$result = $conn->query($sql);
$stats['total_categories'] = $result->fetch_assoc()['total'];

// Low stock products (less than 5)
$sql = "SELECT COUNT(*) as total FROM produk WHERE stok < 5";
$result = $conn->query($sql);
$stats['low_stock'] = $result->fetch_assoc()['total'];

// Out of stock products
$sql = "SELECT COUNT(*) as total FROM produk WHERE stok = 0";
$result = $conn->query($sql);
$stats['out_of_stock'] = $result->fetch_assoc()['total'];

// Recent products
$sql = "SELECT * FROM produk ORDER BY created_at DESC LIMIT 5";
$recent_products = $conn->query($sql);

// Products by category
$sql = "SELECT kategori, COUNT(*) as jumlah FROM produk GROUP BY kategori ORDER BY jumlah DESC";
$category_stats = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin | EduShop</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --success-color: #27ae60;
            --danger-color: #e74c3c;
            --warning-color: #f39c12;
            --info-color: #3498db;
            --light-bg: #f8f9fa;
        }

        .sidebar {
            background: linear-gradient(180deg, var(--primary-color), var(--secondary-color));
            min-height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            z-index: 1000;
        }

        .main-content {
            margin-left: 250px;
            padding: 20px;
        }

        .navbar-admin {
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        .card {
            border: none;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            border-radius: 10px;
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 12px 20px;
            border-radius: 0;
            transition: all 0.3s ease;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: rgba(255,255,255,0.1);
            color: white;
        }

        .stats-card {
            border-radius: 15px;
            color: white;
            overflow: hidden;
        }

        .stats-card.primary {
            background: linear-gradient(135deg, var(--primary-color), #34495e);
        }

        .stats-card.success {
            background: linear-gradient(135deg, var(--success-color), #2ecc71);
        }

        .stats-card.warning {
            background: linear-gradient(135deg, var(--warning-color), #e67e22);
        }

        .stats-card.danger {
            background: linear-gradient(135deg, var(--danger-color), #c0392b);
        }

        .stats-card.info {
            background: linear-gradient(135deg, var(--info-color), #5dade2);
        }

        .stats-icon {
            font-size: 3rem;
            opacity: 0.7;
        }

        .welcome-card {
            background: linear-gradient(135deg, var(--secondary-color), var(--info-color));
            color: white;
            border-radius: 15px;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }
            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <nav class="sidebar">
        <div class="p-3">
            <h4 class="text-white text-center">
                <i class="fas fa-cogs me-2"></i>Admin Panel
            </h4>
        </div>
        <ul class="nav nav-pills flex-column">
            <li class="nav-item">
                <a class="nav-link active" href="dashboard.php">
                    <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="products/index.php">
                    <i class="fas fa-box me-2"></i>Kelola Produk
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="orders/">
                    <i class="fas fa-shopping-cart me-2"></i>Pesanan
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="users/">
                    <i class="fas fa-users me-2"></i>Pengguna
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../home.php">
                    <i class="fas fa-home me-2"></i>Kembali ke Home
                </a>
            </li>
        </ul>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Navbar -->
        <nav class="navbar navbar-expand-lg navbar-admin">
            <div class="container-fluid">
                <h5 class="mb-0">Dashboard Admin</h5>
                <div class="navbar-nav ms-auto">
                    <span class="navbar-text">
                        <i class="fas fa-user-circle me-2"></i>Admin
                        <span class="ms-2 text-muted"><?= date('d M Y, H:i') ?></span>
                    </span>
                </div>
            </div>
        </nav>

        <!-- Welcome Card -->
        <div class="welcome-card p-4 mb-4">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h3 class="mb-2">Selamat Datang di Admin Panel EduShop</h3>
                    <p class="mb-0">Kelola produk, pesanan, dan pengguna dengan mudah melalui dashboard yang intuitif ini.</p>
                </div>
                <div class="col-md-4 text-center">
                    <i class="fas fa-chart-line display-4"></i>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card stats-card primary">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50">Total Produk</h6>
                                <h3 class="mb-0"><?= number_format($stats['total_products']) ?></h3>
                            </div>
                            <div class="stats-icon">
                                <i class="fas fa-box"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card stats-card success">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50">Total Kategori</h6>
                                <h3 class="mb-0"><?= number_format($stats['total_categories']) ?></h3>
                            </div>
                            <div class="stats-icon">
                                <i class="fas fa-tags"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card stats-card warning">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50">Stok Menipis</h6>
                                <h3 class="mb-0"><?= number_format($stats['low_stock']) ?></h3>
                            </div>
                            <div class="stats-icon">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card stats-card danger">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50">Stok Habis</h6>
                                <h3 class="mb-0"><?= number_format($stats['out_of_stock']) ?></h3>
                            </div>
                            <div class="stats-icon">
                                <i class="fas fa-times-circle"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts and Recent Data -->
        <div class="row">
            <!-- Category Chart -->
            <div class="col-lg-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-chart-pie me-2"></i>Produk per Kategori
                        </h5>
                    </div>
                    <div class="card-body">
                        <canvas id="categoryChart" width="400" height="400"></canvas>
                    </div>
                </div>
            </div>

            <!-- Recent Products -->
            <div class="col-lg-6 mb-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-clock me-2"></i>Produk Terbaru
                        </h5>
                        <a href="products/index.php" class="btn btn-sm btn-outline-primary">
                            Lihat Semua
                        </a>
                    </div>
                    <div class="card-body">
                        <?php if ($recent_products->num_rows > 0): ?>
                        <div class="list-group list-group-flush">
                            <?php while($product = $recent_products->fetch_assoc()): ?>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1"><?= htmlspecialchars($product['nama_produk']) ?></h6>
                                    <small class="text-muted">
                                        <span class="badge bg-primary me-2"><?= htmlspecialchars($product['kategori']) ?></span>
                                        Rp <?= number_format($product['harga'], 0, ',', '.') ?>
                                    </small>
                                </div>
                                <div class="text-end">
                                    <small class="text-muted d-block">
                                        <?= date('d/m/Y', strtotime($product['created_at'])) ?>
                                    </small>
                                    <span class="badge <?= $product['stok'] > 10 ? 'bg-success' : ($product['stok'] > 0 ? 'bg-warning' : 'bg-danger') ?>">
                                        Stok: <?= $product['stok'] ?>
                                    </span>
                                </div>
                            </div>
                            <?php endwhile; ?>
                        </div>
                        <?php else: ?>
                        <div class="text-center py-3">
                            <i class="fas fa-box-open display-4 text-muted"></i>
                            <p class="text-muted mt-3">Belum ada produk</p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-lightning-bolt me-2"></i>Aksi Cepat
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <a href="products/create.php" class="btn btn-success w-100">
                                    <i class="fas fa-plus me-2"></i>Tambah Produk
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="products/index.php" class="btn btn-primary w-100">
                                    <i class="fas fa-list me-2"></i>Lihat Semua Produk
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="products/index.php?stok_low=1" class="btn btn-warning w-100">
                                    <i class="fas fa-exclamation-triangle me-2"></i>Cek Stok Menipis
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="../home.php" class="btn btn-outline-secondary w-100">
                                    <i class="fas fa-home me-2"></i>Kembali ke Home
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Category Chart
        const ctx = document.getElementById('categoryChart').getContext('2d');
        
        const categoryData = {
            labels: [
                <?php 
                $category_stats->data_seek(0);
                $labels = [];
                while($cat = $category_stats->fetch_assoc()) {
                    $labels[] = "'" . htmlspecialchars($cat['kategori']) . "'";
                }
                echo implode(', ', $labels);
                ?>
            ],
            datasets: [{
                data: [
                    <?php 
                    $category_stats->data_seek(0);
                    $data = [];
                    while($cat = $category_stats->fetch_assoc()) {
                        $data[] = $cat['jumlah'];
                    }
                    echo implode(', ', $data);
                    ?>
                ],
                backgroundColor: [
                    '#3498db',
                    '#27ae60',
                    '#e74c3c',
                    '#f39c12',
                    '#9b59b6',
                    '#1abc9c',
                    '#34495e',
                    '#e67e22'
                ],
                borderWidth: 0
            }]
        };

        const categoryChart = new Chart(ctx, {
            type: 'doughnut',
            data: categoryData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true
                        }
                    }
                }
            }
        });

        // Auto refresh stats every 30 seconds
        setInterval(() => {
            location.reload();
        }, 30000);
    </script>
</body>
</html>

<?php
$conn->close();
?>