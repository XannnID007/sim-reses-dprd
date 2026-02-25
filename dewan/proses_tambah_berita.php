<?php
session_start();
include '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_dewan       = $_POST['id_dewan'];
    $nama_dewan     = mysqli_real_escape_string($koneksi, $_POST['nama_dewan']);
    $fraksi         = mysqli_real_escape_string($koneksi, $_POST['fraksi']);
    $judul          = mysqli_real_escape_string($koneksi, $_POST['judul']);
    $tanggal        = $_POST['tanggal'];
    $deskripsi      = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);
    $alamat_lengkap = mysqli_real_escape_string($koneksi, $_POST['alamat_lengkap']);
    $dapil          = mysqli_real_escape_string($koneksi, $_POST['dapil']);
    $kecamatan      = mysqli_real_escape_string($koneksi, $_POST['kecamatan']);
    $latitude       = $_POST['latitude'];
    $longitude      = $_POST['longitude'];

    $nama_barang = $_POST['barang'];
    $foto_name   = $_FILES['foto_barang']['name'];
    $foto_tmp    = $_FILES['foto_barang']['tmp_name'];

    // Path upload dari folder dewan/ naik satu level ke root
    $foto_baru   = time() . "_" . basename($foto_name);
    $upload_path = "../upload/" . $foto_baru;

    mysqli_begin_transaction($koneksi);

    try {
        // ✅ Simpan ke tabel berita_reses (bukan 'berita')
        $sql1 = "INSERT INTO berita_reses 
                    (id_dewan, nama_dewan, fraksi, judul, tanggal, deskripsi, alamat_lengkap, dapil, kecamatan, latitude, longitude, status) 
                 VALUES 
                    ('$id_dewan', '$nama_dewan', '$fraksi', '$judul', '$tanggal', '$deskripsi', '$alamat_lengkap', '$dapil', '$kecamatan', '$latitude', '$longitude', 'pending')";

        if (!mysqli_query($koneksi, $sql1)) throw new Exception(mysqli_error($koneksi));

        $id_berita_baru = mysqli_insert_id($koneksi);

        // Upload foto
        if (move_uploaded_file($foto_tmp, $upload_path)) {
            $nama_barang_esc = mysqli_real_escape_string($koneksi, $nama_barang);
            $sql2 = "INSERT INTO barang_kegiatan (id_berita, nama_barang, foto_barang) 
                     VALUES ('$id_berita_baru', '$nama_barang_esc', '$foto_baru')";
            if (!mysqli_query($koneksi, $sql2)) throw new Exception(mysqli_error($koneksi));
        } else {
            throw new Exception("Gagal upload foto. Pastikan folder /upload/ bisa ditulis (permission 755).");
        }

        mysqli_commit($koneksi);
        header("Location: tambah_berita.php?status=success&msg=Laporan berhasil dikirim dan menunggu persetujuan admin!");
        exit();
    } catch (Exception $e) {
        mysqli_rollback($koneksi);
        header("Location: tambah_berita.php?status=error&msg=" . urlencode($e->getMessage()));
        exit();
    }
}
