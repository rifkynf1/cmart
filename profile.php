<?php
require 'includes/config.php';
require 'includes/auth.php';

// jika tidak login, direct ke login page 
if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$user = getCurrentUserData();

// Pesan Error
if (isset($_SESSION['error'])) {
    $error_message = $_SESSION['error'];
    unset($_SESSION['error']);
}

?>

<head>
    <title>C-Mart - My Profile</title>
</head>

<!-- Header -->
<?php include 'includes/header.php'; ?>

<!-- Konten -->

<!-- Menampilkan pesan eror -->
<?php if (!empty($error_message)): ?>
    <div class="container mt-3">
        <div class="alert alert-danger"><?= htmlspecialchars($error_message) ?></div>
    </div>
<?php endif; ?>

<div class="profile-header text-center">
    <div class="container">
        <img src="https://ui-avatars.com/api/?name=<?= urlencode($user['nama']) ?>&background=7e60bf&color=fff&size=256"
            alt="Profile" class="profile-img rounded-circle mb-3">
        <h3><?= htmlspecialchars($user['nama']) ?></h3>
    </div>
</div>

<div class="container mb-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card card-profile mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Basic Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <p class="mb-1"><span class="info-label">NIM :</span></p>
                            <p><?= htmlspecialchars($user['nim']) ?></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <p class="mb-1"><span class="info-label">Full Name :</span></p>
                            <p><?= htmlspecialchars($user['nama']) ?></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <p class="mb-1"><span class="info-label">Phone Number :</span></p>
                            <p><?= !empty($user['no_hp']) ? htmlspecialchars($user['no_hp']) : '<span class="text-muted">Not set</span>' ?></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <p class="mb-1"><span class="info-label">Member Since :</span></p>
                            <p><?= date('d F Y', strtotime($user['created_at'])) ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card card-profile">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-shield-lock me-2"></i>Account Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-3">
                        <a href="edit_profile.php" class="btn btn-warning">
                            <i class="bi bi-pencil-square me-2"></i>Edit Profile
                        </a>
                        <a href="my_product.php" class="btn btn-success">
                            <i class="bi bi-box-seam me-2"></i>My Products
                        </a>
                        <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                            <i class="bi bi-trash me-2"></i>Delete Account
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Delete -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="deleteForm" action="delete_account.php" method="post" class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Account Deletion</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to permanently delete your account?</p>
                <div class="alert alert-danger">
                    <strong>Warning :</strong> This action cannot be undone. All your data will be permanently removed.
                </div>
                <div class="mb-3">
                    <label for="delete_password" class="form-label">Enter your password to confirm :</label>
                    <input type="password" class="form-control" id="delete_password" name="delete_password" required>
                </div>
                <input type="hidden" name="user_id" value="<?= getCurrentUserId() ?>">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-danger">
                    <i class="bi bi-trash-fill me-1"></i>Delete Account
                </button>
            </div>
        </form>
    </div>
</div>


<!-- Footer -->
<?php include 'includes/footer.php'; ?>