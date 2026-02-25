<?php 
include "../config/db.php"; 

// Ambil ID Dewan dari URL
if (!isset($_GET['id'])) {
    header("Location: dewan.php");
    exit;
}

$id_dewan = mysqli_real_escape_string($koneksi, $_GET['id']);

// 1. Ambil data Profil Dewan
$query_dewan = mysqli_query($koneksi, "SELECT * FROM dewan WHERE id_dewan = '$id_dewan'");
$dewan = mysqli_fetch_assoc($query_dewan);

// Jika dewan tidak ditemukan
if (!$dewan) {
    echo "Data Anggota Dewan tidak ditemukan.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kegiatan Reses | <?= $dewan['nama_lengkap'] ?></title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary-dark: #0f172a;
            --accent-color: #3b82f6;
            --bg-light: #f1f5f9;
        }

        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background-color: var(--bg-light); 
            color: #1e293b;
        }

        /* Navbar Premium Dark (Konsisten) */
        .navbar { 
            background: rgba(15, 23, 42, 0.9) !important; 
            backdrop-filter: blur(15px); 
            -webkit-backdrop-filter: blur(15px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding: 18px 0;
        }
        
        .navbar-brand { font-weight: 800; color: #fff !important; letter-spacing: -0.5px; }
        .nav-link { color: rgba(255,255,255,0.7) !important; font-weight: 600; font-size: 0.9rem; transition: 0.3s; }
        .nav-link:hover, .nav-link.active { color: var(--accent-color) !important; }

        /* Profile Header Section */
        .profile-header {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            color: white;
            padding: 80px 0 60px;
            border-radius: 0 0 50px 50px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            position: relative;
        }

        .img-profile {
            width: 140px; 
            height: 140px;
            object-fit: cover;
            border: 6px solid rgba(255,255,255,0.1);
            border-radius: 50%;
            box-shadow: 0 10px 25px rgba(0,0,0,0.3);
            background: #fff;
        }

        .fraksi-badge-header {
            background: rgba(59, 130, 246, 0.2);
            color: #60a5fa;
            border: 1px solid rgba(59, 130, 246, 0.3);
            padding: 6px 16px;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
        }

        /* Card Laporan Style */
        .reses-card {
            border: none;
            border-radius: 25px;
            overflow: hidden;
            background: white;
            box-shadow: 0 10px 25px rgba(0,0,0,0.02);
            transition: all 0.4s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .reses-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.08);
        }

        .img-container {
            height: 220px;
            position: relative;
            overflow: hidden;
        }

        .img-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: 0.5s;
        }

        .reses-card:hover .img-container img {
            transform: scale(1.1);
        }

        .category-tag {
            position: absolute;
            top: 15px;
            left: 15px;
            background: rgba(15, 23, 42, 0.8);
            backdrop-filter: blur(5px);
            color: white;
            padding: 5px 12px;
            border-radius: 10px;
            font-size: 0.7rem;
            font-weight: 700;
        }

        .card-body {
            padding: 25px;
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }

        .judul-laporan {
            font-weight: 800;
            color: #0f172a;
            line-height: 1.4;
            margin-bottom: 12px;
            font-size: 1.15rem;
        }

        .meta-info {
            font-size: 0.8rem;
            color: #64748b;
            font-weight: 600;
            margin-bottom: 15px;
        }

        .meta-info i { color: var(--accent-color); margin-right: 5px; }

        .btn-laporan {
            background: #0f172a;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 15px;
            font-weight: 700;
            font-size: 0.85rem;
            transition: 0.3s;
            margin-top: auto;
        }

        .btn-laporan:hover {
            background: var(--accent-color);
            color: white;
            box-shadow: 0 5px 15px rgba(59, 130, 246, 0.4);
        }

        .empty-state {
            padding: 80px 0;
            text-align: center;
            background: white;
            border-radius: 30px;
            border: 2px dashed #e2e8f0;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="index.php">
            <div class="bg-primary text-white rounded-3 p-2 me-2 d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                <i class="fa-solid fa-landmark fs-6"></i>
            </div>
            RESESHUB
        </a>
        <button class="navbar-toggler border-0 text-white" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <i class="fa-solid fa-bars-staggered"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item"><a class="nav-link px-3" href="index.php">Beranda</a></li>
                <li class="nav-item"><a class="nav-link active px-3" href="dewan.php">Dewan</a></li>
                <li class="nav-item"><a class="nav-link px-3" href="berita.php">Kegiatan</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="profile-header text-center">
    <div class="container">
        <img src="../upload/dewan/<?= $dewan['foto'] ?>" 
             class="img-profile mb-4" 
             onerror="this.src='https://via.placeholder.com/150'">
        
        <div class="mb-3">
            <span class="fraksi-badge-header">Fraksi <?= $dewan['fraksi'] ?></span>
        </div>
        
        <h2 class="fw-800 mb-1"><?= $dewan['nama_lengkap'] ?></h2>
        <p class="opacity-75 fw-600">Daerah Pemilihan (Dapil) <?= $dewan['dapil'] ?></p>
        
        <div class="mt-4">
             <a href="dewan.php" class="btn btn-outline-light btn-sm rounded-pill px-4 py-2 border-opacity-25">
                <i class="fa fa-arrow-left me-2"></i> Kembali ke Daftar
            </a>
        </div>
    </div>
</div>

<div class="container my-5">
    <div class="row mb-5">
        <div class="col-md-12">
            <h4 class="fw-800 text-dark d-flex align-items-center">
                <i class="fa-solid fa-box-archive text-primary me-3"></i>
                Arsip Laporan Reses
            </h4>
            <div style="width: 50px; height: 4px; background: var(--accent-color); border-radius: 10px; margin-top: 10px;"></div>
        </div>
    </div>

    <div class="row g-4">
        <?php
        $nama_pencarian = mysqli_real_escape_string($koneksi, $dewan['nama_lengkap']);
        $query_berita = mysqli_query($koneksi, "SELECT * FROM berita_reses 
                                               WHERE (nama_dewan = '$nama_pencarian' OR id_dewan = '$id_dewan') 
                                               AND status = 'published' 
                                               ORDER BY tanggal DESC");

        if (mysqli_num_rows($query_berita) == 0) {
            echo "
            <div class='col-12'>
                <div class='empty-state'>
                    <div class='bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-4' style='width: 80px; height: 80px;'>
                        <i class='fa-regular fa-folder-open fa-2x text-muted'></i>
                    </div>
                    <h5 class='fw-bold text-dark'>Belum Ada Laporan</h5>
                    <p class='text-muted'>Kegiatan reses untuk anggota dewan ini belum dipublikasikan.</p>
                </div>
            </div>";
        }

        while($data = mysqli_fetch_assoc($query_berita)){
            $id_berita = $data['id_berita'];
            
            // Ambil satu foto dari barang_kegiatan untuk thumbnail
            $q_foto = mysqli_query($koneksi, "SELECT foto_barang FROM barang_kegiatan WHERE id_berita = '$id_berita' LIMIT 1");
            $d_foto = mysqli_fetch_assoc($q_foto);
            
            $img_path = "../upload/" . ($d_foto['foto_barang'] ?? '');
            $img_src = (!empty($d_foto['foto_barang']) && file_exists($img_path)) ? $img_path : 'https://images.unsplash.com/photo-1529107386315-e1a2ed48a620?q=80&w=800';
        ?>
        <div class="col-lg-4 col-md-6 d-flex align-items-stretch">
            <div class="reses-card">
                <div class="img-container">
                    <span class="category-tag">Laporan Resmi</span>
                    <img src="<?= $img_src; ?>" alt="Thumbnail Berita">
                </div>
                <div class="card-body">
                    <div class="meta-info d-flex justify-content-between">
                        <span><i class="fa-regular fa-calendar"></i> <?= date('d M Y', strtotime($data['tanggal'])); ?></span>
                        <span><i class="fa-solid fa-location-dot"></i> <?= $data['kecamatan']; ?></span>
                    </div>
                    
                    <h5 class="judul-laporan"><?= $data['judul']; ?></h5>
                    
                    <p class="text-secondary small mb-4">
                        <?= substr(strip_tags($data['deskripsi']), 0, 110); ?>...
                    </p>
                    
                    <a href="detail_berita.php?id=<?= $data['id_berita']; ?>" class="btn btn-laporan w-100">
                        BACA LAPORAN <i class="fa-solid fa-arrow-right ms-2 fs-xs"></i>
                    </a>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
</div>

<footer class="py-5 bg-white border-top">
    <div class="container text-center">
        <p class="text-muted small mb-0">&copy; 2026 Sekretariat DPRD Kabupaten Bandung. All Rights Reserved.</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>