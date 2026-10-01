<?php 
include 'koneksi.php'; 
session_start(); 
 
if (!isset($_SESSION['admin'])) { 
    header('Location: login.php'); 
    exit; 
} 
 
$query = mysqli_query($koneksi, " 
    SELECT  
        t.id_transaksi, 
        t.tanggal, 
        t.id_pelanggan, 
        t.total_harga, 
        u.username 
    FROM tb_transaksi t 
    LEFT JOIN tb_user u  
        ON t.id_pelanggan = u.id 
    ORDER BY t.id_transaksi DESC 
"); 
?> 
 
<!doctype html> 
<html lang="id"> 
<head> 
    <meta charset="utf-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1"> 
 
    <title>Orders</title> 
 
    <link href="assets/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* =========================
           WARNA UTAMA
        ========================= */
        html, body {
            background-color: #fffaf3 !important;
            color: #6e4c42 !important;
            min-height: 100%;
        }

        body {
            margin: 0;
        }

        /* =========================
           SIDEBAR
        ========================= */
        .sidebar {
            background-color: #6e4c42 !important;
            min-height: 100vh;
        }

        .sidebar .nav-link {
            color: #fffaf3 !important;
            padding: 10px 15px;
            border-radius: 8px;
            margin: 3px 10px;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: #f7d9dc !important;
            color: #6e4c42 !important;
        }

        .sidebar h5 {
            color: #fffaf3 !important;
        }

        /* =========================
           CONTENT
        ========================= */
        main {
            background-color: #fffaf3 !important;
            min-height: 100vh;
        }

        main h1 {
            color: #6e4c42 !important;
            font-weight: 600;
        }

        .border-bottom {
            border-color: #eaded5 !important;
        }

        /* =========================
           TABLE
        ========================= */
        .table {
            border-color: #eaded5 !important;
            overflow: hidden;
        }

        .table thead th {
            background-color: #f7d9dc !important;
            color: #6e4c42 !important;
            border-color: #eaded5 !important;
            font-weight: 600;
            vertical-align: middle;
        }

        .table tbody td {
            background-color: #fff !important;
            color: #6e4c42 !important;
            border-color: #eaded5 !important;
            vertical-align: middle;
        }

        .table-striped > tbody > tr:nth-of-type(odd) > * {
            background-color: #fffaf3 !important;
            color: #6e4c42 !important;
        }

        .table tbody tr:hover td {
            background-color: #f7d9dc !important;
        }

        /* =========================
           TOMBOL DETAIL
        ========================= */
        .btn-primary {
            background-color: #6e4c42 !important;
            border-color: #6e4c42 !important;
            color: #fff !important;
        }

        .btn-primary:hover {
            background-color: #543932 !important;
            border-color: #543932 !important;
        }

        /* =========================
           RESPONSIVE
        ========================= */
        @media (max-width: 768px) {
            .sidebar {
                min-height: auto;
            }

            main {
                min-height: auto;
            }

            .table {
                font-size: 14px;
            }
        }
    </style>
    <?php include 'sweetalert.php'; ?>

</head> 
 
<body> 
 
<div class="container-fluid"> 
 
    <div class="row"> 
 
        <!-- SIDEBAR --> 
        <nav class="col-md-3 col-lg-2 d-md-block sidebar"> 
 
            <div class="position-sticky pt-3"> 
 
                <h5 class="px-3 mb-4"> 
                    Company name 
                </h5> 
 
                <ul class="nav flex-column"> 
 
                    <li class="nav-item"> 
                        <a class="nav-link" href="dashboard.php"> 
                            🏠 Dashboard 
                        </a> 
                    </li> 
 
                    <li class="nav-item"> 
                        <a class="nav-link active" href="orders.php"> 
                            📄 Orders 
                        </a> 
                    </li> 
 
                    <li class="nav-item"> 
                        <a class="nav-link" href="products.php"> 
                            🛒 Products 
                        </a> 
                    </li> 
 
                    <li class="nav-item"> 
                        <a class="nav-link" href="customers.php"> 
                            👥 Customers 
                        </a> 
                    </li>

                    <li class="nav-item mt-3">
                        <a class="nav-link" href="logout.php">
                            🚪 Sign out
                        </a>
                    </li>
 
                </ul> 
 
            </div> 
 
        </nav> 
 
        <!-- CONTENT --> 
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4"> 
 
            <div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom"> 
 
                <h1 class="h2"> 
                    Orders 
                </h1> 
 
            </div> 
 
            <div class="table-responsive"> 
 
                <table class="table table-striped table-bordered"> 
 
                    <thead> 
                        <tr> 
                            <th>No</th> 
                            <th>ID Order</th> 
                            <th>Tanggal</th> 
                            <th>Customer</th> 
                            <th>Total Harga</th> 
                            <th>Aksi</th> 
                        </tr> 
                    </thead> 
 
                    <tbody> 
 
                    <?php 
                    $no = 1; 
 
                    while ($data = mysqli_fetch_assoc($query)) { 
                    ?> 
 
                        <tr> 
 
                            <td><?= $no++ ?></td> 
 
                            <td>
                                #<?= $data['id_transaksi'] ?> 
                            </td> 
 
                            <td>
                                <?= htmlspecialchars($data['tanggal']) ?> 
                            </td> 
 
                            <td>
                                <?= htmlspecialchars($data['username'] ?? 'Tidak diketahui') ?> 
                            </td> 
 
                            <td>
                                Rp <?= number_format($data['total_harga'], 0, ',', '.') ?> 
                            </td> 
 
                            <td>
                                <a href="detail_order.php?id=<?= $data['id_transaksi'] ?>" 
                                   class="btn btn-sm btn-primary"> 
                                    Detail 
                                </a> 
                            </td> 
 
                        </tr> 
 
                    <?php } ?> 
 
                    </tbody> 
 
                </table> 
 
            </div> 
 
        </main> 
 
    </div> 
 
</div> 
 
<script src="assets/dist/js/bootstrap.bundle.min.js"></script> 
 
</body> 
</html>