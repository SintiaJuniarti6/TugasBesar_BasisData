<?php
include 'koneksi.php';

// Tambah Obat
if (isset($_POST['tambah'])) {
    $stmt = $conn->prepare("INSERT INTO obat (nama_obat, jenis) VALUES (?, ?)");
    $stmt->bind_param("ss", $_POST['nama_obat'], $_POST['jenis']);
    $stmt->execute();
    header("Location: obat.php");
    exit;
}

// Hapus Obat
if (isset($_GET['hapus'])) {
    $stmt = $conn->prepare("DELETE FROM obat WHERE id_obat = ?");
    $stmt->bind_param("i", $_GET['hapus']);
    $stmt->execute();
    header("Location: obat.php");
    exit;
}

// Update Obat
if (isset($_POST['update'])) {
    $stmt = $conn->prepare("UPDATE obat SET nama_obat=?, jenis=? WHERE id_obat=?");
    $stmt->bind_param("ssi", $_POST['nama_obat'], $_POST['jenis'], $_POST['id']);
    $stmt->execute();
    header("Location: obat.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Data Obat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
    <h2>Data Obat</h2>

    <!-- Form Tambah -->
    <div class="card mb-4">
        <div class="card-header">Tambah Obat</div>
        <div class="card-body">
            <form method="POST">
                <input name="nama_obat" class="form-control mb-2" placeholder="Nama Obat" required>
                <input name="jenis" class="form-control mb-2" placeholder="Jenis Obat" required>
                <button name="tambah" class="btn btn-primary">Tambah</button>
            </form>
        </div>
    </div>

    <!-- Tabel Data -->
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Jenis</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $result = $conn->query("SELECT * FROM obat ORDER BY id_obat DESC");
            $no = 1;
            while ($row = $result->fetch_assoc()):
            ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($row['nama_obat']) ?></td>
                <td><?= htmlspecialchars($row['jenis']) ?></td>
                <td>
                    <button class="btn btn-warning btn-sm" 
                        data-bs-toggle="modal" 
                        data-bs-target="#editModal"
                        onclick="isiForm('<?= $row['id_obat'] ?>','<?= htmlspecialchars($row['nama_obat']) ?>','<?= htmlspecialchars($row['jenis']) ?>')">
                        Edit
                    </button>
                    <a href="?hapus=<?= $row['id_obat'] ?>" onclick="return confirm('Hapus?')" class="btn btn-danger btn-sm">Hapus</a>
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
        <h5>Edit Obat</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="id" id="editId">
        <input name="nama_obat" id="editNama" class="form-control mb-2" required>
        <input name="jenis" id="editJenis" class="form-control mb-2" required>
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
// Isi form edit
function isiForm(id, nama, jenis) {
    document.getElementById('editId').value = id;
    document.getElementById('editNama').value = nama;
    document.getElementById('editJenis').value = jenis;
}
</script>
</body>
</html>
