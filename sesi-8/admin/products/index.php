<?php
require_once '../../koneksi.php';

// Handle delete request
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $delete_sql = "DELETE FROM product WHERE id = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        $success_message = "Produk berhasil dihapus!";
    } else {
        $error_message = "Gagal menghapus produk!";
    }
}

// Get all products with pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 10;
$offset = ($page - 1) * $per_page;

// Search functionality
$search = isset($_GET['search']) ? $_GET['search'] : '';
$category_filter = isset($_GET['category']) ? $_GET['category'] : '';

$where_clause = "WHERE 1=1";
$params = [];
$types = "";

if (!empty($search)) {
    $where_clause .= " AND name LIKE ?";
    $params[] = "%$search%";
    $types .= "s";
}

if (!empty($category_filter)) {
    $where_clause .= " AND kategori = ?";
    $params[] = $category_filter;
    $types .= "s";
}

// Get total count for pagination
$count_sql = "SELECT COUNT(*) as total FROM product $where_clause";
$count_stmt = $conn->prepare($count_sql);
if (!empty($params)) {
    $count_stmt->bind_param($types, ...$params);
}
$count_stmt->execute();
$total_result = $count_stmt->get_result();
$total_products = $total_result->fetch_assoc()['total'];
$total_pages = ceil($total_products / $per_page);

// Get products for current page
$sql = "SELECT * FROM product $where_clause ORDER BY id DESC LIMIT ? OFFSET ?";
$params[] = $per_page;
$params[] = $offset;
$types .= "ii";

$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$products = $stmt->get_result();

