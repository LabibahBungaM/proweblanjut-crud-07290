<?php
session_start();
require 'koneksi.php'; 


if (isset($_COOKIE['login_username']) && isset($_COOKIE['login_key'])) {
    $cookie_user = $_COOKIE['login_username'];
    $cookie_key  = $_COOKIE['login_key'];

 
    $stmt_cookie = $pdo->prepare("SELECT username FROM users WHERE username = :username");
    $stmt_cookie->bindParam(':username', $cookie_user);
    $stmt_cookie->execute();
    

    if ($stmt_cookie->rowCount() > 0) {
        $row_cookie = $stmt_cookie->fetch(PDO::FETCH_ASSOC);
        
        if ($cookie_key === hash('sha256', $row_cookie['username'])) {
           
            $_SESSION['username'] = $row_cookie['username'];
            $_SESSION['status']   = "login";
        }
    }
}


if (isset($_SESSION['status']) && $_SESSION['status'] == "login") {
    header("Location: index.php");
    exit;
}

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username AND password = :password");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
  
            $_SESSION['username'] = $username;
            $_SESSION['status']   = "login";

          
            if (isset($_POST['remember'])) {
                
                setcookie('login_username', $username, time() + 604800, "/");
            
                setcookie('login_key', hash('sha256', $username), time() + 604800, "/");
            }

            header("Location: index.php"); 
            exit;
        } else {
            echo "<script>alert('Username atau Password salah!');</script>";
        }
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Pastel Inventory</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css"> 
</head>
<body>

    <div class="login-wrapper">
        <div class="card card-login shadow-lg">
            <div class="card-header text-center">
                <h3 class="mb-0 fw-bold text-dark">🍃 Login</h3>
                <small class="text-dark">Pastel Inventory</small>
            </div>
            <div class="card-body p-4">
                <form action="" method="POST">
                    <div class="mb-3">
                        <label for="username" class="form-label fw-semibold">Username</label>
                        <input type="text" class="form-control" id="username" name="username" placeholder="Masukkan username" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label fw-semibold">Password</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password" required>
                    </div>

                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                        <label class="form-check-label text-muted" for="remember" style="font-size: 14px;">
                            Ingat Saya (Remember Me)
                        </label>
                    </div>

                    <button type="submit" name="login" class="btn-custom-add w-100 py-2 fs-5">Masuk</button>
                </form>

                <div class="text-center mt-3">
                    <small class="text-muted">Belum punya akun? <a href="register.php" style="color: var(--color-salmon); font-weight: 600; text-decoration: none;">Daftar di sini</a></small>
                </div>
            </div>
        </div>
    </div>

</body>
</html>