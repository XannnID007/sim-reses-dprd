<?php
session_start();
include "../config/db.php"; 

if(!isset($_SESSION['id_admin'])){
    header("Location: login.php");
    exit();
}

$filter_tgl = isset($_GET['tgl_cari']) ? $_GET['tgl_cari'] : '';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - ResesHub</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary-bg: #f8fafc;
            --sidebar-color: #34495e;
            --accent-blue: #3498db;
        }

        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background-color: var(--primary-bg); 
            color: #334155;
        }
        
        .main-content { margin-left: 250px; padding: 40px; min-height: 100vh; transition: all 0.3s; }

        .bg-main { background: linear-gradient(135deg, #34495e 0%, #2c3e50 100%) !important; }
        
        /* Stats Cards Modern */
        .card-stats { 
            border: none; 
            border-radius: 24px; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.02);
            transition: all 0.3s ease;
        }
        .card-stats:hover { transform: translateY(-7px); box-shadow: 0 15px 35px rgba(0,0,0,0.05); }

        .icon-shape {
            width: 64px;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 18px;
        }
        
        /* Table Glassmorphism Style */
        .card-container { 
            background: white;
            border: none; 
            border-radius: 24px; 
            box-shadow: 0 10px 40px rgba(0,0,0,0.03);
            overflow: hidden;
        }

        .table thead th {
            background-color: #f8fafc;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.7rem;
            letter-spacing: 1px;
            color: #94a3b8;
            border: none;
            padding: 20px;
        }

        .table tbody td { padding: 20px; border-bottom: 1px solid #f1f5f9; }

        /* Status Pills */
        .badge-pill-custom {
            padding: 8px 16px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 0.75rem;
            display: inline-flex;
            align-items: center;
        }
        .status-published { background: #ecfdf5; color: #059669; }
        .status-pending { background: #fffbeb; color: #d97706; }

        /* Filter Section */
        .filter-wrapper {
            background: white;
            padding: 20px;
            border-radius: 20px;
            border: 1px solid #e2e8f0;
        }

        @media (max-width: 992px) { .main-content { margin-left: 0; padding: 20px; } }
    </style>
</head>
<body>

<?php include 'sidebar.php'; ?>

<div class="main-content">
    <div class="container-fluid">
        <div class="row align-items-center mb-5">
            <div class="col-md-7">
                <h2 class="fw-extrabold text-dark mb-1">Pusat Monitoring Reses</h2>
                <p class="text-muted mb-0">Kelola dan verifikasi laporan kegiatan dewan secara real-time.</p>
            </div>
            <div class="col-md-5 text-md-end mt-3 mt-md-0">
                <div class="d-inline-flex align-items-center bg-white p-2 px-3 rounded-pill shadow-sm border">
                    <i class="fa-solid fa-clock-rotate-left text-primary me-2"></i>
                    <span class="fw-bold small"><?php echo date('d M Y'); ?></span>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-md-6 col-xl-4">
                <div class="card card-stats bg-main text-white h-100 p-3">
                    <div class="card-body d-flex align-items-center">
                        <div class="icon-shape bg-white bg-opacity-20 me-4">
                            <i class="fa-solid fa-paper-plane fa-xl text-white"></i>
                        </div>
                        <div>
                            <p class="mb-0 opacity-75 small fw-bold text-uppercase">Telah Terbit</p>
                            <?php 
                                $jml_berita = mysqli_num_rows(mysqli_query($koneksi, "SELECT id_berita FROM berita_reses WHERE status='published'"));
                            ?>
                            <h2 class="mb-0 fw-bold"><?php echo $jml_berita; ?> <span class="fs-6 fw-normal opacity-50">Laporan</span></h2>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-4">
                <div class="card card-stats bg-white h-100 p-3">
                    <div class="card-body d-flex align-items-center">
                        <div class="icon-shape bg-warning bg-opacity-10 me-4">
                            <i class="fa-solid fa-hourglass-half fa-xl text-warning"></i>
                        </div>
                        <div>
                            <p class="mb-0 text-muted small fw-bold text-uppercase">Menunggu Review</p>
                            <?php 
                                $jml_pending = mysqli_num_rows(mysqli_query($koneksi, "SELECT id_berita FROM berita_reses WHERE status='pending'"));
                            ?>
                            <h2 class="mb-0 fw-bold text-dark"><?php echo $jml_pending; ?> <span class="fs-6 fw-normal text-muted">Laporan</span></h2>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12 col-xl-4">
                <div class="card card-stats bg-white h-100 p-3">
                    <div class="card-body d-flex align-items-center">
                        <div class="icon-shape bg-info bg-opacity-10 me-4">
                            <i class="fa-solid fa-user-check fa-xl text-info"></i>
                        </div>
                        <div>
                            <p class="mb-0 text-muted small fw-bold text-uppercase">Total Anggota Dewan</p>
                            <?php 
                                $jml_dewan_db = mysqli_num_rows(mysqli_query($koneksi, "SELECT id_dewan FROM dewan"));
                            ?>
                            <h2 class="mb-0 fw-bold text-dark"><?php echo $jml_dewan_db; ?> <span class="fs-6 fw-normal text-muted">Anggota</span></h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="filter-wrapper shadow-sm mb-4 border-0 d-flex flex-wrap align-items-center justify-content-between">
            <div class="d-flex align-items-center mb-2 mb-md-0">
                <div class="bg-primary bg-opacity-10 p-2 rounded-3 me-3">
                    <i class="fas fa-sliders text-primary"></i>
                </div>
                <h6 class="mb-0 fw-bold text-dark">Filter Laporan</h6>
            </div>
            <form action="" method="GET" class="d-flex gap-2 w-100 w-md-auto mt-3 mt-md-0" style="max-width: 500px;">
                <input type="date" name="tgl_cari" class="form-control border-0 bg-light" value="<?php echo $filter_tgl; ?>">
                <button type="submit" class="btn btn-dark px-4 rounded-pill fw-bold">Cari</button>
                <?php if(!empty($filter_tgl)): ?>
                    <a href="dashboard.php" class="btn btn-outline-secondary rounded-pill"><i class="fas fa-sync"></i></a>
                <?php endif; ?>
            </form>
        </div>

        <div class="card card-container border-0 shadow-sm">
            <div class="p-4 border-bottom d-flex align-items-center">
                <h5 class="mb-0 fw-bold text-dark">Riwayat Kegiatan Terbaru</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th>Detail Kegiatan</th>
                            <th>Anggota Dewan</th>
                            <th>Wilayah Kerja</th>
                            <th>Waktu Input</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $no = 1;
                    $sql = "SELECT * FROM berita_reses";
                    if (!empty($filter_tgl)) { $sql .= " WHERE tanggal = '$filter_tgl'"; }
                    $sql .= " ORDER BY id_berita DESC";
                    $query = mysqli_query($koneksi, $sql);

                    if (mysqli_num_rows($query) > 0) {
                        while ($data = mysqli_fetch_array($query)) {
                            $is_published = ($data['status'] == 'published');
                    ?>
                        <tr>
                            <td class="text-center text-muted fw-bold"><?php echo $no++; ?></td>
                            <td>
                                <div class="fw-bold text-dark mb-0"><?php echo $data['judul']; ?></div>
                                <span class="badge bg-light text-muted fw-normal" style="font-size: 10px;">ID: #<?php echo $data['id_berita']; ?></span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-light rounded-pill me-3 d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                                        <i class="fas fa-user-tie text-secondary"></i>
                                    </div>
                                    <span class="fw-semibold text-dark"><?php echo $data['nama_dewan']; ?></span>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center text-muted small">
                                    <i class="fas fa-map-marker-alt text-danger me-2"></i>
                                    Kec. <?php echo $data['kecamatan']; ?>
                                </div>
                            </td>
                            <td>
                                <div class="small fw-medium text-dark"><?php echo date('d M Y', strtotime($data['tanggal'])); ?></div>
                            </td>
                            <td class="text-center">
                                <div class="badge-pill-custom <?php echo $is_published ? 'status-published' : 'status-pending'; ?>">
                                    <i class="fas <?php echo $is_published ? 'fa-check-circle' : 'fa-clock-rotate-left'; ?> me-2"></i>
                                    <?php echo $is_published ? 'Published' : 'Pending'; ?>
                                </div>
                            </td>
                        </tr>
                    <?php 
                        } 
                    } else {
                        echo "<tr><td colspan='6' class='text-center py-5'>
                                <div class='opacity-25 mb-3'><i class='fa-solid fa-folder-open fa-4x'></i></div>
                                <p class='text-muted'>Tidak ada data laporan yang ditemukan.</p>
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
</body>
</html>