<?php
include '../config/db.php';

$nip      = $_POST['nip'];
$nama     = $_POST['nama'];
$username = $_POST['user_admin'];
$email    = $_POST['email'];
$jabatan  = $_POST['jabatan'];
$password = $_POST['password'];
$konf     = $_POST['konfirmasi'];

if($password !== $konf) {
    echo "<script>alert('Password tidak cocok!'); window.history.back();</script>";
    exit();
}

// Enkripsi password agar aman (bisa login di sistem yang kita buat tadi)
$pass_hash = password_hash($password, PASSWORD_DEFAULT);

$query = mysqli_query($koneksi, "INSERT INTO admin (nip, nama, user_admin, email, jabatan, password) 
                                 VALUES ('$nip', '$nama', '$username', '$email', '$jabatan', '$pass_hash')");

if($query) {
    echo "<script>alert('Registrasi Berhasil!'); window.location.href='../admin/login.php';</script>";
} else {
    echo "Error: " . mysqli_error($koneksi);
}
?>