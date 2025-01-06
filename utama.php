<?php
session_start();
include 'koneksi.php';

// Cek session
if(!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// Fungsi untuk menampilkan pesan kesalahan
function tampilkanPesan($pesan) {
    echo "<div style='color: red; text-align: center; margin: 20px 0;'>$pesan</div>";
}

// Proses Edit
if(isset($_POST['edit'])) {
    // Ambil data dari form
    $original_akun_id = $_POST['original_akun_id']; // akun_id asli
    $new_akun_id = trim($_POST['akun_id']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $email = trim($_POST['email']);
    
    // Validasi input
    if(empty($new_akun_id) || empty($username) || empty($password) || empty($email)) {
        tampilkanPesan("Semua field harus diisi.");
    } else {
        // Optional: Hash password jika diperlukan
        // $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $hashed_password = $password; // Ganti dengan $hashed_password jika menggunakan hashing
        
        // Gunakan prepared statements untuk UPDATE
        $stmt = $koneksi->prepare("UPDATE akun SET akun_id = ?, username = ?, password = ?, email = ? WHERE akun_id = ?");
        if ($stmt) {
            $stmt->bind_param("sssss", $new_akun_id, $username, $hashed_password, $email, $original_akun_id);
            if($stmt->execute()) {
                // Jika akun yang diupdate adalah akun yang sedang login, update session
                if($original_akun_id === $_SESSION['akun_id']) {
                    $_SESSION['akun_id'] = $new_akun_id;
                    $_SESSION['username'] = $username;
                    $_SESSION['email'] = $email;
                }
                $stmt->close();
                header("Location: utama.php");
                exit();
            } else {
                tampilkanPesan("Gagal memperbarui data: " . $stmt->error);
            }
        } else {
            tampilkanPesan("Terjadi kesalahan: " . $koneksi->error);
        }
    }
}

// Proses Hapus
if(isset($_GET['hapus'])) {
    $akun_id = $_GET['hapus'];
    
    // Gunakan prepared statements untuk DELETE
    $stmt = $koneksi->prepare("DELETE FROM akun WHERE akun_id = ?");
    if ($stmt) {
        $stmt->bind_param("s", $akun_id);
        if($stmt->execute()) {
            $stmt->close();
            header("Location: utama.php");
            exit();
        } else {
            tampilkanPesan("Gagal menghapus data: " . $stmt->error);
        }
    } else {
        tampilkanPesan("Terjadi kesalahan: " . $koneksi->error);
    }
}

// Form Edit
if(isset($_GET['edit'])) {
    $akun_id = $_GET['edit'];
    
    // Gunakan prepared statements untuk SELECT
    $stmt = $koneksi->prepare("SELECT * FROM akun WHERE akun_id = ?");
    if ($stmt) {
        $stmt->bind_param("s", $akun_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows === 0) {
            tampilkanPesan("Data tidak ditemukan.");
            exit();
        }
        $data = $result->fetch_assoc();
        $stmt->close();
    } else {
        tampilkanPesan("Terjadi kesalahan: " . $koneksi->error);
        exit();
    }
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Form Edit</title>
        <style>
            /* CSS yang sama seperti sebelumnya */
            body {
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                margin: 0;
                padding: 20px;
                background: #f5f7fb;
            }

            h2 {
                color: #333;
                font-size: 24px;
                margin-bottom: 30px;
                padding-bottom: 10px;
                border-bottom: 2px solid #4FA1D8;
            }

            form {
                background: white;
                padding: 30px;
                border-radius: 10px;
                box-shadow: 0 2px 5px rgba(0,0,0,0.1);
                max-width: 500px;
                margin: 0 auto;
            }

            .form-group {
                margin-bottom: 20px;
            }

            label {
                display: block;
                margin-bottom: 8px;
                color: #666;
                font-weight: 500;
            }

            input[type="text"], 
            input[type="password"], 
            input[type="email"] {
                width: 100%;
                padding: 10px;
                border: 1px solid #ddd;
                border-radius: 5px;
                font-size: 14px;
                transition: all 0.3s ease;
            }

            input[type="text"]:focus, 
            input[type="password"]:focus, 
            input[type="email"]:focus {
                border-color: #4FA1D8;
                box-shadow: 0 0 5px rgba(79, 161, 216, 0.3);
                outline: none;
            }

            input[type="submit"] {
                background: #4FA1D8;
                color: white;
                border: none;
                padding: 12px 25px;
                border-radius: 5px;
                cursor: pointer;
                font-size: 16px;
                font-weight: 500;
                transition: background 0.3s ease;
            }

            input[type="submit"]:hover {
                background: #2980b9;
            }
        </style>
    </head>
    <body>
        <h2>FORM EDIT</h2>
        <form method="POST">
            <!-- Field tersembunyi untuk akun_id asli -->
            <input type="hidden" name="original_akun_id" value="<?php echo htmlspecialchars($data['akun_id']); ?>">
            
            <div class="form-group">
                <label>akun id:</label>
                <input type="text" name="akun_id" value="<?php echo htmlspecialchars($data['akun_id']); ?>" required>
            </div>
            <div class="form-group">
                <label>username:</label>
                <input type="text" name="username" value="<?php echo htmlspecialchars($data['username']); ?>" required>
            </div>
            <div class="form-group">
                <label>password:</label>
                <input type="password" name="password" value="<?php echo htmlspecialchars($data['password']); ?>" required>
            </div>
            <div class="form-group">
                <label>email:</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($data['email']); ?>" required>
            </div>
            <div class="form-group">
                <input type="submit" name="edit" value="Edit">
            </div>
        </form>
    </body>
    </html>
    <?php
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Halaman Utama</title>
    <style>
        /* CSS yang sama seperti sebelumnya */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 20px;
            background: #f5f7fb;
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
            border-collapse: collapse;
            width: 100%;
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
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .action-links a:hover {
            color: #2980b9;
        }

        .info-section p {
            margin: 8px 0;
            color: #666;
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
        <h2>KELOLA AKUN</h2>
        <table>
            <tr>
                <th>akun_id</th>
                <th>username</th>
                <th>email</th>
                <th>AKSI</th>
            </tr>
            <?php
            // Gunakan prepared statements untuk SELECT semua akun
            $query = "SELECT * FROM akun";
            $result = mysqli_query($koneksi, $query);
            if(!$result) {
                echo "<tr><td colspan='4'>Terjadi kesalahan saat mengambil data: " . mysqli_error($koneksi) . "</td></tr>";
            } else {
                while($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['akun_id']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                    echo "<td class='action-links'>
                            <a href='?edit=" . urlencode($row['akun_id']) . "'>Edit</a> |
                            <a href='?hapus=" . urlencode($row['akun_id']) . "' onclick='return confirm(\"Yakin ingin menghapus?\")'>Hapus</a>
                          </td>";
                    echo "</tr>";
                }
            }
            ?>
        </table>
    </div>
</body>
</html>
