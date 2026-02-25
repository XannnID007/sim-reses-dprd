<?php
session_start();
include "../config/db.php";

if(!isset($_SESSION['id_admin'])){
    header("Location: login.php");
    exit();
}

$id = $_GET['id'];
$query = mysqli_query($koneksi, "SELECT * FROM berita_reses WHERE id_berita = '$id'");
$data = mysqli_fetch_array($query);

if (!$data) {
    echo "Data tidak ditemukan!";
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Berita - ResesHub Admin</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background-color: #f8fafc; 
            color: #334155;
        }
        
        .main-content { margin-left: 260px; padding: 40px; }

        /* Card Styling */
        .card { border-radius: 20px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.03); }
        .card-header { background: transparent; border-bottom: 1px solid #f1f5f9; padding: 25px; }

        /* Form Styling */
        .form-label { font-weight: 700; font-size: 0.85rem; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; }
        .form-control { 
            border-radius: 12px; 
            padding: 12px 15px; 
            border: 1px solid #e2e8f0; 
            background-color: #fbfcfd;
            transition: all 0.2s;
        }
        .form-control:focus {
            background-color: #fff;
            border-color: #34495e;
            box-shadow: 0 0 0 4px rgba(52, 73, 94, 0.1);
        }

        /* Foto Item Styling */
        .item-barang-card {
            background: #ffffff;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            border: 1px solid #f1f5f9;
            transition: all 0.3s ease;
        }
        .item-barang-card:hover { border-color: #cbd5e1; box-shadow: 0 5px 15px rgba(0,0,0,0.05); }

        .img-preview-container {
            width: 90px;
            height: 90px;
            border-radius: 12px;
            overflow: hidden;
            border: 3px solid #fff;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .img-barang-edit { width: 100%; height: 100%; object-fit: cover; }

        .section-title { font-size: 1.1rem; font-weight: 700; color: #1e293b; position: relative; padding-left: 15px; }
        .section-title::before {
            content: ""; position: absolute; left: 0; top: 5px; height: 18px; width: 4px; background: #34495e; border-radius: 10px;
        }

        .btn-save { 
            background-color: #34495e; 
            border: none; 
            border-radius: 12px; 
            padding: 12px 30px; 
            font-weight: 700;
            transition: all 0.3s;
        }
        .btn-save:hover { background-color: #2c3e50; transform: translateY(-2px); box-shadow: 0 10px 20px rgba(52, 73, 94, 0.2); }
    </style>
</head>
<body>

    <?php include 'sidebar.php'; ?>

    <div class="main-content">
        <div class="container-fluid">
            
            <div class="mb-4 d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="fw-bold text-dark mb-1">Editor Konten Reses</h2>
                    <p class="text-secondary mb-0">Sesuaikan narasi dan dokumentasi visual hasil reses.</p>
                </div>
                <a href="berita_admin.php" class="btn btn-white border shadow-sm px-3 rounded-pill fw-bold text-secondary">
                    <i class="fas fa-chevron-left me-2"></i> Kembali
                </a>
            </div>

            <form action="proses_edit_berita.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id_berita" value="<?php echo $data['id_berita']; ?>">

                <div class="row g-4">
                    <div class="col-lg-7">
                        <div class="card h-100">
                            <div class="card-header">
                                <span class="section-title">Detail Informasi</span>
                            </div>
                            <div class="card-body p-4">
                                <div class="mb-4">
                                    <label class="form-label">Judul Publikasi</label>
                                    <input type="text" name="judul" class="form-control" value="<?php echo $data['judul']; ?>" placeholder="Contoh: Reses Masa Sidang I Tahun 2024..." required>
                                </div>

                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label">Tanggal Pelaksanaan</label>
                                        <input type="date" name="tanggal" class="form-control" value="<?php echo $data['tanggal']; ?>" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Kecamatan / Wilayah</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0 rounded-start-3"><i class="fas fa-map-marker-alt text-danger"></i></span>
                                            <input type="text" name="kecamatan" class="form-control border-start-0" value="<?php echo $data['kecamatan']; ?>" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label">Lokasi Spesifik / Alamat</label>
                                    <textarea name="alamat_lengkap" class="form-control" rows="2" placeholder="Sebutkan nama gedung atau jalan..."><?php echo $data['alamat_lengkap']; ?></textarea>
                                </div>

                                <div class="mb-0">
                                    <label class="form-label text-primary">Isi Laporan & Aspirasi</label>
                                    <textarea name="deskripsi" 
                                              class="form-control" 
                                              style="min-height: 350px; resize: vertical; line-height: 1.8; font-size: 0.95rem;" 
                                              required><?php echo $data['deskripsi']; ?></textarea>
                                    <div class="form-text mt-2"><i class="fas fa-info-circle me-1"></i> Gunakan bahasa yang baku dan mudah dipahami publik.</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-5">
                        <div class="card h-100">
                            <div class="card-header bg-white">
                                <span class="section-title">Dokumentasi Barang</span>
                            </div>
                            <div class="card-body p-4 bg-light bg-opacity-50">
                                
                                <?php 
                                $id_berita = $data['id_berita'];
                                $qBarang = mysqli_query($koneksi, "SELECT * FROM barang_kegiatan WHERE id_berita = '$id_berita'");
                                
                                if(mysqli_num_rows($qBarang) > 0): 
                                    while($b = mysqli_fetch_assoc($qBarang)): 
                                        $file_barang = "../upload/" . $b['foto_barang'];
                                ?>
                                    <div class="item-barang-card">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="img-preview-container me-3">
                                                <?php if(!empty($b['foto_barang']) && file_exists($file_barang)): ?>
                                                    <img src="<?php echo $file_barang; ?>" class="img-barang-edit shadow-sm">
                                                <?php else: ?>
                                                    <div class="h-100 w-100 bg-secondary d-flex align-items-center justify-content-center text-white">
                                                        <i class="fas fa-image fa-lg"></i>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="fw-bold text-dark mb-1"><?php echo $b['nama_barang']; ?></h6>
                                                <span class="badge bg-white text-secondary border px-2 py-1" style="font-size: 10px;">UID: <?php echo $b['id_barang']; ?></span>
                                            </div>
                                        </div>
                                        
                                        <div class="bg-white p-2 rounded-3 border">
                                            <label class="form-label mb-1 ms-1" style="font-size: 10px;">Ganti Foto (Opsional)</label>
                                            <input type="hidden" name="id_barang[]" value="<?php echo $b['id_barang']; ?>">
                                            <input type="file" name="foto_barang_baru[]" class="form-control form-control-sm border-0 bg-light">
                                        </div>
                                    </div>
                                <?php 
                                    endwhile; 
                                else: ?>
                                    <div class="text-center py-5">
                                        <div class="bg-white d-inline-block p-4 rounded-circle shadow-sm mb-3">
                                            <i class="fas fa-box-open fa-3x text-light"></i>
                                        </div>
                                        <p class="text-muted small fw-medium">Tidak ada data foto barang.</p>
                                    </div>
                                <?php endif; ?>

                                <div class="alert bg-white border-0 shadow-sm mt-3 d-flex align-items-center">
                                    <i class="fas fa-lightbulb text-warning me-3 fs-4"></i>
                                    <div style="font-size: 11px;" class="text-secondary">
                                        <strong>Tips:</strong> Kosongkan input file jika Anda hanya ingin mengubah judul atau deskripsi saja.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mt-4 mb-5 shadow-sm border-0">
                    <div class="card-body p-3 d-flex justify-content-between align-items-center">
                        <div class="ps-3 d-none d-md-block">
                            <span class="text-muted small"><i class="fas fa-history me-1"></i> Perubahan terakhir akan langsung diperbarui ke database.</span>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="berita_admin.php" class="btn btn-light px-4 rounded-pill fw-bold">Batalkan</a>
                            <button type="submit" name="submit" class="btn btn-save text-white px-5 rounded-pill shadow">
                                <i class="fas fa-check-circle me-2"></i>Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>