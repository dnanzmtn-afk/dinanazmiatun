<?php
include 'koneksi.php';
session_start();


/* =========================
   CEK LOGIN
========================= */

if (!isset($_SESSION['id_user'])) {

    $_SESSION['swal'] = [
        'title' => 'Belum Login!',
        'text' => 'Silakan login terlebih dahulu.',
        'icon' => 'warning'
    ];

    header('Location: login.php');
    exit;
}


/* =========================
   HAPUS TRANSAKSI
========================= */

if (isset($_POST['hapus_transaksi'])) {

    $id_transaksi = $_POST['id_transaksi'];
    $id_pelanggan = $_SESSION['id_user'];


    /* =========================
       HAPUS DETAIL TRANSAKSI
    ========================== */

    $hapus_detail = mysqli_query(
        $koneksi,
        "DELETE FROM tb_detail
         WHERE id_transaksi = '$id_transaksi'"
    );


    if (!$hapus_detail) {

        $_SESSION['swal'] = [
            'title' => 'Gagal!',
            'text' => 'Detail transaksi gagal dihapus.',
            'icon' => 'error'
        ];

        header('Location: riwayat transaksi.php');
        exit;
    }


    /* =========================
       HAPUS TRANSAKSI
    ========================== */

    $hapus_transaksi = mysqli_query(
        $koneksi,
        "DELETE FROM tb_transaksi
         WHERE id_transaksi = '$id_transaksi'
         AND id_pelanggan = '$id_pelanggan'"
    );


    if ($hapus_transaksi) {

        $_SESSION['swal'] = [
            'title' => 'Berhasil!',
            'text' => 'Transaksi berhasil dihapus.',
            'icon' => 'success'
        ];

    } else {

        $_SESSION['swal'] = [
            'title' => 'Gagal!',
            'text' => 'Transaksi gagal dihapus.',
            'icon' => 'error'
        ];
    }


    header('Location: riwayat transaksi.php');
    exit;
}


/* =========================
   AMBIL ID PELANGGAN
========================= */

$id_pelanggan = $_SESSION['id_user'];


/* =========================
   AMBIL DATA TRANSAKSI
========================= */

$query = mysqli_query(
    $koneksi,
    "SELECT *
     FROM tb_transaksi
     WHERE id_pelanggan = '$id_pelanggan'
     ORDER BY id_transaksi DESC"
);


/* =========================
   CEK QUERY
========================= */

if (!$query) {
    die("Query gagal: " . mysqli_error($koneksi));
}

?>


<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Riwayat Transaksi - Dina's Cake House
    </title>


    <!-- BOOTSTRAP -->

    <link
        href="assets/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >


    <!-- SWEET ALERT -->

    <script
        src="https://cdn.jsdelivr.net/npm/sweetalert2@11">
    </script>


    <style>

        body {

            background-color: #fffaf3;

            color: #6e4c42;

            font-family: Arial, sans-serif;

        }


        .container {

            max-width: 1100px;

        }


        .judul {

            color: #6e4c42;

            font-weight: bold;

        }


        .card {

            border: none;

            border-radius: 15px;

            box-shadow:
                0 5px 20px
                rgba(0,0,0,0.08);

        }


        .table {

            vertical-align: middle;

        }


        .table thead th {

            background-color: #f7d9dc;

            color: #6e4c42;

            text-align: center;

        }


        .table tbody td {

            text-align: center;

        }


        .btn-detail {

            background-color: #6e4c42;

            color: white;

            border: none;

        }


        .btn-detail:hover {

            background-color: #543932;

            color: white;

        }


        .btn-kembali {

            background-color: #6e4c42;

            color: white;

            border: none;

        }


        .btn-kembali:hover {

            background-color: #543932;

            color: white;

        }


        .btn-hapus {

            background-color: #dc3545;

            color: white;

            border: none;

        }


        .btn-hapus:hover {

            background-color: #bb2d3b;

            color: white;

        }

    </style>

</head>


<body>


