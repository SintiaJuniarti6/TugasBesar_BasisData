<?php 
include 'koneksi.php'; 

// Tambah rekam medis
if (isset($_POST['tambah'])) {
    $id_pasien = $_POST['id_pasien'];
    $id_dokter = $_POST['id_dokter'];
    $id_ruang = $_POST['id_ruang'];
    $id_obat = $_POST['id_obat'];
    $keluhan = $_POST['keluhan'];
    $diagnosa = $_POST['diagnosa'];

    $conn->query("INSERT INTO rekam_medis (id_pasien, id_dokter, id_ruang, id_obat, keluhan, diagnosa)
                  VALUES ('$id_pasien', '$id_dokter', '$id_ruang', '$id_obat', '$keluhan', '$diagnosa')");
    header("Location: rekam_medis.php");
}

// Hapus rekam medis
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $conn->query("DELETE FROM rekam_medis WHERE id_rekam = $id");
    header("Location: rekam_medis.php");
}

// Update rekam medis
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $id_pasien = $_POST['id_pasien'];
    $id_dokter = $_POST['id_dokter'];
    $id_ruang = $_POST['id_ruang'];
    $id_obat = $_POST['id_obat'];
    $keluhan = $_POST['keluhan'];
    $diagnosa = $_POST['diagnosa'];

    $conn->query("UPDATE rekam_medis SET
        id_pasien='$id_pasien', id_dokter='$id_dokter',
        id_ruang='$id_ruang', id_obat='$id_obat',
        keluhan='$keluhan', diagnosa='$diagnosa'
        WHERE id_rekam = $id");
    header("Location: rekam_medis.php");
}

