<?php
session_start();
include '../config/db.php';

if (isset($_POST['submit'])) {
    // Data Utama (Tabel Berita)
    $id_dewan      = $_POST['id_dewan'];
    $nama_dewan    = $_POST['nama_dewan'];
    $fraksi        = $_POST['fraksi'];
    $judul         = $_POST['judul'];
    $tanggal       = $_POST['tanggal'];
    $deskripsi     = $_POST['deskripsi'];
    $alamat_lengkap = $_POST['alamat_lengkap'];
    $dapil         = $_POST['dapil'];
    $kecamatan     = $_POST['kecamatan'];
    $latitude      = $_POST['latitude'];
    $longitude     = $_POST['longitude'];
    
    // Data Tambahan (Tabel Barang Kegiatan)
    $nama_barang   = $_POST['barang']; 
    $foto_name     = $_FILES['foto_barang']['name'];
    $foto_tmp      = $_FILES['foto_barang']['tmp_name'];
    
    // Folder tujuan
    $foto_baru     = time() . "_" . $foto_name;
    $path          = "upload/" . $foto_baru;

    mysqli_begin_transaction($koneksi);

    try {
        // 1. Simpan ke tabel berita
        $sql1 = "INSERT INTO berita (id_dewan, nama_dewan, fraksi, judul, tanggal, deskripsi, alamat_lengkap, dapil, kecamatan, latitude, longitude, status) 
                 VALUES ('$id_dewan', '$nama_dewan', '$fraksi', '$judul', '$tanggal', '$deskripsi', '$alamat_lengkap', '$dapil', '$kecamatan', '$latitude', '$longitude', 'pending')";
        
        if (!mysqli_query($koneksi, $sql1)) throw new Exception(mysqli_error($koneksi));
        
        $id_berita_baru = mysqli_insert_id($koneksi);

        // 2. Upload Foto & Simpan ke tabel barang_kegiatan
        if (move_uploaded_file($foto_tmp, $path)) {
            $sql2 = "INSERT INTO barang_kegiatan (id_berita, nama_barang, foto_barang) 
                     VALUES ('$id_berita_baru', '$nama_barang', '$foto_baru')";
            if (!mysqli_query($koneksi, $sql2)) throw new Exception(mysqli_error($koneksi));
        } else {
            throw new Exception("Gagal upload file ke folder uploads.");
        }

        mysqli_commit($koneksi);
        // REDIRECT PENTING AGAR LOADING BERHENTI
        header("Location: tambah_berita.php?status=success&msg=Laporan berhasil dikirim!");
        exit();

    } catch (Exception $e) {
        mysqli_rollback($koneksi);
        header("Location: tambah_berita.php?status=error&msg=" . $e->getMessage());
        exit();
    }
}
?>