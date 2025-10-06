<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = htmlspecialchars($_POST['nama']);
    $harga = htmlspecialchars($_POST['harga']);
    $deskripsi = htmlspecialchars($_POST['deskripsi']);

    // Validasi input sederhana
    if (empty($nama) || empty($harga) || empty($deskripsi)) {
        echo "Semua field harus diisi!";
        exit();
    }
    if (!is_numeric($harga) || $harga < 0) {
        echo "Harga harus berupa angka positif!";
        exit();
    }
    if(strlen($nama) > 100) {
        echo "Nama tidak boleh lebih dari 100 karakter!";
        exit();
    }
    if(strlen($deskripsi) > 500) {
        echo "Deskripsi tidak boleh lebih dari 500 karakter!";
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <h3 class="text-center mb-4">Data Produk Diterima</h3>
                <ul class="list-group">
                    <li class="list-group-item"><strong>Nama:</strong> <?php echo $nama; ?></li>
                    <li class="list-group-item"><strong>Harga:</strong> <?php echo $harga; ?></li>
                    <li class="list-group-item"><strong>Deskripsi:</strong> <?php echo $deskripsi; ?></li>
                </ul>
                <a href="index.php" class="btn btn-primary w-100 mt-4">Kembali</a>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>