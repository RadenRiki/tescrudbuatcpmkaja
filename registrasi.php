<?php
include 'koneksi.php';

if(isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    
    // Generate akun_id (3 digit random)
    $akun_id = rand(100, 999);
    
    $query = "INSERT INTO akun (akun_id, username, password, email) 
              VALUES ('$akun_id', '$username', '$password', '$email')";
    
    if(mysqli_query($koneksi, $query)) {
        header("Location: index.php");
    } else {
        $error = "Registrasi gagal";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registrasi</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #87CEEB, #4FA1D8);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .register-container {
            background: rgba(255, 255, 255, 0.95);
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            width: 400px;
            text-align: center;
        }

        .logo {
            width: 120px;
            margin-bottom: 20px;
        }

        h2 {
            color: #333;
            margin-bottom: 30px;
            font-size: 24px;
            font-weight: 600;
        }

        .form-group {
            margin-bottom: 20px;
        }

        input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
            outline: none;
        }

        input:focus {
            border-color: #4FA1D8;
            box-shadow: 0 0 5px rgba(79, 161, 216, 0.3);
        }

        .submit-btn {
            background: #4FA1D8;
            color: white;
            border: none;
            padding: 12px;
            width: 100%;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 500;
            transition: background 0.3s ease;
            margin-bottom: 15px;
        }

        .submit-btn:hover {
            background: #3d8abd;
        }

        .login-link {
            color: #4FA1D8;
            text-decoration: none;
            font-size: 14px;
            transition: color 0.3s ease;
        }

        .login-link:hover {
            color: #3d8abd;
            text-decoration: underline;
        }

        .error-msg {
            color: #ff4444;
            margin-bottom: 15px;
            font-size: 14px;
            background: rgba(255, 68, 68, 0.1);
            padding: 10px;
            border-radius: 5px;
            display: none;
        }

        <?php if(isset($error)): ?>
        .error-msg {
            display: block;
        }
        <?php endif; ?>

        @media (max-width: 480px) {
            .register-container {
                width: 90%;
                padding: 30px;
            }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <img src="logo.png" alt="Logo UII" class="logo">
        <h2>REGISTRASI</h2>
        <?php if(isset($error)) echo "<div class='error-msg'>$error</div>"; ?>
        <form method="POST">
            <div class="form-group">
                <input type="text" name="username" placeholder="Username" required>
            </div>
            <div class="form-group">
                <input type="email" name="email" placeholder="Email" required>
            </div>
            <div class="form-group">
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <button type="submit" name="submit" class="submit-btn">SUBMIT</button>
            <a href="index.php" class="login-link">kembali ke login</a>
        </form>
    </div>
</body>
</html>