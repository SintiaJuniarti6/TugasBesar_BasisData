<?php
include 'koneksi.php';

// Tambah Pasien
if (isset($_POST['tambah'])) {
    $stmt = $conn->prepare("INSERT INTO pasien (nama, tanggal_lahir, jenis_kelamin, alamat, no_telepon) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $_POST['nama'], $_POST['tanggal_lahir'], $_POST['jenis_kelamin'], $_POST['alamat'], $_POST['no_telepon']);
    $stmt->execute();
    header("Location: pasien.php");
    exit;
}

// Hapus Pasien
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $stmt = $conn->prepare("DELETE FROM pasien WHERE id_pasien = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: pasien.php");
    exit;
}

// Update Pasien
if (isset($_POST['update'])) {
    $stmt = $conn->prepare("UPDATE pasien SET nama=?, tanggal_lahir=?, jenis_kelamin=?, alamat=?, no_telepon=? WHERE id_pasien=?");
    $stmt->bind_param("sssssi", $_POST['nama'], $_POST['tanggal_lahir'], $_POST['jenis_kelamin'], $_POST['alamat'], $_POST['no_telepon'], $_POST['id']);
    $stmt->execute();
    header("Location: pasien.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Data Pasien</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
    <h2>Data Pasien</h2>

    <!-- Form Tambah -->
    <div class="card mb-4">
        <div class="card-header">Tambah Pasien</div>
        <div class="card-body">
            <form method="POST">
                <input name="nama" class="form-control mb-2" placeholder="Nama" required>
                <input type="date" name="tanggal_lahir" class="form-control mb-2" required>
                <select name="jenis_kelamin" class="form-control mb-2" required>
                    <option value="">Jenis Kelamin</option>
                    <option value="L">Laki-laki</option>
                    <option value="P">Perempuan</option>
                </select>
                <textarea name="alamat" class="form-control mb-2" placeholder="Alamat" required></textarea>
                <input name="no_telepon" class="form-control mb-2" placeholder="No Telepon" required>
                <button name="tambah" class="btn btn-primary">Tambah</button>
            </form>
        </div>
    </div>

    <!-- Tabel Data -->
    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Lahir</th>
                <th>JK</th>
                <th>Alamat</th>
                <th>Telepon</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $result = $conn->query("SELECT * FROM pasien");
            $no = 1;
            while ($row = $result->fetch_assoc()):
            ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($row['nama']) ?></td>
                <td><?= htmlspecialchars($row['tanggal_lahir']) ?></td>
                <td><?= $row['jenis_kelamin'] ?></td>
                <td><?= htmlspecialchars($row['alamat']) ?></td>
                <td><?= htmlspecialchars($row['no_telepon']) ?></td>
                <td>
                    <button class="btn btn-warning btn-sm" 
                        data-bs-toggle="modal" 
                        data-bs-target="#editModal"
                        onclick="isiForm('<?= $row['id_pasien'] ?>','<?= htmlspecialchars($row['nama']) ?>','<?= $row['tanggal_lahir'] ?>','<?= $row['jenis_kelamin'] ?>','<?= htmlspecialchars($row['alamat']) ?>','<?= htmlspecialchars($row['no_telepon']) ?>')">
                        Edit
                    </button>
                    <a href="?hapus=<?= $row['id_pasien'] ?>" onclick="return confirm('Hapus?')" class="btn btn-danger btn-sm">Hapus</a>
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
        <h5>Edit Pasien</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="id" id="editId">
        <input name="nama" id="editNama" class="form-control mb-2" required>
        <input type="date" name="tanggal_lahir" id="editTanggalLahir" class="form-control mb-2" required>
        <select name="jenis_kelamin" id="editJK" class="form-control mb-2" required>
            <option value="L">Laki-laki</option>
            <option value="P">Perempuan</option>
        </select>
        <textarea name="alamat" id="editAlamat" class="form-control mb-2" required></textarea>
        <input name="no_telepon" id="editTelepon" class="form-control mb-2" required>
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
// Native Javascript tanpa event listener
function isiForm(id, nama, lahir, jk, alamat, telepon) {
    document.getElementById('editId').value = id;
    document.getElementById('editNama').value = nama;
    document.getElementById('editTanggalLahir').value = lahir;
    document.getElementById('editJK').value = jk;
    document.getElementById('editAlamat').value = alamat;
    document.getElementById('editTelepon').value = telepon;
}
</script>
</body>
</html>
