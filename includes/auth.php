<?php
session_start();

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function login($nim, $password) {
    global $pdo;
    
    $stmt = $pdo->prepare("SELECT id, nim, nama, password FROM users WHERE nim = ?");
    $stmt->execute([$nim]);
    $user = $stmt->fetch();
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['nim'] = $user['nim'];
        $_SESSION['nama'] = $user['nama']; 
        return true;
    }
    return false;
}

function logout() {
    session_unset();
    session_destroy();
}

//Get UserID
function getCurrentUserId() {
    return $_SESSION['user_id'] ?? null;
}

//Get NIM
function getCurrentUserNIM() {
    return $_SESSION['nim'] ?? null;
}

//Get Nama
function getCurrentUserName() {
    return $_SESSION['nama'] ?? null;
}

//Get User Lengkap
function getCurrentUserData() {
    global $pdo;
    if (!isLoggedIn()) return null;
    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([getCurrentUserId()]);
    return $stmt->fetch();
}
?>