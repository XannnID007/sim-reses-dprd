<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['id_dewan'])) {
    header("Location: login.php");
    exit();
}

$id_dewan = $_SESSION['id_dewan'];

// Ambil data terbaru langsung dari database agar akurat
$query_dewan = mysqli_query($koneksi, "SELECT * FROM dewan WHERE id_dewan = '$id_dewan'");
$user = mysqli_fetch_assoc($query_dewan);

// Sinkronisasi variabel
$nama_user   = !empty($user['nama_lengkap']) ? $user['nama_lengkap'] : ($_SESSION['nama_dewan'] ?? "Anggota Dewan");
$fraksi_user = !empty($user['fraksi']) ? $user['fraksi'] : "-";
$dapil_user  = (!empty($user['dapil']) && $user['dapil'] != "0") ? $user['dapil'] : "1";
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Laporan Reses - ResesHub</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <style>
        body {
            background-color: #f8fafc;
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #334155;
        }

        .main-content {
            margin-left: 260px;
            padding: 40px;
            min-height: 100vh;
        }

        .card-form {
            border: none;
            border-radius: 24px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.03);
            background: white;
            transition: 0.3s;
        }

        .section-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
        }

        .section-title i {
            color: #34495e;
            margin-right: 12px;
        }

        .form-label {
            font-weight: 700;
            color: #64748b;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }

        .form-control,
        .form-select {
            border-radius: 12px;
            padding: 12px 16px;
            border: 1px solid #e2e8f0;
            background-color: #fcfdfe;
            font-size: 0.95rem;
        }

        .form-control.is-invalid,
        .form-select.is-invalid {
            border-color: #ef4444 !important;
            background-color: #fef2f2 !important;
        }

        #map {
            width: 100%;
            height: 400px;
            border-radius: 16px;
            border: 2px solid #f1f5f9;
            z-index: 1;
        }

        .bg-readonly {
            background-color: #f1f5f9 !important;
            font-weight: 600;
            color: #475569;
        }

        .btn-submit {
            background: #34495e;
            border: none;
            padding: 16px 30px;
            border-radius: 14px;
            font-weight: 700;
            transition: 0.3s;
            color: white;
        }

        .btn-submit:hover {
            background: #1a252f;
            transform: translateY(-2px);
        }

        @media (max-width: 992px) {
            .main-content {
                margin-left: 0;
                padding: 20px;
            }
        }
    </style>
</head>

