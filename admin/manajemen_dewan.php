<?php
session_start();
include "../config/db.php"; 

if(!isset($_SESSION['id_admin'])){
    header("Location: login.php");
    exit();
}


$status = "";

if(isset($_POST['simpan_dewan'])){
    $nama     = mysqli_real_escape_string($koneksi, $_POST['nama_lengkap']);
    $user     = mysqli_real_escape_string($koneksi, $_POST['username']);
    $fraksi   = mysqli_real_escape_string($koneksi, $_POST['fraksi']);
    $dapil    = mysqli_real_escape_string($koneksi, $_POST['dapil']);
    $periode  = mysqli_real_escape_string($koneksi, $_POST['periode']);
    $pass     = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $foto_name = $_FILES['foto']['name'];
    $tmp_name  = $_FILES['foto']['tmp_name'];
    
    $ekstensi  = pathinfo($foto_name, PATHINFO_EXTENSION);
    $foto_baru = $user . "_" . time() . "." . $ekstensi;
    $target_dir = "../upload/dewan/";

    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    if(move_uploaded_file($tmp_name, $target_dir . $foto_baru)){
        $insert = mysqli_query($koneksi, "INSERT INTO dewan (nama_lengkap, username, password, fraksi, dapil, periode, foto) 
                  VALUES ('$nama', '$user', '$pass', '$fraksi', '$dapil', '$periode', '$foto_baru')");
        
        if($insert){
            $status = "success"; // Sinyal sukses
        } else {
            $status = "error_db"; // Sinyal error database
        }
    } else {
        $status = "error_upload"; // Sinyal error upload
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Dewan - ResesHub</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body { 
            background-color: #f8fafc; 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            color: #334155;
        }

        .bg-main { background-color: #34495e !important; }
        .main-content { margin-left: 250px; padding: 40px; min-height: 100vh; }
        
        /* Modern Card & Table */
        .card { border: none; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.02); overflow: hidden; }
        
        .table thead th {
            background-color: #f1f5f9;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
            color: #64748b;
            border: none;
            padding: 15px 20px;
        }

        .table tbody tr { transition: all 0.2s ease; border-bottom: 1px solid #f1f5f9; }
        .table tbody tr:hover { background-color: #f8fafc; box-shadow: inset 4px 0 0 #34495e; }
        .table tbody td { padding: 15px 20px; }

        /* Profile Image Styling */
        .img-profile { 
            width: 45px; height: 45px; 
            object-fit: cover; 
            border-radius: 12px; 
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        /* Form Styling */
        .form-control, .form-select {
            border-radius: 10px;
            padding: 10px 15px;
            border: 1px solid #e2e8f0;
        }
        .form-control:focus {
            box-shadow: 0 0 0 3px rgba(52, 73, 94, 0.1);
            border-color: #34495e;
        }

        /* Modal Customization */
        .modal-content { border-radius: 20px; border: none; }
        .modal-header { border-bottom: 1px solid #f1f5f9; padding: 20px 30px; }
    </style>
</head>
<body>

<?php include 'sidebar.php'; ?>

<div class="main-content">
    <div class="container-fluid">
        <div class="mb-4 d-flex justify-content-between align-items-center">
            <div>
                <h2 class="fw-bold text-dark mb-1">Data Anggota Dewan</h2>
                <p class="text-secondary mb-0">Kelola informasi profil dan akun akses anggota dewan.</p>
            </div>
            <button class="btn btn-dark bg-main border-0 px-4 py-2 rounded-pill shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
                <i class="fa fa-plus-circle me-2"></i>Tambah Dewan
            </button>
        </div>

        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="text-center">NO</th>
                                <th>PROFIL</th>
                                <th>NAMA LENGKAP</th>
                                <th>FRAKSI</th>
                                <th>DAPIL</th>
                                <th>PERIODE</th>
                                <th class="text-center">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $query = mysqli_query($koneksi, "SELECT * FROM dewan ORDER BY id_dewan ASC");
                            while($row = mysqli_fetch_array($query)){
                            ?>
                            <tr>
                                <td class="text-center fw-bold text-secondary"><?= $no++; ?></td>
                                <td>
                                    <img src="../upload/dewan/<?= $row['foto']; ?>" class="img-profile" alt="Foto">
                                </td>
                                <td>
                                    <div class="fw-bold text-dark"><?= $row['nama_lengkap']; ?></div>
                                    <small class="text-muted">@<?= $row['username']; ?></small>
                                </td>
                                <td><span class="badge bg-white text-dark border shadow-sm px-3"><?= $row['fraksi']; ?></span></td>
                                <td><span class="fw-medium text-secondary">Dapil <?= $row['dapil']; ?></span></td>
                                <td><span class="text-muted"><?= $row['periode']; ?></span></td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="edit_dewan.php?id=<?= $row['id_dewan']; ?>" 
                                           class="btn btn-sm btn-outline-warning rounded-8 px-3" title="Edit Data">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        
                                           <a href="javascript:void(0)" 
                                            onclick="confirmDelete('<?= $row['nama_lengkap']; ?>', 'hapus_dewan.php?id=<?= $row['id_dewan']; ?>')" 
                                            class="btn btn-sm btn-outline-danger rounded-8 px-3" 
                                            title="Hapus Data">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" enctype="multipart/form-data" class="modal-content shadow-lg">
            <div class="modal-header">
                <h5 class="modal-title fw-bold text-dark">Registrasi Anggota Dewan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="form-label fw-600">Foto Profil Resmi</label>
                    <input type="file" name="foto" class="form-control" required accept="image/*">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-600">Nama Lengkap & Gelar</label>
                    <input type="text" name="nama_lengkap" class="form-control" required placeholder="Contoh: H. Ahmad, S.E.">
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-6">
                        <label class="form-label fw-600">Username</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-600">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-600">Fraksi Partai</label>
                    <input type="text" name="fraksi" class="form-control" required placeholder="Contoh: F-Golkar">
                </div>
                <div class="row g-3">
                    <div class="col-6">
                        <label class="form-label fw-600">Dapil</label>
                        <select name="dapil" class="form-select" required>
                            <option value="" selected disabled>Pilih Dapil</option>
                            <option value="1">Dapil 1</option>
                            <option value="2">Dapil 2</option>
                            <option value="3">Dapil 3</option>
                            <option value="4">Dapil 4</option>
                            <option value="5">Dapil 5</option>
                            <option value="6">Dapil 6</option>
                            <option value="7">Dapil 7</option>
                        </select>
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-600">Periode Jabatan</label>
                        <input type="text" name="periode" class="form-control" required placeholder="2024-2029">
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-light px-4 rounded-pill" data-bs-dismiss="modal">Batal</button>
                <button type="submit" name="simpan_dewan" class="btn btn-dark bg-main px-4 rounded-pill">Simpan Data</button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// Logika Notifikasi Berdasarkan Variabel Status PHP
<?php if($status == "success"): ?>
    Swal.fire({
        title: 'Berhasil!',
        text: 'Data Anggota Dewan telah resmi terdaftar dalam sistem.',
        icon: 'success',
        confirmButtonColor: '#34495e',
        borderRadius: '20px',
        showConfirmButton: true,
        timer: 3000
    }).then(() => {
        window.location = 'manajemen_dewan.php';
    });
<?php elseif($status == "error_db"): ?>
    Swal.fire({
        title: 'Gagal Database!',
        text: 'Terjadi kesalahan saat menyimpan data ke database.',
        icon: 'error',
        confirmButtonColor: '#e74c3c'
    });
<?php elseif($status == "error_upload"): ?>
    Swal.fire({
        title: 'Gagal Upload!',
        text: 'Foto profil tidak dapat diunggah ke server.',
        icon: 'warning',
        confirmButtonColor: '#f39c12'
    });
<?php endif; ?>

// Tambahan: Percantik konfirmasi hapus
function confirmDelete(name, link) {
    Swal.fire({
        title: 'Hapus Data?',
        text: "Anda akan menghapus " + name + ". Tindakan ini tidak dapat dibatalkan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e74c3c',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal',
        borderRadius: '20px'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = link;
        }
    })
    return false;
}
</script>
</body>
</html>