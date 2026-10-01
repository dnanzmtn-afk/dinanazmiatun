<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "koneksi.php";
session_start();

if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}

/* =========================
   HAPUS PRODUK
========================= */
if (isset($_GET['hapus'])) {

    $id = intval($_GET['hapus']);

    $cek = mysqli_query($koneksi, "SELECT poto FROM tb_produk WHERE id='$id'");
    $produk = mysqli_fetch_assoc($cek);

    $hapus = mysqli_query($koneksi, "DELETE FROM tb_produk WHERE id='$id'");

    if ($hapus) {

        // Hapus file gambar jika ada
        if (!empty($produk['poto']) && file_exists("img/" . $produk['poto'])) {
            unlink("img/" . $produk['poto']);
        }

        $_SESSION['swal'] = [
            'title' => 'Berhasil dihapus!',
            'text' => 'Produk berhasil dihapus.',
            'icon' => 'success'
        ];

    } else {

        $_SESSION['swal'] = [
            'title' => 'Gagal menghapus!',
            'text' => 'Produk tidak dapat dihapus.',
            'icon' => 'error'
        ];
    }

    header('Location: products.php');
    exit;
}


/* =========================
   TAMBAH / EDIT PRODUK
========================= */
if (isset($_POST['simpan'])) {

    $id         = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $nama       = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $harga      = intval($_POST['harga']);
    $stok       = intval($_POST['stok']);
    $kategori   = intval($_POST['kategori']);
    $deskripsi  = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);

    $fotoBaru = $_FILES['poto']['name'] ?? '';
    $fotoLama = $_POST['foto_lama'] ?? '';

    /* UPLOAD FOTO */
    $namaFoto = $fotoLama;

    if (!empty($fotoBaru)) {

        $folder = "img/";

        if (!is_dir($folder)) {
            mkdir($folder, 0777, true);
        }

        $ext = strtolower(pathinfo($fotoBaru, PATHINFO_EXTENSION));

        $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

        if (!in_array($ext, $allowed)) {

            $_SESSION['swal'] = [
                'title' => 'Format tidak sesuai!',
                'text' => 'Gunakan JPG, JPEG, PNG, WEBP atau GIF.',
                'icon' => 'error'
            ];

            header('Location: products.php');
            exit;
        }

        $namaFoto = time() . '_' . basename($fotoBaru);

        move_uploaded_file(
            $_FILES['poto']['tmp_name'],
            $folder . $namaFoto
        );

        // Hapus foto lama saat edit
        if (
            $id > 0 &&
            !empty($fotoLama) &&
            file_exists($folder . $fotoLama)
        ) {
            unlink($folder . $fotoLama);
        }
    }


    /* EDIT */
    if ($id > 0) {

        $query = mysqli_query(
            $koneksi,
            "UPDATE tb_produk SET
                nama='$nama',
                harga='$harga',
                stok='$stok',
                id_kategori='$kategori',
                deskripsi='$deskripsi',
                poto='$namaFoto'
             WHERE id='$id'"
        );

        if ($query) {

            $_SESSION['swal'] = [
                'title' => 'Berhasil diedit!',
                'text' => 'Data produk berhasil diperbarui.',
                'icon' => 'success'
            ];

        } else {

            $_SESSION['swal'] = [
                'title' => 'Gagal mengedit!',
                'text' => 'Data produk tidak dapat diperbarui.',
                'icon' => 'error'
            ];
        }

    }

    /* TAMBAH */
    else {

        $query = mysqli_query(
            $koneksi,
            "INSERT INTO tb_produk
            (nama, harga, stok, id_kategori, deskripsi, poto)
            VALUES
            ('$nama', '$harga', '$stok', '$kategori', '$deskripsi', '$namaFoto')"
        );

        if ($query) {

            $_SESSION['swal'] = [
                'title' => 'Produk ditambahkan!',
                'text' => 'Data produk berhasil disimpan.',
                'icon' => 'success'
            ];

        } else {

            $_SESSION['swal'] = [
                'title' => 'Gagal menambahkan!',
                'text' => 'Data produk tidak dapat disimpan.',
                'icon' => 'error'
            ];
        }
    }

    header('Location: products.php');
    exit;
}


/* =========================
   DATA EDIT
========================= */

$edit = false;

$id_edit = 0;
$nama_edit = '';
$harga_edit = '';
$stok_edit = '';
$kategori_edit = '';
$deskripsi_edit = '';
$foto_edit = '';

