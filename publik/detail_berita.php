<?php 
include '../config/db.php';

$id = isset($_GET['id']) ? mysqli_real_escape_string($koneksi, $_GET['id']) : '';

$query = mysqli_query($koneksi, "SELECT berita_reses.*, dewan.fraksi, dewan.foto as foto_profil 
                                 FROM berita_reses 
                                 LEFT JOIN dewan ON berita_reses.id_dewan = dewan.id_dewan 
                                 WHERE berita_reses.id_berita = '$id'");
$data = mysqli_fetch_assoc($query);

if (!$data) { 
    echo "<script>alert('Berita tidak ditemukan!'); window.location='berita.php';</script>"; 
    exit; 
}

$q_barang = mysqli_query($koneksi, "SELECT * FROM barang_kegiatan WHERE id_berita = '$id' LIMIT 1");
$d_barang = mysqli_fetch_assoc($q_barang);

$foto_display = "https://images.unsplash.com/photo-1529107386315-e1a2ed48a620?q=80&w=1200";
if ($d_barang) {
    $path_cek = "../upload/" . $d_barang['foto_barang'];
    if (file_exists($path_cek) && !empty($d_barang['foto_barang'])) {
        $foto_display = $path_cek;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['judul']; ?> | ResesHub</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <style>
        :root {
            --primary-dark: #0f172a;
            --accent-color: #3b82f6;
            --bg-light: #f8fafc;
        }

        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background-color: var(--bg-light); 
            color: #1e293b;
        }

        .navbar { 
            background: rgba(15, 23, 42, 0.9) !important; 
            backdrop-filter: blur(15px); 
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding: 15px 0;
        }
        
        .navbar-brand { font-weight: 800; color: #fff !important; }

        /* Article Header Updated */
        .article-header {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            padding: 40px 0 80px;
            color: white;
            border-radius: 0 0 40px 40px;
        }

        /* Tombol Kembali Floating Style */
        .btn-back-top {
            display: inline-flex;
            align-items: center;
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 600;
            transition: 0.3s;
            margin-bottom: 20px;
            padding: 8px 16px;
            background: rgba(255,255,255,0.05);
            border-radius: 50px;
            border: 1px solid rgba(255,255,255,0.1);
        }

        .btn-back-top:hover {
            color: white;
            background: rgba(255,255,255,0.15);
        }

        .category-badge {
            background: rgba(59, 130, 246, 0.2);
            color: #60a5fa;
            padding: 4px 12px;
            border-radius: 50px;
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            border: 1px solid rgba(59, 130, 246, 0.3);
            margin-bottom: 15px;
        }

        /* Ukuran Judul diperkecil (h1 -> h3 style) */
        .judul-berita {
            font-size: 1.75rem; /* Lebih kecil dan elegan */
            font-weight: 800;
            line-height: 1.3;
            max-width: 800px;
            margin: 0 auto 20px;
        }

        .main-card {
            background: white;
            border: none;
            border-radius: 30px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.05);
            overflow: hidden;
            margin-top: -50px; /* Menumpuk sedikit ke header */
        }

        .img-hero-container { width: 100%; height: 450px; overflow: hidden; }
        .img-hero-container img { width: 100%; height: 100%; object-fit: cover; }

        .article-content { padding: 40px; }
        .content-body { font-size: 1.05rem; color: #334155; text-align: justify; white-space: pre-line; }

        /* Sidebar & Info */
        .info-card {
            background: white;
            border-radius: 20px;
            padding: 20px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.03);
            border: 1px solid #f1f5f9;
            margin-bottom: 20px;
        }

        .info-card h6 { font-weight: 800; font-size: 0.8rem; text-transform: uppercase; margin-bottom: 15px; color: var(--primary-dark); }
        .meta-list { list-style: none; padding: 0; margin: 0; }
        .meta-list li { display: flex; margin-bottom: 12px; font-size: 0.85rem; }
        .meta-list i { width: 25px; color: var(--accent-color); margin-top: 3px; }
        .meta-list span { font-weight: 700; color: #1e293b; display: block; }

        #map-detail { height: 200px; width: 100%; border-radius: 15px; }
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
    </div>
</nav>

<header class="article-header text-center">
    <div class="container">
        <a href="berita.php" class="btn-back-top">
            <i class="fa-solid fa-chevron-left me-2"></i> Kembali ke Berita
        </a>
        <br>
        <span class="category-badge">Laporan Resmi Reses</span>
        <h3 class="judul-berita"><?= $data['judul']; ?></h3>
        
        <div class="d-flex justify-content-center align-items-center gap-3 opacity-75 small">
            <span><i class="fa-regular fa-calendar me-1"></i> <?= date('d F Y', strtotime($data['tanggal'])); ?></span>
            <span>•</span>
            <span><i class="fa-solid fa-location-dot me-1"></i> Kec. <?= $data['kecamatan']; ?></span>
        </div>
    </div>
</header>

<div class="container pb-5">
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="main-card">
                <div class="img-hero-container">
                    <img src="<?= $foto_display; ?>" alt="Foto Utama">
                </div>
                <div class="article-content">
                    <div class="d-flex align-items-center mb-4 p-3 bg-light rounded-4">
                        <img src="../upload/dewan/<?= $data['foto_profil'] ?: 'default.png'; ?>" 
                             class="rounded-circle me-3" 
                             style="width: 45px; height: 45px; object-fit: cover; border: 2px solid white;"
                             onerror="this.src='https://via.placeholder.com/50'">
                        <div>
                            <p class="mb-0 small text-muted" style="font-size: 0.7rem;">Dilaporkan oleh:</p>
                            <h6 class="mb-0 fw-bold" style="font-size: 0.9rem;"><?= $data['nama_dewan']; ?></h6>
                        </div>
                    </div>

                    <h6 class="fw-800 text-dark mb-3 text-uppercase" style="letter-spacing: 1px;">Narasi Kegiatan</h6>
                    <div class="content-body">
                        <?= nl2br($data['deskripsi']); ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="info-card">
                <h6><i class="fa-solid fa-id-card me-2"></i>Detail Informasi</h6>
                <ul class="meta-list">
                    <li>
                        <i class="fa-solid fa-users"></i>
                        <div>
                            <p class="mb-0 text-muted small">Fraksi</p>
                            <span class="badge bg-light text-primary border px-2 py-1 rounded" style="font-size: 0.7rem;"><?= $data['fraksi'] ?: 'Lainnya'; ?></span>
                        </div>
                    </li>
                    <li>
                        <i class="fa-solid fa-map-location-dot"></i>
                        <div>
                            <p class="mb-0 text-muted small">Dapil</p>
                            <span>Wilayah Dapil <?= $data['dapil']; ?></span>
                        </div>
                    </li>
                </ul>
            </div>

            <div class="info-card">
                <h6><i class="fa-solid fa-map-pin me-2"></i>Lokasi</h6>
                <?php if(!empty($data['latitude'])): ?>
                    <div id="map-detail"></div>
                    <a href="https://www.google.com/maps?q=<?= $data['latitude']; ?>,<?= $data['longitude']; ?>" target="_blank" class="btn btn-outline-primary btn-sm w-100 mt-3 rounded-pill">
                        <i class="fa-solid fa-location-arrow me-2"></i> Petunjuk Arah
                    </a>
                <?php else: ?>
                    <div class="text-center py-4 bg-light rounded-3">
                        <p class="small m-0 text-muted italic">Peta tidak tersedia</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<footer class="py-4 bg-white border-top mt-5">
    <div class="container text-center">
        <p class="text-muted small mb-0">© 2026 Sekretariat DPRD Kabupaten Bandung</p>
    </div>
</footer>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    <?php if(!empty($data['latitude'])): ?>
    var map = L.map('map-detail', { scrollWheelZoom: false, zoomControl: false }).setView([<?= $data['latitude']; ?>, <?= $data['longitude']; ?>], 15);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
    L.marker([<?= $data['latitude']; ?>, <?= $data['longitude']; ?>]).addTo(map);
    <?php endif; ?>
</script>

</body>
</html>