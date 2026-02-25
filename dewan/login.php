<?php
session_start();
// Jika sudah login dan tidak sedang menampilkan pesan sukses, langsung ke dashboard
if(isset($_SESSION['id_dewan']) && !isset($_SESSION['login_success'])){
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Dewan - ResesHub</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #34495e;
            --secondary-color: #2c3e50;
            --bg-light: #f1f5f9;
        }

        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background-color: var(--bg-light); 
            height: 100vh; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            margin: 0; 
            overflow: hidden;
            position: relative;
        }

        /* Lingkaran Abstrak Soft di Background */
        body::before, body::after {
            content: "";
            position: absolute;
            width: 450px;
            height: 450px;
            border-radius: 50%;
            background: rgba(203, 213, 225, 0.4); 
            z-index: 0;
        }
        body::before { top: -150px; right: -100px; }
        body::after { bottom: -180px; left: -120px; }

        .login-card { 
            background: #ffffff;
            padding: 2.5rem; 
            border-radius: 2.5rem; 
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.08); 
            width: 100%; 
            max-width: 420px; 
            position: relative;
            z-index: 10;
            border: 1px solid rgba(255, 255, 255, 0.8);
        }

        .logo-container {
            width: 75px; height: 75px; background: white; border-radius: 20px;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 1.2rem; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.05);
        }

        h2 { font-weight: 800; color: var(--secondary-color); letter-spacing: -1px; margin-bottom: 0.2rem; }
        .form-label { font-weight: 700; color: #64748b; font-size: 0.75rem; letter-spacing: 0.5px; margin-bottom: 8px; }

        .input-group {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.3s;
        }

        .input-group:focus-within {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(52, 73, 94, 0.05);
            background-color: #fff;
        }

        .input-group-text { background: transparent; border: none; color: #94a3b8; padding-left: 1.2rem; }
        .form-control { background: transparent; border: none; padding: 0.8rem 1rem 0.8rem 0.5rem; font-size: 0.95rem; }
        .form-control:focus { box-shadow: none; background: transparent; }

        .btn-login { 
            background: var(--primary-color); border: none; border-radius: 12px; 
            padding: 0.9rem; font-weight: 700; color: white; transition: all 0.3s; margin-top: 0.5rem;
        }
        .btn-login:hover { background: var(--secondary-color); transform: translateY(-2px); box-shadow: 0 10px 15px rgba(52, 73, 94, 0.2); color: white; }
        
        .alert {
            border: none; border-radius: 12px; font-size: 0.8rem; font-weight: 600;
            padding: 0.7rem 1rem; display: flex; align-items: center; margin-bottom: 1.5rem;
        }
        .alert-danger { background-color: #fee2e2; color: #991b1b; }
        .alert-warning { background-color: #fff7ed; color: #9a3412; }
    </style>
</head>
<body>

<div class="login-card text-center">
    <div class="logo-container">
        <?php 
            $logo_dprd = "../upload/dprd.png"; 
            $fallback_logo = "https://upload.wikimedia.org/wikipedia/commons/b/b2/Lambang_Dewan_Perwakilan_Rakyat_Daerah.png";
        ?>
        <img src="<?= $logo_dprd ?>" onerror="this.src='<?= $fallback_logo ?>'" width="50" height="50" style="object-fit: contain;">
    </div>
    
    <h2>ResesHub</h2>
    <p class="text-muted small mb-4">Portal Aspirasi Anggota Dewan</p>

    <?php if(isset($_SESSION['error_dewan'])): ?>
        <div class="alert alert-danger alert-dismissible fade show text-start" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            <div><?= $_SESSION['error_dewan']; ?></div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" style="font-size: 0.5rem;"></button>
        </div>
        <?php unset($_SESSION['error_dewan']); ?>
    <?php endif; ?>

    <div id="js-error-notif" class="alert alert-warning alert-dismissible fade show text-start" role="alert" style="display:none;">
        <i class="fas fa-info-circle me-2"></i>
        <span id="error-message"></span>
    </div>

    <form id="loginFormDewan" action="../process/proses_login_dewan.php" method="POST">
        <div class="mb-3 text-start">
            <label class="form-label">USERNAME</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-user-circle"></i></span>
                <input type="text" name="username" id="username" class="form-control" placeholder="Username dewan" autocomplete="off">
            </div>
        </div>

        <div class="mb-4 text-start">
            <label class="form-label">PASSWORD</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-shield-alt"></i></span>
                <input type="password" name="password" id="passwordInput" class="form-control" placeholder="••••••••">
                <span class="input-group-text bg-transparent" style="cursor: pointer;" onclick="togglePass()">
                    <i class="fas fa-eye" id="eyeIcon"></i>
                </span>
            </div>
        </div>

        <button type="submit" name="login" class="btn btn-login w-100 mb-2">
            MASUK SEKARANG <i class="fas fa-arrow-right ms-2"></i>
        </button>
    </form>

    <div class="text-muted small mt-4">
        <i class="fas fa-info-circle me-1"></i> Sekretariat DPRD Kab/Kota
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Fitur Notifikasi Berhasil Login yang Menarik
    <?php if(isset($_SESSION['login_success'])): ?>
        Swal.fire({
            title: 'Berhasil Login!',
            text: '<?= $_SESSION['login_success']; ?>',
            icon: 'success',
            timer: 2000, // Muncul selama 2 detik
            showConfirmButton: false,
            allowOutsideClick: false,
            borderRadius: '20px',
            willClose: () => {
                window.location.href = "dashboard.php";
            }
        });
        <?php unset($_SESSION['login_success']); ?>
    <?php endif; ?>

    document.getElementById('loginFormDewan').addEventListener('submit', function(e) {
        const user = document.getElementById('username').value;
        const pass = document.getElementById('passwordInput').value;
        const notif = document.getElementById('js-error-notif');
        const msg = document.getElementById('error-message');

        if (user === "" || pass === "") {
            e.preventDefault();
            msg.innerText = "Username dan Password wajib diisi!";
            notif.style.display = 'flex';
        }
    });

    function togglePass() {
        const passInput = document.getElementById('passwordInput');
        const eyeIcon = document.getElementById('eyeIcon');
        if (passInput.type === 'password') {
            passInput.type = 'text';
            eyeIcon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            passInput.type = 'password';
            eyeIcon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }
</script>
</body>
</html>