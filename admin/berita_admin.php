<?php
session_start();
if(!isset($_SESSION['id_admin'])){
    header("Location: login.php");
    exit();
}
include "../config/db.php";
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Berita - ResesHub</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    
    <style>
        body { 
            background-color: #f8fafc; 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            color: #334155;
        }

        .bg-main { background-color: #34495e !important; }
        .main-content { margin-left: 250px; padding: 40px; min-height: 100vh; }
        
        /* Table Modernization */
        .card { border: none; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.02); }
        .table thead th {
            background-color: #f8fafc;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.7rem;
            letter-spacing: 1px;
            color: #94a3b8;
            padding: 20px;
            border-bottom: 1px solid #f1f5f9;
        }
        .table tbody tr { transition: all 0.2s; }
        .table tbody td { padding: 20px; border-bottom: 1px solid #f1f5f9; }
        .table tbody tr:hover { background-color: rgba(241, 245, 249, 0.5); }

        /* Badge & Buttons */
        .badge-soft-success { background: #ecfdf5; color: #059669; border: 1px solid #d1fae5; }
        .badge-soft-warning { background: #fffbeb; color: #d97706; border: 1px solid #fef3c7; }
        
        .btn-action { 
            width: 38px; height: 38px; 
            display: inline-flex; align-items: center; justify-content: center;
            border-radius: 10px; transition: all 0.3s;
        }
        .btn-action:hover { transform: translateY(-3px); }

        /* Modal Enhancements */
        .modal-content { border-radius: 24px; border: none; }
        .modal-header { border-bottom: 1px solid #f1f5f9; padding: 25px 30px; }
        .modal-body { padding: 30px; }
        
        .info-card-lite {
            background: #ffffff;
            border-radius: 16px;
            padding: 20px;
            border: 1px solid #f1f5f9;
            height: 100%;
        }

        .map-container { 
            width: 100%; 
            height: 280px; 
            border-radius: 16px; 
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            z-index: 1;
        }

        .gallery-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
            gap: 15px;
        }
        .gallery-item {
            border-radius: 12px;
            overflow: hidden;
            height: 120px;
            border: 2px solid #fff;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            cursor: pointer;
            transition: 0.3s;
        }
        .gallery-item:hover { transform: scale(1.05); }
        .gallery-item img { width: 100%; height: 100%; object-fit: cover; }

        .description-box {
            background: #f8fafc;
            border-radius: 12px;
            padding: 20px;
            font-size: 0.95rem;
            line-height: 1.7;
            color: #475569;
            border-left: 4px solid #34495e;
        }
    </style>
</head>
<body>

    <?php include 'sidebar.php'; ?>

    <div class="main-content">
        <div class="container-fluid">
            
            <div class="mb-5 d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="fw-bold text-dark mb-1">Berita & Hasil Reses</h2>
                    <p class="text-secondary mb-0">Review laporan kegiatan dewan sebelum dipublikasikan.</p>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4 text-center" style="width: 80px;">ID</th>
                                <th>DETAIL LAPORAN</th>
                                <th>ANGGOTA DEWAN</th>
                                <th>WILAYAH</th>
                                <th>STATUS</th>
                                <th class="text-center pe-4">KELOLA</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = mysqli_query($koneksi, "SELECT * FROM berita_reses ORDER BY id_berita DESC");
                            while($data = mysqli_fetch_assoc($query)){
                                $statusClass = ($data['status'] == 'published') ? 'badge-soft-success' : 'badge-soft-warning';
                            ?>
                                <tr>
                                    <td class="text-center ps-4">
                                        <span class="fw-bold text-muted">#<?= $data['id_berita'] ?></span>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark mb-1"><?= $data['judul']; ?></div>
                                        <div class="small text-muted"><i class="far fa-calendar me-1"></i> <?= date('d M Y', strtotime($data['tanggal'])) ?></div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-light rounded-circle p-2 me-2 text-center" style="width: 35px;"><i class="fas fa-user-tie text-secondary"></i></div>
                                            <span class="fw-medium"><?= $data['nama_dewan']; ?></span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border-0 px-2 py-1"><i class="fas fa-map-marker-alt text-danger me-1"></i> <?= $data['kecamatan']; ?></span>
                                    </td>
                                    <td>
                                        <span class="badge rounded-pill <?= $statusClass ?> text-uppercase px-3" style="font-size: 10px;"><?= $data['status']; ?></span>
                                    </td>
                                    <td class="text-center pe-4">
                                        <div class="d-flex justify-content-center gap-2">
                                            <button class="btn btn-primary btn-action shadow-sm" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#lihatModal<?= $data['id_berita'] ?>" 
                                                    onclick="renderMap(<?= $data['id_berita'] ?>, <?= $data['latitude'] ?>, <?= $data['longitude'] ?>)">
                                                <i class="fa fa-eye"></i>
                                            </button>
                                            
                                            <a href="edit_berita.php?id=<?= $data['id_berita']; ?>" class="btn btn-warning btn-action shadow-sm text-white">
                                                <i class="fa fa-edit"></i>
                                            </a>

                                            <?php if($data['status'] == 'pending'): ?>
                                                <a href="javascript:void(0)" 
                                                   onclick="konfirmasiPublikasi(<?= $data['id_berita']; ?>)"
                                                   class="btn btn-success btn-action shadow-sm text-white" title="Publish Sekarang">
                                                    <i class="fa fa-paper-plane"></i>
                                                </a>
                                            <?php endif; ?>

                                            <a href="javascript:void(0)" 
                                               onclick="konfirmasiHapus(<?= $data['id_berita']; ?>)"
                                               class="btn btn-danger btn-action shadow-sm text-white">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>

                                <div class="modal fade" id="lihatModal<?= $data['id_berita'] ?>" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                                        <div class="modal-content shadow-lg">
                                            <div class="modal-header bg-white">
                                                <h5 class="modal-title fw-bold text-dark">
                                                    <span class="text-primary me-2">Detail Laporan</span> #<?= $data['id_berita'] ?>
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body bg-light/30">
                                                <div class="row g-4">
                                                    <div class="col-lg-5">
                                                        <div class="mb-4">
                                                            <h6 class="fw-bold mb-3"><i class="fas fa-map-marked-alt me-2 text-primary"></i>Lokasi Kegiatan</h6>
                                                            <div id="map-<?= $data['id_berita'] ?>" class="map-container"></div>
                                                            <div class="mt-2 text-end small">
                                                                <a href="https://www.google.com/maps?q=<?= $data['latitude'] ?>,<?= $data['longitude'] ?>" target="_blank" class="text-decoration-none fw-bold">Buka di Google Maps <i class="fas fa-external-link-alt ms-1"></i></a>
                                                            </div>
                                                        </div>
                                                        
                                                        <div>
                                                            <h6 class="fw-bold mb-3"><i class="fas fa-images me-2 text-primary"></i>Dokumentasi Barang</h6>
                                                            <div class="gallery-container">
                                                                <?php 
                                                                $id_reses = $data['id_berita']; 
                                                                $qBarang = mysqli_query($koneksi, "SELECT * FROM barang_kegiatan WHERE id_berita = '$id_reses'");
                                                                while($b = mysqli_fetch_assoc($qBarang)): 
                                                                    $file_barang = "../upload/" . $b['foto_barang'];
                                                                ?>
                                                                    <div class="gallery-item" title="<?= $b['nama_barang']; ?>">
                                                                        <img src="<?= $file_barang; ?>" onclick="window.open(this.src)">
                                                                    </div>
                                                                <?php endwhile; ?>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-7">
                                                        <div class="info-card-lite shadow-sm">
                                                            <h4 class="fw-bold text-dark mb-4"><?= $data['judul'] ?></h4>
                                                            
                                                            <div class="row g-3 mb-4">
                                                                <div class="col-6">
                                                                    <label class="small text-muted text-uppercase fw-bold">Anggota Dewan</label>
                                                                    <p class="mb-0 fw-semibold"><?= $data['nama_dewan'] ?></p>
                                                                </div>
                                                                <div class="col-6">
                                                                    <label class="small text-muted text-uppercase fw-bold">Dapil</label>
                                                                    <p class="mb-0 fw-semibold">Dapil <?= $data['dapil'] ?></p>
                                                                </div>
                                                                <div class="col-12">
                                                                    <label class="small text-muted text-uppercase fw-bold">Alamat Lengkap</label>
                                                                    <p class="mb-0 small"><?= $data['alamat_lengkap'] ?></p>
                                                                </div>
                                                            </div>

                                                            <label class="small text-muted text-uppercase fw-bold mb-2 d-block">Narasi Laporan</label>
                                                            <div class="description-box">
                                                                <?= nl2br($data['deskripsi']) ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer border-0">
                                                <button type="button" class="btn btn-secondary px-4 rounded-pill" data-bs-dismiss="modal">Tutup</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        // Render Map Logic
        var maps = {};
        function renderMap(id, lat, lng) {
            setTimeout(function() {
                var mapId = 'map-' + id;
                if (maps[id]) { maps[id].remove(); }

                maps[id] = L.map(mapId).setView([lat, lng], 15);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap'
                }).addTo(maps[id]);

                L.marker([lat, lng]).addTo(maps[id])
                    .bindPopup("Titik Kegiatan Reses").openPopup();
                
                maps[id].invalidateSize();
            }, 300);
        }

        // --- Fitur Notifikasi & Konfirmasi ---

        function konfirmasiPublikasi(id) {
            Swal.fire({
                title: 'Publikasi Berita?',
                text: "Berita akan dapat dilihat oleh publik setelah dipublikasikan.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Publikasikan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "proses_publish.php?id=" + id;
                }
            })
        }

        function konfirmasiHapus(id) {
            Swal.fire({
                title: 'Hapus Berita?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "proses_hapus.php?id=" + id;
                }
            })
        }

        // Deteksi Notifikasi dari URL (Flash Message)
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('status')) {
            const status = urlParams.get('status');
            const msg = urlParams.get('msg');

            if (status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: msg || 'Tindakan berhasil dilakukan.',
                    timer: 3000,
                    showConfirmButton: false
                });
            } else if (status === 'error') {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: msg || 'Terjadi kesalahan sistem.',
                });
            }
        }
    </script>
</body>
</html>