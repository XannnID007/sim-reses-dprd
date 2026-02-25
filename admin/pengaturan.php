<?php
session_start();
include '../config/db.php';

if(!isset($_SESSION['id_admin'])){
    header("Location: login.php");
    exit();
}

$id = $_SESSION['id_admin'];

// Mengambil data terbaru dari database admin
$query = mysqli_query($koneksi, "SELECT * FROM admin WHERE id_admin='$id'");
$user = mysqli_fetch_assoc($query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Administrator - ResesHub</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body { 
            background-color: #f8fafc; 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            color: #334155;
        }

        .bg-main { background-color: #34495e !important; }
        .text-main { color: #34495e !important; }
        
        .main-content { margin-left: 250px; padding: 40px; min-height: 100vh; }
        
        /* Profile Card */
        .card-profile { 
            border: none; 
            border-radius: 24px; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.02);
            background: #ffffff;
        }

        .img-logo-wrapper {
            background: #f1f5f9;
            width: 140px;
            height: 140px;
            border-radius: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
        }

        .img-logo { 
            width: 90px; 
            height: 90px; 
            object-fit: contain; 
        }

        /* Information List */
        .info-label { 
            font-weight: 700; 
            font-size: 0.75rem; 
            color: #94a3b8; 
            text-transform: uppercase; 
            letter-spacing: 1px;
            margin-bottom: 5px;
        }
        
        .info-value { 
            font-weight: 600; 
            color: #1e293b; 
            font-size: 1.1rem;
            padding-bottom: 10px;
            border-bottom: 2px solid #f1f5f9;
            margin-bottom: 20px;
        }

        .alert-custom {
            border: none;
            border-radius: 15px;
            background-color: #fff4e5;
            border-left: 5px solid #ffa500;
        }

        @media (max-width: 768px) { .main-content { margin-left: 0; padding: 20px; } }
    </style>
</head>
<body>

<?php include 'sidebar.php'; ?>

<div class="main-content">
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="fw-bold text-dark">Pengaturan Akun</h2>
                <p class="text-muted">Kelola informasi kredensial dan identitas administrator sistem.</p>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-10">
                <div class="card card-profile p-4 p-md-5">
                    <div class="row align-items-start">
                        <div class="col-md-4 text-center border-end">
                            <div class="img-logo-wrapper mb-4">
                                <img src="../upload/dprd.png" 
                                     class="img-logo" 
                                     alt="Logo DPRD"
                                     onerror="this.src='https://upload.wikimedia.org/wikipedia/commons/b/b2/Lambang_Dewan_Perwakilan_Rakyat_Daerah.png'">
                            </div>
                            <h4 class="fw-bold mb-1"><?= $user['nama']; ?></h4>
                            <span class="badge bg-main px-3 py-2 rounded-pill mb-3">Administrator System</span>
                            
                            <div class="mt-4 pt-4 border-top">
                                <p class="small text-muted mb-1">Status Keanggotaan</p>
                                <div class="d-flex align-items-center justify-content-center text-success fw-bold">
                                    <i class="fas fa-check-circle me-2"></i> Akun Aktif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-8 ps-md-5 mt-4 mt-md-0">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="info-label">NIP / ID Pegawai</div>
                                    <div class="info-value"><?= $user['nip'] ?? 'Belum Diatur'; ?></div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-label">Username</div>
                                    <div class="info-value">@<?= $user['user_admin']; ?></div>
                                </div>
                                <div class="col-12">
                                    <div class="info-label">Jabatan Struktural</div>
                                    <div class="info-value"><?= $user['jabatan']; ?></div>
                                </div>
                                <div class="col-12">
                                    <div class="info-label">Alamat Email</div>
                                    <div class="info-value"><?= $user['email']; ?></div>
                                </div>
                            </div>

                            <div class="alert alert-custom p-4 mt-4">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-shield-alt fa-2x text-warning"></i>
                                    </div>
                                    <div class="ms-3">
                                        <h6 class="fw-bold text-dark mb-1">Keamanan Data Instansi</h6>
                                        <p class="small text-dark opacity-75 mb-0">
                                            Demi keamanan protokol data Sekretariat DPRD, perubahan informasi profil (Nama, NIP, & Email) hanya dapat dilakukan melalui koordinasi dengan <strong>Super Admin atau IT Support</strong>.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>