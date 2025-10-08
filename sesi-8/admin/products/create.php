<?php
require_once '../../koneksi.php';

$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_produk = trim($_POST['nama_produk']);
    $kategori = trim($_POST['kategori']);
    $harga = (float)$_POST['harga'];
    $stok = (int)$_POST['stok'];
    $deskripsi = trim($_POST['deskripsi']);
    
    // Validation
    if (empty($nama_produk) || empty($kategori) || $harga <= 0) {
        $error_message = "Nama produk, kategori, dan harga harus diisi dengan benar!";
    } else {
        // Handle file upload
        $gambar = '';
        if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = '../../images/';
            $file_extension = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));
            $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            
            if (in_array($file_extension, $allowed_extensions)) {
                $gambar = uniqid() . '.' . $file_extension;
                $upload_path = $upload_dir . $gambar;
                
                if (!move_uploaded_file($_FILES['gambar']['tmp_name'], $upload_path)) {
                    $error_message = "Gagal mengupload gambar!";
                }
            } else {
                $error_message = "Format gambar tidak didukung! Gunakan JPG, PNG, GIF, atau WebP.";
            }
        }
        
        if (empty($error_message)) {
            $sql = "INSERT INTO produk (nama_produk, kategori, harga, gambar, deskripsi, stok) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssdssi", $nama_produk, $kategori, $harga, $gambar, $deskripsi, $stok);
            
            if ($stmt->execute()) {
                $success_message = "Produk berhasil ditambahkan!";
                // Reset form
                $_POST = [];
            } else {
                $error_message = "Gagal menambahkan produk: " . $conn->error;
            }
        }
    }
}

