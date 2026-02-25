<?php
include "../config/db.php"; 
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Anggota DPRD | ResesHub</title>
    
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
            background-color: var(--bg-light); 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            color: #1e293b;
        }

        /* Navbar Premium Dark */
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

        /* Header Section */
        .header-section { 
            padding: 80px 0 60px; 
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); 
            color: white;
            text-align: center; 
            border-radius: 0 0 50px 50px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        /* Card Style - FIXED UNTUK TINGGI SEJAJAR */
        .card-dewan { 
            border: none; 
            border-radius: 30px; 
            overflow: hidden; 
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            background: white;
            box-shadow: 0 10px 25px rgba(0,0,0,0.02);
            text-decoration: none; 
            display: flex; /* Pakai Flex */
            flex-direction: column; /* Arah vertikal */
            height: 100%; /* Paksa tinggi 100% dari kolom */
            position: relative;
        }
        
        .card-dewan:hover { 
            transform: translateY(-12px); 
            box-shadow: 0 25px 50px rgba(0,0,0,0.1); 
        }

        .card-header-color { 
            height: 110px; 
            background: linear-gradient(45deg, #1e293b, #3b82f6);
            flex-shrink: 0;
        }
        
        .img-container { 
            width: 130px; 
            height: 130px; 
            margin: -65px auto 15px; 
            position: relative; 
            z-index: 2; 
            flex-shrink: 0;
        }

        .img-dewan { 
            width: 100%; 
            height: 100%; 
            object-fit: cover; 
            border-radius: 50%; 
            border: 6px solid white; 
            background: #fff;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }

        /* Body kartu dibuat flex-grow agar mendorong footer ke bawah */
        .card-body-content {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            padding: 0 0 20px 0;
        }
        
        .nama-dewan { 
            font-size: 1.15rem; 
            font-weight: 800; 
            color: #0f172a; 
            padding: 0 15px;
            line-height: 1.3;
            min-height: 3rem; /* Memberikan ruang minimal agar tetap sejajar */
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .fraksi-badge {
            display: inline-block;
            background: rgba(59, 130, 246, 0.1);
            color: var(--accent-color);
            padding: 5px 15px;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 700;
            margin: 0 auto 15px;
            text-transform: uppercase;
        }

        .info-box {
            background: #f8fafc;
            border-radius: 20px;
            padding: 15px;
            margin: auto 15px 15px; /* Margin auto atas mendorong info-box ke bawah jika konten sedikit */
        }

        .info-item { font-size: 0.8rem; color: #64748b; margin-bottom: 5px; font-weight: 600; }
        .info-item i { width: 20px; color: var(--accent-color); }
        
        .view-activity { 
            background: #0f172a;
            color: white;
            padding: 12px;
            font-size: 0.8rem;
            font-weight: 700;
            transition: 0.3s;
            margin-top: auto; /* Memastikan nempel di paling bawah kartu */
        }

        .card-dewan:hover .view-activity {
            background: var(--accent-color);
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

<div class="header-section">
    <div class="container">
        <span class="badge bg-primary mb-3 px-3 py-2 rounded-pill shadow-sm">KABUPATEN BANDUNG</span>
        <h1 class="fw-800 mb-2">Profil Anggota DPRD</h1>
        <p class="opacity-75">Daftar wakil rakyat yang bertugas pada Periode 2024-2029</p>
    </div>
</div>

<div class="container my-5">
    <div class="row g-4 justify-content-center">
        <?php
        $query = mysqli_query($koneksi, "SELECT id_dewan, nama_lengkap, fraksi, dapil, foto FROM dewan ORDER BY id_dewan ASC");
        $fallback = "https://upload.wikimedia.org/wikipedia/commons/b/b2/Lambang_Dewan_Perwakilan_Rakyat_Daerah.png";

        while($d = mysqli_fetch_array($query)) {
            $path_tampil = !empty($d['foto']) ? "../upload/dewan/" . $d['foto'] : $fallback;
        ?>
        
        <div class="col-lg-3 col-md-6 d-flex align-items-stretch">
            <a href="kegiatan_reses.php?id=<?= $d['id_dewan'] ?>" class="card-dewan text-center">
                <div class="card-header-color"></div>
                
                <div class="img-container">
                    <img src="<?= $path_tampil ?>" 
                         onerror="this.src='<?= $fallback ?>'" 
                         class="img-dewan shadow" 
                         alt="<?= $d['nama_lengkap'] ?>">
                </div>

                <div class="card-body-content">
                    <span class="fraksi-badge">Fraksi <?= $d['fraksi'] ?></span>
                    <h5 class="nama-dewan mb-3"><?= $d['nama_lengkap'] ?></h5>

                    <div class="info-box text-start">
                        <div class="info-item">
                            <i class="fa-solid fa-id-badge"></i> ID Dewan: #<?= $d['id_dewan'] ?>
                        </div>
                        <div class="info-item">
                            <i class="fa-solid fa-map-location-dot"></i> Daerah Pilih: <?= $d['dapil'] ?>
                        </div>
                    </div>
                    
                    <div class="view-activity">
                        LIHAT KEGIATAN <i class="fas fa-chevron-right ms-2" style="font-size: 0.7rem;"></i>
                    </div>
                </div>
            </a>
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