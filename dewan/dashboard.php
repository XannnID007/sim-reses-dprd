<?php
session_start();
include '../config/db.php';

if(!isset($_SESSION['id_dewan'])){
    header("Location: login.php");
    exit();
}

$id_dewan = $_SESSION['id_dewan'];
// Mengambil data dewan terbaru
$query_dewan = mysqli_query($koneksi, "SELECT * FROM dewan WHERE id_dewan = '$id_dewan'");
$user = mysqli_fetch_assoc($query_dewan);

// Hitung statistik singkat
$reses_count = mysqli_num_rows(mysqli_query($koneksi, "SELECT id_berita FROM berita_reses WHERE id_dewan = '$id_dewan'"));
$published_count = mysqli_num_rows(mysqli_query($koneksi, "SELECT id_berita FROM berita_reses WHERE id_dewan = '$id_dewan' AND status='published'"));
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Dewan - ResesHub</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    
    <style>
        :root {
            --primary-color: #34495e;
            --accent-blue: #3498db;
            --bg-light: #f8fafc;
        }

        body { 
            background-color: var(--bg-light); 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            color: #334155;
        }

        .main-content { 
            margin-left: 260px; 
            padding: 40px; 
            min-height: 100vh; 
        }
        /* Welcome Banner */
        .welcome-banner {
            background: linear-gradient(135deg, #34495e 0%, #2c3e50 100%);
            border-radius: 24px;
            color: white;
            padding: 40px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(52, 73, 94, 0.2);
        }

        .welcome-banner i.bg-icon {
            position: absolute;
            right: -20px;
            bottom: -30px;
            font-size: 180px;
            opacity: 0.1;
            transform: rotate(-15deg);
        }

        /* Stats Mini Cards */
        .stat-badge {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(5px);
            border-radius: 12px;
            padding: 10px 20px;
            display: inline-flex;
            align-items: center;
            margin-right: 15px;
        }

        /* Table Design */
        .card-table {
            border: none;
            border-radius: 24px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.03);
            overflow: hidden;
            background: white;
        }

        .table thead th {
            background-color: #f8fafc;
            border: none;
            padding: 20px;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #64748b;
        }

        .table tbody td {
            padding: 20px;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
        }

        /* Status Badges */
        .badge-status {
            padding: 8px 16px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 0.7rem;
            letter-spacing: 0.5px;
        }
        .status-published { background: #dcfce7; color: #15803d; }
        .status-pending { background: #fef9c3; color: #a16207; }

        .date-box {
            background: #f1f5f9;
            border-radius: 12px;
            padding: 8px 15px;
            text-align: center;
            min-width: 70px;
        }

        @media (max-width: 992px) {
            .main-content { margin-left: 0; padding: 20px; }
        }
    </style>
</head>
<body>

<?php include 'sidebar.php'; ?>

<div class="main-content">
    <div class="container-fluid text-start">
        
        <div class="welcome-banner mb-5">
            <i class="fa-solid fa-landmark-dome bg-icon"></i>
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h6 class="text-uppercase fw-bold opacity-75 mb-2" style="letter-spacing: 2px;">Panel Anggota Dewan</h6>
                    <h1 class="fw-bold mb-3">Selamat Datang, <?= $_SESSION['nama_dewan']; ?></h1>
                    <p class="lead opacity-75 mb-4">Pantau dan kelola laporan kegiatan reses Anda dalam satu dashboard terintegrasi.</p>
                    
                    <div class="d-flex flex-wrap gap-2">
                        <div class="stat-badge">
                            <i class="fas fa-file-signature me-2"></i>
                            <span><strong><?= $reses_count ?></strong> Total Reses</span>
                        </div>
                        <div class="stat-badge">
                            <i class="fas fa-check-double me-2"></i>
                            <span><strong><?= $published_count ?></strong> Terpublikasi</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex align-items-center justify-content-between mb-4 px-2">
            <div>
                <h4 class="fw-bold mb-1 text-dark">Kalender Kegiatan</h4>
                <p class="text-muted small mb-0">Riwayat laporan kegiatan reses yang telah Anda inputkan.</p>
            </div>
            <a href="tambah_berita.php" class="btn btn-primary d-md-none rounded-circle p-3 shadow">
                <i class="fas fa-plus"></i>
            </a>
        </div>

        <div class="card card-table">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">Waktu</th>
                            <th>Judul Kegiatan</th>
                            <th class="text-center">Dapil</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = mysqli_query($koneksi, "SELECT * FROM berita_reses WHERE id_dewan = '$id_dewan' ORDER BY id_berita DESC");

                        if(mysqli_num_rows($query) > 0) {
                            while($row = mysqli_fetch_array($query)){
                                $is_published = ($row['status'] == 'published');
                        ?>
                        <tr>
                            <td class="ps-4">
                                <div class="date-box">
                                    <span class="d-block fw-bold text-dark mb-0"><?= date('d', strtotime($row['tanggal'])); ?></span>
                                    <small class="text-uppercase text-muted" style="font-size: 9px;"><?= date('M Y', strtotime($row['tanggal'])); ?></small>
                                </div>
                            </td>
                            <td>
                                <div class="fw-bold text-dark"><?= $row['judul']; ?></div>
                                <div class="text-muted small mt-1">
                                    <i class="fas fa-location-dot me-1 text-danger opacity-75"></i> Kecamatan <?= $row['kecamatan'] ?? 'Wilayah Reses'; ?>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-light text-dark border px-3 py-2 rounded-pill fw-bold" style="font-size: 11px;">
                                    DAPIL <?= $row['dapil']; ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge-status <?= $is_published ? 'status-published' : 'status-pending'; ?>">
                                    <i class="fas <?= $is_published ? 'fa-check-circle' : 'fa-clock'; ?> me-1"></i>
                                    <?= strtoupper($row['status']); ?>
                                </span>
                            </td>
                        </tr>
                        <?php 
                            } 
                        } else {
                            echo "<tr><td colspan='4' class='text-center py-5'>
                                    <div class='opacity-25 mb-3'><i class='fa-solid fa-folder-open fa-3x'></i></div>
                                    <p class='text-muted'>Anda belum menambahkan laporan kegiatan reses.</p>
                                  </td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// --- SCRIPT PENERIMA NOTIFIKASI ---
const urlParams = new URLSearchParams(window.location.search);
if (urlParams.has('status')) {
    const status = urlParams.get('status');
    const msg = urlParams.get('msg');
    
    if (status === 'success') {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: msg,
            confirmButtonColor: '#34495e'
        });
    } else if (status === 'error') {
        Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: msg,
            confirmButtonColor: '#34495e'
        });
    }

    // Bersihkan URL dari parameter status agar notif tidak muncul lagi saat refresh
    window.history.replaceState({}, document.title, window.location.pathname);
}
</script>

</body>
</html>