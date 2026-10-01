<?php
session_start();

if (!isset($_SESSION['id_user'])) {
    header('Location: login.php');
    exit;
}

$koneksi = mysqli_connect("localhost", "root", "", "db_toko");

if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

$id_user = $_SESSION['id_user'];

/* =========================
   TAMBAH PRODUK KE KERANJANG
========================= */
if (isset($_POST["add"])) {

    $id_produk = $_GET["id"];

    if (isset($_SESSION["cart"][$id_produk])) {

        $_SESSION["cart"][$id_produk]['jumlah'] += $_POST["jumlah"];

    } else {

        $_SESSION["cart"][$id_produk] = array(
            'id' => $id_produk,
            'nama' => $_POST["hidden_nama"],
            'harga' => $_POST["hidden_harga"],
            'foto' => $_POST["hidden_foto"],
            'jumlah' => $_POST["jumlah"]
        );
    }

    $_SESSION['swal'] = [
        'title' => 'Berhasil!',
        'text' => 'Produk berhasil ditambahkan ke keranjang.',
        'icon' => 'success'
    ];

    header('Location: keranjang.php');
    exit;
}


/* =========================
   AKSI KERANJANG
========================= */
if (isset($_GET["aksi"])) {

    /* HAPUS PRODUK */
    if ($_GET["aksi"] == "hapus") {

        $id_produk = $_GET["id"];

        if (isset($_SESSION["cart"][$id_produk])) {

            unset($_SESSION["cart"][$id_produk]);

            $_SESSION['swal'] = [
                'title' => 'Produk dihapus!',
                'text' => 'Produk berhasil dihapus dari keranjang.',
                'icon' => 'success'
            ];

            header('Location: keranjang.php');
            exit;
        }
    }


    /* BELI PRODUK */
    elseif ($_GET["aksi"] == "beli") {

        if (empty($_SESSION["cart"])) {
            $_SESSION['swal'] = [
                'title' => 'Keranjang kosong!',
                'text' => 'Tambahkan produk terlebih dahulu sebelum membeli.',
                'icon' => 'warning'
            ];
            header('Location: keranjang.php');
            exit;
        }

        $total = 0;

        foreach ($_SESSION["cart"] as $value) {
            $total += ($value["jumlah"] * $value["harga"]);
        }


        /* SIMPAN TRANSAKSI */
        $query = mysqli_query(
            $koneksi,
            "INSERT INTO tb_transaksi(tanggal, id_pelanggan, total_harga)
             VALUES ('" . date("Y-m-d") . "', '$id_user', '$total')"
        ) or die("Error insert transaksi: " . mysqli_error($koneksi));

        $id_transaksi = mysqli_insert_id($koneksi);


        /* SIMPAN DETAIL */
        foreach ($_SESSION["cart"] as $value) {

            $id_produk = $value['id'];
            $jumlah = $value['jumlah'];

            $sql = "INSERT INTO tb_detail(id_transaksi, id_produk, jumlah)
                    VALUES ('$id_transaksi', '$id_produk', '$jumlah')";

            mysqli_query($koneksi, $sql);
        }


        /* KOSONGKAN KERANJANG */
        unset($_SESSION["cart"]);

        $_SESSION['swal'] = [
            'title' => 'Pesanan berhasil!',
            'text' => 'Pesanan berhasil dibuat.',
            'icon' => 'success'
        ];

        header('Location: cetak.php?id=' . $id_transaksi);
        exit;
    }
}
?>

<!doctype html>
<html lang="id">

