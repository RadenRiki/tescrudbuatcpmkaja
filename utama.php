<?php
session_start();
include 'koneksi.php';

// Cek session
if(!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// Proses Edit
if(isset($_POST['edit'])) {
    $akun_id = $_POST['akun_id'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    
    $query = "UPDATE akun SET username='$username', password='$password', email='$email' WHERE akun_id='$akun_id'";
    mysqli_query($koneksi, $query);
    header("Location: utama.php");
}

// Proses Hapus
if(isset($_GET['hapus'])) {
    $akun_id = $_GET['hapus'];
    $query = "DELETE FROM akun WHERE akun_id='$akun_id'";
    mysqli_query($koneksi, $query);
    header("Location: utama.php");
}

// Form Edit
if(isset($_GET['edit'])) {
    $akun_id = $_GET['edit'];
    $query = "SELECT * FROM akun WHERE akun_id='$akun_id'";
    $result = mysqli_query($koneksi, $query);
    $data = mysqli_fetch_assoc($result);
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Form Edit</title>
        <style>
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
            <input type="hidden" name="akun_id" value="<?php echo $data['akun_id']; ?>">
            <div class="form-group">
                <label>akun id: <?php echo $data['akun_id']; ?></label>
            </div>
            <div class="form-group">
                <label>username:</label>
                <input type="text" name="username" value="<?php echo $data['username']; ?>">
            </div>
            <div class="form-group">
                <label>password:</label>
                <input type="password" name="password" value="<?php echo $data['password']; ?>">
            </div>
            <div class="form-group">
                <label>email:</label>
                <input type="email" name="email" value="<?php echo $data['email']; ?>">
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
        <p>akun id: <?php echo $_SESSION['akun_id']; ?></p>
        <p>username: <?php echo $_SESSION['username']; ?></p>
        <p>email: <?php echo $_SESSION['email']; ?></p>
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
            $query = "SELECT * FROM akun";
            $result = mysqli_query($koneksi, $query);
            while($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>".$row['akun_id']."</td>";
                echo "<td>".$row['username']."</td>";
                echo "<td>".$row['email']."</td>";
                echo "<td class='action-links'>
                        <a href='?edit=".$row['akun_id']."'>Edit</a> |
                        <a href='?hapus=".$row['akun_id']."' onclick='return confirm(\"Yakin ingin menghapus?\")'>Hapus</a>
                      </td>";
                echo "</tr>";
            }
            ?>
        </table>
    </div>
</body>
</html>