<div class="container py-5">


    <!-- =========================
         JUDUL
    ========================== -->

    <div class="text-center mb-4">

        <h2 class="judul">

            Riwayat Transaksi

        </h2>


        <p class="text-muted">

            Daftar transaksi yang pernah kamu lakukan.

        </p>

    </div>



    <!-- =========================
         CARD TRANSAKSI
    ========================== -->

    <div class="card p-4">


        <?php if (mysqli_num_rows($query) > 0) { ?>


            <div class="table-responsive">

                <table class="table table-bordered table-hover">


                    <thead>

                        <tr>

                            <th>
                                No
                            </th>

                            <th>
                                ID Transaksi
                            </th>

                            <th>
                                Tanggal
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

                    $no = 1;

                    while (
                        $data =
                        mysqli_fetch_assoc($query)
                    ) {

                    ?>


                        <tr>


                            <!-- NO -->

                            <td>

                                <?= $no++; ?>

                            </td>



                            <!-- ID TRANSAKSI -->

                            <td>

                                #<?= htmlspecialchars(
                                    $data['id_transaksi']
                                ); ?>

                            </td>



                            <!-- TANGGAL -->

                            <td>

                                <?= date(
                                    'd-m-Y',
                                    strtotime(
                                        $data['tanggal']
                                    )
                                ); ?>

                            </td>



                            <!-- TOTAL -->

                            <td>

                                Rp

                                <?= number_format(
                                    $data['total_harga'],
                                    0,
                                    ',',
                                    '.'
                                ); ?>

                            </td>



                            <!-- AKSI -->

                            <td>


                                <!-- CETAK INVOICE -->

                                <a
                                    href="cetak.php?id=<?= $data['id_transaksi']; ?>"
                                    target="_blank"
                                    class="btn btn-sm btn-detail"
                                >

                                    🧾 Cetak Invoice

                                </a>


                                <!-- HAPUS -->

                                <button
                                    type="button"
                                    class="btn btn-sm btn-hapus"
                                    onclick="konfirmasiHapus(<?= $data['id_transaksi']; ?>)"
                                >

                                    🗑️ Hapus

                                </button>


                            </td>


                        </tr>


                    <?php

                    }

                    ?>


                    </tbody>

                </table>

            </div>


        <?php } else { ?>


            <!-- =========================
                 BELUM ADA TRANSAKSI
            ========================== -->

            <div class="text-center py-5">


                <h5>

                    Belum Ada Transaksi

                </h5>


                <p class="text-muted">

                    Kamu belum melakukan transaksi.

                </p>


                <a
                    href="index.php"
                    class="btn btn-kembali"
                >

                    Belanja Sekarang

                </a>


            </div>


        <?php } ?>


    </div>



    <!-- =========================
         TOMBOL KEMBALI
    ========================== -->

    <div class="mt-4">


    <a href="index.php"
       class="btn btn-kembali">

    ← Kembali ke Halaman Awal

    </a>


    </div>


</div>



<!-- =========================
     BOOTSTRAP JS
========================== -->

<script
    src="assets/dist/js/bootstrap.bundle.min.js">
</script>



<!-- =========================
     SWEET ALERT HAPUS
========================== -->

<script>

function konfirmasiHapus(id) {

    Swal.fire({

        title: 'Hapus Transaksi?',

        text: 'Transaksi yang dihapus tidak dapat dikembalikan.',

        icon: 'warning',

        position: 'center',

        showCancelButton: true,

        confirmButtonText: 'Ya, Hapus',

        cancelButtonText: 'Batal',

        confirmButtonColor: '#dc3545',

        cancelButtonColor: '#6e4c42'

    }).then((result) => {


        if (result.isConfirmed) {


            // Membuat form secara otomatis

            const form =
                document.createElement('form');


            form.method = 'POST';

            form.action = 'riwayat transaksi.php';



            // Input untuk aksi hapus

            const inputAksi =
                document.createElement('input');

            inputAksi.type = 'hidden';

            inputAksi.name = 'hapus_transaksi';

            inputAksi.value = '1';



            // Input ID transaksi

            const inputId =
                document.createElement('input');

            inputId.type = 'hidden';

            inputId.name = 'id_transaksi';

            inputId.value = id;



            form.appendChild(inputAksi);

            form.appendChild(inputId);


            document.body.appendChild(form);


            form.submit();

        }

    });

}

</script>



<!-- =========================
     SWEET ALERT HASIL AKSI
========================== -->

<?php

if (isset($_SESSION['swal'])) {

    $swal = $_SESSION['swal'];

    unset($_SESSION['swal']);

?>

<script>

Swal.fire({

    title: <?= json_encode($swal['title']); ?>,

    text: <?= json_encode($swal['text']); ?>,

    icon: <?= json_encode($swal['icon']); ?>,

    position: 'center',

    confirmButtonColor: '#6e4c42'

});

</script>

<?php } ?>


</body>

</html>