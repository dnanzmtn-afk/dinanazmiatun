<?php
session_start();
include 'koneksi.php';
?>

<!doctype html>
<html lang="id">
<head>

    <meta charset="utf-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <title>Dina's Cake House</title>

    <link
        href="assets/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >


    <style>

        /* =========================
           WARNA UTAMA
        ========================= */

        html,
        body {

            background-color: #fffaf3 !important;

            color: #6e4c42 !important;

        }


        /* =========================
           NAVBAR
        ========================= */

        .navbar-custom {

            background-color: #6e4c42 !important;

        }


        .navbar-brand {

            color: #fffaf3 !important;

            font-weight: 600;

        }


        .navbar-brand:hover {

            color: #f7d9dc !important;

        }


        /* Tombol navbar */

        .btn-profil {

            background-color: #f7d9dc !important;

            border-color: #f7d9dc !important;

            color: #6e4c42 !important;

        }


        .btn-keranjang {

            background-color: #fffaf3 !important;

            border-color: #fffaf3 !important;

            color: #6e4c42 !important;

        }


        .btn-logout {

            background-color: #543932 !important;

            border-color: #543932 !important;

            color: #fff !important;

        }


        .btn-login {

            background-color: #f7d9dc !important;

            border-color: #f7d9dc !important;

            color: #6e4c42 !important;

        }


        .btn-profil:hover,
        .btn-keranjang:hover,
        .btn-login:hover {

            opacity: 0.85;

        }


        .btn-logout:hover {

            background-color: #3e2a24 !important;

        }


        /* =========================
           HERO
        ========================= */

        .hero {

            background-color: #fffaf3 !important;

        }


        .hero h1 {

            color: #6e4c42 !important;

            font-weight: 600;

        }


        .hero p {

            color: #6e4c42 !important;

        }


        /* Tombol utama */

        .btn-main {

            background-color: #6e4c42 !important;

            border-color: #6e4c42 !important;

            color: #fff !important;

        }


        .btn-main:hover {

            background-color: #543932 !important;

            border-color: #543932 !important;

        }


        /* Tombol kedua */

        .btn-second {

            background-color: #f7d9dc !important;

            border-color: #f7d9dc !important;

            color: #6e4c42 !important;

        }


        .btn-second:hover {

            background-color: #f3c3ca !important;

            border-color: #f3c3ca !important;

        }


        /* =========================
           PRODUK
        ========================= */

        .product-section {

            background-color: #f7d9dc !important;

        }


        .product-card {

            background-color: #fff !important;

            border: 1px solid #eaded5 !important;

            border-radius: 12px;

            overflow: hidden;

            transition: 0.2s;

        }


        .product-card:hover {

            transform: translateY(-3px);

            box-shadow:
                0 10px 25px
                rgba(86, 59, 48, 0.12) !important;

        }


        .product-card img {

            width: 100%;

            height: 225px;

            object-fit: cover;

            background-color: #fffaf3;

        }


        .product-card .card-body {

            background-color: #fff !important;

        }


        .product-name {

            color: #6e4c42 !important;

            font-size: 17px;

        }


        .product-price {

            color: #6e4c42 !important;

            font-size: 16px;

        }


        .product-description {

            color: #6e4c42 !important;

            opacity: 0.85;

        }


        /* =========================
           TOMBOL VIEW
        ========================= */

        .btn-view {

            background-color: #6e4c42 !important;

            border-color: #6e4c42 !important;

            color: #fff !important;

        }


        .btn-view:hover {

            background-color: #543932 !important;

            border-color: #543932 !important;

            color: #fff !important;

        }


        /* =========================
           FOOTER
        ========================= */

        footer {

            background-color: #6e4c42 !important;

            color: #fffaf3 !important;

        }


        footer h5 {

            color: #f7d9dc !important;

            font-weight: bold;

            margin-bottom: 15px;

        }


        footer p,
        footer a {

            color: #fffaf3 !important;

        }


        footer a {

            text-decoration: none;

        }


        footer a:hover {

            color: #f7d9dc !important;

        }


        .footer-kontak {

            line-height: 1.8;

        }


        .footer-icon {

            margin-right: 6px;

        }


        .footer-divider {

            border-color: rgba(255, 255, 255, 0.25);

        }


        /* =========================
           RESPONSIVE
        ========================= */

        @media (max-width: 768px) {

            .product-card img {

                height: 200px;

            }

        }

    </style>


    <?php include 'sweetalert.php'; ?>

</head>


<body>


<!-- =========================
     NAVBAR
========================= -->

<header>

    <div class="navbar navbar-custom shadow-sm">

        <div class="container">


            <a
                href="index.php"
                class="navbar-brand d-flex align-items-center"
            >

                <strong>
                    Dina's Cake House
                </strong>

            </a>


            <div class="d-flex gap-2 align-items-center">


                <?php if (isset($_SESSION['id_user'])): ?>


                    <!-- PROFIL -->

                    <a
                        href="profil.php"
                        class="btn btn-profil btn-sm"
                    >

                        Profil

                    </a>


                    <!-- RIWAYAT TRANSAKSI -->

                    <a
                        href="riwayat transaksi.php"
                        class="btn btn-light btn-sm"
                    >

                        Riwayat Transaksi

                    </a>


                    <!-- KERANJANG -->

                    <a
                        href="keranjang.php"
                        class="btn btn-keranjang btn-sm"
                    >

                        Keranjang

                    </a>


                    <!-- LOGOUT -->

                    <a
                        href="logout.php"
                        class="btn btn-logout btn-sm"
                    >

                        Logout

                    </a>


                <?php else: ?>


                    <!-- LOGIN -->

                    <a
                        href="login.php"
                        class="btn btn-login btn-sm"
                    >

                        Login

                    </a>


                <?php endif; ?>


            </div>

        </div>

    </div>

