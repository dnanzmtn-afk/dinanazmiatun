<?php
session_start();
include 'koneksi.php';

if (!isset($_GET['id'])) {
    die("ID transaksi tidak ditemukan.");
}

$id_transaksi = $_GET['id'];

/* =========================
   AMBIL DATA TRANSAKSI
========================= */

$query_transaksi = mysqli_query(
    $koneksi,
    "SELECT *
     FROM tb_transaksi
     WHERE id_transaksi = '$id_transaksi'"
);

if (!$query_transaksi) {
    die("Query transaksi gagal: " . mysqli_error($koneksi));
}

$transaksi = mysqli_fetch_assoc($query_transaksi);

if (!$transaksi) {
    die("Data transaksi tidak ditemukan.");
}


/* =========================
   AMBIL DETAIL PRODUK
========================= */

$query_detail = mysqli_query(
    $koneksi,
    "SELECT 
        d.id_detail,
        d.id_transaksi,
        d.id_produk,
        d.jumlah,
        p.nama,
        p.harga,
        p.poto
     FROM tb_detail d
     JOIN tb_produk p ON d.id_produk = p.id
     WHERE d.id_transaksi = '$id_transaksi'"
);

if (!$query_detail) {
    die("Query detail gagal: " . mysqli_error($koneksi));
}

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <title>
        Detail Pesanan - Dina's Cake House
    </title>


    <!-- BOOTSTRAP -->

    <link
        href="assets/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >


    <style>

        body {
            margin: 0;
            background-color: #fffaf3;
            color: #6e4c42;
            font-family: Arial, sans-serif;
        }


        .container-pesanan {

            max-width: 900px;

            margin: 50px auto;

            padding: 20px;

        }


        .card-pesanan {

            background: #ffffff;

            border: 1px solid #eaded5;

            border-radius: 18px;

            padding: 35px;

            box-shadow:
                0 10px 30px
                rgba(86, 59, 48, 0.10);

        }


        .header-pesanan {

            background-color: #765044;

            color: white;

            padding: 25px;

            border-radius: 14px;

            margin-bottom: 25px;

        }


        .header-pesanan h2 {

            margin: 0;

            font-weight: bold;

        }


        .header-pesanan p {

            margin: 5px 0 0;

        }


        .info {

            background-color: #fff5f3;

            padding: 18px;

            border-radius: 12px;

            margin-bottom: 25px;

        }


        .info p {

            margin: 5px 0;

        }


        .table thead {

            background-color: #f7d9dc;

            color: #6e4c42;

        }


        .table td,
        .table th {

            vertical-align: middle;

        }


        .total-row {

            background-color: #fff5f3;

            font-weight: bold;

            color: #765044;

        }


        .btn-cokelat {

            background-color: #765044;

            border: none;

            color: white;

            border-radius: 8px;

            padding: 10px 18px;

        }


        .btn-cokelat:hover {

            background-color: #5f4036;

            color: white;

        }


        .btn-pink {

            background-color: #f7d9dc;

            border: none;

            color: #6e4c42;

            border-radius: 8px;

            padding: 10px 18px;

        }


        .btn-pink:hover {

            background-color: #f3c3ca;

            color: #6e4c42;

        }


        @media print {

            .tombol {

                display: none;

            }


            body {

                background: white;

            }


            .card-pesanan {

                box-shadow: none;

                border: none;

            }

        }

    </style>

</head>


<body>


<div class="container-pesanan">


    <div class="card-pesanan">


        <!-- =========================
             HEADER
        ========================== -->

        <div class="header-pesanan">

            <h2>
                Dina's Cake House
            </h2>

            <p>
                Detail Pesanan
            </p>

        </div>



        <!-- =========================
             INFORMASI TRANSAKSI
        ========================== -->

        <div class="info">

            <p>

                <strong>
                    ID Transaksi:
                </strong>

                <?= htmlspecialchars(
                    $transaksi['id_transaksi']
                ) ?>

            </p>


            <p>

                <strong>
                    ID Pelanggan:
                </strong>

                <?= htmlspecialchars(
                    $transaksi['id_pelanggan']
                ) ?>

            </p>


            <p>

                <strong>
                    Tanggal:
                </strong>

                <?= htmlspecialchars(
                    $transaksi['tanggal']
                ) ?>

            </p>

        </div>



        <!-- =========================
             DETAIL PRODUK
        ========================== -->

        <div class="table-responsive">

            <table class="table">


                <thead>

                    <tr>

                        <th>
                            No
                        </th>

                        <th>
                            Produk
                        </th>

                        <th>
                            Harga
                        </th>

                        <th>
                            Jumlah
                        </th>

                        <th>
                            Subtotal
                        </th>

                    </tr>

                </thead>


                <tbody>


                <?php

                $no = 1;

                while (
                    $detail =
                    mysqli_fetch_assoc($query_detail)
                ):

                    $subtotal =
                        $detail['harga']
                        *
                        $detail['jumlah'];

                ?>


                    <tr>


                        <td>

                            <?= $no++ ?>

                        </td>


                        <td>

                            <?= htmlspecialchars(
                                $detail['nama']
                            ) ?>

                        </td>


                        <td>

                            Rp

                            <?= number_format(
                                $detail['harga'],
                                0,
                                ',',
                                '.'
                            ) ?>

                        </td>


                        <td>

                            <?= $detail['jumlah'] ?>

                        </td>


                        <td>

                            Rp

                            <?= number_format(
                                $subtotal,
                                0,
                                ',',
                                '.'
                            ) ?>

                        </td>


                    </tr>


                <?php endwhile; ?>


                </tbody>


                <tfoot>


                    <tr class="total-row">


                        <td
                            colspan="4"
                            class="text-end"
                        >

                            Total Harga

                        </td>


                        <td>

                            Rp

                            <?= number_format(
                                $transaksi['total_harga'],
                                0,
                                ',',
                                '.'
                            ) ?>

                        </td>


                    </tr>


                </tfoot>


            </table>

        </div>



        <!-- =========================
             TOMBOL
        ========================== -->

        <div class="tombol text-center mt-4">


            <a
                href="index.php"
                class="btn btn-cokelat"
            >

                Kembali ke Beranda

            </a>


            <button
                onclick="window.print()"
                class="btn btn-pink"
            >

                Cetak Pesanan

            </button>


        </div>


    </div>

</div>


</body>

</html>