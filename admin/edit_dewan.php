<?php 
session_start();
include '../config/db.php'; 

if(!isset($_SESSION['id_admin'])){
    header("Location: login.php");
    exit();
}

// Inisialisasi status untuk SweetAlert
$status = "";
$error_msg = "";

// Ambil ID dari URL
$id = mysqli_real_escape_string($koneksi, $_GET['id']);
$query = mysqli_query($koneksi, "SELECT * FROM dewan WHERE id_dewan = '$id'");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    header("Location: manajemen_dewan.php");
    exit;
}

// Proses Update
if (isset($_POST['update'])) {
    $nama     = mysqli_real_escape_string($koneksi, $_POST['nama_lengkap']);
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $fraksi   = mysqli_real_escape_string($koneksi, $_POST['fraksi']);
    $dapil    = mysqli_real_escape_string($koneksi, $_POST['dapil']);
    $periode  = mysqli_real_escape_string($koneksi, $_POST['periode']);
    
    // Flag untuk cek keberhasilan
    $success_flag = true;

    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        if(!mysqli_query($koneksi, "UPDATE dewan SET password='$password' WHERE id_dewan='$id'")) {
            $success_flag = false;
        }
    }

    $foto_nama = $_FILES['foto']['name'];
    if (!empty($foto_nama)) {
        $ext = pathinfo($foto_nama, PATHINFO_EXTENSION);
        $foto_baru = $username . "_" . time() . "." . $ext;
        
        if(move_uploaded_file($_FILES['foto']['tmp_name'], "../upload/dewan/" . $foto_baru)){
            if($data['foto'] && file_exists("../upload/dewan/".$data['foto'])) {
                unlink("../upload/dewan/".$data['foto']);
            }
            mysqli_query($koneksi, "UPDATE dewan SET foto='$foto_baru' WHERE id_dewan='$id'");
        } else {
            $success_flag = false;
            $error_msg = "Gagal mengunggah foto.";
        }
    }

    $sql = "UPDATE dewan SET 
            nama_lengkap = '$nama', 
            username = '$username', 
            fraksi = '$fraksi', 
            dapil = '$dapil', 
            periode = '$periode' 
            WHERE id_dewan = '$id'";

    if ($success_flag && mysqli_query($koneksi, $sql)) {
        $status = "success";
    } else {
        $status = "error";
        $error_msg = $error_msg ?: mysqli_error($koneksi);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil Dewan - ResesHub</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body { background-color: #f8fafc; font-family: 'Plus Jakarta Sans', sans-serif; color: #334155; }
        .bg-main { background-color: #34495e !important; }
        .main-content { margin-left: 250px; padding: 40px; min-height: 100vh; }
        .card-edit { border: none; border-radius: 24px; box-shadow: 0 20px 40px rgba(0,0,0,0.03); overflow: hidden; }
        .header-edit { background: linear-gradient(135deg, #34495e 0%, #1e293b 100%); padding: 40px; color: white; text-align: center; }
        .form-label { font-weight: 600; font-size: 0.85rem; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; }
        .form-control, .form-select { border-radius: 12px; padding: 12px 15px; border: 1px solid #e2e8f0; transition: all 0.2s; }
        .form-control:focus { border-color: #34495e; box-shadow: 0 0 0 4px rgba(52, 73, 94, 0.1); }
        .profile-upload-wrapper { position: relative; display: inline-block; margin-top: -60px; }
        .preview-img { width: 120px; height: 120px; object-fit: cover; border-radius: 30px; border: 5px solid #fff; box-shadow: 0 10px 20px rgba(0,0,0,0.1); background: #fff; }
        .btn-upload-icon { position: absolute; bottom: 5px; right: 5px; background: #34495e; color: white; width: 32px; height: 32px; border-radius: 10px; display: flex; align-items: center; justify-content: center; cursor: pointer; border: 3px solid #fff; }
        .btn-round { border-radius: 12px; padding: 12px 30px; font-weight: 600; }
    </style>
</head>
<body>

<?php include 'sidebar.php'; ?>

<div class="main-content">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="mb-4">
                    <a href="manajemen_dewan.php" class="text-decoration-none text-secondary small fw-bold">
                        <i class="fa fa-arrow-left me-2"></i> KEMBALI KE DAFTAR
                    </a>
                </div>

                <div class="card card-edit">
                    <div class="header-edit">
                        <h3 class="fw-bold mb-1">Edit Profil Anggota</h3>
                        <p class="mb-0 opacity-75">Perbarui informasi kredensial dan data dapil anggota dewan</p>
                    </div>
                    
                    <div class="card-body p-4 p-md-5 pt-0 text-center">
                        <form method="POST" enctype="multipart/form-data">
                            <div class="profile-upload-wrapper mb-5">
                                <img id="livePreview" src="../upload/dewan/<?= $data['foto'] ?: 'default.png' ?>" class="preview-img">
                                <label for="fotoInput" class="btn-upload-icon">
                                    <i class="fa fa-camera-retro"></i>
                                </label>
                                <input type="file" name="foto" id="fotoInput" class="d-none" accept="image/*" onchange="previewImage(this)">
                            </div>

                            <div class="row g-4 text-start">
                                <div class="col-md-6">
                                    <label class="form-label">Nama Lengkap & Gelar</label>
                                    <input type="text" name="nama_lengkap" class="form-control" value="<?= $data['nama_lengkap'] ?>" required>
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label">Username</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">@</span>
                                        <input type="text" name="username" class="form-control" value="<?= $data['username'] ?>" required>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label">Ganti Password <small class="text-muted fw-normal">(Kosongkan jika tidak ingin diubah)</small></label>
                                    <input type="password" name="password" class="form-control" placeholder="••••••••••••">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Fraksi</label>
                                    <input type="text" name="fraksi" class="form-control" value="<?= $data['fraksi'] ?>" required>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Dapil</label>
                                    <select name="dapil" class="form-select" required>
                                        <option value="" disabled>Pilih Dapil</option>
                                        <?php for($i=1; $i<=7; $i++): ?>
                                            <option value="<?= $i ?>" <?= ($data['dapil'] == $i) ? 'selected' : '' ?>>Dapil <?= $i ?></option>
                                        <?php endfor; ?>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Periode</label>
                                    <input type="text" name="periode" class="form-control" value="<?= $data['periode'] ?>" required>
                                </div>

                                <div class="col-12 mt-5 border-top pt-4 d-flex justify-content-between">
                                    <button type="button" onclick="window.location='manajemen_dewan.php'" class="btn btn-light px-4 btn-round">Batal</button>
                                    <button type="submit" name="update" class="btn btn-dark bg-main border-0 btn-round">
                                        <i class="fa fa-save me-2"></i> Simpan Perubahan
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Preview Image Logic
    function previewImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('livePreview').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // SweetAlert2 Notification Logic
    <?php if($status == "success"): ?>
        Swal.fire({
            title: 'Berhasil!',
            text: 'Data profil anggota dewan telah diperbarui.',
            icon: 'success',
            confirmButtonColor: '#34495e',
            borderRadius: '20px'
        }).then(() => {
            window.location = 'manajemen_dewan.php';
        });
    <?php elseif($status == "error"): ?>
        Swal.fire({
            title: 'Gagal Update!',
            text: '<?= $error_msg ?>',
            icon: 'error',
            confirmButtonColor: '#e74c3c',
            borderRadius: '20px'
        });
    <?php endif; ?>
</script>

</body>
</html>