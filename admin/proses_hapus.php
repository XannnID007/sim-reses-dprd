<?php
include "../config/db.php";

// Pastikan ada parameter ID yang dikirim
if(isset($_GET['id'])){
    $id = $_GET['id'];
?>
<!DOCTYPE html>
<html>
<head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body>

<?php
    // Proses Hapus Data
    $query = mysqli_query($koneksi, "DELETE FROM berita_reses WHERE id_berita = '$id'");

    if($query){
        echo "<script>
            Swal.fire({
                title: 'Terhapus!',
                text: 'Laporan reses telah berhasil dihapus dari sistem.',
                icon: 'success',
                confirmButtonColor: '#d33',
                confirmButtonText: 'Oke',
                customClass: { popup: 'rounded-4' }
            }).then((result) => {
                window.location='berita_admin.php';
            });
        </script>";
    } else {
        $error_msg = mysqli_error($koneksi);
        echo "<script>
            Swal.fire({
                title: 'Gagal!',
                text: 'Data gagal dihapus: $error_msg',
                icon: 'error',
                confirmButtonColor: '#6c757d'
            }).then((result) => {
                window.location='berita_admin.php';
            });
        </script>";
    }
?>

</body>
</html>
<?php
} else {
    header("Location: berita_admin.php");
}
?>