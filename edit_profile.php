<?php
require 'includes/config.php';
require 'includes/auth.php';

// jika tidak login, direct ke login page
if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$error = '';
$success = '';

// Variabel Get User Data dari auth.php
$user = getCurrentUserData();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Handle profile update
    if (isset($_POST['nama'])) {
        $nama = $_POST['nama'];
        $no_hp = $_POST['no_hp'];
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        // Validasi cek nama dan no hp
        if (empty($nama) || empty($no_hp)) {
            $error = "Name and Phone Number are required!";
        } else {

            // Update Basic Info
            $stmt = $pdo->prepare("UPDATE users SET nama = ?, no_hp = ? WHERE id = ?");
            $stmt->execute([$nama, $no_hp, getCurrentUserId()]);

            // Update session nama
            $_SESSION['nama'] = $nama;

            // Check Jika user ganti password
            if (!empty($current_password) || !empty($new_password) || !empty($confirm_password)) {
                if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
                    $error = "All password fields must be filled to change password!";
                } elseif ($new_password !== $confirm_password) {
                    $error = "New password and confirmation don't match!";
                } elseif (!password_verify($current_password, $user['password'])) {
                    $error = "Current password is incorrect!";
                } else {
                    $hashedPassword = password_hash($new_password, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
                    $stmt->execute([$hashedPassword, getCurrentUserId()]);
                    $success = 'Profile and password updated successfully! <a href="index.php" class="alert-link">Back To Home</a>';
                }
            } else {
                $success = 'Profile updated successfully! <a href="index.php" class="alert-link">Back To Home</a>';
            }
        }
    }

    // Refresh Setelah Data Update
    $user = getCurrentUserData();
}
?>

<head>
    <title>C-Mart - Edit Profile</title>
    <link rel="icon" type="image" href="assets/img/logo_cmart.png">
</head>

<body>

    <!-- Header -->
    <?php include 'includes/header.php'; ?>

    <!-- Konten -->
    <div class="container mt-4 mb-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow">
                    <div class="card-header bg-purple text-white">
                        <h4 class="mb-0"><i class="bi bi-person-gear me-2"></i>Edit Profile</h4>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($error)): ?>
                            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                        <?php endif; ?>

                        <?php if (!empty($success)): ?>
                            <div class="alert alert-success"><?= htmlspecialchars_decode($success) ?></div>
                        <?php endif; ?>

                        <form method="post">
                            <div class="mb-3">
                                <label for="nim" class="form-label">NIM : </label>
                                <input type="text" class="form-control" id="nim" value="<?= htmlspecialchars($user['nim']) ?>" readonly>
                            </div>

                            <div class="mb-3">
                                <label for="nama" class="form-label">Full Name : </label>
                                <input type="text" class="form-control" id="nama" name="nama" value="<?= htmlspecialchars($user['nama']) ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="no_hp" class="form-label">Phone Number : </label>
                                <input type="tel" class="form-control" id="no_hp" name="no_hp" value="<?= htmlspecialchars($user['no_hp']) ?>" required>
                            </div>

                            <hr class="my-4">

                            <h5 class="mb-3"><i class="bi bi-key me-2"></i> Change Password :</h5>
                            <div class="mb-3">
                                <label for="current_password" class="form-label">Current Password : </label>
                                <input type="password" class="form-control" id="current_password" name="current_password">
                            </div>

                            <div class="mb-3">
                                <label for="new_password" class="form-label">New Password : </label>
                                <input type="password" class="form-control" id="new_password" name="new_password">
                            </div>

                            <div class="mb-3">
                                <label for="confirm_password" class="form-label">Confirm New Password :</label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-between mt-4">
                                <button type="submit" class="btn btn-primary me-md-2"><i class="bi bi-save me-2"></i>Update Profile</button>
                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                    <i class="bi bi-trash-fill me-1"></i>Delete Account
                                </button>
                            </div>
                        </form>
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

    <script>
        document.getElementById('deleteForm').addEventListener('submit', function(e) {
            const password = document.getElementById('delete_password').value;
            if (!password) {
                e.preventDefault();
                alert('Please enter your password to confirm account deletion.');
            }
        });
    </script>
</body>

</html>