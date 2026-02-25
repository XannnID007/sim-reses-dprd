<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Admin - ResesHub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Inter', sans-serif; background: linear-gradient(135deg, #34495e 0%, #2c3e50 100%); height: 100vh; display: flex; align-items: center; }
        .card { border-radius: 1.5rem; border: none; box-shadow: 0 10px 25px rgba(0,0,0,0.2); }
        .form-control { border-radius: 0.75rem; padding: 0.6rem 1rem; }
        .btn-main { background-color: #34495e; color: white; border-radius: 0.75rem; font-weight: 600; }
        .btn-main:hover { background-color: #2c3e50; color: white; }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card p-4">
                <div class="text-center mb-4">
                    <i class="fa-solid fa-user-plus fa-3x mb-2" style="color: #34495e;"></i>
                    <h3 class="fw-bold">Register Admin</h3>
                    <p class="text-muted small">Buat akun untuk mengelola data reses</p>
                </div>

                <form action="../process/proses_register.php" method="POST">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">NIP / No. Pegawai</label>
                        <input type="text" name="nip" class="form-control" placeholder="19850101..." required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control" placeholder="Nama tanpa gelar" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold">Username</label>
                            <input type="text" name="user_admin" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold">Jabatan</label>
                            <input type="text" name="jabatan" class="form-control" placeholder="Contoh: Staf IT" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="admin@dprd.go.id" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label small fw-bold">Konfirmasi</label>
                            <input type="password" name="konfirmasi" class="form-control" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-main w-100 mb-3">Daftar Sekarang</button>
                    <div class="text-center">
                        <small class="text-muted">Sudah punya akun? <a href="login.php" class="text-decoration-none">Login</a></small>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>