if (isset($_GET['edit'])) {

    $id_edit = intval($_GET['edit']);

    $queryEdit = mysqli_query(
        $koneksi,
        "SELECT * FROM tb_produk WHERE id='$id_edit'"
    );

    if ($dataEdit = mysqli_fetch_assoc($queryEdit)) {

        $edit = true;

        $nama_edit      = $dataEdit['nama'];
        $harga_edit     = $dataEdit['harga'];
        $stok_edit      = $dataEdit['stok'];
        $kategori_edit  = $dataEdit['id_kategori'];
        $deskripsi_edit = $dataEdit['deskripsi'];
        $foto_edit      = $dataEdit['poto'];
    }
}


/* =========================
   KATEGORI
========================= */

$kategori = mysqli_query(
    $koneksi,
    "SELECT * FROM tb_kategori ORDER BY nama_kategori ASC"
);


/* =========================
   PRODUK
========================= */

$produk = mysqli_query(
    $koneksi,
    "SELECT 
        p.*,
        k.nama_kategori
     FROM tb_produk p
     LEFT JOIN tb_kategori k
        ON p.id_kategori = k.id_kategori
     ORDER BY p.id DESC"
);
?>

<!doctype html>
<html lang="id" data-bs-theme="dark">

<head>

    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Products - Dashboard</title>

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
   NAVBAR / SIDEBAR
===================================== */

.sidebar {
    background-color: #6e4c42 !important;
    min-height: 100vh;
}