<body>

    <?php include 'sidebar.php'; ?>

    <div class="main-content">
        <div class="container-fluid text-start">
            <div class="row mb-4">
                <div class="col-12">
                    <h2 class="fw-bold text-dark">Buat Laporan Reses</h2>
                    <p class="text-muted">Lengkapi data di bawah ini untuk mengirim laporan kegiatan ke admin.</p>
                </div>
            </div>

            <form id="formReses" action="proses_tambah_berita.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id_dewan" value="<?= $id_dewan; ?>">
                <input type="hidden" name="nama_dewan" value="<?= htmlspecialchars($nama_user) ?>">
                <input type="hidden" name="fraksi" value="<?= htmlspecialchars($fraksi_user) ?>">
                <input type="hidden" name="dapil" id="dapil_val" value="<?= htmlspecialchars($dapil_user) ?>">

                <div class="row g-4">
                    <div class="col-lg-7">
                        <div class="card card-form p-4 h-100">
                            <div class="section-title"><i class="fas fa-info-circle"></i> Informasi Kegiatan</div>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label class="form-label">Nama Anggota Dewan</label>
                                    <input type="text" class="form-control bg-readonly" value="<?= $nama_user ?>" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Fraksi</label>
                                    <input type="text" class="form-control bg-readonly" value="<?= $fraksi_user ?>" readonly>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Judul Laporan</label>
                                <input type="text" name="judul" class="form-control" placeholder="Judul kegiatan..." required>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label class="form-label">Dapil</label>
                                    <input type="text" class="form-control bg-readonly" value="Dapil <?= $dapil_user ?>" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Tanggal</label>
                                    <input type="date" name="tanggal" class="form-control" required>
                                </div>
                            </div>

                            <div class="mb-0">
                                <label class="form-label">Deskripsi Lengkap</label>
                                <textarea name="deskripsi" class="form-control" rows="8" placeholder="Ceritakan jalannya kegiatan..." required></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-5">
                        <div class="card card-form p-4">
                            <div class="section-title"><i class="fas fa-map-marked-alt"></i> Lokasi & Dokumentasi</div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Kecamatan</label>
                                    <select name="kecamatan" id="kecamatan" class="form-select" required>
                                        <option value="">-- Pilih --</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Aspirasi Utama</label>
                                    <input type="text" name="barang" class="form-control" placeholder="Contoh: Perbaikan Jalan" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Alamat Lengkap</label>
                                <input type="text" name="alamat_lengkap" class="form-control" placeholder="Jl. Desa..." required>
                            </div>

                            <div class="mb-4">
                                <label class="form-label text-primary">Klik Peta Untuk Koordinat</label>
                                <div id="map"></div>
                                <div class="row g-2 mt-2">
                                    <div class="col-6"><input type="text" name="latitude" id="lat" class="form-control form-control-sm bg-light" readonly required></div>
                                    <div class="col-6"><input type="text" name="longitude" id="lng" class="form-control form-control-sm bg-light" readonly required></div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Unggah Foto</label>
                                <input type="file" name="foto_barang" class="form-control" accept="image/*" required>
                            </div>

                            <button type="submit" class="btn btn-submit w-100">
                                <i class="fas fa-paper-plane me-2"></i> Kirim Laporan
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Data Dapil & Map Setup
        const dataDapil = {
            1: ["Soreang", "Pasir Jambu", "Ciwidey", "Rancabali", "Cangkuang", "Kutawaringin"],
            2: ["Margahayu", "Margaasih", "Katapang", "Dayeuhkolot"],
            3: ["Cileunyi", "Cimenyan", "Cilengkrang", "Bojongsoang"],
            4: ["Cicalengka", "Nagreg", "Cikancung", "Rancaekek"],
            5: ["Majalaya", "Solokanjeruk", "Paseh", "Ibun"],
            6: ["Ciparay", "Pacet", "Kertasari", "Baleendah"],
            7: ["Banjaran", "Pameungpeuk", "Pangalengan", "Arjasari", "Cimaung"]
        };

        const map = L.map('map').setView([-7.022, 107.518], 11);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
        let marker = L.marker([-7.022, 107.518], {
            draggable: true
        }).addTo(map);

        function updateLatLng(lat, lng) {
            document.getElementById("lat").value = lat.toFixed(7);
            document.getElementById("lng").value = lng.toFixed(7);
        }

        marker.on('dragend', () => updateLatLng(marker.getLatLng().lat, marker.getLatLng().lng));
        map.on('click', (e) => {
            marker.setLatLng(e.latlng);
            updateLatLng(e.latlng.lat, e.latlng.lng);
        });

        window.onload = function() {
            // Isi Dropdown Kecamatan
            const userDapil = document.getElementById("dapil_val").value;
            const selectKecamatan = document.getElementById("kecamatan");
            if (dataDapil[userDapil]) {
                dataDapil[userDapil].forEach(item => {
                    let opt = document.createElement("option");
                    opt.value = item;
                    opt.text = item;
                    selectKecamatan.appendChild(opt);
                });
            }
            setTimeout(() => map.invalidateSize(), 500);

            // MUNCULKAN NOTIFIKASI SETELAH REDIRECT
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('status')) {
                Swal.fire({
                    icon: urlParams.get('status'),
                    title: urlParams.get('status') === 'success' ? 'Berhasil' : 'Gagal',
                    text: urlParams.get('msg'),
                    confirmButtonColor: '#34495e'
                }).then(() => {
                    window.history.replaceState({}, document.title, window.location.pathname);
                });
            }
        };

        // Handle Submit
        document.getElementById('formReses').addEventListener('submit', function(e) {
            e.preventDefault();
            const form = this;

            if (!document.getElementById('lat').value) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Lokasi?',
                    text: 'Klik peta dulu!'
                });
                return;
            }

            Swal.fire({
                title: 'Kirim Laporan?',
                text: "Pastikan data sudah benar.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Kirim!',
                confirmButtonColor: '#34495e'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Sedang Memproses...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    form.submit(); // Ini akan memicu file proses_tambah_berita.php
                }
            });
        });
    </script>
</body>

</html>