// Get existing categories for select options
$categories_sql = "SELECT DISTINCT kategori FROM produk ORDER BY kategori";
$categories_result = $conn->query($categories_sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk | Admin EduShop</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --success-color: #27ae60;
            --danger-color: #e74c3c;
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

        .form-control:focus,
        .form-select:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
        }

        .image-preview {
            max-width: 200px;
            max-height: 200px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .drop-zone {
            border: 2px dashed #ddd;
            border-radius: 10px;
            padding: 30px;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .drop-zone:hover {
            border-color: var(--secondary-color);
            background-color: #f8f9ff;
        }

        .drop-zone.dragover {
            border-color: var(--success-color);
            background-color: #f0fff4;
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
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">
                            <a href="index.php" class="text-decoration-none">Kelola Produk</a>
                        </li>
                        <li class="breadcrumb-item active">Tambah Produk</li>
                    </ol>
                </nav>
                <div class="navbar-nav ms-auto">
                    <span class="navbar-text">
                        <i class="fas fa-user-circle me-2"></i>Admin
                    </span>
                </div>
            </div>
        </nav>

        <!-- Alert Messages -->
        <?php if (!empty($success_message)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i><?= $success_message ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <?php if (!empty($error_message)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i><?= $error_message ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <!-- Form Card -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-plus-circle me-2"></i>Tambah Produk Baru
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data" id="productForm">
                    <div class="row">
                        <!-- Product Information -->
                        <div class="col-md-8">
                            <h6 class="mb-3 text-primary">
                                <i class="fas fa-info-circle me-2"></i>Informasi Produk
                            </h6>
                            
                            <div class="mb-3">
                                <label for="nama_produk" class="form-label">Nama Produk *</label>
                                <input type="text" 
                                       class="form-control" 
                                       id="nama_produk" 
                                       name="nama_produk" 
                                       placeholder="Masukkan nama produk"
                                       value="<?= isset($_POST['nama_produk']) ? htmlspecialchars($_POST['nama_produk']) : '' ?>"
                                       required>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="kategori" class="form-label">Kategori *</label>
                                        <div class="input-group">
                                            <select class="form-select" id="kategori" name="kategori" required>
                                                <option value="">Pilih atau buat kategori baru</option>
                                                <?php 
                                                $categories_result->data_seek(0);
                                                while($category = $categories_result->fetch_assoc()): ?>
                                                    <option value="<?= htmlspecialchars($category['kategori']) ?>"
                                                            <?= (isset($_POST['kategori']) && $_POST['kategori'] === $category['kategori']) ? 'selected' : '' ?>>
                                                        <?= htmlspecialchars($category['kategori']) ?>
                                                    </option>
                                                <?php endwhile; ?>
                                            </select>
                                            <button class="btn btn-outline-secondary" type="button" id="newCategoryBtn">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                        <input type="text" 
                                               class="form-control mt-2 d-none" 
                                               id="new_kategori" 
                                               placeholder="Nama kategori baru">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="harga" class="form-label">Harga (Rp) *</label>
                                        <input type="number" 
                                               class="form-control" 
                                               id="harga" 
                                               name="harga" 
                                               min="0" 
                                               step="1000"
                                               placeholder="0"
                                               value="<?= isset($_POST['harga']) ? $_POST['harga'] : '' ?>"
                                               required>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="stok" class="form-label">Stok</label>
                                <input type="number" 
                                       class="form-control" 
                                       id="stok" 
                                       name="stok" 
                                       min="0"
                                       placeholder="Jumlah stok"
                                       value="<?= isset($_POST['stok']) ? $_POST['stok'] : '0' ?>">
                            </div>

                            <div class="mb-3">
                                <label for="deskripsi" class="form-label">Deskripsi</label>
                                <textarea class="form-control" 
                                          id="deskripsi" 
                                          name="deskripsi" 
                                          rows="4" 
                                          placeholder="Deskripsi produk (opsional)"><?= isset($_POST['deskripsi']) ? htmlspecialchars($_POST['deskripsi']) : '' ?></textarea>
                            </div>
                        </div>

                        <!-- Product Image -->
                        <div class="col-md-4">
                            <h6 class="mb-3 text-primary">
                                <i class="fas fa-image me-2"></i>Gambar Produk
                            </h6>
                            
                            <div class="drop-zone" id="dropZone">
                                <input type="file" 
                                       class="d-none" 
                                       id="gambar" 
                                       name="gambar" 
                                       accept="image/*">
                                <div id="dropContent">
                                    <i class="fas fa-cloud-upload-alt display-4 text-muted mb-3"></i>
                                    <p class="mb-2">Klik untuk pilih gambar atau drag & drop</p>
                                    <small class="text-muted">JPG, PNG, GIF, WebP (Max: 2MB)</small>
                                </div>
                                <div id="imagePreview" class="d-none">
                                    <img class="image-preview" id="previewImg" alt="Preview">
                                    <div class="mt-2">
                                        <button type="button" class="btn btn-sm btn-outline-danger" id="removeImage">
                                            <i class="fas fa-trash me-1"></i>Hapus
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <hr class="my-4">
                    <div class="d-flex justify-content-between">
                        <a href="index.php" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Kembali
                        </a>
                        <div>
                            <button type="reset" class="btn btn-outline-warning me-2">
                                <i class="fas fa-undo me-2"></i>Reset
                            </button>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save me-2"></i>Simpan Produk
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // New category functionality
        document.getElementById('newCategoryBtn').addEventListener('click', function() {
            const select = document.getElementById('kategori');
            const newInput = document.getElementById('new_kategori');
            
            if (newInput.classList.contains('d-none')) {
                newInput.classList.remove('d-none');
                newInput.focus();
                select.value = '';
                this.innerHTML = '<i class="fas fa-times"></i>';
            } else {
                newInput.classList.add('d-none');
                newInput.value = '';
                this.innerHTML = '<i class="fas fa-plus"></i>';
            }
        });

        document.getElementById('new_kategori').addEventListener('blur', function() {
            if (this.value.trim() !== '') {
                document.getElementById('kategori').value = this.value.trim();
            }
        });

        // Image upload functionality
        const dropZone = document.getElementById('dropZone');
        const fileInput = document.getElementById('gambar');
        const dropContent = document.getElementById('dropContent');
        const imagePreview = document.getElementById('imagePreview');
        const previewImg = document.getElementById('previewImg');
        const removeBtn = document.getElementById('removeImage');

        dropZone.addEventListener('click', () => fileInput.click());

        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZone.classList.add('dragover');
        });

        dropZone.addEventListener('dragleave', () => {
            dropZone.classList.remove('dragover');
        });

        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropZone.classList.remove('dragover');
            
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                fileInput.files = files;
                handleFileSelect(files[0]);
            }
        });

        fileInput.addEventListener('change', (e) => {
            if (e.target.files.length > 0) {
                handleFileSelect(e.target.files[0]);
            }
        });

        removeBtn.addEventListener('click', () => {
            fileInput.value = '';
            imagePreview.classList.add('d-none');
            dropContent.classList.remove('d-none');
        });

        function handleFileSelect(file) {
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    previewImg.src = e.target.result;
                    dropContent.classList.add('d-none');
                    imagePreview.classList.remove('d-none');
                };
                reader.readAsDataURL(file);
            }
        }

        // Form validation
        document.getElementById('productForm').addEventListener('submit', function(e) {
            const nama = document.getElementById('nama_produk').value.trim();
            const kategori = document.getElementById('kategori').value || document.getElementById('new_kategori').value.trim();
            const harga = parseFloat(document.getElementById('harga').value);

            if (!nama || !kategori || !harga || harga <= 0) {
                e.preventDefault();
                alert('Mohon lengkapi semua field yang wajib diisi!');
                return false;
            }

            // Set kategori value if using new category
            if (document.getElementById('new_kategori').value.trim()) {
                document.getElementById('kategori').value = document.getElementById('new_kategori').value.trim();
            }
        });

        // Auto-hide alerts
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);

        // Format currency input
        document.getElementById('harga').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            e.target.value = value;
        });
    </script>
</body>
</html>

<?php
$conn->close();
?>