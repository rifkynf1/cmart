<?php
require 'includes/config.php';
require 'includes/auth.php';

// Jika sudah login, direct ke index
if (isLoggedIn()) {
    header('Location: index.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nim = $_POST['nim'];
    $password = $_POST['password'];

    if (login($nim, $password)) {
        header('Location: index.php');
        exit;
    } else {
        $error = 'Incorrect NIM or Password';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>C-Mart Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="icon" type="image/png" href="assets/img/logo_cmart.png">
    <style>
        .card-with-bg {
            position: relative;
            background: url('assets/img/logo_full.png') center/cover no-repeat;
            background-size: cover;
        }

        .card-with-bg::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: #000000;
            background: radial-gradient(circle, rgba(0, 0, 0, 0.7) 100%, rgba(156, 94, 255, 0.2) 100%);
            border-radius: inherit;
        }

        .card-content {
            position: relative;
            z-index: 1;
        }
    </style>
</head>

<body style="background-color: rgb(96, 64, 171);">

    <section class="py-3 py-md-4" style="background-color: rgb(96, 64, 171);">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-5 col-xxl-4">
                    <div class="card rounded-3 shadow-sm card-with-bg">
                        <div class="card-body p-3 p-md-4 p-xl-5 card-content">
                            <div class="text-center mb-3">
                                <a href="login.php">
                                    <img class="rounded-circle" src="assets/img/logo_full.png" alt="Logo Cafe" width="100" height="100">
                                </a>
                            </div>
                            <h2 class="fs-6 fw-normal text-center text-white mb-4">Sign in to your account</h2>

                            <!-- Alert Eror -->
                            <?php if ($error): ?>
                                <div class="alert alert-danger"><?= $error ?></div>
                            <?php endif; ?>

                            <!-- Alert Sukses Registrasi -->
                            <?php if (isset($_GET['registered'])): ?>
                                <div class="alert alert-success">Registration success! Please login</div>
                            <?php endif; ?>

                            <form action="" method="post" class="needs-validation" novalidate>
                                <div class="row gy-2 overflow-hidden">
                                    <div class="col-12">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" name="nim" id="nim" placeholder="22041298" pattern="\d{8}" required maxlength="8" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                            <label for="email" class="form-label">NIM</label>
                                            <div class="invalid-feedback">Please enter your 8-digit NIM</div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-floating mb-3">
                                            <input type="password" class="form-control" name="password" id="password" value="" placeholder="Password" required>
                                            <label for="password" class="form-label">Password</label>
                                            <div class="invalid-feedback" style="color: red;">Password must be at least 8 characters with 1 uppercase letter</div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="d-flex gap-2 justify-content-between">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="" name="rememberMe" id="rememberMe">
                                                <label class="form-check-label text-black text-white" for="rememberMe">
                                                    Keep me logged in
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="d-grid my-3">
                                            <button class="btn btn-primary btn-lg" type="submit" name="submit">Log in</button>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <p class="m-0 text-white text-center">Don't have an account? <a href="register.php" class="link-primary text-decoration-none">Sign up</a></p>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</body>

<script>
    (() => {
        'use strict'

        const forms = document.querySelectorAll('.needs-validation')

        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }

                form.classList.add('was-validated')
            }, false)
        })
    })()
</script>

</html>