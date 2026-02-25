<?php
session_start();
include "../config/db.php";

// Pastikan ada script SweetAlert2 tersedia saat echo nanti
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
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // 1. Ambil data teks dari form
    $id             = $_POST['id_berita'];
    $judul          = mysqli_real_escape_string($koneksi, $_POST['judul']);
    $tanggal        = $_POST['tanggal'];
    $deskripsi      = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);
    $kecamatan      = mysqli_real_escape_string($koneksi, $_POST['kecamatan']);
    $alamat_lengkap = mysqli_real_escape_string($koneksi, $_POST['alamat_lengkap']);

    // 2. Update data teks di tabel berita_reses
    $query_teks = mysqli_query($koneksi, "UPDATE berita_reses SET 
        judul     = '$judul', 
        tanggal   = '$tanggal',
        alamat_lengkap = '$alamat_lengkap', 
        deskripsi = '$deskripsi',
        kecamatan = '$kecamatan' 
        WHERE id_berita = '$id'");

    // 3. Proses Update Foto Barang (Jika ada yang diunggah)
    if (isset($_POST['id_barang'])) {
        $ids_barang = $_POST['id_barang'];
        $files      = $_FILES['foto_barang_baru'];

        foreach ($ids_barang as $index => $id_barang) {
            if ($files['error'][$index] === UPLOAD_ERR_OK) {
                $tmp_name = $files['tmp_name'][$index];
                $name     = $files['name'][$index];
                
                $ekstensi   = pathinfo($name, PATHINFO_EXTENSION);
                $nama_baru  = "barang_" . time() . "_" . $index . "." . $ekstensi;
                $target_dir = "../upload/" . $nama_baru;

                $q_lama   = mysqli_query($koneksi, "SELECT foto_barang FROM barang_kegiatan WHERE id_barang = '$id_barang'");
                $d_lama   = mysqli_fetch_assoc($q_lama);
                $foto_lama = $d_lama['foto_barang'];

                if (move_uploaded_file($tmp_name, $target_dir)) {
                    if (!empty($foto_lama) && file_exists("../upload/" . $foto_lama)) {
                        unlink("../upload/" . $foto_lama);
                    }
                    mysqli_query($koneksi, "UPDATE barang_kegiatan SET foto_barang = '$nama_baru' WHERE id_barang = '$id_barang'");
                }
            }
        }
    }

    // 4. Final Check & Elegant Alert
    if ($query_teks) {
        echo "<script>
            Swal.fire({
                title: 'Berhasil!',
                text: 'Data laporan reses telah berhasil diperbarui.',
                icon: 'success',
                confirmButtonColor: '#34495e',
                confirmButtonText: 'Mantap!',
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
                text: 'Terjadi kesalahan: $error_msg',
                icon: 'error',
                confirmButtonColor: '#dc3545'
            }).then((result) => {
                window.history.back();
            });
        </script>";
    }

} else {
    header("Location: berita_admin.php");
}
?>
</body>
</html>