// Helper untuk ambil nama
function getNama($table, $id_field, $id, $nama_field) {
    global $conn;
    $res = $conn->query("SELECT $nama_field FROM $table WHERE $id_field = $id");
    $row = $res->fetch_assoc();
    return $row[$nama_field];
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Rekam Medis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
    <h2 class="mb-4">Data Rekam Medis</h2>

    <div class="card mb-4">
        <div class="card-header">Tambah Rekam Medis</div>
        <div class="card-body">
            <form method="POST">
                <div class="row mb-2">
                    <div class="col">
                        <select name="id_pasien" class="form-control" required>
                            <option value="">Pilih Pasien</option>
                            <?php
                            $pasien = $conn->query("SELECT * FROM pasien");
                            while ($p = $pasien->fetch_assoc()) {
                                echo "<option value='{$p['id_pasien']}'>{$p['nama']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col">
                        <select name="id_dokter" class="form-control" required>
                            <option value="">Pilih Dokter</option>
                            <?php
                            $dokter = $conn->query("SELECT * FROM dokter");
                            while ($d = $dokter->fetch_assoc()) {
                                echo "<option value='{$d['id_dokter']}'>{$d['nama']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col">
                        <select name="id_ruang" class="form-control" required>
                            <option value="">Pilih Ruang</option>
                            <?php
                            $ruang = $conn->query("SELECT * FROM ruang");
                            while ($r = $ruang->fetch_assoc()) {
                                echo "<option value='{$r['id_ruang']}'>{$r['nama_ruang']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col">
                        <select name="id_obat" class="form-control" required>
                            <option value="">Pilih Obat</option>
                            <?php
                            $obat = $conn->query("SELECT * FROM obat");
                            while ($o = $obat->fetch_assoc()) {
                                echo "<option value='{$o['id_obat']}'>{$o['nama_obat']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <textarea name="keluhan" class="form-control mb-2" placeholder="Keluhan" required></textarea>
                <textarea name="diagnosa" class="form-control mb-2" placeholder="Diagnosa" required></textarea>
                <button name="tambah" class="btn btn-primary">Tambah</button>
            </form>
        </div>
    </div>

    <table class="table table-bordered table-striped">
        <thead class="table-dark"><tr>
            <th>No</th>
            <th>Pasien</th><th>Dokter</th><th>Ruang</th><th>Obat</th><th>Keluhan</th><th>Diagnosa</th><th>Aksi</th>
        </tr></thead>
        <tbody>
        <?php
        $data = $conn->query("SELECT * FROM rekam_medis ORDER BY id_rekam DESC");
        $no = 1;
        while ($row = $data->fetch_assoc()):
        ?>
        <tr>
            <td><?= $no++ ?></td>
            <td><?= getNama("pasien", "id_pasien", $row['id_pasien'], "nama") ?></td>
            <td><?= getNama("dokter", "id_dokter", $row['id_dokter'], "nama") ?></td>
            <td><?= getNama("ruang", "id_ruang", $row['id_ruang'], "nama_ruang") ?></td>
            <td><?= getNama("obat", "id_obat", $row['id_obat'], "nama_obat") ?></td>
            <td><?= htmlspecialchars($row['keluhan']) ?></td>
            <td><?= htmlspecialchars($row['diagnosa']) ?></td>
            <td>
                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal"
                    onclick="isiEditForm('<?= $row['id_rekam'] ?>','<?= $row['id_pasien'] ?>','<?= $row['id_dokter'] ?>','<?= $row['id_ruang'] ?>','<?= $row['id_obat'] ?>','<?= htmlspecialchars($row['keluhan']) ?>','<?= htmlspecialchars($row['diagnosa']) ?>')">
                    Edit
                </button>
                <a href="?hapus=<?= $row['id_rekam'] ?>" onclick="return confirm('Yakin?')" class="btn btn-sm btn-danger">Hapus</a>
            </td>
        </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="editModal" tabindex="-1">
  <div class="modal-dialog">
    <form method="POST" class="modal-content">
      <div class="modal-header">
        <h5>Edit Rekam Medis</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="id" id="editId">

        <label>Pasien</label>
        <select name="id_pasien" id="editPasien" class="form-control mb-2" required>
            <?php
            $pasien = $conn->query("SELECT * FROM pasien");
            while ($p = $pasien->fetch_assoc()) {
                echo "<option value='{$p['id_pasien']}'>{$p['nama']}</option>";
            }
            ?>
        </select>

        <label>Dokter</label>
        <select name="id_dokter" id="editDokter" class="form-control mb-2" required>
            <?php
            $dokter = $conn->query("SELECT * FROM dokter");
            while ($d = $dokter->fetch_assoc()) {
                echo "<option value='{$d['id_dokter']}'>{$d['nama']}</option>";
            }
            ?>
        </select>

        <label>Ruang</label>
        <select name="id_ruang" id="editRuang" class="form-control mb-2" required>
            <?php
            $ruang = $conn->query("SELECT * FROM ruang");
            while ($r = $ruang->fetch_assoc()) {
                echo "<option value='{$r['id_ruang']}'>{$r['nama_ruang']}</option>";
            }
            ?>
        </select>

        <label>Obat</label>
        <select name="id_obat" id="editObat" class="form-control mb-2" required>
            <?php
            $obat = $conn->query("SELECT * FROM obat");
            while ($o = $obat->fetch_assoc()) {
                echo "<option value='{$o['id_obat']}'>{$o['nama_obat']}</option>";
            }
            ?>
        </select>

        <textarea name="keluhan" id="editKeluhan" class="form-control mb-2" required></textarea>
        <textarea name="diagnosa" id="editDiagnosa" class="form-control mb-2" required></textarea>
      </div>
      <div class="modal-footer">
        <button name="update" class="btn btn-success">Simpan</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
      </div>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function isiEditForm(id, id_pasien, id_dokter, id_ruang, id_obat, keluhan, diagnosa) {
    document.getElementById('editId').value = id;
    document.getElementById('editPasien').value = id_pasien;
    document.getElementById('editDokter').value = id_dokter;
    document.getElementById('editRuang').value = id_ruang;
    document.getElementById('editObat').value = id_obat;
    document.getElementById('editKeluhan').value = keluhan;
    document.getElementById('editDiagnosa').value = diagnosa;
}
</script>
</body>
</html>
