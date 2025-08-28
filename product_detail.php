<?php
require 'includes/config.php';
require 'includes/auth.php';

// jika parameter id di url tidak ada, direct ke index
if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

// querry untuk ambil product data dari user
$productId = $_GET['id'];
$stmt = $pdo->prepare("SELECT products.*, users.nama, users.no_hp 
                       FROM products 
                       JOIN users ON products.user_id = users.id 
                       WHERE products.id = ?");
$stmt->execute([$productId]);
$product = $stmt->fetch();

if (!$product) {
    header('Location: index.php');
    exit;
}

// Variabel (Penjual = isowner) Dan (Pembeli = canbuy)
$isOwner = isLoggedIn() && ($product['user_id'] == getCurrentUserId());
$canBuy = isLoggedIn() && !$isOwner && !empty($product['no_hp']);

// Direct ke WA
$waLink = '';
if ($canBuy) {
    // Ambil hanya angka
    $whatsapp_number = preg_replace('/[^0-9]/', '', $product['no_hp']);

    // Ganti awalan 0 menjadi 62 
    if (str_starts_with($whatsapp_number, '0')) {
        $whatsapp_number = '62' . substr($whatsapp_number, 1);
    }

    $productUrl = "http://localhost/kodephp/marketplace_kampus/product_detail.php?id=" . $product['id'];

    $message = "Hello, I want to buy a product :\n";
    $message .= "Title : " . $product['title'] . "\n";
    $message .= "Price : Rp " . number_format($product['price'], 0) . "\n";
    $message .= "Is it still available? \n\n";
    $message .= "Product Link : " . $productUrl;

    $waLink = "https://api.whatsapp.com/send?phone=" . $whatsapp_number . "&text=" . urlencode($message);
}

?>

<!-- Header -->
<?php require 'includes/header.php'; ?>

<div class="container-lg">
    <div class="row">

        <!-- Sidebar -->
        <?php include 'includes/sidebar.php'; ?>

        <!-- Konten utama -->
        <div class="col-lg-9">
            <div class="card mb-4 mt-2">
                <div class="card-body">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="my_product.php">My Product</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Detail Product</li>
                        </ol>
                    </nav>
                    <hr>

                    <h1 class="mb-4 text-center"><?= htmlspecialchars($product['title']) ?></h1>

                    <div class="row">
                        <div class="col-md-6 mb-4 mb-md-0">
                            <img src="<?= htmlspecialchars($product['image']) ?>" class="img-fluid rounded" alt="<?= htmlspecialchars($product['title']) ?>">
                        </div>

                        <div class="col-md-6">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <h3 class="text-purple mb-0">Rp <?= number_format($product['price'], 0) ?></h3>
                                <span class="badge bg-success">Ready</span>
                            </div>

                            <div class="mb-4">
                                <span class="fw-bold">Seller :</span>
                                <span class="ms-2 fw-semibold text-primary"><?= htmlspecialchars($product['nama']) ?></span>
                            </div>

                            <div class="mb-4">
                                <span class="fw-bold">Product Description :</span>
                                <p class="text-muted"><?= nl2br(htmlspecialchars($product['description'])) ?></p>
                            </div>

                            <!-- jika pembeli, ada button buy -->
                            <?php if ($canBuy): ?>
                                <div class="mb-4">
                                    <a href="<?= $waLink ?>" class="btn btn-success btn-lg w-100" target="_blank">
                                        <i class="bi bi-whatsapp"></i> Buy Via Whatsapp
                                    </a>
                                </div>
                            
                            <!-- Jika belum login, ada button dan direct ke login page -->
                            <?php elseif (!$isOwner && !isLoggedIn()): ?>
                                <div class="mb-3">
                                    <a href="login.php" class="btn btn-success btn-lg w-100">
                                        <i class="bi bi-whatsapp"></i> Login to Buy
                                    </a>
                                </div>

                            <!-- jika owner tidak ada button buy tapi, ada button edit delete -->
                            <?php elseif ($isOwner): ?>
                                <div class="d-flex gap-2 mb-3">
                                    <a href="edit_product.php?id=<?= $product['id'] ?>" class="btn btn-warning">
                                        <i class="bi bi-pencil"></i> Edit Product
                                    </a>
                                    <a href="delete_product.php?id=<?= $product['id'] ?>" class="btn btn-danger"
                                        onclick="return confirm('Are you sure you want to delete this product?')">
                                        <i class="bi bi-trash"></i> Delete
                                    </a>
                                </div>

                                <!-- jika sudah login, dan tidak ada nomor hp muncul alert -->
                            <?php elseif (isLoggedIn() && empty($product['no_hp'])): ?>
                                <div class="alert alert-warning">
                                    The seller has not provided a WhatsApp number
                                </div>
                            <?php endif; ?>

                            <div class="d-flex gap-2">
                                <a href="my_product.php" class="btn btn-outline-primary">
                                    <i class="bi bi-arrow-left"></i> Back to My Products
                                </a>
                                <a href="product.php" class="btn btn-outline-primary">
                                    <i class="bi bi-arrow-left"></i> Back to Products Page
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<?php require 'includes/footer.php'; ?>