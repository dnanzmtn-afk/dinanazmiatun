<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id_user'])) {
    $_SESSION['swal'] = [
        'title' => 'Belum Login!',
        'text' => 'Silakan login terlebih dahulu.',
        'icon' => 'warning'
    ];

    header('Location: login.php');
    exit;
}

$id_user = (int) $_SESSION['id_user'];

$result = mysqli_query(
    $koneksi,
    "SELECT * FROM tb_user WHERE id='$id_user' LIMIT 1"
);

$user = $result ? mysqli_fetch_assoc($result) : null;

if (!$user) {
    session_destroy();
    header('Location: login.php');
    exit;
}


/* =========================
   UPDATE PROFIL
========================= */

if (isset($_POST['update_profil'])) {

    $username = trim($_POST['username'] ?? '');

    if ($username === '') {

        $_SESSION['swal'] = [
            'title' => 'Gagal!',
            'text' => 'Username tidak boleh kosong.',
            'icon' => 'error'
        ];

    } else {

        $username = mysqli_real_escape_string(
            $koneksi,
            $username
        );

        $cek = mysqli_query(
            $koneksi,
            "SELECT id FROM tb_user
             WHERE username='$username'
             AND id!='$id_user'
             LIMIT 1"
        );

        if (mysqli_num_rows($cek) > 0) {

            $_SESSION['swal'] = [
                'title' => 'Username sudah dipakai!',
                'text' => 'Gunakan username lain.',
                'icon' => 'warning'
            ];

        } elseif (
            mysqli_query(
                $koneksi,
                "UPDATE tb_user
                 SET username='$username'
                 WHERE id='$id_user'"
            )
        ) {

            $_SESSION['swal'] = [
                'title' => 'Berhasil!',
                'text' => 'Profil berhasil diperbarui.',
                'icon' => 'success'
            ];

            $_SESSION['username'] = $username;

        } else {

            $_SESSION['swal'] = [
                'title' => 'Gagal!',
                'text' => 'Profil gagal diperbarui.',
                'icon' => 'error'
            ];
        }
    }

    header('Location: profil.php');
    exit;
}


/* =========================
   UBAH PASSWORD
========================= */

if (isset($_POST['ubah_password'])) {

    $lama = $_POST['password_lama'] ?? '';
    $baru = $_POST['password_baru'] ?? '';
    $konfirmasi = $_POST['konfirmasi_password'] ?? '';

    if ($lama !== $user['password']) {

        $_SESSION['swal'] = [
            'title' => 'Gagal!',
            'text' => 'Password lama salah.',
            'icon' => 'error'
        ];

    } elseif (strlen($baru) < 6) {

        $_SESSION['swal'] = [
            'title' => 'Gagal!',
            'text' => 'Password baru minimal 6 karakter.',
            'icon' => 'warning'
        ];

    } elseif ($baru !== $konfirmasi) {

        $_SESSION['swal'] = [
            'title' => 'Gagal!',
            'text' => 'Konfirmasi password tidak sama.',
            'icon' => 'error'
        ];

    } else {

        $baru = mysqli_real_escape_string(
            $koneksi,
            $baru
        );

        if (
            mysqli_query(
                $koneksi,
                "UPDATE tb_user
                 SET password='$baru'
                 WHERE id='$id_user'"
            )
        ) {

            $_SESSION['swal'] = [
                'title' => 'Berhasil!',
                'text' => 'Password berhasil diubah.',
                'icon' => 'success'
            ];

        } else {

            $_SESSION['swal'] = [
                'title' => 'Gagal!',
                'text' => 'Password gagal diubah.',
                'icon' => 'error'
            ];
        }
    }

    header('Location: profil.php');
    exit;
}


/* =========================
   AMBIL DATA TERBARU
========================= */

$result = mysqli_query(
    $koneksi,
    "SELECT * FROM tb_user
     WHERE id='$id_user'
     LIMIT 1"
);

$user = mysqli_fetch_assoc($result);

?>

<!doctype html>
<html lang="id">

