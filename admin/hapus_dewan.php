<?php 
include '../config/db.php'; 

// Variabel untuk menangkap status proses
$status = "";
$pesan_error = "";

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($koneksi, $_GET['id']);
    
    // 1. Ambil data dewan untuk mendapatkan nama file foto
    $query_foto = mysqli_query($koneksi, "SELECT foto FROM dewan WHERE id_dewan = '$id'");
    $data_dewan = mysqli_fetch_assoc($query_foto);
    
    if ($data_dewan) {
        // 2. Hapus file fisik foto
        $nama_foto = $data_dewan['foto'];
        if (!empty($nama_foto)) {
            $path_foto = "../upload/dewan/" . $nama_foto;
            if (file_exists($path_foto)) {
                unlink($path_foto);
            }
        }
        
        // 3. Hapus data dari database
        $delete = mysqli_query($koneksi, "DELETE FROM dewan WHERE id_dewan = '$id'");
        
        if ($delete) {
            $status = "success";
        } else {
            $status = "error_db";
            $pesan_error = mysqli_error($koneksi);
        }
    } else {
        $status = "not_found";
    }
} else {
    header("Location: manajemen_dewan.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proses Hapus - ResesHub</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }</style>
</head>
<body>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        <?php if($status == "success"): ?>
            Swal.fire({
                title: 'Dihapus!',
                text: 'Data Anggota Dewan telah berhasil dihapus dari sistem.',
                icon: 'success',
                confirmButtonColor: '#34495e',
                borderRadius: '20px'
            }).then(() => {
                window.location = 'manajemen_dewan.php';
            });

        <?php elseif($status == "error_db"): ?>
            Swal.fire({
                title: 'Gagal Menghapus!',
                text: 'Kesalahan: <?= $pesan_error ?>',
                icon: 'error',
                confirmButtonColor: '#e74c3c',
                borderRadius: '20px'
            }).then(() => {
                window.location = 'manajemen_dewan.php';
            });

        <?php elseif($status == "not_found"): ?>
            Swal.fire({
                title: 'Data Tidak Ada',
                text: 'ID Anggota Dewan tidak ditemukan dalam database.',
                icon: 'warning',
                confirmButtonColor: '#64748b',
                borderRadius: '20px'
            }).then(() => {
                window.location = 'manajemen_dewan.php';
            });
        <?php endif; ?>
    </script>
</body>
</html>