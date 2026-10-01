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
$result = mysqli_query($koneksi, "SELECT * FROM tb_user WHERE id='$id_user' LIMIT 1");
$user = $result ? mysqli_fetch_assoc($result) : null;

if (!$user) {
    session_destroy();
    header('Location: login.php');
    exit;
}

if (isset($_POST['update_profil'])) {
    $username = trim($_POST['username'] ?? '');
    if ($username === '') {
        $_SESSION['swal'] = ['title'=>'Gagal!', 'text'=>'Username tidak boleh kosong.', 'icon'=>'error'];
    } else {
        $username = mysqli_real_escape_string($koneksi, $username);
        $cek = mysqli_query($koneksi, "SELECT id FROM tb_user WHERE username='$username' AND id!='$id_user' LIMIT 1");
        if (mysqli_num_rows($cek) > 0) {
            $_SESSION['swal'] = ['title'=>'Username sudah dipakai!', 'text'=>'Gunakan username lain.', 'icon'=>'warning'];
        } elseif (mysqli_query($koneksi, "UPDATE tb_user SET username='$username' WHERE id='$id_user'")) {
            $_SESSION['swal'] = ['title'=>'Berhasil!', 'text'=>'Profil berhasil diperbarui.', 'icon'=>'success'];
            $_SESSION['username'] = $username;
        } else {
            $_SESSION['swal'] = ['title'=>'Gagal!', 'text'=>'Profil gagal diperbarui.', 'icon'=>'error'];
        }
    }
    header('Location: profil.php');
    exit;
}

if (isset($_POST['ubah_password'])) {
    $lama = $_POST['password_lama'] ?? '';
    $baru = $_POST['password_baru'] ?? '';
    $konfirmasi = $_POST['konfirmasi_password'] ?? '';

    if ($lama !== $user['password']) {
        $_SESSION['swal'] = ['title'=>'Gagal!', 'text'=>'Password lama salah.', 'icon'=>'error'];
    } elseif (strlen($baru) < 6) {
        $_SESSION['swal'] = ['title'=>'Gagal!', 'text'=>'Password baru minimal 6 karakter.', 'icon'=>'warning'];
    } elseif ($baru !== $konfirmasi) {
        $_SESSION['swal'] = ['title'=>'Gagal!', 'text'=>'Konfirmasi password tidak sama.', 'icon'=>'error'];
    } else {
        $baru = mysqli_real_escape_string($koneksi, $baru);
        if (mysqli_query($koneksi, "UPDATE tb_user SET password='$baru' WHERE id='$id_user'")) {
            $_SESSION['swal'] = ['title'=>'Berhasil!', 'text'=>'Password berhasil diubah.', 'icon'=>'success'];
        } else {
            $_SESSION['swal'] = ['title'=>'Gagal!', 'text'=>'Password gagal diubah.', 'icon'=>'error'];
        }
    }
    header('Location: profil.php');
    exit;
}

$result = mysqli_query($koneksi, "SELECT * FROM tb_user WHERE id='$id_user' LIMIT 1");
$user = mysqli_fetch_assoc($result);
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Profil Saya</title>
    <link href="assets/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body{background:#f5f5f5}.profile{max-width:760px;margin:50px auto}.card{border:0;border-radius:18px;box-shadow:0 8px 25px #0001}.head{background:#212529;color:white;border-radius:18px 18px 0 0;padding:30px;text-align:center}.avatar{width:80px;height:80px;border-radius:50%;background:white;color:#212529;display:flex;align-items:center;justify-content:center;margin:auto;font-size:34px;font-weight:bold}.body{padding:30px}.form-control{border-radius:10px}.btn{border-radius:10px}
    </style>
</head>
<body>
<div class="container profile">
    <div class="card">
        <div class="head">
            <div class="avatar"><?= strtoupper(substr($user['username'],0,1)); ?></div>
            <h3 class="mt-3 mb-1">Profil Saya</h3>
            <p class="mb-0"><?= htmlspecialchars($user['username']); ?></p>
        </div>
        <div class="body">
            <h5 class="mb-3">Informasi Akun</h5>
            <form method="post">
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($user['username']); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Role</label>
                    <input type="text" class="form-control" value="<?= htmlspecialchars($user['role']); ?>" readonly>
                </div>
                <button name="update_profil" class="btn btn-primary">Simpan Perubahan</button>
            </form>

            <hr class="my-4">
            <h5 class="mb-3">Ganti Password</h5>
            <form method="post">
                <div class="mb-3"><label class="form-label">Password Lama</label><input type="password" name="password_lama" class="form-control" required></div>
                <div class="mb-3"><label class="form-label">Password Baru</label><input type="password" name="password_baru" class="form-control" minlength="6" required></div>
                <div class="mb-3"><label class="form-label">Konfirmasi Password Baru</label><input type="password" name="konfirmasi_password" class="form-control" minlength="6" required></div>
                <button name="ubah_password" class="btn btn-warning">Ubah Password</button>
            </form>

            <hr class="my-4">
            <a href="index.php" class="btn btn-secondary">Beranda</a>
            <a href="keranjang.php" class="btn btn-success">Keranjang</a>
            <button type="button" onclick="logoutConfirm()" class="btn btn-danger">Logout</button>
        </div>
    </div>
</div>
<script>
function logoutConfirm(){
    Swal.fire({title:'Logout?',text:'Yakin ingin keluar?',icon:'question',showCancelButton:true,confirmButtonText:'Ya, Logout',cancelButtonText:'Batal',confirmButtonColor:'#dc3545'}).then(r=>{if(r.isConfirmed) location.href='logout.php';});
}
<?php if(isset($_SESSION['swal'])): $swal=$_SESSION['swal']; unset($_SESSION['swal']); ?>
Swal.fire({title:<?=json_encode($swal['title'])?>,text:<?=json_encode($swal['text'])?>,icon:<?=json_encode($swal['icon'])?>});
<?php endif; ?>
</script>
</body>
</html>
