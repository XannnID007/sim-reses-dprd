<?php
include "../config/db.php";

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
    // Ubah status ke 'published'
    $query = mysqli_query($koneksi, "UPDATE berita_reses SET status = 'published' WHERE id_berita = '$id'");

    if($query){
        echo "<script>
            Swal.fire({
                title: 'Berhasil Terbit!',
                text: 'Laporan reses sekarang dapat dilihat oleh publik.',
                icon: 'success',
                confirmButtonColor: '#198754',
                confirmButtonText: 'Sip, Mantap!',
                customClass: { popup: 'rounded-4' }
            }).then((result) => {
                window.location='berita_admin.php';
            });
        </script>";
    } else {
        $error_msg = mysqli_error($koneksi);
        echo "<script>
            Swal.fire({
                title: 'Gagal Publikasi!',
                text: 'Terjadi kesalahan sistem: $error_msg',
                icon: 'error',
                confirmButtonColor: '#dc3545'
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