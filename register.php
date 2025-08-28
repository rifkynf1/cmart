<?php
require 'includes/config.php';
require 'includes/auth.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nim = $_POST['nim'];
    $nama = $_POST['nama'];
    $no_hp = $_POST['no_hp'];
    $password = $_POST['password'];

    // Validasi nim, nama, nohp, password tidak kosong
    if (empty($nim) || empty($nama) || empty($no_hp) || empty($password)) {
        $error = "All fields must be filled in!";

        // Validasi nim harus 8 digit
    } else if (!preg_match('/^\d{8}$/', $nim)) {
        $error = "NIM must be exactly 8 digits!";

        // Validasi no_hp harus minimal 10 digit angka
    } else if (!preg_match('/^\d{10,13}$/', $no_hp)) {
        $error = "Phone number must be between 10 and 13 digits!";

        // Validasi password harus 1 huruf besar dan 8 kata 
    } else if (strlen($password) < 8 || !preg_match('/[A-Z]/', $password)) {
        $error = "Password must be at least 8 characters and contain at least 1 uppercase letter!";
    } else {
        try {
            $pdo->beginTransaction();

            // Cek NIM sudah terdaftar
            $stmt = $pdo->prepare("SELECT id FROM users WHERE nim = ?");
            $stmt->execute([$nim]);

            if ($stmt->rowCount() > 0) {
                $error = "NIM already registered!";
            } else {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                $stmt = $pdo->prepare("INSERT INTO users (nim, nama, no_hp, password) VALUES (?, ?, ?, ?)");
                $stmt->execute([$nim, $nama, $no_hp, $hashedPassword]);

                $pdo->commit();
                header('Location: login.php?registered=1');
                exit;
            }
        } catch (PDOException $e) {
            $pdo->rollBack();
            $error = "Registration failed. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>C-Mart Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="icon" type="image" href="assets/img/logo_cmart.png">
    <style>
        body {
            background-color: rgb(96, 64, 171);
        }

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

<body>
    <section class="py-3 py-md-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-5 col-xxl-4">
                    <div class="card card-register shadow-sm card-with-bg">
                        <div class="card-body p-3 p-md-4 p-xl-5 card-content">
                            <div class="text-center mb-3">
                                <a href="#">
                                    <img class="rounded-circle" src="assets/img/logo_full.png" alt="Logo" width="100" height="100">
                                </a>
                            </div>
                            <h2 class="fs-6 fw-normal text-center text-white mb-4">Create Your Account</h2>

                            <!-- menampilkan pesan eror -->
                            <?php if (!empty($error)): ?>
                                <div class="alert alert-danger alert-dismissible fade show">
                                    <?= htmlspecialchars($error) ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            <?php endif; ?>

                            <!-- Menampilkan alert sukses registrasi -->
                            <?php if (isset($_GET['registered'])): ?>
                                <div class="alert alert-success alert-dismissible fade show">
                                    Registration success! Please login
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            <?php endif; ?>

                            <form action="" method="post" class="needs-validation" novalidate>
                                <div class="row gy-2 overflow-hidden">
                                    <div class="col-12">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" name="nim" id="nim" placeholder="22041298" pattern="\d{8}" required maxlength="8" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                            <label for="nim" class="form-label">NIM</label>
                                            <div class="invalid-feedback">NIM must be exactly 8 digits (numbers only)</div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-floating mb-3">
                                            <input type="password" class="form-control" name="password" id="password" placeholder="Password" pattern="(?=.*[A-Z]).{8,}" required>
                                            <label for="password" class="form-label">Password</label>
                                            <div class="invalid-feedback" style="color: red">Password must be at least 8 characters with 1 uppercase letter</div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" name="nama" id="nama" placeholder="Your Name" required>
                                            <label for="nama" class="form-label">Full Name</label>
                                            <div class="invalid-feedback">Please enter your name</div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-floating mb-3">
                                            <input type="tel" class="form-control" name="no_hp" id="no_hp" placeholder="08123456789" pattern="\d{10,13}" maxlength="13" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
                                            <label for="no_hp" class="form-label">Phone Number</label>
                                            <div class="invalid-feedback">Phone number must be 10–13 digits (numbers only)</div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="d-grid my-3">
                                            <button class="btn btn-primary btn-lg" type="submit" name="submit">
                                                <i class="bi bi-person-plus me-2"></i>Register</button>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <p class="m-0 text-white text-center">Already have an account? <a href="login.php" class="link-primary text-decoration-none">Login</a></p>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
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

        // Validasi Password 1 Uppercase dan min 8 karakter
        passwordInput.addEventListener('input', function() {
            const password = this.value;
            const hasUpperCase = /[A-Z]/.test(password);
            const hasMinLength = password.length >= 8;

            if (!password) {
                passwordFeedback.textContent = '';
                passwordFeedback.className = 'form-text';
            } else {
                let feedback = [];
                if (!hasMinLength) feedback.push('at least 8 characters');
                if (!hasUpperCase) feedback.push('1 uppercase letter');

                if (feedback.length > 0) {
                    passwordFeedback.innerHTML = `Requirements: ${feedback.join(', ')}`;
                    passwordFeedback.className = 'form-text text-danger';
                } else {
                    passwordFeedback.textContent = '✓ Password meets all requirements';
                    passwordFeedback.className = 'form-text text-success';
                }
            }
        });

        // Validasi NIM 8 digit
        document.getElementById('nim').addEventListener('input', function() {
            this.setCustomValidity(
                this.value.length !== 8 ? 'NIM must be exactly 8 digits' : ''
            );
        });

        // Validasi No HP 10–13 digit angka
        const phoneInput = document.getElementById('no_hp');
        const phoneFeedback = document.getElementById('phoneFeedback');

        phoneInput.addEventListener('input', function() {
            const phone = this.value;

            if (!phone) {
                phoneFeedback.textContent = '';
                phoneFeedback.className = 'form-text';
                this.setCustomValidity('Please enter your phone number');
            } else if (!/^\d{10,13}$/.test(phone)) {
                phoneFeedback.textContent = 'Phone number must be 10–13 digits (numbers only)';
                phoneFeedback.className = 'form-text text-danger';
                this.setCustomValidity('Phone number must be 10–13 digits (numbers only)');
            } else {
                phoneFeedback.textContent = '✓ Valid phone number';
                phoneFeedback.className = 'form-text text-success';
                this.setCustomValidity('');
            }
        });
    </script>
</body>

</html>