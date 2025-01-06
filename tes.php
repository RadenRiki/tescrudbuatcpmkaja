<!DOCTYPE html>
<html>
<head>
    <title>Kelola Data</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="#">Ujian KK1.SIF302.4</a>
            <div class="navbar-nav">
                <a class="nav-link" href="utama.php">KELOLA AKUN</a>
                <a class="nav-link" href="kelola_data.php">KELOLA DATA</a>
                <a class="nav-link" href="logout.php">LOGOUT</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <!-- Info Section -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title">INFORMASI AKUN AKTIF LOGIN</h5>
            </div>
            <div class="card-body">
                <p class="card-text">akun id: <?php echo $_SESSION['akun_id']; ?></p>
                <p class="card-text">username: <?php echo $_SESSION['username']; ?></p>
                <p class="card-text">email: <?php echo $_SESSION['email']; ?></p>
            </div>
        </div>

        <!-- Table Section -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">TABEL MAHASISWA</h5>
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>NIM</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT * FROM mahasiswa";
                        $result = mysqli_query($koneksi, $query);
                        while($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>".$row['nim']."</td>";
                            echo "<td>".$row['nama']."</td>";
                            echo "<td>".$row['email']."</td>";
                            echo "<td>
                                    <a href='?edit_mhs=".$row['nim']."' class='btn btn-sm btn-primary'>Edit</a>
                                    <a href='?hapus_mhs=".$row['nim']."' class='btn btn-sm btn-danger' onclick='return confirm(\"Yakin?\")'>Hapus</a>
                                  </td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Form Edit dengan Bootstrap -->
    <?php if(isset($_GET['edit_mhs'])): ?>
    <div class="modal" style="display: block;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Mahasiswa</h5>
                </div>
                <div class="modal-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">NIM</label>
                            <input type="text" class="form-control" value="<?php echo $data['nim']; ?>" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nama</label>
                            <input type="text" name="nama" class="form-control" value="<?php echo $data['nama']; ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="<?php echo $data['email']; ?>">
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>