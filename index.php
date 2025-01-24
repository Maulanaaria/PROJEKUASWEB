<?php
// Mulai session
session_start();

// Cek apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    // Jika belum login, arahkan ke halaman login
    header("Location: login.php");
    exit();
}

// Jika sudah login, tampilkan halaman utama
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Utama</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%); /* Warna gradient */
            color: #fff;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        h1 {
            font-size: 2rem;
        }
        nav ul {
            list-style-type: none;
            padding: 0;
        }
        nav ul li {
            display: inline;
            margin-right: 20px;
        }
        nav ul li a {
            color: white;
            text-decoration: none;
            font-size: 1.2rem;
            transition: color 0.3s;
        }
        nav ul li a:hover {
            color: #f0f0f0;
        }
        .content {
            background: rgba(0, 0, 0, 0.6); /* Membuat latar belakang konten menjadi transparan */
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }
        .logout {
            margin-top: 20px;
            font-size: 1.2rem;
            color: white;
            text-decoration: none;
        }
        .logout:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="content">
        <h1>Selamat datang di halaman utama, <?php echo $_SESSION['username']; ?>!</h1>
        <nav>
            <ul>
                <li><a href="dashboardcrud.php">Dashboard CRUD</a></li>
                <li><a href="stats.php">Statistik Pembelian</a></li>
                <li><a href="halaman.php">Halaman Dashboard</a></li>
            </ul>
        </nav>
        <p><a href="logout.php" class="logout">Logout</a></p>
    </div>
</body>
</html>
