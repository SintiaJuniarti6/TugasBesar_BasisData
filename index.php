<?php
include 'koneksi.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Klinik</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .sidebar { height: 100vh; background-color: #1a1a40; color: white; }
        .sidebar a { color: white; display: block; padding: 10px 20px; text-decoration: none; }
        .sidebar a:hover, .sidebar .active { background-color: #4e4e8a; }
        .card-icon { font-size: 40px; color: #6c757d; }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-2 sidebar p-0">
            <h3 class="text-center py-3">Klinik</h3>
            <a href="index.php" class="active"><i class="bi bi-house-door"></i> Dashboard</a>
            <a href="dokter.php"><i class="bi bi-person"></i> Dokter</a>
            <a href="pasien.php"><i class="bi bi-people"></i> Pasien</a>
            <a href="ruang.php"><i class="bi bi-door-open"></i> Ruang</a>
            <a href="obat.php"><i class="bi bi-capsule"></i> Obat</a>
            <a href="rekam_medis.php"><i class="bi bi-bar-chart"></i> Rekam Medis</a>
        </div>

        <!-- Main Content -->
        <div class="col-md-10 p-4">
            <h2>Dashboard</h2>
            <div class="row">
                <?php
                // Cek koneksi berjalan normal
                if (!$conn) {
                    die("Koneksi database gagal: " . mysqli_connect_error());
                }

                // Query data
                $result_dokter = $conn->query("SELECT COUNT(*) as total FROM dokter");
                $result_pasien = $conn->query("SELECT COUNT(*) as total FROM pasien");
                $result_ruang = $conn->query("SELECT COUNT(*) as total FROM ruang");
                $result_obat   = $conn->query("SELECT COUNT(*) as total FROM obat");

                // Baca hasil query
                $dokter = ($result_dokter) ? $result_dokter->fetch_assoc()['total'] : 0;
                $pasien = ($result_pasien) ? $result_pasien->fetch_assoc()['total'] : 0;
                $ruang  = ($result_ruang) ? $result_ruang->fetch_assoc()['total'] : 0;
                $obat   = ($result_obat) ? $result_obat->fetch_assoc()['total'] : 0;
                ?>
                <div class="col-md-3">
                    <div class="card text-center shadow">
                        <div class="card-body">
                            <div class="card-icon mb-2"><i class="bi bi-person"></i></div>
                            <h5><?= $dokter ?> Dokter</h5>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center shadow">
                        <div class="card-body">
                            <div class="card-icon mb-2"><i class="bi bi-people"></i></div>
                            <h5><?= $pasien ?> Pasien</h5>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center shadow">
                        <div class="card-body">
                            <div class="card-icon mb-2"><i class="bi bi-door-open"></i></div>
                            <h5><?= $ruang ?> Ruang</h5>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center shadow">
                        <div class="card-body">
                            <div class="card-icon mb-2"><i class="bi bi-capsule"></i></div>
                            <h5><?= $obat ?> Obat</h5>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tambahan Rekam Medis -->
            <div class="row mt-4">
                <?php
                $result_rekam = $conn->query("SELECT COUNT(*) as total FROM rekam_medis");
                $rekam = ($result_rekam) ? $result_rekam->fetch_assoc()['total'] : 0;
                ?>
                <div class="col-md-3">
                    <div class="card text-center shadow">
                        <div class="card-body">
                            <div class="card-icon mb-2"><i class="bi bi-clipboard2-pulse"></i></div>
                            <h5><?= $rekam ?> Rekam Medis</h5>
                        </div>
                    </div>
                </div>
            </div>

        </div> <!-- End Main Content -->
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
