<?php include '../config/db.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Berita Kegiatan Reses | ResesHub</title>
    
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
            min-height: 100vh;
        }

        /* Background Image with Overlay */
        .bg-overlay {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background-image: url('../upload/dprd.png');
            background-size: cover;
            background-position: center;
            filter: blur(5px);
            opacity: 0.15;
            z-index: -1;
        }

        /* Navbar Premium Dark */
        .navbar { 
            background: rgba(15, 23, 42, 0.9) !important; 
            backdrop-filter: blur(15px); 
            -webkit-backdrop-filter: blur(15px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding: 18px 0;
            z-index: 1100;
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
            margin-bottom: 50px;
        }

        /* Berita Card Style */
        .card-berita { 
            border: none; 
            border-radius: 25px; 
            overflow: hidden; 
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        
        .card-berita:hover { 
            transform: translateY(-12px); 
            box-shadow: 0 20px 40px rgba(0,0,0,0.1); 
            background: #fff;
        }

        .img-container { 
            width: 100%; 
            height: 220px; 
            overflow: hidden; 
            position: relative;
        }
        
        .img-container img { 
            width: 100%; 
            height: 100%; 
            object-fit: cover; 
            transition: 0.5s;
        }

        .card-berita:hover .img-container img {
            transform: scale(1.1);
        }

        .badge-kecamatan {
            position: absolute;
            top: 15px;
            right: 15px;
            background: rgba(59, 130, 246, 0.9);
            color: white;
            padding: 5px 15px;
            border-radius: 50px;
            font-size: 0.7rem;
            font-weight: 700;
            backdrop-filter: blur(5px);
        }

        .card-body { 
            padding: 25px;
            display: flex; 
            flex-direction: column; 
            flex-grow: 1;
        }

        .card-title { 
            font-size: 1.15rem; 
            font-weight: 800; 
            color: #0f172a;
            line-height: 1.4;
            margin-bottom: 15px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            height: 3.2em;
        }

        .meta-info {
            font-size: 0.8rem;
            color: #64748b;
            margin-bottom: 8px;
            font-weight: 600;
        }

        .meta-info i { color: var(--accent-color); width: 20px; }

        .btn-read { 
            background: #f1f5f9;
            color: #0f172a;
            font-weight: 700;
            border-radius: 12px;
            padding: 10px;
            transition: 0.3s;
            border: none;
            margin-top: auto;
            font-size: 0.85rem;
        }

        .card-berita:hover .btn-read { 
            background: var(--accent-color); 
            color: #fff;
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
        }
    </style>
</head>
<body>

<div class="bg-overlay"></div>

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
                <li class="nav-item"><a class="nav-link px-3" href="dewan.php">Dewan</a></li>
                <li class="nav-item"><a class="nav-link active px-3" href="berita.php">Kegiatan</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="header-section">
    <div class="container">
        <span class="badge bg-primary mb-3 px-3 py-2 rounded-pill shadow-sm">NEWS & UPDATE</span>
        <h1 class="fw-800 mb-2">Kegiatan Reses Terbaru</h1>
        <p class="opacity-75">Transparansi aspirasi masyarakat melalui laporan langsung dari lapangan</p>
    </div>
</div>

<div class="container pb-5">
    <div class="row g-4 justify-content-center">
        <?php
        $query = mysqli_query($koneksi, "SELECT * FROM berita_reses WHERE status='published' ORDER BY tanggal DESC");
        
        while($row = mysqli_fetch_assoc($query)){
            $id_berita = $row['id_berita'];
            $q_foto = mysqli_query($koneksi, "SELECT foto_barang FROM barang_kegiatan WHERE id_berita = '$id_berita' LIMIT 1");
            $d_foto = mysqli_fetch_assoc($q_foto);
            
            $img_path = "../upload/" . ($d_foto['foto_barang'] ?? '');
            $img_src = (!empty($d_foto['foto_barang']) && file_exists($img_path)) ? $img_path : 'https://images.unsplash.com/photo-1529107386315-e1a2ed48a620?q=80&w=800';
        ?>
        <div class="col-md-6 col-lg-4 d-flex align-items-stretch">
            <div class="card card-berita w-100">
                <div class="img-container">
                    <span class="badge-kecamatan">Kec. <?= $row['kecamatan']; ?></span>
                    <img src="<?= $img_src; ?>" alt="Berita Reses">
                </div>
                <div class="card-body">
                    <div class="meta-info">
                        <i class="fa-regular fa-calendar-check"></i> <?= date('d M Y', strtotime($row['tanggal'])); ?>
                    </div>
                    <h5 class="card-title text-uppercase"><?= $row['judul']; ?></h5>
                    
                    <div class="meta-info mb-4">
                        <i class="fa-solid fa-circle-user"></i> <?= $row['nama_dewan']; ?>
                    </div>

                    <a href="detail_berita.php?id=<?= $row['id_berita']; ?>" class="btn btn-read d-flex align-items-center justify-content-center">
                        Baca Selengkapnya <i class="fa-solid fa-chevron-right ms-2" style="font-size: 0.7rem;"></i>
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