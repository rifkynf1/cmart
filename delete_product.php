<?php
require 'includes/config.php';
require 'includes/auth.php';

// jika tidak login, direct ke login page
if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}
// jika parameter id di url tidak ada, direct ke index
if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$productId = $_GET['id'];

// Cek kepemilikan produk
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ? AND user_id = ?");
$stmt->execute([$productId, getCurrentUserId()]);
$product = $stmt->fetch();

if ($product) {
    // Hapus gambar
    if ($product['image'] && file_exists($product['image'])) {
        unlink($product['image']);
    }
    
    // Hapus dari database
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$productId]);
}

header('Location: my_product.php');
exit;
?>