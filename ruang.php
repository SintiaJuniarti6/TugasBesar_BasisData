<?php 
include 'koneksi.php'; 

// Tambah Data
if (isset($_POST['tambah'])) {
    $nama = $_POST['nama'];
    $lantai = $_POST['lantai'];
    $stmt = $conn->prepare("INSERT INTO ruang (nama_ruang, lantai) VALUES (?, ?)");
    $stmt->bind_param("ss", $nama, $lantai);
    $stmt->execute();
    $stmt->close();
    header("Location: ruang.php");
    exit;
}

// Hapus Data
if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    $stmt = $conn->prepare("DELETE FROM ruang WHERE id_ruang = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: ruang.php");
    exit;
}

// Update Data
if (isset($_POST['update'])) {
    $id = intval($_POST['id']);
    $nama = $_POST['nama'];
    $lantai = $_POST['lantai'];
    $stmt = $conn->prepare("UPDATE ruang SET nama_ruang = ?, lantai = ? WHERE id_ruang = ?");
    $stmt->bind_param("ssi", $nama, $lantai, $id);
    $stmt->execute();
    $stmt->close();
    header("Location: ruang.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Data Ruang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
    <h2 class="mb-4">Data Ruang</h2>

    <!-- Form Tambah Ruang -->
    <div class="card mb-4">
        <div class="card-header">Tambah Ruang</div>
        <div class="card-body">
            <form method="POST">
                <input type="text" name="nama" class="form-control mb-2" placeholder="Nama Ruang" required>
                <select name="lantai" class="form-control mb-2" required>
                    <option value="">Pilih Lokasi (Lantai)</option>
                    <option value="Lantai 1">Lantai 1</option>
                    <option value="Lantai 2">Lantai 2</option>
                    <option value="Lantai 3">Lantai 3</option>
                </select>
                <button class="btn btn-primary" name="tambah">Tambah</button>
            </form>
        </div>
    </div>

    <!-- Tabel Data Ruang -->
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>No</th>
                <th>Nama Ruang</th>
                <th>Lantai</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $data = $conn->query("SELECT * FROM ruang ORDER BY id_ruang DESC");
        $no = 1;
        if ($data->num_rows == 0) {
            echo "<tr><td colspan='4'>Belum ada data ruang.</td></tr>";
        }
        while ($row = $data->fetch_assoc()):
        ?>
        <tr>
            <td><?= $no++ ?></td>
            <td><?= htmlspecialchars($row['nama_ruang']) ?></td>
            <td><?= htmlspecialchars($row['lantai']) ?></td>
            <td>
                <button 
                    class="btn btn-warning btn-sm editBtn" 
                    data-id="<?= $row['id_ruang'] ?>" 
                    data-nama="<?= htmlspecialchars($row['nama_ruang']) ?>" 
                    data-lantai="<?= htmlspecialchars($row['lantai']) ?>">
                    Edit
                </button>
                <a href="?hapus=<?= $row['id_ruang'] ?>" onclick="return confirm('Yakin ingin menghapus?')" class="btn btn-danger btn-sm">Hapus</a>
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
            <input type="hidden" name="id" id="editId">
            <div class="modal-header">
                <h5 class="modal-title">Edit Ruang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="text" name="nama" id="editNama" class="form-control mb-2" required>
                <select name="lantai" id="editLantai" class="form-control mb-2" required>
                    <option value="Lantai 1">Lantai 1</option>
                    <option value="Lantai 2">Lantai 2</option>
                    <option value="Lantai 3">Lantai 3</option>
                </select>
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
// handle tombol edit
document.querySelectorAll('.editBtn').forEach(button => {
    button.addEventListener('click', function() {
        document.getElementById('editId').value = this.dataset.id;
        document.getElementById('editNama').value = this.dataset.nama;
        document.getElementById('editLantai').value = this.dataset.lantai;
        var editModal = new bootstrap.Modal(document.getElementById('editModal'));
        editModal.show();
    });
});
</script>
</body>
</html>
