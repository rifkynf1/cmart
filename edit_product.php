<?php
require 'includes/config.php';
require 'includes/auth.php';
require 'includes/functions.php';

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
$errors = [];
$success = false;

// Cek Pemilik Produk
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ? AND user_id = ?");
$stmt->execute([$productId, getCurrentUserId()]);
$product = $stmt->fetch();

// jika tidak ad product direct ke index
if (!$product) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $price = trim($_POST['price']);

    // Validasi
    if (empty($title)) $errors[] = 'Title must be filled in';
    if (empty($description)) $errors[] = 'Description is required';
    if (empty($price) || !is_numeric($price)) $errors[] = 'Prices must be number';

    // Handle Gambar Baru
    $imagePath = $product['image'];
    if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadResult = handleProductImageUpload($_FILES['image']);
        if (!$uploadResult['success']) {
            $errors[] = $uploadResult['error'];
        } else {
            // Delete Gambar Lama
            if ($imagePath && file_exists($imagePath)) {
                unlink($imagePath);
            }
            $imagePath = $uploadResult['file_path'];
        }
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("UPDATE products 
                              SET title = ?, description = ?, price = ?, image = ?
                              WHERE id = ?");
        $stmt->execute([$title, $description, $price, $imagePath, $productId]);
        $success = true;
        $product['title'] = $title;
        $product['description'] = $description;
        $product['price'] = $price;
        $product['image'] = $imagePath;
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
        <div class="col-lg-9 mt-2 mb-5">
            <div class="card shadow-sm">
                <div class="card-body">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="my_product.php">My Product</a></li>
                            <li class="breadcrumb-item"><a href="product_detail.php?id=<?= $productId ?>">Product Details</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Edit Product</li>
                        </ol>
                    </nav>


                    <?php if ($success): ?>
                        <div class="alert alert-success d-flex align-items-center">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            <div>
                                Product successfully updated!
                                <a href="product_detail.php?id=<?= $productId ?>" class="alert-link">View Products</a>
                            </div>
                        </div>
                    <?php else: ?>
                        <?php if (!empty($errors)): ?>
                            <div class="alert alert-danger">
                                <h5 class="alert-heading"><i class="bi bi-exclamation-triangle-fill"></i> Error</h5>
                                <ul class="mb-0">
                                    <?php foreach ($errors as $error): ?>
                                        <li><?= $error ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                        
                        <hr>
                        <p class="fs-1 mb-4 text-center"><i class="bi bi-pencil me-5"></i>Edit Product<i class="bi bi-pencil ms-5"></i></p>
                        <hr>

                        <form method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                            <div class="mb-3">
                                <label for="title" class="form-label">Product Title : </label>
                                <input type="text" class="form-control" id="title" name="title"
                                    value="<?= htmlspecialchars($product['title']) ?>" required>
                                <div class="invalid-feedback">
                                    Please fill in the product title
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Description : </label>
                                <textarea class="form-control" id="description" name="description"
                                    rows="5" required><?= htmlspecialchars($product['description']) ?></textarea>
                                <div class="invalid-feedback">
                                    Please fill in the product description
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="price" class="form-label">Price (Rp) : </label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" class="form-control" id="price" name="price"
                                        value="<?= htmlspecialchars($product['price']) ?>" required>
                                </div>
                                <div class="invalid-feedback">
                                    Please fill in a valid price
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="image" class="form-label">Product Photos : </label>
                                <input type="file" class="form-control" id="image" name="image" accept="image/*">
                                <div class="form-text mt-2">* Leave it blank if you don't want to change the photo.</div>
                                <?php if ($product['image']): ?>
                                    <div class="mt-2">
                                        <img src="<?= htmlspecialchars($product['image']) ?>" class="img-thumbnail" style="max-height: 200px;">
                                        <p class="text-muted small mt-1">Current photo</p>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="d-flex flex-column flex-sm-row gap-2">
                                <button type="submit" class="btn btn-primary flex-grow-1 flex-sm-grow-0">
                                    <i class="bi bi-save"></i> Update Product
                                </button>
                                <a href="product_detail.php?id=<?= $productId ?>" class="btn btn-outline-secondary flex-grow-1 flex-sm-grow-0">
                                    <i class="bi bi-x-circle"></i> Cancel
                                </a>
                                <a href="delete_product.php?id=<?= $product['id'] ?>" class="btn btn-danger"
                                    onclick="return confirm('Are you sure you want to delete this product?')">
                                    <i class="bi bi-trash"></i> Delete
                                </a>
                            </div>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Bootstrap form validation
    (function() {
        'use strict'

        var forms = document.querySelectorAll('.needs-validation')

        Array.prototype.slice.call(forms)
            .forEach(function(form) {
                form.addEventListener('submit', function(event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }

                    form.classList.add('was-validated')
                }, false)
            })
    })()
</script>

<!-- Footer -->
<?php require 'includes/footer.php'; ?>