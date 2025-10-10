<?php
session_start();
header('Content-Type: application/json');

// Simulasi menambah produk ke keranjang
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($input['product_id']) || !isset($input['quantity'])) {
        echo json_encode(['success' => false, 'message' => 'Data tidak lengkap']);
        exit;
    }
    
    $product_id = (int)$input['product_id'];
    $quantity = (int)$input['quantity'];
    
    // Initialize cart if not exists
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    
    // Add or update product in cart
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = $quantity;
    }
    
    // Count total items in cart
    $total_items = array_sum($_SESSION['cart']);
    
    echo json_encode([
        'success' => true, 
        'message' => 'Produk berhasil ditambahkan ke keranjang',
        'total_items' => $total_items
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
}
?>