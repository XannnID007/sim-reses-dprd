<div class="d-flex flex-column flex-shrink-0 p-4 text-white bg-sidebar sidebar-fixed">
    <a href="dashboard.php" class="d-flex align-items-center mb-4 me-md-auto text-white text-decoration-none px-2">
        <div class="bg-white rounded-3 p-2 me-3 shadow-sm">
            <i class="fa-solid fa-landmark text-sidebar fs-5"></i>
        </div>
        <span class="fs-5 fw-800 tracking-wider">RESESHUB</span>
    </a>

    <div class="profile-card-sidebar text-center mb-4 p-3 rounded-4">
        <?php 
            $foto_session = $_SESSION['foto'] ?? ''; 
            $path_foto = "../upload/dewan/" . $foto_session;
            $fallback_logo = "https://upload.wikimedia.org/wikipedia/commons/b/b2/Lambang_Dewan_Perwakilan_Rakyat_Daerah.png";

            if (!empty($foto_session) && file_exists($path_foto)) {
                $gambar_profil = $path_foto;
            } else {
                $gambar_profil = $fallback_logo;
            }
        ?>
        
        <div class="position-relative d-inline-block mb-3">
            <img src="<?= $gambar_profil ?>" 
                 onerror="this.src='<?= $fallback_logo ?>'" 
                 class="rounded-circle shadow-lg border border-3 border-white-50" 
                 width="75" height="75" 
                 style="object-fit: cover;">
            <span class="position-absolute bottom-0 end-0 p-1 bg-success border border-2 border-white rounded-circle ripple-status"></span>
        </div>
        
        <div class="small fw-bold text-white text-uppercase ls-wide" style="font-size: 11px; line-height: 1.4;">
            <?= $_SESSION['nama_dewan'] ?? 'Anggota Dewan' ?>
        </div>
        <div class="badge bg-soft-light mt-1" style="font-size: 10px; font-weight: 400;">
            ID : <?= $_SESSION['id_dewan'] ?? '-' ?>
        </div>
    </div>

    <ul class="nav nav-pills flex-column mb-auto">
        <?php $current_page = basename($_SERVER['PHP_SELF']); ?>
        <li class="nav-item">
            <a href="dashboard.php" class="nav-link text-white <?= $current_page == 'dashboard.php' ? 'active' : '' ?>">
                <i class="fa-solid fa-house-chimney me-3"></i> Dashboard
            </a>
        </li>
        <li>
            <a href="tambah_berita.php" class="nav-link text-white <?= $current_page == 'tambah_berita.php' ? 'active' : '' ?>">
                <i class="fa-solid fa-pen-to-square me-3"></i> Buat Laporan
            </a>
        </li>
        <li>
            <a href="pengaturan.php" class="nav-link text-white <?= $current_page == 'pengaturan.php' ? 'active' : '' ?>">
                <i class="fa-solid fa-user-gear me-3"></i> Pengaturan
            </a>
        </li>
    </ul>
    
    <div class="mt-auto pt-4 border-top border-white-10">
        <a href="#" class="logout-btn d-flex align-items-center justify-content-center text-decoration-none shadow-sm" id="btn-logout">
            <i class="fa-solid fa-power-off me-2"></i> Keluar Sesi
        </a>
    </div>
</div>

<style>
    /* WARNA UTAMA SIDEBAR DIUBAH DISINI */
    .bg-sidebar { 
        background-color: #1e293b !important; /* Warna biru gelap sesuai gambar dashboard */
    } 
    
    .text-sidebar { color: #1e293b !important; }
    
    .sidebar-fixed { 
        width: 260px; 
        height: 100vh; 
        position: fixed; 
        left: 0; 
        top: 0; 
        z-index: 1050; 
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    /* Perbaikan agar teks tidak transparan/putih di latar putih */
    .profile-card-sidebar {
        background: rgba(255, 255, 255, 0.08); /* Sedikit lebih terang agar kontras */
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .nav-link { 
        color: rgba(255, 255, 255, 0.7) !important; 
        transition: 0.3s;
        border-radius: 12px;
        margin-bottom: 5px;
    }

    .nav-link:hover {
        background: rgba(255, 255, 255, 0.1);
        color: #fff !important;
    }

    .nav-link.active {
        background: #3b82f6 !important; /* Warna biru tombol aktif */
        color: #fff !important;
    }

    .logout-btn {
        background: rgba(239, 68, 68, 0.1);
        color: #f87171;
        padding: 12px;
        border-radius: 12px;
        transition: 0.3s;
        border: 1px solid rgba(239, 68, 68, 0.2);
    }

    .logout-btn:hover {
        background: #ef4444;
        color: white;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.getElementById('btn-logout').addEventListener('click', function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Keluar Sesi?',
            text: "Anda akan mengakhiri sesi akses dewan saat ini.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Keluar',
            cancelButtonText: 'Batal',
            borderRadius: '1.2rem'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "logout.php";
            }
        });
    });
</script>