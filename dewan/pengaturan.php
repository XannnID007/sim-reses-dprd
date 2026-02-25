<?php
session_start();
include '../config/db.php';

// Proteksi Halaman
if(!isset($_SESSION['id_dewan'])){
    header("Location: login.php");
    exit();
}

$id = $_SESSION['id_dewan'];

// Mengambil data lengkap dewan berdasarkan ID yang login
$query = mysqli_query($koneksi, "SELECT * FROM dewan WHERE id_dewan='$id'");
$user = mysqli_fetch_assoc($query);

// Menentukan Path Foto
$foto_path = "../upload/dewan/" . ($user['foto'] ?? '');
$fallback_logo = "https://upload.wikimedia.org/wikipedia/commons/b/b2/Lambang_Dewan_Perwakilan_Rakyat_Daerah.png";
$display_img = (!empty($user['foto']) && file_exists($foto_path)) ? $foto_path : $fallback_logo;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Akun - ResesHub</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-dark: #34495e;
            --accent-blue: #3498db;
            --bg-body: #f8fafc;
        }

        body { 
            background-color: var(--bg-body); 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            color: #334155;
        }

        .main-content { 
    margin-left: 260px; 
    padding: 40px; 
    min-height: 100vh; 
}
        /* Profile Card Styling */
        .profile-card {
            border: none;
            border-radius: 30px;
            overflow: hidden;
            box-shadow: 0 20px 50px rgba(0,0,0,0.05);
            background: white;
        }

        .profile-header-bg {
            background: linear-gradient(135deg, #34495e 0%, #2c3e50 100%);
            height: 120px;
            position: relative;
        }

        .img-profile-wrapper {
            margin-top: -75px;
            position: relative;
            z-index: 2;
        }

        .img-profile { 
            width: 150px; 
            height: 150px; 
            object-fit: cover; 
            background: #fff;
            border: 6px solid #fff;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            border-radius: 50%;
        }

        /* Info Labels */
        .info-box {
            padding: 20px;
            border-radius: 20px;
            background: #fcfdfe;
            border: 1px solid #f1f5f9;
            transition: all 0.3s ease;
            height: 100%;
        }

        .info-box:hover {
            border-color: var(--accent-blue);
            transform: translateY(-5px);
        }

        .info-label { 
            color: #94a3b8; 
            font-size: 0.7rem; 
            text-transform: uppercase; 
            font-weight: 800;
            letter-spacing: 1px;
            margin-bottom: 5px;
            display: block;
        }
        
        .info-value { 
            color: #1e293b; 
            font-weight: 700; 
            font-size: 1.1rem; 
            display: block;
        }

        .status-badge {
            background: #dcfce7;
            color: #166534;
            padding: 6px 16px;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 700;
        }

        .alert-custom {
            background-color: #fffbeb;
            border: 1px solid #fef3c7;
            border-radius: 20px;
            color: #92400e;
        }

        @media (max-width: 992px) { .main-content { margin-left: 0; padding: 20px; } }
    </style>
</head>
<body>

<?php include 'sidebar.php'; ?>

<div class="main-content">
    <div class="container-fluid text-start">
        
        <div class="d-flex align-items-center mb-5 px-2">
            <div class="bg-white p-3 rounded-4 shadow-sm me-3">
                <i class="fas fa-user-gear fa-xl text-primary"></i>
            </div>
            <div>
                <h2 class="fw-bold mb-0 text-dark">Profil & Pengaturan</h2>
                <p class="text-muted mb-0">Kelola identitas resmi Anda dalam sistem ResesHub.</p>
            </div>
        </div>

        <div class="row justify-content-start">
            <div class="col-lg-11">
                <div class="profile-card">
                    <div class="profile-header-bg"></div>
                    
                    <div class="card-body p-4 p-md-5 pt-0">
                        <div class="text-center img-profile-wrapper mb-4">
                            <img src="<?= $display_img ?>" alt="Foto Dewan" class="img-profile mb-3">
                            <h3 class="fw-bold text-dark mb-1"><?= $user['nama_lengkap'] ?? 'Nama Tidak Terdaftar'; ?></h3>
                            <div class="d-flex justify-content-center align-items-center gap-2 mt-2">
                                <span class="status-badge"><i class="fas fa-shield-check me-1"></i> Akun Terverifikasi</span>
                                <span class="badge bg-light text-dark border rounded-pill px-3 py-2 fw-bold">ID: <?= $user['id_dewan']; ?></span>
                            </div>
                        </div>

                        <hr class="my-5 opacity-50">

                        <div class="row g-4">
                            <div class="col-md-4">
                                <div class="info-box">
                                    <span class="info-label">Username</span>
                                    <span class="info-value text-primary">@<?= $user['username']; ?></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="info-box">
                                    <span class="info-label">Fraksi Anggota</span>
                                    <span class="info-value"><?= $user['fraksi'] ?? '-'; ?></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="info-box">
                                    <span class="info-label">Daerah Pemilihan</span>
                                    <span class="info-value">Dapil <?= $user['dapil'] ?? '-'; ?></span>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="p-4 border rounded-4 bg-light d-flex align-items-center">
                                    <div class="bg-white p-3 rounded-circle me-4 shadow-sm">
                                        <i class="fas fa-landmark fa-2x text-warning"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-1">DPRD Kabupaten Bandung</h6>
                                        <p class="text-muted small mb-0">Periode Aktif: 2024 - 2029</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-5">
                            <div class="alert alert-custom p-4 d-flex align-items-start" role="alert">
                                <i class="fas fa-circle-info mt-1 me-3 fa-lg"></i>
                                <div>
                                    <h6 class="fw-bold mb-1">Informasi Perubahan Data</h6>
                                    <p class="small mb-0">
                                        Untuk menjaga validitas data negara, informasi profil seperti <strong>Nama Lengkap, Fraksi, dan Dapil</strong> hanya dapat diubah melalui Sekretariat DPRD. Jika terdapat ketidaksesuaian data, silakan hubungi Administrator Sistem.
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>