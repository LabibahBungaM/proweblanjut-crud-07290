<?php
session_start();
require 'koneksi.php'; 


if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        echo "<script>alert('Konfirmasi password tidak cocok!');</script>";
    } else {
        try {
            $stmt_cek = $pdo->prepare("SELECT * FROM users WHERE username = :username");
            $stmt_cek->bindParam(':username', $username);
            $stmt_cek->execute();

            if ($stmt_cek->rowCount() > 0) {
                echo "<script>alert('Username sudah terdaftar! Silakan pilih username lain.');</script>";
            } else {
                $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
                $stmt->bindParam(':username', $username);
                $stmt->bindParam(':password', $password);
                
                if ($stmt->execute()) {
                    echo "<script>
                            alert('Registrasi berhasil! Silakan login.');
                            window.location.href = 'login.php';
                          </script>";
                    exit;
                } else {
                    echo "<script>alert('Gagal mendaftar akun.');</script>";
                }
            }
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Pastel Inventory</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css"> 
</head>
<body style="background-color: var(--color-cream); font-family: 'Poppins', sans-serif;">

    <div class="d-flex align-items-center justify-content-center" style="min-height: 100vh; padding: 20px;">
        
        <div class="card shadow-lg" style="width: 100%; max-width: 400px; border-radius: 20px; border: none;">
            
            <div class="card-header text-center" style="background-color: var(--color-mint); border-top-left-radius: 20px; border-top-right-radius: 20px; padding: 20px;">
                <h3 class="mb-0 fw-bold text-dark">📝 Daftar Akun</h3>
                <small class="text-dark">Pastel Inventory</small>
            </div>
            
            <div class="card-body p-4">
                <form action="" method="POST">
                    <div class="mb-3">
                        <label for="username" class="form-label fw-semibold">Username Baru</label>
                        <input type="text" class="form-control" id="username" name="username" placeholder="Buat username" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label fw-semibold">Password</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Buat password" required>
                    </div>
                    <div class="mb-4">
                        <label for="confirm_password" class="form-label fw-semibold">Konfirmasi Password</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Ulangi password" required>
                    </div>
                    <button type="submit" name="register" class="btn-custom-add w-100 py-2 fs-5">Daftar</button>
                </form>

                <div class="text-center mt-3">
                    <small class="text-muted">Sudah punya akun? <a href="login.php" style="color: var(--color-salmon); font-weight: 600; text-decoration: none;">Login di sini</a></small>
                </div>
            </div>
            
        </div>
    </div>

</body>
</html>