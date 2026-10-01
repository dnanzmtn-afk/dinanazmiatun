<?php
include 'koneksi.php';

$id = $_GET['id'] ?? '';

$hasil = mysqli_query($koneksi, "SELECT * FROM tb_produk WHERE id='$id'");
$data = mysqli_fetch_assoc($hasil);

if (!$data) {
    echo "<script>
        alert('Produk tidak ditemukan!');
        window.location='index.php';
    </script>";
    exit;
}

$nama = $data['nama'];
$harga = $data['harga'];
$deskripsi = $data['deskripsi'];
$poto = $data['poto'];
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?= htmlspecialchars($nama) ?> - Dina's Cake House</title>

    <link href="assets/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --cream: #fffaf3;
            --pink: #f7d9dc;
            --pink2: #f3c3ca;
            --brown: #6e4c42;
            --dark: #3e302c;
            --white: #fff;
            --line: #eaded5;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: var(--cream);
            color: var(--brown);
            font-family: Arial, sans-serif;
        }

        /* NAVBAR */
        .navbar-custom {
            background: var(--brown);
            padding: 15px 0;
            box-shadow: 0 3px 15px rgba(86, 59, 48, .12);
        }

        .navbar-brand {
            color: white !important;
            font-size: 22px;
            font-weight: bold;
        }

        .navbar-brand:hover {
            color: var(--pink) !important;
        }

        .nav-link {
            color: white !important;
            margin-left: 8px;
            border-radius: 20px;
            padding: 8px 15px !important;
        }

        .nav-link:hover {
            background: var(--pink);
            color: var(--brown) !important;
        }

        /* DETAIL PRODUK */
        .detail-wrapper {
            padding: 70px 0;
            min-height: 75vh;
        }

        .product-detail {
            background: white;
            border-radius: 25px;
            padding: 35px;
            box-shadow: 0 12px 35px rgba(86, 59, 48, .10);
            border: 1px solid var(--line);
        }

        .product-image {
            width: 100%;
            height: 450px;
            object-fit: cover;
            border-radius: 20px;
            background: var(--pink);
        }

        .product-title {
            font-size: 36px;
            font-weight: bold;
            color: var(--brown);
            margin-bottom: 15px;
        }

        .product-price {
            font-size: 27px;
            font-weight: bold;
            color: var(--brown);
            margin-bottom: 20px;
        }

        .product-description {
            color: #6f625d;
            font-size: 16px;
            line-height: 1.8;
            margin-bottom: 25px;
        }

        .label-jumlah {
            font-weight: bold;
            color: var(--brown);
            margin-bottom: 8px;
        }

        .jumlah-input {
            width: 110px;
            border: 1px solid var(--line);
            border-radius: 10px;
            padding: 10px 12px;
            color: var(--brown);
            background: var(--cream);
            margin-bottom: 20px;
        }

        .btn-cart {
            background: var(--brown);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 25px;
            font-weight: bold;
            transition: .2s;
        }

        .btn-cart:hover {
            background: var(--dark);
            color: white;
            transform: translateY(-2px);
        }

        .btn-back {
            background: var(--pink);
            color: var(--brown);
            border: none;
            padding: 12px 25px;
            border-radius: 25px;
            font-weight: bold;
            text-decoration: none;
            display: inline-block;
            margin-left: 8px;
        }

        .btn-back:hover {
            background: var(--pink2);
            color: var(--brown);
        }

        /* FOOTER */
        footer {
            background: var(--brown);
            color: white;
            padding: 25px 0;
            margin-top: 30px;
        }

        footer p {
            margin: 0;
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .detail-wrapper {
                padding: 35px 15px;
            }

            .product-detail {
                padding: 20px;
            }

            .product-image {
                height: 320px;
                margin-bottom: 25px;
            }

            .product-title {
                font-size: 28px;
            }

            .product-price {
                font-size: 23px;
            }

            .btn-back {
                margin-left: 0;
                margin-top: 10px;
            }
        }
    </style>
    <?php include 'sweetalert.php'; ?>

</head>

<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-custom">
    <div class="container">

        <a class="navbar-brand" href="index.php">
            🍰 Dina's Cake House
        </a>

        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarMenu"
            aria-controls="navbarMenu"
            aria-expanded="false"
            aria-label="Toggle navigation"
            style="background:#f7d9dc;"
        >
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarMenu">
            <ul class="navbar-nav ms-auto align-items-lg-center">

                <li class="nav-item">
                    <a class="nav-link" href="index.php">
                        Beranda
                    </a>
                </li>

                <?php if (isset($_SESSION['id_user'])): ?>

                    <li class="nav-item">
                        <a class="nav-link" href="profil.php">
                            Profil
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="keranjang.php">
                            🛒 Keranjang
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">
                            Logout
                        </a>
                    </li>

                <?php else: ?>

                    <li class="nav-item">
                        <a class="nav-link" href="login.php">
                            Login
                        </a>
                    </li>

                <?php endif; ?>

            </ul>
        </div>
    </div>
</nav>


<!-- DETAIL PRODUK -->
<section class="detail-wrapper">

    <div class="container">

        <div class="product-detail">

            <div class="row align-items-center">

                <!-- FOTO -->
                <div class="col-md-6 mb-4 mb-md-0">

                    <img
                        src="img/<?= htmlspecialchars($poto) ?>"
                        alt="<?= htmlspecialchars($nama) ?>"
                        class="product-image"
                    >

                </div>


                <!-- INFORMASI -->
                <div class="col-md-6">

                    <h1 class="product-title">
                        <?= htmlspecialchars($nama) ?>
                    </h1>

                    <div class="product-price">
                        Rp <?= number_format($harga, 0, ',', '.') ?>
                    </div>

                    <p class="product-description">
                        <?= nl2br(htmlspecialchars($deskripsi)) ?>
                    </p>

                    <form method="post" action="keranjang.php?id=<?= $id ?>">

                        <input
                            type="hidden"
                            name="hidden_foto"
                            value="<?= htmlspecialchars($poto) ?>"
                        >

                        <input
                            type="hidden"
                            name="hidden_nama"
                            value="<?= htmlspecialchars($nama) ?>"
                        >

                        <input
                            type="hidden"
                            name="hidden_harga"
                            value="<?= htmlspecialchars($harga) ?>"
                        >

                        <div class="label-jumlah">
                            Jumlah
                        </div>

                        <input
                            type="number"
                            name="jumlah"
                            value="1"
                            min="1"
                            class="jumlah-input"
                        >

                        <br>

                        <button
                            type="submit"
                            name="add"
                            class="btn-cart"
                        >
                            🛒 Tambah ke Keranjang
                        </button>

                        <a href="index.php" class="btn-back">
                            ← Kembali
                        </a>

                    </form>

                </div>

            </div>

        </div>

    </div>

</section>


<!-- FOOTER -->
<footer>
    <div class="container text-center">

        <p>
            &copy; <?= date('Y') ?> Dina's Cake House.
            Semua Hak Dilindungi.
        </p>

    </div>
</footer>


<script src="assets/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>