<?php
session_start();

// Hancurkan semua session secara permanen
session_unset();
session_destroy();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout - ResesHub</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f1f5f9; /* Sama dengan bg login */
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            overflow: hidden;
        }

        /* Styling khusus untuk mempercantik SweetAlert */
        .swal2-popup {
            border-radius: 2rem !important;
            padding: 2rem !important;
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.1) !important;
        }
        
        .swal2-title {
            color: #2c3e50 !important;
            font-weight: 800 !important;
            letter-spacing: -0.5px;
        }

        .swal2-html-container {
            color: #64748b !important;
            font-weight: 500 !important;
        }

        /* Animasi fade background agar tidak kaku */
        .logout-bg-overlay {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: linear-gradient(135deg, rgba(52, 73, 94, 0.05) 0%, rgba(203, 213, 225, 0.4) 100%);
            z-index: -1;
        }
    </style>
</head>
<body>

    <div class="logout-bg-overlay"></div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            title: 'Berhasil Logout',
            html: 'Sesi Anda telah berakhir secara aman.<br>Mengalihkan kembali ke halaman login...',
            icon: 'success',
            iconColor: '#34495e', // Warna primer dewan Anda
            showConfirmButton: false,
            timer: 2000, // Memberi waktu user untuk membaca (2 detik)
            timerProgressBar: true,
            allowOutsideClick: false,
            allowEscapeKey: false,
            // Custom animasi masuk dan keluar
            showClass: {
                popup: 'animate__animated animate__fadeInUp animate__faster'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutDown animate__faster'
            },
            willClose: () => {
                // Pindah ke login tanpa reload localhost alert
                window.location.href = "login.php";
            }
        });
    </script>
</body>
</html>