<head>

    <meta charset="utf-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <title>Profil Saya - Dina's Cake House</title>

    <link
        href="assets/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>

        /* =========================
           WARNA UTAMA
        ========================= */

        html,
        body {
            min-height: 100%;
        }

        body {
            margin: 0;
            background-color: #fffaf3 !important;
            color: #6e4c42 !important;
        }


        /* =========================
           NAVBAR
        ========================= */

        .topbar {
            background-color: #6e4c42 !important;
            padding: 14px 0;
        }

        .brand {
            color: #fffaf3 !important;
            text-decoration: none;
            font-size: 20px;
            font-weight: 600;
        }

        .brand:hover {
            color: #f7d9dc !important;
        }


        /* =========================
           CONTAINER
        ========================= */

        .profile {
            max-width: 760px;
            margin: 40px auto;
            padding: 0 15px;
        }


        /* =========================
           CARD
        ========================= */

        .profile-card {
            background-color: #fff !important;
            border: 1px solid #eaded5;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 12px 35px rgba(86, 59, 48, 0.12);
        }


        /* =========================
           HEADER PROFILE
        ========================= */

        .profile-head {
            background-color: #6e4c42 !important;
            color: #fffaf3 !important;
            padding: 35px 20px;
            text-align: center;
        }

        .avatar {
            width: 85px;
            height: 85px;
            border-radius: 50%;
            background-color: #f7d9dc !important;
            color: #6e4c42 !important;

            display: flex;
            align-items: center;
            justify-content: center;

            margin: auto;

            font-size: 34px;
            font-weight: bold;

            border: 3px solid #fffaf3;
        }

        .profile-head h3 {
            color: #fffaf3 !important;
        }

        .profile-head p {
            color: #f7d9dc !important;
        }


        /* =========================
           BODY
        ========================= */

        .profile-body {
            padding: 30px;
        }

        .profile-body h5 {
            color: #6e4c42 !important;
            font-weight: 600;
        }


        /* =========================
           INPUT
        ========================= */

        .form-label {
            color: #6e4c42 !important;
            font-weight: 500;
        }

        .form-control {
            background-color: #fffaf3 !important;
            border: 1px solid #eaded5 !important;
            color: #6e4c42 !important;
            border-radius: 10px;
        }

        .form-control:focus {
            background-color: #fff !important;
            border-color: #f3c3ca !important;
            box-shadow: 0 0 0 0.2rem rgba(247, 217, 220, 0.45) !important;
        }


        /* =========================
           TOMBOL
        ========================= */

        .btn {
            border-radius: 10px;
        }


        /* Simpan */

        .btn-save {
            background-color: #6e4c42 !important;
            border-color: #6e4c42 !important;
            color: #fff !important;
        }

        .btn-save:hover {
            background-color: #543932 !important;
            border-color: #543932 !important;
        }


        /* Ubah password */

        .btn-password {
            background-color: #f7d9dc !important;
            border-color: #f7d9dc !important;
            color: #6e4c42 !important;
        }

        .btn-password:hover {
            background-color: #f3c3ca !important;
            border-color: #f3c3ca !important;
        }


        /* Beranda */

        .btn-home {
            background-color: #6e4c42 !important;
            border-color: #6e4c42 !important;
            color: #fff !important;
        }

        .btn-home:hover {
            background-color: #543932 !important;
        }


        /* Keranjang */

        .btn-cart {
            background-color: #f7d9dc !important;
            border-color: #f7d9dc !important;
            color: #6e4c42 !important;
        }

        .btn-cart:hover {
            background-color: #f3c3ca !important;
        }


        /* Logout */

        .btn-logout {
            background-color: #543932 !important;
            border-color: #543932 !important;
            color: #fff !important;
        }

        .btn-logout:hover {
            background-color: #3e2a24 !important;
        }


        /* =========================
           GARIS
        ========================= */

        hr {
            border-color: #eaded5 !important;
            opacity: 1;
        }


        /* =========================
           MOBILE
        ========================= */

        @media (max-width: 576px) {

            .profile {
                margin: 20px auto;
            }

            .profile-body {
                padding: 20px;
            }

            .profile-head {
                padding: 28px 15px;
            }

            .btn {
                margin-bottom: 5px;
            }

        }

    </style>

    <?php include 'sweetalert.php'; ?>