<head>

    <meta charset="utf-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1">

    <title>Keranjang - Dina's Cake House</title>

    <link href="assets/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <?php include 'sweetalert.php'; ?>


    <style>

        /* =========================================
           WARNA UTAMA DINA'S CAKE HOUSE
        ========================================= */

        :root {
            --cream: #fffaf3;
            --cream-gelap: #f8eee5;

            --cokelat: #765044;
            --cokelat-gelap: #5f4036;

            --pink: #f7d9dc;
            --pink-gelap: #e9bfc4;

            --teks: #6e4c42;

            --putih: #ffffff;

            --border: #eaded5;
        }


        /* =========================================
           BODY
        ========================================= */

        html,
        body {
            margin: 0;
            padding: 0;
            min-height: 100%;
        }

        body {
            background-color: var(--cream) !important;
            color: var(--teks);
            font-family: Arial, sans-serif;
        }


        /* =========================================
           NAVBAR
        ========================================= */

        .navbar-custom {
            background-color: var(--cokelat);
            padding: 15px 0;
            box-shadow: 0 3px 12px rgba(86, 59, 48, 0.12);
        }

        .navbar-brand {
            color: white !important;
            font-size: 23px;
            font-weight: bold;
            text-decoration: none;
        }

        .navbar-brand:hover {
            color: var(--pink) !important;
        }


        /* =========================================
           LOGO
        ========================================= */

        .navbar-logo {
            width: 48px;
            height: 48px;
            object-fit: contain;
            margin-right: 10px;
            background-color: white;
            border-radius: 50%;
            padding: 3px;
        }


        /* =========================================
           MENU NAVBAR
        ========================================= */

        .navbar-menu {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .navbar-menu a {
            text-decoration: none;
            color: white;
            padding: 9px 15px;
            border-radius: 8px;
            transition: 0.2s;
        }

        .navbar-menu a:hover {
            background-color: var(--pink);
            color: var(--teks);
        }


        /* =========================================
           TOMBOL NAVBAR
        ========================================= */

        .btn-navbar-pink {
            background-color: var(--pink);
            color: var(--teks) !important;
            border: none;
        }

        .btn-navbar-pink:hover {
            background-color: var(--pink-gelap) !important;
        }

        .btn-navbar-white {
            background-color: white;
            color: var(--teks) !important;
            border: none;
        }

        .btn-navbar-white:hover {
            background-color: var(--cream-gelap);
        }

        .btn-navbar-brown {
            background-color: var(--cokelat-gelap);
            color: white !important;
            border: 1px solid rgba(255,255,255,0.15);
        }

        .btn-navbar-brown:hover {
            background-color: #4d332c;
        }


        /* =========================================
           JUDUL HALAMAN
        ========================================= */

        .page-title {
            color: var(--cokelat);
            font-weight: 700;
            text-align: center;
            margin-top: 50px;
            margin-bottom: 10px;
        }

        .page-subtitle {
            text-align: center;
            color: var(--teks);
            opacity: 0.75;
            margin-bottom: 40px;
        }


        /* =========================================
           CARD KERANJANG
        ========================================= */

        .cart-card {
            background-color: white;
            border: 1px solid var(--border);
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 8px 25px rgba(86, 59, 48, 0.08);
        }


        /* =========================================
           TABEL
        ========================================= */

        .cart-table {
            margin-bottom: 0;
            vertical-align: middle;
        }

        .cart-table thead {
            background-color: var(--cokelat);
            color: white;
        }

        .cart-table thead th {
            border: none;
            padding: 15px;
            font-weight: 600;
        }

        .cart-table tbody td {
            padding: 18px 15px;
            border-color: var(--border);
            color: var(--teks);
        }

        .cart-table tbody tr:hover {
            background-color: #fff8f6;
        }


        /* =========================================
           FOTO PRODUK
        ========================================= */

        .product-image {
            width: 90px;
            height: 90px;
            object-fit: cover;
            border-radius: 12px;
            border: 2px solid var(--pink);
            margin-right: 12px;
            vertical-align: middle;
        }

        .product-name {
            font-weight: 600;
            color: var(--cokelat);
        }


        /* =========================================
           TOTAL
        ========================================= */

        .grand-total {
            background-color: #fff5f3;
        }

        .grand-total td,
        .grand-total th {
            color: var(--cokelat);
            font-weight: 700;
            padding: 18px 15px;
        }


        /* =========================================
           TOMBOL
        ========================================= */

        .btn-brown {
            background-color: var(--cokelat);
            border: 1px solid var(--cokelat);
            color: white;
            border-radius: 8px;
            padding: 9px 18px;
        }

        .btn-brown:hover {
            background-color: var(--cokelat-gelap);
            border-color: var(--cokelat-gelap);
            color: white;
        }

        .btn-pink {
            background-color: var(--pink);
            border: 1px solid var(--pink);
            color: var(--teks);
            border-radius: 8px;
            padding: 9px 18px;
        }

        .btn-pink:hover {
            background-color: var(--pink-gelap);
            border-color: var(--pink-gelap);
            color: var(--teks);
        }

        .btn-delete {
            background-color: #f4d3d3;
            border: none;
            color: #7a4545;
            border-radius: 7px;
            padding: 7px 13px;
        }

        .btn-delete:hover {
            background-color: #e8bebe;
            color: #633838;
        }


        /* =========================================
           KERANJANG KOSONG
        ========================================= */

        .empty-cart {
            text-align: center;
            padding: 60px 20px;
        }

        .empty-icon {
            font-size: 55px;
            margin-bottom: 20px;
        }

        .empty-cart h4 {
            color: var(--cokelat);
            font-weight: 600;
        }

        .empty-cart p {
            color: var(--teks);
            opacity: 0.7;
        }


        /* =========================================
           FOOTER
        ========================================= */

        footer {
            margin-top: 70px;
            padding: 25px 0;
            background-color: var(--cokelat);
            color: white;
        }

        footer p {
            margin: 0;
        }

        footer a {
            color: var(--pink);
            text-decoration: none;
        }

        footer a:hover {
            color: white;
        }


        /* =========================================
           RESPONSIVE HP
        ========================================= */

        @media (max-width: 768px) {

            .navbar-brand {
                font-size: 18px;
            }

            .navbar-menu {
                margin-top: 15px;
                flex-wrap: wrap;
                justify-content: center;
            }

            .cart-card {
                padding: 12px;
                overflow-x: auto;
            }

            .cart-table {
                min-width: 700px;
            }

            .page-title {
                margin-top: 30px;
            }
        }

    </style>

</head>


<body>


<!-- =========================================
     NAVBAR
========================================= -->

<nav class="navbar-custom">

    <div class="container">

        <div class="d-flex flex-wrap align-items-center justify-content-between">

            <!-- LOGO + NAMA -->
            <a href="index.php"
               class="navbar-brand d-flex align-items-center">

                <img src="logo.png"
                     class="navbar-logo"
                     alt="Dina's Cake House">

                Dina's Cake House

            </a>


            <!-- MENU -->
            <div class="navbar-menu">

                <a href="index.php">
                    Beranda
                </a>

                <a href="profil.php"
                   class="btn-navbar-pink">
                    Profil
                </a>

                <a href="keranjang.php"
                   class="btn-navbar-white">
                    Keranjang
                </a>

                <a href="logout.php"
                   class="btn-navbar-brown">
                    Logout
                </a>

            </div>

        </div>

    </div>

</nav>



<!-- =========================================
     ISI KERANJANG
========================================= -->

<main class="container">


    <h2 class="page-title">
        Keranjangmu
    </h2>

    <p class="page-subtitle">
        Periksa kembali pesananmu sebelum melakukan pembelian.
    </p>


    <?php if (!empty($_SESSION["cart"])) { ?>


        <div class="cart-card">

            <div class="table-responsive">

                <table class="table cart-table">

                    <thead>

                        <tr class="text-center align-middle">

                            <th>
                                Produk
                            </th>

                            <th>
                                Jumlah
                            </th>

                            <th>
                                Harga
                            </th>

                            <th>
                                Total Harga
                            </th>

                            <th>
                                Aksi
                            </th>

                        </tr>

                    </thead>


                    <tbody>


                    <?php

                    $total = 0;

                    foreach ($_SESSION["cart"] as $key => $value) {

                    ?>


                        <tr>

                            <!-- PRODUK -->
                            <td>

                                <div class="d-flex align-items-center">

                                    <img
                                        src="img/<?= htmlspecialchars($value["foto"]) ?>"
                                        class="product-image"
                                        alt="<?= htmlspecialchars($value["nama"]) ?>"
                                    >

                                    <span class="product-name">
                                        <?= htmlspecialchars($value["nama"]) ?>
                                    </span>

                                </div>

                            </td>


                            <!-- JUMLAH -->
                            <td class="text-center">

                                <?= $value["jumlah"] ?>

                            </td>


                            <!-- HARGA -->
                            <td class="text-center">

                                Rp <?= number_format($value["harga"], 0, ',', '.') ?>

                            </td>


                            <!-- TOTAL -->
                            <td class="text-center">

                                Rp
                                <?= number_format(
                                    $value["jumlah"] * $value["harga"],
                                    0,
                                    ',',
                                    '.'
                                ) ?>

                            </td>


                            <!-- AKSI -->
                            <td class="text-center">

                                <a
                                href="keranjang.php?aksi=hapus&id=<?= $value["id"]; ?>"
                                class="btn btn-delete"
                            >
                                Hapus
                               </a>

                            </td>

                        </tr>


                    <?php

                        $total += ($value["jumlah"] * $value["harga"]);

                    }

                    ?>


                    <!-- GRAND TOTAL -->

                    <tr class="grand-total">

                        <td colspan="3"
                            class="text-end">

                            Grand Total

                        </td>

                        <th class="text-center">

                            Rp <?= number_format($total, 0, ',', '.') ?>

                        </th>

                        <td class="text-center">

                            <a
                                href="keranjang.php?aksi=beli"
                                class="btn btn-brown"
                            >
                                Beli Sekarang
                            </a>

                        </td>

                    </tr>


                    </tbody>

                </table>

            </div>

        </div>


    <?php } else { ?>


        <!-- KERANJANG KOSONG -->

        <div class="cart-card">

            <div class="empty-cart">

                <div class="empty-icon">
                    🧁
                </div>

                <h4>
                    Keranjangmu masih kosong
                </h4>

                <p>
                    Yuk pilih kue favoritmu dan tambahkan ke keranjang.
                </p>

                <a
                    href="index.php"
                    class="btn btn-brown"
                >
                    Lihat Produk
                </a>

            </div>

        </div>


    <?php } ?>


</main>



<!-- =========================================
     FOOTER
========================================= -->

<footer>

    <div class="container">

        <div class="row align-items-center">

            <div class="col-md-6">

                <p>
                    &copy; <?= date('Y') ?> Dina's Cake House
                </p>

            </div>

            <div class="col-md-6 text-md-end">

                <a href="index.php">
                    Beranda
                </a>

                &nbsp; | &nbsp;

                <a href="profil.php">
                    Profil
                </a>

            </div>

        </div>

    </div>

</footer>



<script src="assets/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>