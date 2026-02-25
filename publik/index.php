<?php include '../config/db.php'; ?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ResesHub | DPRD Kabupaten Bandung</title>

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

        /* Navbar Premium Dark (Sama dengan Profil) */
        .navbar {
            background: rgba(15, 23, 42, 0.85) !important;
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding: 18px 0;
            z-index: 1000;
        }

        .navbar-brand {
            font-weight: 800;
            font-size: 1.4rem;
            letter-spacing: -0.5px;
            color: #ffffff !important;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.75) !important;
            font-weight: 600;
            font-size: 0.9rem;
            margin: 0 5px;
            transition: 0.3s;
        }

        .nav-link:hover,
        .nav-link.active {
            color: var(--accent-color) !important;
            opacity: 1;
        }

        .btn-portal {
            background: var(--accent-color);
            color: white !important;
            border-radius: 12px;
            padding: 8px 20px !important;
            font-weight: 700;
            font-size: 0.85rem;
            transition: 0.3s;
            border: none;
        }

        .btn-portal:hover {
            transform: translateY(-2px);
            background: #2563eb;
            box-shadow: 0 8px 20px rgba(59, 130, 246, 0.3);
        }

        /* Hero Section */
        .hero-section {
            background: linear-gradient(rgba(15, 23, 42, 0.75), rgba(15, 23, 42, 0.75)), url('../upload/dprd.png');
            background-size: cover;
            background-position: center;
            padding: 150px 0 170px;
            color: white;
            text-align: center;
            margin-top: -85px;
            /* Narik ke atas agar nyambung dengan navbar transparan */
        }

        .hero-title {
            font-weight: 800;
            font-size: 3.5rem;
            letter-spacing: -1px;
            margin-bottom: 20px;
        }

        .hero-subtitle {
            font-size: 1.1rem;
            opacity: 0.9;
            max-width: 600px;
            margin: 0 auto;
        }

        /* Map Container */
        .map-wrapper {
            margin-top: -100px;
            background: white;
            padding: 12px;
            border-radius: 30px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.15);
            position: relative;
            z-index: 5;
        }

        #map-sebaran {
            height: 550px;
            width: 100%;
            border-radius: 22px;
        }

        /* News Cards */
        .section-title {
            font-weight: 800;
            font-size: 2rem;
            color: #0f172a;
        }

        .reses-card {
            border: none;
            border-radius: 24px;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            background: white;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.02);
        }

        .reses-card:hover {
            transform: translateY(-12px);
            box-shadow: 0 25px 45px rgba(0, 0, 0, 0.08);
        }

        .img-container {
            height: 220px;
            overflow: hidden;
        }

        .card-img-top {
            height: 100%;
            object-fit: cover;
            transition: 0.6s;
        }

        .reses-card:hover .card-img-top {
            transform: scale(1.1);
        }

        .badge-dapil {
            background: rgba(59, 130, 246, 0.1);
            color: var(--accent-color);
            padding: 6px 14px;
            border-radius: 10px;
            font-weight: 800;
            font-size: 0.7rem;
            text-transform: uppercase;
        }

        .btn-more {
            background: #f1f5f9;
            color: #0f172a;
            border-radius: 12px;
            padding: 12px;
            font-weight: 700;
            border: none;
            transition: 0.3s;
        }

        .btn-more:hover {
            background: var(--accent-color);
            color: white;
        }

        footer {
            background: #0f172a;
            color: rgba(255, 255, 255, 0.6);
            padding: 60px 0 40px;
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
                    <li class="nav-item"><a class="nav-link active" href="index.php">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="dewan.php">Dewan</a></li>
                    <li class="nav-item"><a class="nav-link" href="berita.php">Kegiatan</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="hero-section">
        <div class="container">
            <h1 class="hero-title">Monitoring Reses Digital</h1>
            <p class="hero-subtitle">Transparansi aspirasi dan pemetaan kegiatan lapangan Anggota DPRD Kabupaten Bandung secara real-time.</p>
        </div>
    </div>

    <div class="container mb-5">
        <div class="map-wrapper">
            <div id="map-sebaran"></div>
        </div>

        <div class="mt-5 pt-5">
            <div class="d-flex align-items-end justify-content-between mb-4">
                <div>
                    <h3 class="section-title mb-1">Kegiatan Terbaru</h3>
                    <p class="text-muted mb-0">Laporan reses terkini dari wilayah Kabupaten Bandung.</p>
                </div>
                <a href="berita.php" class="text-primary fw-bold text-decoration-none d-none d-md-block">
                    Lihat Semua <i class="fa-solid fa-arrow-right-long ms-2"></i>
                </a>
            </div>

            <div class="row mt-4">
                <?php
                $sql = "SELECT b.*, bk.foto_barang 
                FROM berita_reses b 
                LEFT JOIN barang_kegiatan bk ON b.id_berita = bk.id_berita 
                WHERE b.status = 'published' 
                ORDER BY b.tanggal DESC LIMIT 3";
                $query = mysqli_query($koneksi, $sql);
                while ($data = mysqli_fetch_assoc($query)) {
                    $path_foto = "../upload/" . $data['foto_barang'];
                    $src_gambar = (!empty($data['foto_barang']) && file_exists($path_foto)) ? $path_foto : "https://via.placeholder.com/600x400?text=Dokumentasi+Reses";
                ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 reses-card border-0">
                            <div class="img-container">
                                <img src="<?php echo $src_gambar; ?>" class="card-img-top" alt="Berita">
                            </div>
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="badge-dapil">Dapil <?php echo $data['dapil']; ?></span>
                                    <div class="small text-muted fw-bold">
                                        <i class="fa-regular fa-calendar me-1"></i> <?php echo date('d M Y', strtotime($data['tanggal'])); ?>
                                    </div>
                                </div>
                                <h5 class="fw-800 mb-2" style="font-size: 1.15rem;"><?php echo $data['nama_dewan']; ?></h5>
                                <p class="text-muted small mb-3"><i class="fa-solid fa-location-dot text-danger me-1"></i> <?php echo $data['kecamatan']; ?></p>
                                <p class="card-text small text-secondary" style="line-height: 1.6; opacity: 0.8;">
                                    <?php echo substr(strip_tags($data['deskripsi']), 0, 90); ?>...
                                </p>
                            </div>
                            <div class="card-footer bg-white border-0 pb-4 px-4">
                                <a href="detail_berita.php?id=<?php echo $data['id_berita']; ?>" class="btn btn-more w-100">Baca Selengkapnya</a>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>

    <footer>
        <div class="container text-center text-md-start">
            <div class="row gy-4">
                <div class="col-md-6">
                    <h5 class="text-white fw-bold mb-3">ResesHub</h5>
                    <p class="small w-75">Membangun transparansi antara dewan dan rakyat melalui data digital yang akurat dan dapat dipertanggungjawabkan.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="small mb-0">&copy; 2026 Sekretariat DPRD Kabupaten Bandung. All Rights Reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        // Tunggu sampai halaman + Leaflet benar-benar siap
        window.addEventListener('load', function() {

            // =============================================
            // INISIALISASI PETA
            // =============================================
            var map = L.map('map-sebaran', {
                center: [-7.0234, 107.5123],
                zoom: 11,
                scrollWheelZoom: false
            });

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors',
                maxZoom: 18
            }).addTo(map);

            var markerGroup = L.featureGroup().addTo(map);

            // Custom icon titik biru
            var dotIcon = L.divIcon({
                className: '',
                html: `
        <div style="position:relative; width:30px; height:42px;">
            <!-- Badan pin -->
            <div style="
                width: 30px;
                height: 30px;
                background: linear-gradient(135deg, #3b82f6, #1d4ed8);
                border: 3px solid white;
                border-radius: 50% 50% 50% 0;
                transform: rotate(-45deg);
                box-shadow: 0 4px 15px rgba(59,130,246,0.5);
                position: absolute;
                top: 0; left: 0;
            "></div>
            <!-- Titik putih di tengah -->
            <div style="
                width: 10px;
                height: 10px;
                background: white;
                border-radius: 50%;
                position: absolute;
                top: 10px;
                left: 10px;
                transform: rotate(0deg);
            "></div>
        </div>
    `,
                iconSize: [30, 42],
                iconAnchor: [15, 42],
                popupAnchor: [0, -44]
            });

            // =============================================
            // DATA DARI DATABASE (PHP)
            // =============================================
            var markersData = [
                <?php
                $sql_map = mysqli_query($koneksi, "
            SELECT id_berita, nama_dewan, kecamatan, judul, tanggal, latitude, longitude
            FROM berita_reses 
            WHERE (status = 'published' OR status = '1')
              AND latitude  IS NOT NULL AND latitude  != ''
              AND longitude IS NOT NULL AND longitude != ''
        ");

                while ($row = mysqli_fetch_assoc($sql_map)) {
                    $nama     = addslashes(htmlspecialchars($row['nama_dewan']));
                    $kec      = addslashes(htmlspecialchars($row['kecamatan']));
                    $judul    = addslashes(htmlspecialchars($row['judul']));
                    $tanggal  = date('d M Y', strtotime($row['tanggal']));
                    $lat      = (float) $row['latitude'];
                    $lng      = (float) $row['longitude'];
                    $id       = (int)   $row['id_berita'];
                    echo "{id:$id, nama:'$nama', kec:'$kec', judul:'$judul', tanggal:'$tanggal', lat:$lat, lng:$lng},\n";
                }
                ?>
            ];

            // =============================================
            // PLOT MARKER
            // =============================================
            markersData.forEach(function(m) {
                var popup =
                    '<div style="font-family:sans-serif;min-width:200px;padding:4px">' +
                    '<p style="margin:0 0 4px;font-size:10px;color:#64748b;text-transform:uppercase;letter-spacing:1px">Laporan Reses</p>' +
                    '<b style="font-size:13px;color:#0f172a">' + m.nama + '</b><br>' +
                    '<span style="font-size:12px;color:#475569">📍 Kec. ' + m.kec + '</span><br>' +
                    '<span style="font-size:11px;color:#94a3b8">🗓 ' + m.tanggal + '</span><br><br>' +
                    '<a href="detail_berita.php?id=' + m.id + '" ' +
                    'style="display:block;background:#3b82f6;color:#fff;padding:7px;' +
                    'border-radius:8px;text-align:center;font-size:11px;' +
                    'font-weight:700;text-decoration:none">' +
                    'Lihat Detail →' +
                    '</a>' +
                    '</div>';

                L.marker([m.lat, m.lng], {
                        icon: dotIcon
                    })
                    .addTo(markerGroup)
                    .bindPopup(popup, {
                        maxWidth: 220
                    });
            });

            // Auto zoom ke semua marker
            if (markerGroup.getLayers().length > 0 && markerGroup.getBounds().isValid()) {
                map.fitBounds(markerGroup.getBounds(), {
                    padding: [60, 60],
                    maxZoom: 13
                });
            }

            // Fix ukuran peta setelah render
            setTimeout(function() {
                map.invalidateSize();
            }, 300);
            setTimeout(function() {
                map.invalidateSize();
            }, 800);

        }); // end window.load
    </script>

</body>

</html>