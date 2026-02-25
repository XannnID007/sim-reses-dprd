<div class="d-flex flex-column flex-shrink-0 p-4 text-white bg-sidebar-admin sidebar-fixed">
    <a href="dashboard.php" class="d-flex align-items-center mb-4 text-white text-decoration-none">
        <div class="bg-white rounded-3 p-2 me-2 shadow-sm d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
            <i class="fa-solid fa-landmark text-dark fs-5"></i>
        </div>
        <span class="fs-5 fw-bold tracking-tight">RESESHUB</span>
    </a>

    <div class="profile-card mb-4 text-center">
        <?php 
            $logo_dprd = "../upload/dprd.png"; 
            $fallback_logo = "https://upload.wikimedia.org/wikipedia/commons/b/b2/Lambang_Dewan_Perwakilan_Rakyat_Daerah.png";
        ?>
        <div class="position-relative d-inline-block mb-3">
            <img src="<?= $logo_dprd ?>" 
                 onerror="this.src='<?= $fallback_logo ?>'" 
                 class="rounded-circle shadow-lg bg-white p-2" 
                 width="80" height="80" 
                 style="object-fit: contain; border: 2px solid rgba(255,255,255,0.2);">
            <span class="position-absolute bottom-0 end-0 bg-success border border-2 border-white rounded-circle p-2"></span>
        </div>
        
        <h6 class="mb-0 fw-bold text-white"><?= $_SESSION['user_admin'] ?? 'Administrator' ?></h6>
        <p class="mb-0 opacity-50" style="font-size: 11px; letter-spacing: 1px;">ID: <?= $_SESSION['nip'] ?? '123456789' ?></p>
    </div>

    <ul class="nav nav-pills flex-column mb-auto pt-2">
        <?php $current_page = basename($_SERVER['PHP_SELF']); ?>
        <li class="nav-item mb-2">
            <a href="dashboard.php" class="nav-link py-3 px-3 <?= $current_page == 'dashboard.php' ? 'active' : '' ?>">
                <i class="fa-solid fa-grid-2 me-3 opacity-75"></i> Dashboard
            </a>
        </li>
        <li class="nav-item mb-2">
            <a href="berita_admin.php" class="nav-link py-3 px-3 <?= ($current_page == 'berita_admin.php' || $current_page == 'tambah_berita.php' || $current_page == 'edit_berita.php') ? 'active' : '' ?>">
                <i class="fa-solid fa-newspaper me-3 opacity-75"></i> Manajemen Berita
            </a>
        </li>
        <li class="nav-item mb-2">
            <a href="manajemen_dewan.php" class="nav-link py-3 px-3 <?= ($current_page == 'manajemen_dewan.php') ? 'active' : '' ?>">
                <i class="fa-solid fa-users-viewfinder me-3 opacity-75"></i> Manajemen Dewan
            </a>
        </li>
        <li class="nav-item mb-2">
            <a href="pengaturan.php" class="nav-link py-3 px-3 <?= $current_page == 'pengaturan.php' ? 'active' : '' ?>">
                <i class="fa-solid fa-sliders me-3 opacity-75"></i> Pengaturan
            </a>
        </li>
    </ul>

    <div class="mt-auto">
        <a href="javascript:void(0)" class="btn btn-logout w-100 d-flex align-items-center justify-content-center" id="btn-logout-admin">
            <i class="fa-solid fa-power-off me-2"></i> Keluar Sistem
        </a>
    </div>
</div>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap');

    /* Memastikan Background Sidebar Biru Gelap sesuai gambar Dashboard Admin */
    .bg-sidebar-admin {
        background: #2c3e50 !important; /* Warna dasar biru tua */
    }

    .sidebar-fixed {
        width: 260px;
        height: 100vh;
        position: fixed;
        left: 0;
        top: 0;
        z-index: 1050;
        background: linear-gradient(180deg, #34495e 0%, #2c3e50 100%) !important;
        box-shadow: 4px 0 15px rgba(0,0,0,0.1);
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .profile-card {
        padding: 15px;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 15px;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .nav-link {
        color: rgba(255, 255, 255, 0.7) !important;
        border-radius: 12px !important;
        transition: all 0.3s ease;
        font-weight: 500;
        font-size: 0.95rem;
    }

    .nav-link:hover {
        background-color: rgba(255, 255, 255, 0.1) !important;
        color: #fff !important;
        transform: translateX(5px);
    }

    .nav-link.active {
        background: rgba(255, 255, 255, 0.15) !important;
        color: #fff !important;
        font-weight: 700;
        position: relative;
    }

    .btn-logout {
        background: rgba(231, 76, 60, 0.1);
        color: #e74c3c;
        border: 1px solid rgba(231, 76, 60, 0.2);
        padding: 12px;
        border-radius: 12px;
        font-weight: 700;
        transition: 0.3s;
    }

    .btn-logout:hover {
        background: #e74c3c;
        color: white;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.getElementById('btn-logout-admin').addEventListener('click', function(e) {
        Swal.fire({
            title: 'Keluar Sistem?',
            text: "Anda akan mengakhiri sesi Administrator.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e74c3c',
            cancelButtonColor: '#34495e',
            confirmButtonText: 'Ya, Keluar',
            cancelButtonText: 'Batal',
            borderRadius: '15px'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "logout.php";
            }
        });
    });
</script>