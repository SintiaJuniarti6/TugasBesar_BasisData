<?php  
include 'koneksi.php';

// === Tambah Dokter ===
if (isset($_POST['tambah'])) {
    $stmt = $conn->prepare("INSERT INTO dokter (nama, spesialis, no_telepon) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $_POST['nama'], $_POST['spesialis'], $_POST['no_telepon']);
    $stmt->execute();
    header("Location: dokter.php");
    exit;
}

// === Hapus Dokter ===
if (isset($_GET['hapus'])) {
    $stmt = $conn->prepare("DELETE FROM dokter WHERE id_dokter = ?");
    $stmt->bind_param("i", $_GET['hapus']);
    $stmt->execute();
    header("Location: dokter.php");
    exit;
}

// === Update Dokter ===
if (isset($_POST['update'])) {
    $stmt = $conn->prepare("UPDATE dokter SET nama=?, spesialis=?, no_telepon=? WHERE id_dokter=?");
    $stmt->bind_param("sssi", $_POST['nama'], $_POST['spesialis'], $_POST['no_telepon'], $_POST['id']);
    $stmt->execute();
    header("Location: dokter.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Dokter</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-4">
    <h2>Data Dokter</h2>

    <!-- Form Tambah Dokter -->
    <div class="card mb-4">
        <div class="card-header">Tambah Dokter</div>
        <div class="card-body">
            <form method="POST">
                <input type="text" name="nama" class="form-control mb-2" placeholder="Nama Dokter" required>
                <input type="text" name="spesialis" class="form-control mb-2" placeholder="Spesialis" required>
                <input type="text" name="no_telepon" class="form-control mb-2" placeholder="No Telepon" required>
                <button type="submit" name="tambah" class="btn btn-primary">Tambah</button>
            </form>
        </div>
    </div>

    <!-- Tabel Data Dokter -->
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Spesialis</th>
                <th>No Telepon</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $data = $conn->query("SELECT * FROM dokter ORDER BY id_dokter DESC");
        $no = 1;
        while ($row = $data->fetch_assoc()):
        ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($row['nama']) ?></td>
                <td><?= htmlspecialchars($row['spesialis']) ?></td>
                <td><?= htmlspecialchars($row['no_telepon']) ?></td>
                <td>
                    <button class="btn btn-warning btn-sm"
                        data-bs-toggle="modal" 
                        data-bs-target="#editModal"
                        onclick="isiEditForm('<?= $row['id_dokter'] ?>','<?= htmlspecialchars($row['nama']) ?>','<?= htmlspecialchars($row['spesialis']) ?>','<?= htmlspecialchars($row['no_telepon']) ?>')">
                        Edit
                    </button>
                    <a href="?hapus=<?= $row['id_dokter'] ?>" onclick="return confirm('Yakin hapus?')" class="btn btn-danger btn-sm">Hapus</a>
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
        <h5 class="modal-title">Edit Dokter</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="id" id="editId">
        <input type="text" name="nama" id="editNama" class="form-control mb-2" required>
        <input type="text" name="spesialis" id="editSpesialis" class="form-control mb-2" required>
        <input type="text" name="no_telepon" id="editTelepon" class="form-control mb-2" required>
      </div>
      <div class="modal-footer">
        <button type="submit" name="update" class="btn btn-success">Simpan</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
      </div>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Fungsi untuk isi form edit
function isiEditForm(id, nama, spesialis, telepon) {
    document.getElementById('editId').value = id;
    document.getElementById('editNama').value = nama;
    document.getElementById('editSpesialis').value = spesialis;
    document.getElementById('editTelepon').value = telepon;
}
</script>
</body>
</html>