.sidebar .nav-link {
    color: #fffaf3 !important;
    font-size: .875rem;
    font-weight: 500;
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
   JUDUL
===================================== */

.page-title {
    color: #6e4c42 !important;
    margin-bottom: 25px;
}


/* =====================================
   FORM TAMBAH / EDIT PRODUK
===================================== */

.form-card {
    background-color: #f7d9dc !important;
    color: #6e4c42 !important;
    border-radius: 12px;
    padding: 25px;
    margin-bottom: 30px;
}

.form-card h4,
.form-card label {
    color: #6e4c42 !important;
}


/* =====================================
   INPUT
===================================== */

.form-control,
.form-select {
    background-color: #ffffff !important;
    color: #6e4c42 !important;
    border: 1px solid #eaded5 !important;
}

.form-control:focus,
.form-select:focus {
    border-color: #d8aeb5 !important;
    box-shadow: 0 0 0 .2rem rgba(247, 217, 220, .5) !important;
}


/* =====================================
   TOMBOL UTAMA
===================================== */

.btn-primary {
    background-color: #6e4c42 !important;
    border-color: #6e4c42 !important;
    color: #ffffff !important;
}

.btn-primary:hover {
    background-color: #543932 !important;
    border-color: #543932 !important;
}


/* =====================================
   TOMBOL EDIT
===================================== */

.btn-warning {
    background-color: #f7d9dc !important;
    border-color: #f7d9dc !important;
    color: #6e4c42 !important;
}

.btn-warning:hover {
    background-color: #efc4ca !important;
    border-color: #efc4ca !important;
    color: #6e4c42 !important;
}


/* =====================================
   TOMBOL HAPUS
===================================== */

.btn-danger {
    background-color: #c96f6f !important;
    border-color: #c96f6f !important;
    color: #ffffff !important;
}


/* =====================================
   TOMBOL BATAL
===================================== */

.btn-secondary {
    background-color: #eaded5 !important;
    border-color: #eaded5 !important;
    color: #6e4c42 !important;
}


/* =====================================
   TABEL PRODUK
===================================== */

.table-card {
    background-color: #6e4c42 !important;
    color: #ffffff !important;
    border-radius: 12px;
    padding: 20px;
}

.table-card h4 {
    color: #ffffff !important;
}


/* Header tabel */
.table thead th {
    background-color: #f7d9dc !important;
    color: #6e4c42 !important;
    border-color: #eaded5 !important;
}


/* Isi tabel */
.table tbody td {
    color: #6e4c42 !important;
    background-color: #ffffff !important;
    border-color: #eaded5 !important;
}


/* Hover tabel */
.table-hover tbody tr:hover td {
    background-color: #fff1f3 !important;
}


/* =====================================
   FOTO PRODUK
===================================== */

.product-image {
    width: 70px;
    height: 70px;
    object-fit: cover;
    border-radius: 8px;
    border: 2px solid #f7d9dc;
}


/* =====================================
   BADGE JUMLAH PRODUK
===================================== */

.badge.bg-secondary {
    background-color: #f7d9dc !important;
    color: #6e4c42 !important;
}


/* =====================================
   GARIS
===================================== */

.border-bottom {
    border-color: #eaded5 !important;
}


/* =====================================
   LINK
===================================== */

a {
    color: #6e4c42;
}

a:hover {
    color: #543932;
}


/* =====================================
   RESPONSIVE
===================================== */

@media (max-width: 767px) {

    .sidebar {
        min-height: auto;
    }

    .form-card,
    .table-card {
        padding: 15px;
    }

}

</style>

    <?php include 'sweetalert.php'; ?>

</head>

<body>

<div class="container-fluid">

    <div class="row">

        <!-- SIDEBAR -->

        <nav class="col-md-3 col-lg-2 d-md-block bg-dark sidebar">

            <div class="position-sticky pt-3">

                <h5 class="text-white px-3 mb-4">
                    Company name
                </h5>

                <ul class="nav flex-column">

                    <li class="nav-item">

                        <a
                            class="nav-link d-flex align-items-center gap-2"
                            href="dashboard.php"
                        >
                            🏠 Dashboard
                        </a>

                    </li>

                    <li class="nav-item">

                        <a
                            class="nav-link d-flex align-items-center gap-2"
                            href="orders.php"
                        >
                            📄 Orders
                        </a>

                    </li>

                    <li class="nav-item">

                        <a
                            class="nav-link active d-flex align-items-center gap-2"
                            href="products.php"
                        >
                            🛒 Products
                        </a>

                    </li>

                    <li class="nav-item">

                        <a
                            class="nav-link d-flex align-items-center gap-2"
                            href="customers.php"
                        >
                            👥 Customers
                        </a>

                    </li>

                </ul>

                <hr class="my-3">

                <ul class="nav flex-column">

                    <li class="nav-item">

                        <a
                            class="nav-link d-flex align-items-center gap-2"
                            href="logout.php"
                        >
                            🚪 Sign out
                        </a>

                    </li>

                </ul>

            </div>

        </nav>


        <!-- CONTENT -->

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">

            <div
                class="d-flex justify-content-between flex-wrap flex-md-nowrap
                align-items-center pt-3 pb-2 mb-3 border-bottom"
            >

                <h1 class="h2 page-title">
                    Products
                </h1>

                <a
                    href="products.php"
                    class="btn btn-primary"
                >
                    + Tambah Produk
                </a>

            </div>


            <!-- FORM -->

            <div class="form-card">

                <h4 class="mb-4">

                    <?= $edit ? 'Edit Produk' : 'Tambah Produk' ?>

                </h4>

                <form
                    method="POST"
                    enctype="multipart/form-data"
                >

                    <input
                        type="hidden"
                        name="id"
                        value="<?= $id_edit ?>"
                    >

                    <input
                        type="hidden"
                        name="foto_lama"
                        value="<?= htmlspecialchars($foto_edit) ?>"
                    >


                    <div class="row g-3">

                        <!-- NAMA -->

                        <div class="col-md-6">

                            <label class="form-label">
                                Nama Produk
                            </label>

                            <input
                                type="text"
                                name="nama"
                                class="form-control"
                                value="<?= htmlspecialchars($nama_edit) ?>"
                                placeholder="Contoh: Donat Matcha"
                                required
                            >

                        </div>


                        <!-- HARGA -->

                        <div class="col-md-3">

                            <label class="form-label">
                                Harga
                            </label>

                            <input
                                type="number"
                                name="harga"
                                class="form-control"
                                value="<?= $harga_edit ?>"
                                placeholder="100000"
                                required
                            >

                        </div>


                        <!-- STOK -->

                        <div class="col-md-3">

                            <label class="form-label">
                                Stok
                            </label>

                            <input
                                type="number"
                                name="stok"
                                class="form-control"
                                value="<?= $stok_edit ?>"
                                placeholder="10"
                                required
                            >

                        </div>


                        <!-- KATEGORI -->

                        <div class="col-md-6">

                            <label class="form-label">
                                Kategori
                            </label>

                            <select
                                name="kategori"
                                class="form-select"
                                required
                            >

                                <option value="">
                                    -- Pilih Kategori --
                                </option>

                                <?php while ($k = mysqli_fetch_assoc($kategori)) { ?>

                                    <option
                                        value="<?= $k['id_kategori'] ?>"
                                        <?= ($kategori_edit == $k['id_kategori']) ? 'selected' : '' ?>
                                    >

                                        <?= htmlspecialchars($k['nama_kategori']) ?>

                                    </option>

                                <?php } ?>

                            </select>

                        </div>


                        <!-- DESKRIPSI -->

                        <div class="col-md-6">

                            <label class="form-label">
                                Deskripsi
                            </label>

                            <input
                                type="text"
                                name="deskripsi"
                                class="form-control"
                                value="<?= htmlspecialchars($deskripsi_edit) ?>"
                                placeholder="Deskripsi produk"
                            >

                        </div>


                        <!-- FOTO -->

                        <div class="col-md-8">

                            <label class="form-label">
                                Foto Produk
                            </label>

                            <input
                                type="file"
                                name="poto"
                                class="form-control"
                                accept="image/*"
                            >

                        </div>


                        <!-- PREVIEW -->

                        <?php if (!empty($foto_edit)) { ?>

                            <div class="col-md-4">

                                <label class="form-label">
                                    Foto Saat Ini
                                </label>

                                <br>

                                <img
                                    src="img/<?= htmlspecialchars($foto_edit) ?>"
                                    class="product-image"
                                >

                            </div>

                        <?php } ?>


                        <!-- BUTTON -->

                        <div class="col-12">

                            <button
                                type="submit"
                                name="simpan"
                                class="btn btn-primary"
                            >

                                <?= $edit ? 'Simpan Perubahan' : 'Tambah Produk' ?>

                            </button>

                            <?php if ($edit) { ?>

                                <a
                                    href="products.php"
                                    class="btn btn-secondary"
                                >
                                    Batal
                                </a>

                            <?php } ?>

                        </div>

                    </div>

                </form>

            </div>


            <!-- TABLE -->

            <div class="table-card">

                <div class="d-flex justify-content-between align-items-center mb-3">

                    <h4 class="mb-0">
                        Daftar Produk
                    </h4>

                    <span class="badge bg-secondary">
                        <?= mysqli_num_rows($produk) ?> Produk
                    </span>

                </div>


                <div class="table-responsive">

                    <table class="table table-striped table-hover align-middle">

                        <thead>

                            <tr>

                                <th>No</th>

                                <th>Foto</th>

                                <th>Nama</th>

                                <th>Harga</th>

                                <th>Stok</th>

                                <th>Kategori</th>

                                <th>Deskripsi</th>

                                <th>Aksi</th>

                            </tr>

                        </thead>


                        <tbody>

                        <?php

                        $no = 1;

                        while ($p = mysqli_fetch_assoc($produk)) {

                        ?>

                            <tr>

                                <td>
                                    <?= $no++ ?>
                                </td>


                                <td>

                                    <?php if (!empty($p['poto'])) { ?>

                                        <img
                                            src="img/<?= htmlspecialchars($p['poto']) ?>"
                                            class="product-image"
                                            alt="<?= htmlspecialchars($p['nama']) ?>"
                                        >

                                    <?php } else { ?>

                                        <span class="text-secondary">
                                            Tidak ada foto
                                        </span>

                                    <?php } ?>

                                </td>


                                <td>
                                    <strong>
                                        <?= htmlspecialchars($p['nama']) ?>
                                    </strong>
                                </td>


                                <td>
                                    Rp <?= number_format($p['harga'], 0, ',', '.') ?>
                                </td>


                                <td>
                                    <?= $p['stok'] ?>
                                </td>


                                <td>
                                    <?= htmlspecialchars($p['nama_kategori'] ?? '-') ?>
                                </td>


                                <td>
                                    <?= htmlspecialchars($p['deskripsi']) ?>
                                </td>


                                <td>

                                    <div class="d-flex gap-1">

                                        <a
                                            href="products.php?edit=<?= $p['id'] ?>"
                                            class="btn btn-sm btn-warning"
                                        >
                                            Edit
                                        </a>

                                        <a
                                            href="products.php?hapus=<?= $p['id'] ?>"
                                            class="btn btn-sm btn-danger"
                                            onclick="return konfirmasiHapus(event, this.href)"
                                        >
                                            Hapus
                                        </a>

                                    </div>

                                </td>

                            </tr>

                        <?php } ?>

                        </tbody>

                    </table>

                </div>

            </div>

        </main>

    </div>

</div>


<script src="assets/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script>

function konfirmasiHapus(event, url) {

    event.preventDefault();

    Swal.fire({

        title: 'Hapus produk?',

        text: 'Data produk yang dihapus tidak dapat dikembalikan.',

        icon: 'warning',

        showCancelButton: true,

        confirmButtonText: 'Ya, hapus!',

        cancelButtonText: 'Batal',

        reverseButtons: true

    }).then((result) => {

        if (result.isConfirmed) {

            window.location.href = url;

        }

    });

    return false;
}

</script>


<?php

if (isset($_SESSION['swal'])) {

    $swal = $_SESSION['swal'];

    unset($_SESSION['swal']);

?>

<script>

Swal.fire({

    title: <?= json_encode($swal['title']) ?>,

    text: <?= json_encode($swal['text']) ?>,

    icon: <?= json_encode($swal['icon']) ?>,

    confirmButtonText: 'OK'

});

</script>

<?php } ?>


</body>

</html>