</header>



<!-- =========================
     CONTENT
========================= -->

<main>


    <!-- =========================
         HERO
    ========================= -->

    <section class="hero py-5 text-center">

        <div class="container">

            <div class="row py-lg-5">

                <div class="col-lg-6 col-md-8 mx-auto">


                    <h1 class="display-5">

                        Dina's Cake House

                    </h1>


                    <p class="lead">

                        Temukan berbagai pilihan kue lezat untuk
                        menemani momen spesialmu.

                    </p>


                    <p>


                        <a
                            href="#produk"
                            class="btn btn-main my-2"
                        >

                            Lihat Produk

                        </a>


                        <a
                            href="keranjang.php"
                            class="btn btn-second my-2"
                        >

                            Keranjang

                        </a>


                    </p>


                </div>

            </div>

        </div>

    </section>



    <!-- =========================
         PRODUK
    ========================= -->

    <div
        class="product-section py-5"
        id="produk"
    >

        <div class="container">


            <div
                class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4"
            >


                <?php

                $query = mysqli_query(
                    $koneksi,
                    "SELECT * FROM tb_produk ORDER BY id DESC"
                );

                while (
                    $data = mysqli_fetch_assoc($query)
                ):

                ?>


                <div class="col">


                    <div
                        class="card product-card shadow-sm h-100"
                    >


                        <!-- FOTO PRODUK -->

                        <img
                            src="img/<?= htmlspecialchars($data['poto']) ?>"
                            alt="<?= htmlspecialchars($data['nama']) ?>"
                        >


                        <div class="card-body">


                            <!-- NAMA -->

                            <p class="product-name mb-2">

                                <b>

                                    <?= htmlspecialchars(
                                        $data['nama']
                                    ) ?>

                                </b>

                            </p>


                            <!-- HARGA -->

                            <p class="product-price mb-2">

                                <b>

                                    Rp
                                    <?= number_format(
                                        $data['harga'],
                                        0,
                                        ',',
                                        '.'
                                    ) ?>

                                </b>

                            </p>


                            <!-- DESKRIPSI -->

                            <p class="product-description mb-3">

                                <?= htmlspecialchars(
                                    $data['deskripsi']
                                ) ?>

                            </p>


                            <div
                                class="d-flex justify-content-between align-items-center"
                            >


                                <div class="btn-group">


                                    <a
                                        href="detail.php?id=<?= $data['id'] ?>"
                                        class="btn btn-sm btn-view"
                                    >

                                        Detail

                                    </a>


                                </div>


                                <small class="text-body-secondary">

                                    Tersedia

                                </small>


                            </div>


                        </div>

                    </div>

                </div>


                <?php endwhile; ?>


            </div>

        </div>

    </div>


</main>



<!-- =========================
     FOOTER KONTAK
========================= -->

<footer class="py-5">


    <div class="container">


        <div class="row">


            <!-- =========================
                 INFORMASI TOKO
            ========================= -->

            <div class="col-md-4 mb-4">


                <h5>

                    Dina's Cake House

                </h5>


                <p>

                    Toko kue dengan berbagai pilihan
                    kue lezat untuk menemani
                    momen spesialmu.

                </p>


                <p class="mb-0">

                    &copy;
                    <?= date('Y') ?>
                    Dina's Cake House

                </p>


            </div>



            <!-- =========================
                 KONTAK
            ========================= -->

            <div class="col-md-4 mb-4 footer-kontak">


                <h5>

                    Kontak Kami

                </h5>


                <p>

                    <span class="footer-icon">
                        📞
                    </span>

                    <a href="tel:081234567890">

                        0812-3456-7890

                    </a>

                </p>


                <p>

                    <span class="footer-icon">
                        📧
                    </span>

                    <a href="mailto:dinascakehouse@gmail.com">

                        dinascakehouse@gmail.com

                    </a>

                </p>


                <p>

                    <span class="footer-icon">
                        💬
                    </span>

                    <a
                        href="https://wa.me/6285608291380"
                        target="_blank"
                    >

                        WhatsApp

                    </a>

                </p>
                <p>

                    <span class="footer-icon">
                       📸
                    </span>

                <a
                    href="https://instagram.com/dinascakehouse"
                    target="_blank"
                >

                    Instagram

                </a>

                </p>

            </div>



            <!-- =========================
                 ALAMAT & JAM
            ========================= -->

            <div class="col-md-4 mb-4">


                <h5>

                    Alamat Toko

                </h5>


                <p>

                    📍 Jl. Cake House No. 10,
                    Jakarta

                </p>


                <p>

                    🕐 Senin - Sabtu:
                    08.00 - 20.00

                </p>


                <p>

                    Minggu:
                    09.00 - 18.00

                </p>


            </div>


        </div>


        <hr class="footer-divider">


        <!-- =========================
             BAGIAN BAWAH FOOTER
        ========================= -->

        <div class="row">


            <div class="col-md-6">


                <p class="mb-0">

                    Dina's Cake House
                    &copy; <?= date('Y') ?>

                </p>


            </div>


            <div class="col-md-6 text-md-end">


                <a
                    href="#"
                    class="text-decoration-none"
                >

                    Kembali ke atas ↑

                </a>


            </div>


        </div>


    </div>


</footer>



<!-- =========================
     BOOTSTRAP JS
========================= -->

<script
    src="assets/dist/js/bootstrap.bundle.min.js"
>
</script>


</body>

</html>