<?php
session_start();
include 'koneksi.php';

// Cek session
if(!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// Proses Edit Mahasiswa
if(isset($_POST['edit_mhs'])) {
    $nim = $_POST['nim'];
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    
    // Gunakan prepared statements untuk mencegah SQL injection
    $stmt = $koneksi->prepare("UPDATE mahasiswa SET nama=?, email=? WHERE nim=?");
    $stmt->bind_param("sss", $nama, $email, $nim);
    $stmt->execute();
    $stmt->close();
    
    header("Location: kelola_data.php");
    exit();
}

// Proses Hapus Mahasiswa
if(isset($_GET['hapus_mhs'])) {
    $nim = $_GET['hapus_mhs'];
    
    $stmt = $koneksi->prepare("DELETE FROM mahasiswa WHERE nim=?");
    $stmt->bind_param("s", $nim);
    $stmt->execute();
    $stmt->close();
    
    header("Location: kelola_data.php");
    exit();
}

// Proses Edit Mata Kuliah
if(isset($_POST['edit_mk'])) {
    $kode_mk = $_POST['kode_mk'];
    $nama_mk = $_POST['nama_mk'];
    $sks = $_POST['sks'];
    
    $stmt = $koneksi->prepare("UPDATE mata_kuliah SET nama_mk=?, sks=? WHERE kode_mk=?");
    $stmt->bind_param("sis", $nama_mk, $sks, $kode_mk);
    $stmt->execute();
    $stmt->close();
    
    header("Location: kelola_data.php");
    exit();
}

// Proses Hapus Mata Kuliah
if(isset($_GET['hapus_mk'])) {
    $kode_mk = $_GET['hapus_mk'];
    
    $stmt = $koneksi->prepare("DELETE FROM mata_kuliah WHERE kode_mk=?");
    $stmt->bind_param("s", $kode_mk);
    $stmt->execute();
    $stmt->close();
    
    header("Location: kelola_data.php");
    exit();
}

// Fetch data untuk Edit Mahasiswa
if(isset($_GET['edit_mhs'])) {
    $nim = $_GET['edit_mhs'];
    $stmt = $koneksi->prepare("SELECT * FROM mahasiswa WHERE nim=?");
    $stmt->bind_param("s", $nim);
    $stmt->execute();
    $result = $stmt->get_result();
    $data_mhs = $result->fetch_assoc();
    $stmt->close();
}

// Fetch data untuk Edit Mata Kuliah
if(isset($_GET['edit_mk'])) {
    $kode_mk = $_GET['edit_mk'];
    $stmt = $koneksi->prepare("SELECT * FROM mata_kuliah WHERE kode_mk=?");
    $stmt->bind_param("s", $kode_mk);
    $stmt->execute();
    $result = $stmt->get_result();
    $data_mk = $result->fetch_assoc();
    $stmt->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Kelola Data</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: #f5f7fb;
            margin: 0;
            padding: 20px;
        }

        .header {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            color: #333;
            font-size: 24px;
        }

        .nav {
            margin-top: 15px;
            padding: 10px 0;
            border-top: 1px solid #eee;
        }

        .nav a {
            text-decoration: none;
            color: #4FA1D8;
            font-weight: 500;
            padding: 8px 15px;
            border-radius: 5px;
            transition: all 0.3s ease;
            margin-right: 10px;
        }

        .nav a:hover {
            background: #4FA1D8;
            color: white;
        }

        .info-section, .data-section {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }

        h2 {
            color: #333;
            font-size: 20px;
            margin-top: 0;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #4FA1D8;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            background: white;
            border-radius: 8px;
            overflow: hidden;
        }

        th {
            background: #4FA1D8;
            color: white;
            font-weight: 500;
            text-align: left;
            padding: 12px 15px;
        }

        td {
            padding: 12px 15px;
            border-bottom: 1px solid #eee;
        }

        tr:last-child td {
            border-bottom: none;
        }

        tr:hover {
            background: #f8f9fa;
        }

        .action-links a {
            text-decoration: none;
            color: #4FA1D8;
            margin-right: 10px;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .action-links a.edit {
            background: #4FA1D8;
            color: white;
        }

        .action-links a.delete {
            background: #ff4444;
            color: white;
        }

        .action-links a:hover {
            opacity: 0.8;
        }

        .info-section p {
            margin: 8px 0;
            color: #666;
        }

        /* Modal Styles */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .modal {
            background: white;
            padding: 30px;
            border-radius: 10px;
            width: 90%;
            max-width: 500px;
        }

        .modal h3 {
            margin-bottom: 20px;
            color: #333;
            font-size: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #666;
            font-weight: 500;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .form-group input:focus {
            border-color: #4FA1D8;
            box-shadow: 0 0 5px rgba(79, 161, 216, 0.3);
            outline: none;
        }

        .modal-buttons {
            text-align: right;
            margin-top: 20px;
        }

        .modal-buttons button,
        .modal-buttons a {
            padding: 8px 15px;
            border-radius: 5px;
            font-size: 14px;
            cursor: pointer;
            margin-left: 10px;
            border: none;
        }

        .btn-cancel {
            background: #ddd;
            color: #666;
            text-decoration: none;
        }

        .btn-save {
            background: #4FA1D8;
            color: white;
        }

        .btn-save:hover,
        .btn-cancel:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Ujian KK1.SIF302.4</h1>
        <div class="nav">
            <a href="utama.php">KELOLA AKUN</a>
            <a href="kelola_data.php">KELOLA DATA</a>
            <a href="logout.php">LOGOUT</a>
        </div>
    </div>

    <div class="info-section">
        <h2>INFORMASI AKUN AKTIF LOGIN</h2>
        <p>akun id: <?php echo htmlspecialchars($_SESSION['akun_id']); ?></p>
        <p>username: <?php echo htmlspecialchars($_SESSION['username']); ?></p>
        <p>email: <?php echo htmlspecialchars($_SESSION['email']); ?></p>
    </div>

    <div class="data-section">
        <h2>TABEL MAHASISWA</h2>
        <table>
            <tr>
                <th>NIM</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Aksi</th>
            </tr>
            <?php
            $query = "SELECT * FROM mahasiswa";
            $result = mysqli_query($koneksi, $query);
            while($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>".htmlspecialchars($row['nim'])."</td>";
                echo "<td>".htmlspecialchars($row['nama'])."</td>";
                echo "<td>".htmlspecialchars($row['email'])."</td>";
                echo "<td class='action-links'>
                        <a href='?edit_mhs=".urlencode($row['nim'])."' class='edit'>Edit</a>
                        <a href='?hapus_mhs=".urlencode($row['nim'])."' class='delete' onclick='return confirm(\"Yakin ingin menghapus?\")'>Hapus</a>
                      </td>";
                echo "</tr>";
            }
            ?>
        </table>
    </div>

    <div class="data-section">
        <h2>TABEL MATA KULIAH</h2>
        <table>
            <tr>
                <th>Kode MK</th>
                <th>Nama MK</th>
                <th>SKS</th>
                <th>Aksi</th>
            </tr>
            <?php
            $query = "SELECT * FROM mata_kuliah";
            $result = mysqli_query($koneksi, $query);
            while($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>".htmlspecialchars($row['kode_mk'])."</td>";
                echo "<td>".htmlspecialchars($row['nama_mk'])."</td>";
                echo "<td>".htmlspecialchars($row['sks'])."</td>";
                echo "<td class='action-links'>
                        <a href='?edit_mk=".urlencode($row['kode_mk'])."' class='edit'>Edit</a>
                        <a href='?hapus_mk=".urlencode($row['kode_mk'])."' class='delete' onclick='return confirm(\"Yakin ingin menghapus?\")'>Hapus</a>
                      </td>";
                echo "</tr>";
            }
            ?>
        </table>
    </div>

    <?php if(isset($_GET['edit_mhs']) && isset($data_mhs)): ?>
    <div class="modal-overlay">
        <div class="modal">
            <h3>Edit Data Mahasiswa</h3>
            <form method="POST">
                <input type="hidden" name="nim" value="<?php echo htmlspecialchars($data_mhs['nim']); ?>">
                <div class="form-group">
                    <label>NIM</label>
                    <input type="text" value="<?php echo htmlspecialchars($data_mhs['nim']); ?>" readonly>
                </div>
                <div class="form-group">
                    <label>Nama</label>
                    <input type="text" name="nama" value="<?php echo htmlspecialchars($data_mhs['nama']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($data_mhs['email']); ?>" required>
                </div>
                <div class="modal-buttons">
                    <a href="kelola_data.php" class="btn-cancel">Batal</a>
                    <button type="submit" name="edit_mhs" class="btn-save">Simpan</button>
                </div>
            </form>
        </div>
    </div>
    <?php endif; ?>

    <?php if(isset($_GET['edit_mk']) && isset($data_mk)): ?>
    <div class="modal-overlay">
        <div class="modal">
            <h3>Edit Mata Kuliah</h3>
            <form method="POST">
                <input type="hidden" name="kode_mk" value="<?php echo htmlspecialchars($data_mk['kode_mk']); ?>">
                <div class="form-group">
                    <label>Kode MK</label>
                    <input type="text" value="<?php echo htmlspecialchars($data_mk['kode_mk']); ?>" readonly>
                </div>
                <div class="form-group">
                    <label>Nama MK</label>
                    <input type="text" name="nama_mk" value="<?php echo htmlspecialchars($data_mk['nama_mk']); ?>" required>
                </div>
                <div class="form-group">
                    <label>SKS</label>
                    <input type="number" name="sks" value="<?php echo htmlspecialchars($data_mk['sks']); ?>" required>
                </div>
                <div class="modal-buttons">
                    <a href="kelola_data.php" class="btn-cancel">Batal</a>
                    <button type="submit" name="edit_mk" class="btn-save">Simpan</button>
                </div>
            </form>
        </div>
    </div>
    <?php endif; ?>

</body>
</html>