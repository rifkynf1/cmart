<?php
require 'includes/config.php';
require 'includes/auth.php';

// Jika tidak login, direct ke login page
if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$userId = getCurrentUserId();

// Query untuk mendapatkan produk user yang login
try {
    $stmt = $pdo->prepare("SELECT products.*, users.nim 
                          FROM products 
                          JOIN users ON products.user_id = users.id 
                          WHERE products.user_id = ?
                          ORDER BY created_at DESC");
    $stmt->execute([$userId]);
    $products = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Error retrieving product data: " . $e->getMessage());
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
                            <li class="breadcrumb-item active" aria-current="page">My Product</li>
                        </ol>
                    </nav>
                    <hr>
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <p class="fs-1"><i class="bi bi-bag-check me-3"></i>My Product</p>
                        <a href="add_product.php" class="btn btn-purple text-white">
                            <i class="bi bi-plus-circle"></i> Add Product
                        </a>
                    </div>
                    <hr>

                    <!-- Alert Jika User Belum Posting Produk -->
                    <?php if (empty($products)): ?>
                        <div class="alert alert-info">
                            You don't have any products yet. <a href="add_product.php" class="alert-link">Add your first product</a>.
                        </div>
                    <?php else: ?>
                        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                            <?php foreach ($products as $product): ?>
                                <div class="col">
                                    <div class="card h-100">
                                        <img src="<?= htmlspecialchars($product['image']) ?>" class="card-img-top product-image" alt="<?= htmlspecialchars($product['title']) ?>">
                                        <div class="card-body">
                                            <h5 class="card-title text-purple"><?= htmlspecialchars($product['title']) ?></h5>
                                            <p class="card-text text-muted"><?= substr(htmlspecialchars($product['description']), 0, 50) ?>...</p>
                                            <p class="text-purple fw-bold">Rp <?= number_format($product['price'], 0) ?></p>
                                            <p class="text-muted small">Status:
                                                <span class="badge bg-<?= $product['status'] === 'available' ? 'success' : 'secondary' ?>">
                                                    <?= $product['status'] === 'available' ? 'Ready' : 'Sold' ?>
                                                </span>
                                            </p>
                                        </div>
                                        <div class="card-footer bg-transparent d-flex justify-content-between">
                                            <a href="product_detail.php?id=<?= $product['id'] ?>" class="btn btn-sm btn-purple text-white">
                                                <i class="bi bi-eye me-1"></i>Detail</a>
                                            <a href="edit_product.php?id=<?= $product['id'] ?>" class="btn btn-sm btn-warning">
                                                <i class="bi bi-pencil me-1"></i>Edit</a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<?php require 'includes/footer.php'; ?>