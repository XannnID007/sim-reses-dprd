<?php
session_start();
include "../config/db.php"; 

if(!isset($_SESSION['id_admin'])){
    header("Location: login.php");
    exit();
}

// Proses Simpan Data
if(isset($_POST['simpan_dewan'])){
    $nama     = mysqli_real_escape_string($koneksi, $_POST['nama_lengkap']);
    $user     = mysqli_real_escape_string($koneksi, $_POST['username']);
    $fraksi   = mysqli_real_escape_string($koneksi, $_POST['fraksi']);
    $dapil    = mysqli_real_escape_string($koneksi, $_POST['dapil']);
    $periode  = mysqli_real_escape_string($koneksi, $_POST['periode']);
    $pass     = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Logika Upload Foto
    $foto_name = $_FILES['foto']['name'];
    $tmp_name  = $_FILES['foto']['tmp_name'];
    
    $ekstensi  = pathinfo($foto_name, PATHINFO_EXTENSION);
    $foto_baru = $user . "_" . time() . "." . $ekstensi;
    $target_dir = "../upload/dewan/";

    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    if(move_uploaded_file($tmp_name, $target_dir . $foto_baru)){
        $query = "INSERT INTO dewan (nama_lengkap, username, password, fraksi, dapil, periode, foto) 
                  VALUES ('$nama', '$user', '$pass', '$fraksi', '$dapil', '$periode', '$foto_baru')";
        
        if(mysqli_query($koneksi, $query)){
            echo "<script>alert('Data Dewan Berhasil Disimpan!'); window.location='manajemen_dewan.php';</script>";
        }
    } else {
        echo "<script>alert('Gagal mengunggah foto. Pastikan folder upload tersedia.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Dewan - ResesHub</title>
    
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
        .text-main { color: #34495e !important; }
        
        .main-content { margin-left: 250px; padding: 40px; min-height: 100vh; }
        
        /* Modern Form & Card */
        .card { border: none; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.02); }
        
        .form-label { font-weight: 600; font-size: 0.9rem; color: #475569; margin-bottom: 8px; }
        
        .form-control, .form-select {
            border-radius: 12px;
            padding: 12px 15px;
            border: 1px solid #e2e8f0;
            background-color: #fcfcfc;
            transition: all 0.2s ease;
        }

        .form-control:focus, .form-select:focus {
            background-color: #fff;
            border-color: #34495e;
            box-shadow: 0 0 0 4px rgba(52, 73, 94, 0.1);
        }

        .preview-container {
            border: 2px dashed #e2e8f0;
            border-radius: 15px;
            padding: 10px;
            text-align: center;
            background: #fff;
        }

        #img-preview {
            max-width: 100%;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }

        .info-card {
            background: linear-gradient(135deg, #34495e 0%, #2c3e50 100%);
            color: white;
            border-radius: 20px;
            border: none;
        }

        .btn-round { border-radius: 12px; padding: 12px 25px; font-weight: 600; }
    </style>
</head>
<body>

<?php include 'sidebar.php'; ?>

<div class="main-content">
    <div class="container-fluid">
        <div class="mb-4">
            <a href="manajemen_dewan.php" class="text-decoration-none text-secondary small fw-bold">
                <i class="fa fa-arrow-left me-2"></i> KEMBALI KE DAFTAR
            </a>
            <h2 class="fw-bold text-dark mt-2">Registrasi Anggota Dewan</h2>
            <p class="text-muted">Lengkapi formulir di bawah untuk menambahkan data dewan baru ke sistem.</p>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow-sm p-4 mb-4">
                    <form method="POST" enctype="multipart/form-data">
                        <div class="row g-4">
                            <div class="col-12">
                                <label class="form-label">Nama Lengkap & Gelar</label>
                                <input type="text" name="nama_lengkap" class="form-control" placeholder="Contoh: H. Sugih, S.T., M.Si." required>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Username (ID Akses)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0" style="border-radius: 12px 0 0 12px;">@</span>
                                    <input type="text" name="username" class="form-control" style="border-radius: 0 12px 12px 0;" placeholder="sugih_dprd" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Password Sementara</label>
                                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Fraksi</label>
                                <input type="text" name="fraksi" class="form-control" placeholder="Contoh: Fraksi Partai Golkar" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Wilayah Pemilihan (Dapil)</label>
                                <select name="dapil" class="form-select" required>
                                    <option value="" selected disabled>Pilih Dapil...</option>
                                    <?php for($i=1; $i<=7; $i++): ?>
                                        <option value="Bandung <?= $i ?>">Bandung <?= $i ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Periode Jabatan</label>
                                <input type="text" name="periode" class="form-control" placeholder="2024-2029" required>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Foto Profil Resmi</label>
                                <input type="file" name="foto" class="form-control" accept="image/*" required onchange="previewImage(this)">
                                <div class="form-text mt-2"><i class="fa fa-info-circle me-1"></i> Gunakan foto formal latar belakang polos. Maks 2MB.</div>
                                
                                <div id="imagePreview" class="mt-4 d-none">
                                    <p class="small fw-bold text-muted mb-2">Preview Foto:</p>
                                    <div class="preview-container" style="width: 180px;">
                                        <img id="img-preview" src="#" alt="Preview">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-5 pt-3 border-top d-flex justify-content-between align-items-center">
                            <button type="reset" class="btn btn-light text-secondary btn-round">Reset Form</button>
                            <button type="submit" name="simpan_dewan" class="btn btn-dark bg-main border-0 btn-round">
                                <i class="fa fa-save me-2"></i> Simpan Anggota
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="card info-card p-4 shadow-sm">
                    <h5 class="fw-bold mb-3"><i class="fa fa-lightbulb me-2 text-warning"></i> Panduan Input</h5>
                    <div class="small opacity-75">
                        <div class="mb-3">
                            <h6 class="fw-bold mb-1">Identitas</h6>
                            <p>Pastikan nama lengkap menyertakan gelar akademik yang sah untuk keperluan publikasi.</p>
                        </div>
                        <div class="mb-3">
                            <h6 class="fw-bold mb-1">Akses Login</h6>
                            <p>Username tidak boleh menggunakan spasi. Password akan dienkripsi secara aman di database.</p>
                        </div>
                        <div class="mb-0">
                            <h6 class="fw-bold mb-1">Kualitas Foto</h6>
                            <p>Sangat disarankan menggunakan foto dengan aspek rasio 3:4 agar tampil sempurna pada halaman profil publik.</p>
                        </div>
                    </div>
                </div>

                <div class="card mt-4 border-0 bg-light p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fa fa-shield-halved fa-2x text-main opacity-25"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="fw-bold mb-0 text-dark">Data Aman</h6>
                            <p class="small text-muted mb-0">Semua data terenkripsi SSL</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function previewImage(input) {
        const preview = document.getElementById('imagePreview');
        const img = document.getElementById('img-preview');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                img.src = e.target.result;
                preview.classList.remove('d-none');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>