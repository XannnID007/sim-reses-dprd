<?php
session_start();
include "../config/db.php"; 

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = $_POST['password'];

    // Query sinkron mencari dewan berdasarkan username
    $query = mysqli_query($koneksi, "SELECT * FROM dewan WHERE username = '$username'");
    $data  = mysqli_fetch_assoc($query);

    if ($data) {
        // Verifikasi password hash
        if (password_verify($password, $data['password'])) {
            // Set Session Lengkap
            $_SESSION['id_dewan']   = $data['id_dewan'];
            $_SESSION['nama_dewan'] = $data['nama_lengkap'];
            $_SESSION['username']   = $data['username'];
            $_SESSION['foto']       = $data['foto']; 
            
            // Tambahkan session sukses untuk memicu SweetAlert
            $_SESSION['login_success'] = "Selamat Datang, " . $data['nama_lengkap'] . "!";
            header("Location: ../dewan/login.php"); 
            exit();
        } else {
            $_SESSION['error_dewan'] = "Password yang Anda masukkan salah!";
        }
    } else {
        $_SESSION['error_dewan'] = "Akun belum terdaftar!";
    }
    
    header("Location: ../dewan/login.php");
    exit();
} else {
    header("Location: ../dewan/login.php");
    exit();
}