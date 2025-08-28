<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>C-Mart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="icon" type="image" href="assets/img/logo_cmart.png">
</head>

<body>
    <nav class="navbar navbar-expand navbar-dark mb-0" style="background-color:rgb(126, 96, 191)">
        <div class="container-lg">
            <a class="navbar-brand" href="index.php">
                <img src="assets/img/logo_cmart.png" alt="C-Mart Logo" style="height: 30px;">
                C-Mart
            </a>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNavDropdown">
                <ul class="navbar-nav">

                    <!-- Cek jika sudah login akan muncul Nama Akun -->
                    <?php if (isLoggedIn()): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person-circle"></i> <?= htmlspecialchars(getCurrentUserName() ?? getCurrentUserNIM()) ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end mt-2" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="profile.php"><i class="bi bi-person"></i> Profile</a></li>
                                <li><a class="dropdown-item" href="edit_profile.php"><i class="bi bi-person-gear"></i> Edit Profile</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="logout.php"><i class="bi bi-box-arrow-left"></i> Logout</a></li>
                            </ul>
                        </li>

                    <!-- Jika tidak login maka ada tombol login -->
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">
                                <i class="bi bi-box-arrow-in-right"></i> Login
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="content-wrapper">