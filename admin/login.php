<?php session_start(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - ResesHub</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --admin-navy: #243447; /* Warna navy sesuai gambar */
            --accent-color: #3e4e5f;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--admin-navy);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            overflow: hidden;
            position: relative;
        }

        /* Elemen Lingkaran Abstrak di Background */
        body::before, body::after {
            content: "";
            position: absolute;
            width: 400px;
            height: 400px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.03);
            z-index: 0;
        }
        body::before { top: -100px; right: -100px; }
        body::after { bottom: -150px; left: -150px; }

        .login-card {
            background: #ffffff;
            padding: 2.5rem;
            border-radius: 2rem;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            position: relative;
            z-index: 10;
        }

        .brand-icon {
            width: 70px; height: 70px; 
            background: #f8fafc; 
            display: flex; align-items: center; justify-content: center;
            border-radius: 18px; margin: 0 auto 1rem; 
            font-size: 2rem; color: var(--admin-navy);
            box-shadow: inset 0 0 10px rgba(0,0,0,0.05);
        }

        h2 { color: var(--admin-navy); font-weight: 800; margin-bottom: 0.2rem; }
        .text-secondary { font-size: 0.85rem; }

        /* NOTIFIKASI */
        .alert {
            border: none;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: 600;
            padding: 0.6rem 1rem;
            display: flex;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        .alert-danger { background-color: #fee2e2; color: #991b1b; }
        .alert-warning { background-color: #fff7ed; color: #9a3412; }

        /* FORM */
        .form-label { 
            font-weight: 700; 
            color: #64748b; 
            font-size: 0.75rem; 
            margin-bottom: 8px;
            display: block;
        }
        
        .input-group {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.3s;
        }

        .input-group:focus-within {
            border-color: var(--admin-navy);
            box-shadow: 0 0 0 3px rgba(36, 52, 71, 0.1);
        }

        .input-group-text {
            background: transparent;
            border: none;
            color: #94a3b8;
            padding-left: 1rem;
        }

        .form-control {
            background: transparent;
            border: none;
            padding: 0.75rem 1rem 0.75rem 0.5rem;
            font-size: 0.9rem;
        }

        .form-control:focus {
            box-shadow: none;
            background: transparent;
        }

        .btn-login {
            background-color: var(--admin-navy);
            border: none;
            border-radius: 12px;
            padding: 0.85rem;
            color: white;
            font-weight: 700;
            width: 100%;
            margin-top: 1rem;
            transition: 0.3s;
        }

        .btn-login:hover {
            background-color: #1a252f;
            transform: translateY(-2px);
            box-shadow: 0 8px 15px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body>

<div class="login-card text-center">
    <div class="brand-icon">
        <i class="fa-solid fa-landmark-dome"></i>
    </div>
    
    <h2>ResesHub</h2>
    <p class="text-secondary mb-4">Portal Administrasi Aspirasi Rakyat</p>

    <?php if(isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show text-start" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            <div><?= $_SESSION['error']; ?></div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" style="font-size: 0.5rem;"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div id="js-error-notif" class="alert alert-warning alert-dismissible fade show text-start" role="alert" style="display:none;">
        <i class="fas fa-info-circle me-2"></i>
        <span id="error-message"></span>
    </div>

    <form id="loginForm" action="../process/proses_login.php" method="POST">
        <div class="mb-3 text-start">
            <label class="form-label">NIP / No. Pegawai</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                <input type="text" name="nip" id="nip" class="form-control" placeholder="Masukkan NIP">
            </div>
        </div>

        <div class="mb-3 text-start">
            <label class="form-label">Username</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-user"></i></span>
                <input type="text" name="user_admin" id="user_admin" class="form-control" placeholder="Username admin">
            </div>
        </div>

        <div class="mb-4 text-start">
            <label class="form-label">Password</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                <input type="password" name="password" id="password" class="form-control" placeholder="••••••••">
            </div>
        </div>

        <button type="submit" class="btn btn-login">
            Masuk Ke Dashboard <i class="fas fa-arrow-right ms-2"></i>
        </button>
    </form>
</div>

<script>
    document.getElementById('loginForm').addEventListener('submit', function(e) {
        const nip = document.getElementById('nip').value;
        const user = document.getElementById('user_admin').value;
        const pass = document.getElementById('password').value;
        const notif = document.getElementById('js-error-notif');
        const msg = document.getElementById('error-message');

        if (nip === "" || user === "" || pass === "") {
            e.preventDefault();
            msg.innerText = "Semua input wajib diisi!";
            notif.style.display = 'flex';
        }
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>