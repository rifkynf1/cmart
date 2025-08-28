<?php
require 'includes/config.php';
require 'includes/auth.php';
require 'includes/functions.php';

// Jika tidak login, direct ke login page
if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $price = trim($_POST['price']);
    $image = $_FILES['image'];

    // Validasi
    if (empty($title)) $errors[] = 'Title must be filled in';
    if (empty($description)) $errors[] = 'Description is required';
    if (empty($price) || !is_numeric($price)) $errors[] = 'Prices must be number';

    // Handle image upload
    $imagePath = null;
    if ($image['error'] === UPLOAD_ERR_OK) {
        $uploadResult = handleProductImageUpload($image);
        if (!$uploadResult['success']) {
            $errors[] = $uploadResult['error'];
        } else {
            $imagePath = $uploadResult['file_path'];
        }
    } else {
        $errors[] = 'Product images must be uploaded';
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO products 
                              (user_id, title, description, price, image) 
                              VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            getCurrentUserId(),
            $title,
            $description,
            $price,
            $imagePath
        ]);
        $success = true;
    }
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
            <div class="card mb-4 mt-2">
                <div class="card-body">

                    <body class="bg-light">
                        <div class="container py-5">
                            <div class="row justify-content-center">
                                <div class="col-lg-9 col-md-10 mb-5">
                                    <div class="card shadow-sm">
                                        <div class="card-header bg-purple text-white">
                                            <h1 class="h4 mb-0">
                                                <i class="bi bi-plus-circle me-2"></i>Add New Products</h1>
                                        </div>

                                        <div class="card-body">
                                            <?php if ($success): ?>
                                                <div class="alert alert-success">
                                                    <i class="bi bi-check-circle-fill"></i> Product added successfully!
                                                    <div class="mt-2">
                                                        <a href="product.php" class="btn btn-sm btn-outline-success">Return to Product Page</a>
                                                        <a href="add_product.php" class="btn btn-sm btn-success">Add Other Products</a>
                                                    </div>
                                                </div>
                                            <?php else: ?>
                                                <?php if (!empty($errors)): ?>
                                                    <div class="alert alert-danger">
                                                        <h5 class="alert-heading"><i class="bi bi-exclamation-triangle-fill"></i> There is an error:</h5>
                                                        <ul class="mb-0">
                                                            <?php foreach ($errors as $error): ?>
                                                                <li><?= htmlspecialchars($error) ?></li>
                                                            <?php endforeach; ?>
                                                        </ul>
                                                    </div>
                                                <?php endif; ?>

                                                <form method="post" enctype="multipart/form-data">
                                                    <div class="mb-3">
                                                        <label for="title" class="form-label">Product Title</label>
                                                        <input type="text" class="form-control" id="title" name="title" required>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="description" class="form-label">Description</label>
                                                        <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="price" class="form-label">Price (Rp)</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text">Rp</span>
                                                            <input type="number" class="form-control" id="price" name="price" required>
                                                        </div>
                                                    </div>

                                                    <div class="mb-4">
                                                        <label for="image" class="form-label">Product Photos</label>
                                                        <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
                                                        <div class="form-text">Supported formats: JPG, PNG, GIF. Maximum 2MB.</div>
                                                    </div>

                                                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                                        <a href="index.php" class="btn btn-outline-secondary me-md-2">
                                                            <i class="bi bi-arrow-left"></i> Return
                                                        </a>
                                                        <button type="submit" class="btn btn-purple">
                                                            <i class="bi bi-plus-circle"></i> Add Product
                                                        </button>
                                                    </div>
                                                </form>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                 </div>                                               
            </div>
                    
<!-- Footer -->
<?php require 'includes/footer.php'; ?>