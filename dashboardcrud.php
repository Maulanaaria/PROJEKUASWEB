<?php
// Koneksi ke database
$host = 'localhost';
$db = 'bookstore';
$user = 'root';
$pass = '';
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Tambah Data
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add'])) {
    $book_title = $_POST['book_title'];
    $author = $_POST['author'];
    $quantity = $_POST['quantity'];
    $price_per_unit = $_POST['price_per_unit'];
    $purchase_date = $_POST['purchase_date'];

    $stmt = $conn->prepare("INSERT INTO purchases (book_title, author, quantity, price_per_unit, purchase_date) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssids", $book_title, $author, $quantity, $price_per_unit, $purchase_date);
    $stmt->execute();
    $stmt->close();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Hapus Data
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM purchases WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Edit Data
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    $id = $_POST['id'];
    $book_title = $_POST['book_title'];
    $author = $_POST['author'];
    $quantity = $_POST['quantity'];
    $price_per_unit = $_POST['price_per_unit'];
    $purchase_date = $_POST['purchase_date'];

    $stmt = $conn->prepare("UPDATE purchases SET book_title = ?, author = ?, quantity = ?, price_per_unit = ?, purchase_date = ? WHERE id = ?");
    $stmt->bind_param("ssidsi", $book_title, $author, $quantity, $price_per_unit, $purchase_date, $id);
    $stmt->execute();
    $stmt->close();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Ambil data pembelian
$sql = "SELECT * FROM purchases";
$result = $conn->query($sql);

// Hitung total pembelian
$total_purchase = 0;
$total_books = 0;
while ($row = $result->fetch_assoc()) {
    $total_purchase += $row['quantity'] * $row['price_per_unit'];
    $total_books += $row['quantity'];
}
$total_transactions = $result->num_rows;
$average_price_per_unit = $total_books > 0 ? $total_purchase / $total_books : 0;
$average_books_per_transaction = $total_transactions > 0 ? $total_books / $total_transactions : 0;
$result->data_seek(0); // Reset pointer hasil query
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pembelian Buku</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #87CEEB;
            background-size: cover;
            background-position: center;
        }
        .container {
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 10px;
            padding: 20px;
        }
        .card {
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <div class="container my-5">
        <h1 class="text-center mb-4">Dashboard Pembelian Buku</h1>

        <!-- Ringkasan -->
        <div class="row text-center mb-4">
            <div class="col-md-4">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h3>Total Buku Dibeli</h3>
                        <p class="fs-4"><?= $total_books ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h3>Total Pengeluaran</h3>
                        <p class="fs-4">Rp <?= number_format($total_purchase, 2, ',', '.') ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <h3>Jumlah Transaksi</h3>
                        <p class="fs-4"><?= $total_transactions ?></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row text-center mb-4">
            <div class="col-md-6">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h3>Rata-rata Harga per Unit</h3>
                        <p class="fs-4">Rp <?= number_format($average_price_per_unit, 2, ',', '.') ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card bg-secondary text-white">
                    <div class="card-body">
                        <h3>Rata-rata Buku per Transaksi</h3>
                        <p class="fs-4"><?= number_format($average_books_per_transaction, 2, ',', '.') ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Tambah Data -->
        <div class="mb-4">
            <h3>Tambah Data Pembelian</h3>
            <form method="POST">
                <div class="row g-3">
                    <div class="col-md-3">
                        <input type="text" name="book_title" class="form-control" placeholder="Judul Buku" required>
                    </div>
                    <div class="col-md-2">
                        <input type="text" name="author" class="form-control" placeholder="Penulis" required>
                    </div>
                    <div class="col-md-2">
                        <input type="number" name="quantity" class="form-control" placeholder="Jumlah" required>
                    </div>
                    <div class="col-md-2">
                        <input type="number" step="0.01" name="price_per_unit" class="form-control" placeholder="Harga per Unit" required>
                    </div>
                    <div class="col-md-2">
                        <input type="date" name="purchase_date" class="form-control" required>
                    </div>
                    <div class="col-md-1">
                        <button type="submit" name="add" class="btn btn-primary w-100">Tambah</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Tabel Data -->
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Judul Buku</th>
                        <th>Penulis</th>
                        <th>Jumlah</th>
                        <th>Harga per Unit</th>
                        <th>Total Harga</th>
                        <th>Tanggal Pembelian</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    while ($row = $result->fetch_assoc()) :
                        $isEdit = isset($_GET['edit']) && $_GET['edit'] == $row['id'];
                    ?>
                        <tr>
                            <?php if ($isEdit): ?>
                                <form method="POST">
                                    <td><?= $no++ ?></td>
                                    <td><input type="text" name="book_title" value="<?= $row['book_title'] ?>" required></td>
                                    <td><input type="text" name="author" value="<?= $row['author'] ?>" required></td>
                                    <td><input type="number" name="quantity" value="<?= $row['quantity'] ?>" required></td>
                                    <td><input type="number" step="0.01" name="price_per_unit" value="<?= $row['price_per_unit'] ?>" required></td>
                                    <td>-</td>
                                    <td><input type="date" name="purchase_date" value="<?= $row['purchase_date'] ?>" required></td>
                                    <td>
                                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                        <button type="submit" name="update" class="btn btn-success btn-sm">Simpan</button>
                                        <a href="<?= $_SERVER['PHP_SELF'] ?>" class="btn btn-secondary btn-sm">Batal</a>
                                    </td>
                                </form>
                            <?php else: ?>
                                <td><?= $no++ ?></td>
                                <td><?= htmlspecialchars($row['book_title']) ?></td>
                                <td><?= htmlspecialchars($row['author']) ?></td>
                                <td><?= $row['quantity'] ?></td>
                                <td>Rp <?= number_format($row['price_per_unit'], 2, ',', '.') ?></td>
                                <td>Rp <?= number_format($row['quantity'] * $row['price_per_unit'], 2, ',', '.') ?></td>
                                <td><?= $row['purchase_date'] ?></td>
                                <td>
                                    <a href="?edit=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                                    <a href="?delete=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</a>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
