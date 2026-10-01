<?php
include 'koneksi.php';
session_start();

if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}

$query = mysqli_query($koneksi, "SELECT * FROM tb_user WHERE role='pelanggan'");
?>

<!doctype html>
<html lang="id">

<head>

    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Customers - Dashboard</title>

    <link href="assets/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>

        /* =====================================
           WARNA UTAMA
        ===================================== */

        html,
        body {
            background-color: #fffaf3 !important;
            color: #6e4c42 !important;
        }


        /* =====================================
           SIDEBAR
        ===================================== */

        .sidebar {
            background-color: #6e4c42 !important;
            min-height: 100vh;
        }

        .sidebar .nav-link {
            color: #fffaf3 !important;
            font-size: .875rem;
            font-weight: 500;
            padding: 10px 15px;
        }

        .sidebar .nav-link:hover {
            background-color: #f7d9dc !important;
            color: #6e4c42 !important;
        }

        .sidebar .nav-link.active {
            background-color: #f7d9dc !important;
            color: #6e4c42 !important;
        }


        /* =====================================
           JUDUL SIDEBAR
        ===================================== */

        .sidebar h5 {
            color: #fffaf3 !important;
        }


        /* =====================================
           AREA UTAMA
        ===================================== */

        main {
            background-color: #fffaf3 !important;
            min-height: 100vh;
        }


        /* =====================================
           JUDUL
        ===================================== */

        main h1 {
            color: #6e4c42 !important;
            font-weight: 600;
        }


        /* =====================================
           GARIS
        ===================================== */

        .border-bottom {
            border-color: #eaded5 !important;
        }


        /* =====================================
           TABEL
        ===================================== */

        .table {
            background-color: #ffffff !important;
            color: #6e4c42 !important;
            border-color: #eaded5 !important;
        }

        .table thead th {
            background-color: #f7d9dc !important;
            color: #6e4c42 !important;
            border-color: #eaded5 !important;
        }

        .table tbody td {
            background-color: #ffffff !important;
            color: #6e4c42 !important;
            border-color: #eaded5 !important;
        }

        .table-hover tbody tr:hover td {
            background-color: #fff1f3 !important;
        }


        /* =====================================
           BADGE ROLE
        ===================================== */

        .badge.bg-primary {
            background-color: #6e4c42 !important;
            color: #ffffff !important;
            padding: 6px 10px;
            border-radius: 6px;
        }


        /* =====================================
           RESPONSIVE
        ===================================== */

        @media (max-width: 767px) {

            .sidebar {
                min-height: auto;
            }

            main {
                padding-top: 15px;
            }

        }

    </style>

    <?php include 'sweetalert.php'; ?>

</head>


<body>

<div class="container-fluid">

    <div class="row">


        <!-- ==============================
             SIDEBAR
        =============================== -->

        <nav class="col-md-3 col-lg-2 d-md-block sidebar">

            <div class="position-sticky pt-3">

                <h5 class="px-3 mb-4">
                    Company name
                </h5>


                <ul class="nav flex-column">

                    <li class="nav-item">

                        <a
                            class="nav-link"
                            href="dashboard.php"
                        >
                            🏠 Dashboard
                        </a>

                    </li>


                    <li class="nav-item">

                        <a
                            class="nav-link"
                            href="orders.php"
                        >
                            📄 Orders
                        </a>

                    </li>


                    <li class="nav-item">

                        <a
                            class="nav-link"
                            href="products.php"
                        >
                            🛒 Products
                        </a>

                    </li>


                    <li class="nav-item">

                        <a
                            class="nav-link active"
                            href="customers.php"
                        >
                            👥 Customers
                        </a>

                    </li>

                </ul>


                <hr class="my-3" style="border-color: #eaded5;">


                <ul class="nav flex-column">

                    <li class="nav-item">

                        <a
                            class="nav-link"
                            href="logout.php"
                        >
                            🚪 Sign out
                        </a>

                    </li>

                </ul>

            </div>

        </nav>


        <!-- ==============================
             CONTENT
        =============================== -->

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">

            <div
                class="d-flex justify-content-between flex-wrap flex-md-nowrap
                align-items-center pt-3 pb-2 mb-3 border-bottom"
            >

                <h1 class="h2">
                    Customers
                </h1>

            </div>


            <!-- ==============================
                 TABEL CUSTOMER
            =============================== -->

            <div class="table-responsive">

                <table class="table table-striped table-bordered align-middle">

                    <thead>

                        <tr>

                            <th>No</th>

                            <th>ID</th>

                            <th>Username</th>

                            <th>Role</th>

                        </tr>

                    </thead>


                    <tbody>

                    <?php

                    $no = 1;

                    while ($data = mysqli_fetch_assoc($query)) {

                    ?>

                        <tr>

                            <td>
                                <?= $no++ ?>
                            </td>

                            <td>
                                <?= $data['id'] ?>
                            </td>

                            <td>
                                <strong>
                                    <?= htmlspecialchars($data['username']) ?>
                                </strong>
                            </td>

                            <td>

                                <span class="badge bg-primary">

                                    <?= htmlspecialchars($data['role']) ?>

                                </span>

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