<?php
session_start();
include 'koneksi.php';

if(isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    $query = "SELECT * FROM akun WHERE username='$username' AND password='$password'";
    $result = mysqli_query($koneksi, $query);
    
    if(mysqli_num_rows($result) == 1) {
        $data = mysqli_fetch_assoc($result);
        $_SESSION['akun_id'] = $data['akun_id'];
        $_SESSION['username'] = $data['username'];
        $_SESSION['email'] = $data['email'];
        
        header("Location: utama.php");
    } else {
        $error = "Username atau password salah";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
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

        .login-container {
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

        .login-btn {
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

        .login-btn:hover {
            background: #3d8abd;
        }

        .signup-link {
            color: #4FA1D8;
            text-decoration: none;
            font-size: 14px;
            transition: color 0.3s ease;
        }

        .signup-link:hover {
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
            .login-container {
                width: 90%;
                padding: 30px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <img src="logo.png" alt="Logo UII" class="logo">
        <h2>LOGIN</h2>
        <?php if(isset($error)) echo "<div class='error-msg'>$error</div>"; ?>
        <form method="POST">
            <div class="form-group">
                <input type="text" name="username" placeholder="Username" required>
            </div>
            <div class="form-group">
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <button type="submit" name="login" class="login-btn">LOG IN</button>
            <a href="registrasi.php" class="signup-link">SIGN UP</a>
        </form>
    </div>
</body>
</html>