<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pastel Inventory</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<nav class="navbar navbar-expand-lg mb-4 shadow-sm" style="background-color: var(--color-mint);">
  <div class="container">
    <a class="navbar-brand fw-bold text-dark" href="index.php">🍃 MyInventory</a>
    
    <div class="d-flex align-items-center ms-auto">
        <?php if(isset($_SESSION['username'])) : ?>
            <span class="me-3 fw-semibold text-dark">
                Halo, <?= htmlspecialchars($_SESSION['username']); ?>!
            </span>
            <a href="logout.php" class="btn btn-sm rounded-pill px-3 shadow-sm fw-bold" 
               style="background-color: var(--color-salmon); color: white; border: none;" 
               onclick="return confirm('Yakin ingin keluar dari aplikasi?')">
               Logout
            </a>
        <?php endif; ?>
    </div>
    
  </div>
</nav>

<div class="container mt-4">