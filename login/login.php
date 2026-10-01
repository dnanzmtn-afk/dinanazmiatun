<?php
include 'koneksi.php';
session_start();

if (isset($_GET['logout']) && $_GET['logout'] == '1') {
    $_SESSION['swal'] = [
        'title' => 'Logout berhasil!',
        'text' => 'Kamu sudah keluar dari akun.',
        'icon' => 'success'
    ];
}

if (isset($_POST['login'])) {

    $username = $_POST['username'];
    $password = $_POST['password'];

    $login = mysqli_query(
        $koneksi,
        "SELECT * FROM tb_user 
         WHERE username='$username' 
         AND password='$password'"
    );

    if (!$login) {
        die("Query gagal: " . mysqli_error($koneksi));
    }

    if (mysqli_num_rows($login) > 0) {

        $data = mysqli_fetch_array($login);

        /* =========================
           LOGIN ADMIN
        ========================= */
        if ($data['role'] == 'admin') {

            $_SESSION['admin'] = $data['username'];
            $_SESSION['id_user'] = $data['id'];
            $_SESSION['username'] = $data['username'];

            $_SESSION['swal'] = [
                'title' => 'Login berhasil!',
                'text' => 'Selamat datang di halaman admin.',
                'icon' => 'success'
            ];

            header('Location: dashboard.php');
            exit;

        /* =========================
           LOGIN PELANGGAN
        ========================= */
        } elseif ($data['role'] == 'pelanggan') {

            $_SESSION['id_user'] = $data['id'];
            $_SESSION['username'] = $data['username'];

            $_SESSION['swal'] = [
                'title' => 'Login berhasil!',
                'text' => 'Selamat datang.',
                'icon' => 'success'
            ];

            header('Location: index.php');
            exit;
        }

    } else {

        $_SESSION['swal'] = [
            'title' => 'Login gagal!',
            'text' => 'Username atau password salah.',
            'icon' => 'error'
        ];
    }
}
?>

<!doctype html>
<html lang="id">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Login - Dina's Cake House</title>

    <link href="assets/dist/css/bootstrap.min.css" rel="stylesheet">

    <?php include 'sweetalert.php'; ?>

    <style>

        /* =========================
           WARNA UTAMA
        ========================= */

        html,
        body {
            height: 100%;
        }

        body {
            margin: 0;
            background-color: #fffaf3 !important;
            color: #6e4c42 !important;
            font-family: Arial, sans-serif;
        }


        /* =========================
           LOGIN WRAPPER
        ========================= */

        .login-wrapper {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            background-color: #fffaf3;
        }


        /* =========================
           LOGIN CARD
        ========================= */

        .login-card {
            width: 100%;
            max-width: 420px;
            background-color: #fff !important;
            border: 1px solid #eaded5;
            border-radius: 18px;
            padding: 40px 35px;
            box-shadow: 0 15px 40px rgba(86, 59, 48, 0.12);
        }


        /* =========================
           LOGO
        ========================= */

        .login-logo {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid #f7d9dc;
            margin-bottom: 20px;
        }


        /* =========================
           JUDUL
        ========================= */

        .login-title {
            color: #6e4c42 !important;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .login-subtitle {
            color: #6e4c42 !important;
            opacity: 0.75;
            margin-bottom: 25px;
        }


        /* =========================
           INPUT
        ========================= */

        .form-floating {
            margin-bottom: 12px;
        }

        .form-control {
            background-color: #fffaf3 !important;
            border: 1px solid #eaded5 !important;
            color: #6e4c42 !important;
        }

        .form-control:focus {
            background-color: #fff !important;
            border-color: #f3c3ca !important;
            box-shadow: 0 0 0 0.2rem rgba(247, 217, 220, 0.45) !important;
        }

        .form-floating label {
            color: #6e4c42 !important;
        }


        /* =========================
           CHECKBOX
        ========================= */

        .form-check-input {
            border-color: #eaded5 !important;
        }

        .form-check-input:checked {
            background-color: #6e4c42 !important;
            border-color: #6e4c42 !important;
        }

        .form-check-label {
            color: #6e4c42 !important;
        }


        /* =========================
           TOMBOL LOGIN
        ========================= */

        .btn-login {
            width: 100%;
            background-color: #6e4c42 !important;
            border-color: #6e4c42 !important;
            color: #fff !important;
            font-weight: 600;
            padding: 11px;
            border-radius: 8px;
        }

        .btn-login:hover {
            background-color: #543932 !important;
            border-color: #543932 !important;
            color: #fff !important;
        }


        /* =========================
           REGISTER
        ========================= */

        .register-link {
            display: block;
            margin-top: 18px;
            color: #6e4c42 !important;
            text-decoration: none;
        }

        .register-link:hover {
            color: #543932 !important;
            text-decoration: underline;
        }


        /* =========================
           COPYRIGHT
        ========================= */

        .copyright {
            color: #6e4c42 !important;
            opacity: 0.65;
            margin-top: 25px;
            margin-bottom: 0;
            font-size: 14px;
        }

    </style>

</head>

<body>

<div class="login-wrapper">

    <div class="login-card">

        <form method="post">

            <!-- LOGO -->
            <div class="text-center">
                <img src="logo.png" class="login-logo" alt="Dina's Cake House">

                <h1 class="h3 login-title">
                    Dina's Cake House
                </h1>

                <p class="login-subtitle">
                    Silakan masuk ke akun kamu
                </p>

            </div>


            <!-- USERNAME -->
            <div class="form-floating">

                <input
                    type="text"
                    name="username"
                    class="form-control"
                    id="floatingInput"
                    placeholder="Username"
                    required
                >

                <label for="floatingInput">
                    Username
                </label>

            </div>


            <!-- PASSWORD -->
            <div class="form-floating">

                <input
                    type="password"
                    name="password"
                    class="form-control"
                    id="floatingPassword"
                    placeholder="Password"
                    required
                >

                <label for="floatingPassword">
                    Password
                </label>

            </div>


            <!-- REMEMBER ME -->
            <div class="form-check text-start my-3">

                <input
                    class="form-check-input"
                    type="checkbox"
                    value="remember-me"
                    id="checkDefault"
                >

                <label
                    class="form-check-label"
                    for="checkDefault"
                >
                    Remember me
                </label>

            </div>


            <!-- LOGIN -->
            <button
                class="btn btn-login"
                type="submit"
                name="login"
            >
                Sign in
            </button>


            <!-- REGISTER -->
            <a
                href="register.php"
                class="register-link text-center"
            >
                Belum punya akun? Daftar di sini
            </a>


            <!-- COPYRIGHT -->
            <p class="copyright text-center">
                &copy; <?= date('Y') ?> Dina's Cake House
            </p>

        </form>

    </div>

</div>


<script src="assets/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>