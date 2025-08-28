<?php
require 'includes/config.php';
require 'includes/auth.php';

// Query untuk mendapatkan produk
try {
    $stmt = $pdo->query("SELECT products.*, users.nim 
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

        <!-- Konten -->
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

                    <p class="text-center fs-1">Welcome To The Campus Mart</p>
                    <hr>

                    <div class="mt-4 fs-5">
                        <p><strong class="cmart">Campus Mart</strong> is an online buying and selling platform specifically for students.
                            Here, you can buy and sell various items, both new and used,
                            ranging from college supplies, gadgets, books, to daily necessities for boarding students.</p>

                        <p>All transactions are done directly via <strong class="wa">WhatsApp</strong>, making it faster, more practical, and without intermediaries.
                            Campus Mart is here as a safe and comfortable place for all students
                            to help each other meet their needs at more affordable prices.</p>

                        <p>No matter which campus you come from, every student has the right to
                            join, sell, or buy items here.
                            Let's create an honest, active, and supportive campus buying and selling community.</p>

                        <p>Find your dream item or start selling now!</p>
                    </div>

                    <!-- Cek Apakah Sudah Login Dan Tampil Alert -->
                    <?php if (isLoggedIn()): ?>
                        <div class="alert alert-info">
                            Want to add a product? <a href="add_product.php">Click here</a>.
                        </div>
                    <?php endif; ?>

                    <!-- Jika sudah login, tampil button -->
                    <?php if (isLoggedIn()): ?>
                        <div class="btn-toolbar mb-4 d-flex flex-column flex-sm-row justify-content-center justify-content-sm-start gap-2">
                            <a href="add_product.php" class="btn btn-primary btn-lg flex-grow-1 flex-sm-grow-0">
                                <i class="bi bi-plus-circle"></i> Add Product
                            </a>
                            <a href="product.php" class="btn btn-secondary btn-lg flex-grow-1 flex-sm-grow-0">
                                <i class="bi bi-box-seam"></i> View Product
                            </a>
                            <a href="my_product.php" class="btn btn-success btn-lg flex-grow-1 flex-sm-grow-0">
                                <i class="bi bi-bag-check"></i> My Product
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Footer -->
            <?php require 'includes/footer.php'; ?>