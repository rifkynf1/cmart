<?php
require 'includes/config.php';
require 'includes/auth.php';

// Query untuk mendapatkan produk
try {
    $stmt = $pdo->query("SELECT products.*, users.nama, users.no_hp
                        FROM products 
                        JOIN users ON products.user_id = users.id 
                        WHERE status = 'available'
                        ORDER BY created_at DESC");
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

            <?php if (!isLoggedIn()): ?>
                <div class="alert alert-info mt-3">
                    <p class="mb-2">Join the Campus Mart community! Log in now to buy, sell, and get full access to all marketplace features.
                        <a href="login.php">Click Here</a> or tap the login button below.
                    </p>
                    <div class="text-center">
                        <a href="login.php" class="btn btn-purple me-2">
                            <i class="bi bi-box-arrow-in-right"></i> LOGIN
                        </a> OR 
                        <a href="register.php" class="btn btn-purple ms-2">
                            <i class="bi bi-person-plus"></i> REGISTER
                        </a>
                    </div>
                </div>
            <?php endif; ?>

            <div class="card mb-4 mt-2">
                <div class="card-body">

                    <!-- Jika sudah login tapi belum post product, muncul alert link to addproduct -->
                    <?php if (empty($products)): ?>
                        <div class="alert alert-info">
                            There are no products available yet. <?php if (isLoggedIn()): ?>You can <a href="add_product.php">Add Products</a> First.<?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div class="row row-cols-2 row-cols-sm-2 row-cols-md-3 g-4">
                            <?php foreach ($products as $product): ?>
                                <div class="col">
                                    <div class="card h-100">
                                        <img src="<?= htmlspecialchars($product['image']) ?>" class="card-img-top product-image" style="height: 180px; object-fit:cover;" alt="<?= htmlspecialchars($product['title']) ?>">
                                        <div class="card-body">
                                            <h5 class="card-title text-purple"><?= htmlspecialchars(strlen($product['title']) > 15 ? substr($product['title'], 0, 15) : $product['title']) ?></h5>
                                            <p class="card-text text-muted"><?= htmlspecialchars(strlen($product['description']) > 20 ? substr($product['description'], 0, 20) . '...' : $product['description']) ?></p>
                                            <p class="text-purple fw-bold">Rp <?= number_format($product['price'], 0) ?></p>
                                            <span class="text-muted small">Seller :</span>
                                            <span class="text-primary"> <?= htmlspecialchars($product['nama']) ?></span>
                                        </div>
                                        <div class="card-footer bg-transparent d-flex justify-content-between">
                                            <a href="product_detail.php?id=<?= $product['id'] ?>" class="btn btn-sm btn-purple text-white">
                                                <i class="bi bi-eye me-1"></i>Detail
                                            </a>

                                            <?php if (isLoggedIn() && $product['user_id'] != getCurrentUserId()): ?>
                                                <?php if (!empty($product['no_hp'])): ?>
                                                    <?php
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

                                                    $wa_link = "https://api.whatsapp.com/send?phone=" . $whatsapp_number . "&text=" . urlencode($message);
                                                    ?>
                                                    <a href="<?= $wa_link ?>" class="btn btn-sm btn-success" target="_blank">
                                                        <i class="bi bi-whatsapp"></i> Buy Via WA
                                                    </a>
                                                <?php else: ?>
                                                    <button class="btn btn-sm btn-secondary" disabled>Whatsapp Number Not Available</button>
                                                <?php endif; ?>
                                            <?php endif; ?>
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