</head>


<body>


<!-- =========================
     NAVBAR
========================= -->

<div class="topbar">

    <div class="container">

        <a
            href="index.php"
            class="brand"
        >
            Dina's Cake House
        </a>

    </div>

</div>


<!-- =========================
     PROFILE
========================= -->

<div class="container profile">

    <div class="profile-card">


        <!-- HEADER -->

        <div class="profile-head">

            <div class="avatar">

                <?= strtoupper(
                    substr($user['username'], 0, 1)
                ); ?>

            </div>

            <h3 class="mt-3 mb-1">
                Profil Saya
            </h3>

            <p class="mb-0">
                <?= htmlspecialchars(
                    $user['username']
                ); ?>
            </p>

        </div>


        <!-- BODY -->

        <div class="profile-body">


            <!-- =========================
                 INFORMASI AKUN
            ========================= -->

            <h5 class="mb-3">
                Informasi Akun
            </h5>


            <form method="post">

                <div class="mb-3">

                    <label class="form-label">
                        Username
                    </label>

                    <input
                        type="text"
                        name="username"
                        class="form-control"
                        value="<?= htmlspecialchars(
                            $user['username']
                        ); ?>"
                        required
                    >

                </div>


                <div class="mb-3">

                    <label class="form-label">
                        Role
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        value="<?= htmlspecialchars(
                            $user['role']
                        ); ?>"
                        readonly
                    >

                </div>


                <button
                    name="update_profil"
                    class="btn btn-save"
                >
                    Simpan Perubahan
                </button>

            </form>


            <hr class="my-4">


            <!-- =========================
                 GANTI PASSWORD
            ========================= -->

            <h5 class="mb-3">
                Ganti Password
            </h5>


            <form method="post">

                <div class="mb-3">

                    <label class="form-label">
                        Password Lama
                    </label>

                    <input
                        type="password"
                        name="password_lama"
                        class="form-control"
                        required
                    >

                </div>


                <div class="mb-3">

                    <label class="form-label">
                        Password Baru
                    </label>

                    <input
                        type="password"
                        name="password_baru"
                        class="form-control"
                        minlength="6"
                        required
                    >

                </div>


                <div class="mb-3">

                    <label class="form-label">
                        Konfirmasi Password Baru
                    </label>

                    <input
                        type="password"
                        name="konfirmasi_password"
                        class="form-control"
                        minlength="6"
                        required
                    >

                </div>


                <button
                    name="ubah_password"
                    class="btn btn-password"
                >
                    Ubah Password
                </button>

            </form>


            <hr class="my-4">


            <!-- =========================
                 NAVIGASI
            ========================= -->

            <div class="d-flex flex-wrap gap-2">

                <a
                    href="index.php"
                    class="btn btn-home"
                >
                    Beranda
                </a>


                <a
                    href="keranjang.php"
                    class="btn btn-cart"
                >
                    Keranjang
                </a>


                <button
                    type="button"
                    onclick="logoutConfirm()"
                    class="btn btn-logout"
                >
                    Logout
                </button>

            </div>


        </div>

    </div>

</div>


<script>

function logoutConfirm() {

    Swal.fire({

        title: 'Logout?',

        text: 'Yakin ingin keluar?',

        icon: 'question',

        showCancelButton: true,

        confirmButtonText: 'Ya, Logout',

        cancelButtonText: 'Batal',

        confirmButtonColor: '#6e4c42',

        cancelButtonColor: '#f7d9dc'

    }).then((result) => {

        if (result.isConfirmed) {

            location.href = 'logout.php';

        }

    });

}


<?php

if (isset($_SESSION['swal'])):

    $swal = $_SESSION['swal'];

    unset($_SESSION['swal']);

?>

Swal.fire({

    title: <?= json_encode($swal['title']) ?>,

    text: <?= json_encode($swal['text']) ?>,

    icon: <?= json_encode($swal['icon']) ?>

});

<?php endif; ?>

</script>


<script src="assets/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>