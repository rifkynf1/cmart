<?php
require 'includes/config.php';
require 'includes/auth.php';

// Jika tidak login, direct ke login page 
if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['delete_password'] ?? '';
    $user_id = getCurrentUserId();

    // Verifikasi password
    $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($password, $user['password'])) {
        $_SESSION['error'] = "Wrong password! Account deletion canceled.";
        header('Location: profile.php');
        exit;
    }

    try {
        $pdo->beginTransaction();

        // Hapus User Produk
        $deleteProducts = $pdo->prepare("DELETE FROM products WHERE user_id = ?");
        $deleteProducts->execute([$user_id]);

        // Hapus akun
        $deleteUser = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $deleteUser->execute([$user_id]);

        // Periksa Benar kehapus
        if ($deleteUser->rowCount() > 0) {
            $pdo->commit();
            
            // Hapus session dan redirect
            session_unset();
            session_destroy();
            header('Location: login.php?deleted=1');
            exit;
        } else {
            throw new Exception("Failed to delete account.");
        }
    } catch (Exception $e) {
        $pdo->rollBack();
        $_SESSION['error'] = "There is an error: " . $e->getMessage();
        header('Location: profile.php');
        exit;
    }
}

header('Location: profile.php');
exit;
?>