// Get categories for filter
$categories_sql = "SELECT DISTINCT category FROM product ORDER BY category";
$categories_result = $conn->query($categories_sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Kelola Produk | EduShop</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --success-color: #27ae60;
            --danger-color: #e74c3c;
            --warning-color: #f39c12;
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
        }

        .table th {
            background-color: var(--light-bg);
            border: none;
            font-weight: 600;
            color: var(--primary-color);
        }

        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
            border-radius: 0.375rem;
        }

        .product-image {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 8px;
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
            background: linear-gradient(135deg, var(--success-color), #2ecc71);
            color: white;
            border-radius: 15px;
        }

        .filter-section {
            background-color: var(--light-bg);
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
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
                <a class="nav-link" href="../dashboard.php">
                    <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="index.php">
                    <i class="fas fa-box me-2"></i>Kelola Produk
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../orders/">
                    <i class="fas fa-shopping-cart me-2"></i>Pesanan
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../users/">
                    <i class="fas fa-users me-2"></i>Pengguna
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../../home.php">
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
                <h5 class="mb-0">Kelola Produk</h5>
                <div class="navbar-nav ms-auto">
                    <span class="navbar-text">
                        <i class="fas fa-user-circle me-2"></i>Admin
                    </span>
                </div>
            </div>
        </nav>

        <!-- Alert Messages -->
        <?php if (isset($success_message)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i><?= $success_message ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <?php if (isset($error_message)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i><?= $error_message ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card stats-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50">Total Produk</h6>
                                <h3 class="mb-0"><?= $total_products ?></h3>
                            </div>
                            <div class="text-white-50">
                                <i class="fas fa-box fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="filter-section">
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <label for="search" class="form-label">Cari Produk</label>
                    <input type="text" class="form-control" id="search" name="search" 
                           placeholder="Nama produk..." value="<?= htmlspecialchars($search) ?>">
                </div>
                <div class="col-md-3">
                    <label for="category" class="form-label">Kategori</label>
                    <select class="form-select" id="category" name="category">
                        <option value="">Semua Kategori</option>
                        <?php while($category = $categories_result->fetch_assoc()): ?>
                            <option value="<?= htmlspecialchars($category['category']) ?>" 
                                    <?= $category_filter === $category['category'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($category['category']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-search me-1"></i>Cari
                    </button>
                    <a href="index.php" class="btn btn-outline-secondary">
                        <i class="fas fa-refresh me-1"></i>Reset
                    </a>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <a href="create.php" class="btn btn-success w-100">
                        <i class="fas fa-plus me-1"></i>Tambah Produk
                    </a>
                </div>
            </form>
        </div>

        <!-- Products Table -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-list me-2"></i>Daftar Produk
                    <?php if (!empty($search) || !empty($category_filter)): ?>
                        <small class="text-muted">(<?= $total_products ?> hasil ditemukan)</small>
                    <?php endif; ?>
                </h5>
            </div>
            <div class="card-body">
                <?php if ($products->num_rows > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover" id="productsTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Gambar</th>
                                <th>Nama Produk</th>
                                <th>Kategori</th>
                                <th>Harga</th>
                                <th>Stok</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($product = $products->fetch_assoc()): ?>
                            <tr>
                                <td><?= $product['id'] ?></td>
                                <td>
                                    <?php if (!empty($product['gambar'])): ?>
                                        <img src="../../images/<?= htmlspecialchars($product['image']) ?>" 
                                             class="product-image" alt="Product">
                                    <?php else: ?>
                                        <div class="product-image bg-light d-flex align-items-center justify-content-center">
                                            <i class="fas fa-image text-muted"></i>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <strong><?= htmlspecialchars($product['name']) ?></strong>
                                    <br><small class="text-muted"><?= htmlspecialchars(substr($product['description'], 0, 50)) ?>...</small>
                                </td>
                                <td>
                                    <span class="badge bg-primary"><?= htmlspecialchars($product['category']) ?></span>
                                </td>
                                <td>
                                    <strong class="text-success">Rp <?= number_format($product['price'], 0, ',', '.') ?></strong>
                                </td>
                                <td>
                                    <?php if ($product['stock'] > 10): ?>
                                        <span class="badge bg-success"><?= $product['stock'] ?></span>
                                    <?php elseif ($product['stock'] > 0): ?>
                                        <span class="badge bg-warning"><?= $product['stock'] ?></span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Habis</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="edit.php?id=<?= $product['id'] ?>" 
                                           class="btn btn-outline-primary btn-sm" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-outline-danger btn-sm" 
                                                title="Hapus"
                                                onclick="confirmDelete(<?= $product['id'] ?>, '<?= htmlspecialchars($product['name']) ?>')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <?php if ($total_pages > 1): ?>
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center mt-4">
                        <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                            <a class="page-link" href="?page=<?= $page-1 ?>&search=<?= urlencode($search) ?>&category=<?= urlencode($category_filter) ?>">
                                Previous
                            </a>
                        </li>
                        
                        <?php for($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?= $page == $i ? 'active' : '' ?>">
                            <a class="page-link" href="?page=<?= $i ?>&search=<?= urlencode($search) ?>&category=<?= urlencode($category_filter) ?>">
                                <?= $i ?>
                            </a>
                        </li>
                        <?php endfor; ?>
                        
                        <li class="page-item <?= $page >= $total_pages ? 'disabled' : '' ?>">
                            <a class="page-link" href="?page=<?= $page+1 ?>&search=<?= urlencode($search) ?>&category=<?= urlencode($category_filter) ?>">
                                Next
                            </a>
                        </li>
                    </ul>
                </nav>
                <?php endif; ?>

                <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-box-open display-4 text-muted mb-3"></i>
                    <h5 class="text-muted">Tidak ada produk ditemukan</h5>
                    <p class="text-muted">Coba ubah filter pencarian atau <a href="create.php">tambah produk baru</a></p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus produk <strong id="productName"></strong>?</p>
                    <p class="text-danger"><i class="fas fa-exclamation-triangle me-2"></i>Aksi ini tidak dapat dibatalkan!</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <a href="#" class="btn btn-danger" id="deleteConfirmBtn">
                        <i class="fas fa-trash me-2"></i>Ya, Hapus
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

    <script>
        // Initialize DataTables with custom options
        $(document).ready(function() {
            $('#productsTable').DataTable({
                "paging": false,
                "searching": false,
                "info": false,
                "ordering": true,
                "order": [[ 0, "desc" ]],
                "columnDefs": [
                    { "orderable": false, "targets": [1, 7] }
                ],
                "language": {
                    "emptyTable": "Tidak ada data produk",
                    "zeroRecords": "Tidak ada data yang cocok"
                }
            });
        });

        // Delete confirmation function
        function confirmDelete(id, name) {
            document.getElementById('productName').textContent = name;
            document.getElementById('deleteConfirmBtn').href = '?delete=' + id;
            new bootstrap.Modal(document.getElementById('deleteModal')).show();
        }

        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);

        // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const search = document.getElementById('search').value;
            const category = document.getElementById('category').value;
            
            if (!search && !category) {
                e.preventDefault();
                window.location.href = 'index.php';
            }
        });
    </script>
</body>
</html>

<?php
$conn->close();
?>
