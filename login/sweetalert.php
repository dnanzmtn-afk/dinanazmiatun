<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php if (isset($_SESSION['swal'])):
    $swal = $_SESSION['swal'];
    unset($_SESSION['swal']);
?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    Swal.fire({
        title: <?= json_encode($swal['title'] ?? '', JSON_UNESCAPED_UNICODE) ?>,
        text: <?= json_encode($swal['text'] ?? '', JSON_UNESCAPED_UNICODE) ?>,
        icon: <?= json_encode($swal['icon'] ?? 'info') ?>,
        position: 'center',
        showConfirmButton: true,
        confirmButtonText: 'OK',
        confirmButtonColor: '#6e4c42',
        timer: <?= isset($swal['timer']) ? (int)$swal['timer'] : 2500 ?>,
        timerProgressBar: true
    });
});
</script>
<?php endif; ?>
