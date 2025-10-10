<?php
session_start();

// Handle update quantity
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_quantity'])) {
        $id = (int)$_POST['product_id'];
        $qty = max(1, (int)$_POST['quantity']);
        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id] = $qty;
        }
    }
    if (isset($_POST['delete_item'])) {
        $id = (int)$_POST['product_id'];
        unset($_SESSION['cart'][$id]);
    }
    header("Location: cart.php");
    exit;
}

// Simulasi menampilkan isi keranjang
$cart_items = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$total_items = array_sum($cart_items);
if($total_items > 0){
    require 'koneksi.php';
    $product_id_from_cart = implode(',', array_keys($cart_items));

    $sql = "SELECT id, name, price, image FROM product WHERE id IN (" . implode(',', array_keys($cart_items)) . ")";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $products = $stmt->get_result();
    $product_details = $products->fetch_all(MYSQLI_ASSOC);
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
    <div class="container mt-5">
        <h1 class="mb-4">Keranjang Belanja</h1>
        <?php if ($total_items > 0): ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Gambar</th>
                        <th>Produk</th>
                        <th>Harga</th>
                        <th>Jumlah</th>
                        <th>Total</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($product_details as $product): 
                        $quantity = $cart_items[$product['id']];
                        $total_price = $product['price'] * $quantity;
                    ?>
                    <tr>
                        <td><img src="images/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="img-fluid" style="max-height: 100px; object-fit: cover;"></td>
                        <td><?= htmlspecialchars($product['name']) ?></td>
                        <td>Rp <?= number_format($product['price'], 0, ',', '.') ?></td>
                        <td>
                            <form method="post" class="d-flex align-items-center">
                                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                <input type="number" name="quantity" value="<?= $quantity ?>" min="1" class="form-control form-control-sm me-2" style="width: 70px;">
                                <button type="submit" name="update_quantity" class="btn btn-sm btn-success me-2">Update</button>
                            </form>
                        </td>
                        <td>Rp <?= number_format($total_price, 0, ',', '.') ?></td>
                        <td>
                            <form method="post" onsubmit="return confirm('Hapus item ini dari keranjang?');">
                                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                <button type="submit" name="delete_item" class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <h4>Total Item: <?= $total_items ?></h4>
            <h4>Total Harga: Rp <?= number_format(array_sum(array_map(function($product) {
                global $cart_items;
                return $product['price'] * $cart_items[$product['id']];
            }, $product_details)), 0, ',', '.') ?></h4>
            <?php
                $total_price = array_sum(array_map(function($product) use ($cart_items) {
                    return $product['price'] * $cart_items[$product['id']];
                }, $product_details));
                $message = "Halo, saya ingin memesan produk berikut:%0A";
                foreach ($product_details as $product) {
                    $quantity = $cart_items[$product['id']];
                    $total_product_price = $product['price'] * $quantity;
                    $message .= "- " . htmlspecialchars($product['name']) . " (Qty: " . $quantity . ") - Rp " . number_format($total_product_price, 0, ',', '.') . "%0A";
                }
                $message .= "Total Harga: Rp " . number_format($total_price, 0, ',', '.') . "%0A";
                $message .= "Terima kasih!";
            ?>
            <a href="https://wa.me/1234567890?text=<?= urlencode($message) ?>" class="btn btn-primary mt-3">Pesan melalui Whatsapp</a>
        <?php else: ?>
            <p>Keranjang Anda kosong.</p>
        <?php endif; ?>
        <a href="home.php" class="btn btn-primary mt-3">Lanjutkan Belanja</a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
