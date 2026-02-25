<?php
session_start();
include '../config/db.php';

// Ambil data dan proteksi dari SQL Injection sederhana
$nip        = mysqli_real_escape_string($koneksi, $_POST['nip']);
$user_admin = mysqli_real_escape_string($koneksi, $_POST['user_admin']);
$password   = $_POST['password'];

// Query cek Admin berdasarkan Username DAN NIP agar sinkron
$query = mysqli_query($koneksi, "SELECT * FROM admin WHERE user_admin='$user_admin' AND nip='$nip'");
$data  = mysqli_fetch_assoc($query);

if($data){
    // Cek Password
    if(password_verify($password, $data['password'])){
        // Jika Benar
        $_SESSION['nip'] = $data['nip'];
        $_SESSION['id_admin'] = $data['id_admin'];
        $_SESSION['nama'] = $data['nama'];
        
        header("Location: ../admin/dashboard.php");
        exit();
    } else {
        // Password Salah
        $_SESSION['error'] = "Password yang Anda masukkan salah!";
        header("Location: ../admin/login.php");
        exit();
    }
} else {
    // Username atau NIP tidak cocok
    $_SESSION['error'] = "Kombinasi NIP dan Username tidak ditemukan!";
    header("Location: ../admin/login.php");
    exit();
}
?>