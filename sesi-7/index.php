<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <h3 class="text-center mb-4">Form Input Data Produk</h3>
                <form action="/sesi-7/process.php" method="POST" id="form">
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="nama" name="nama" required>
                    </div>
                    <div class="mb-3">
                        <label for="harga" class="form-label">Harga</label>
                        <input type="number" class="form-control" id="harga" name="harga" required>
                    </div>
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="deskripsi" name="deskripsi" rows="4" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Kirim</button>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Validasi Form Js -->
     <script>
        $(document).ready(function() {
            $('#form').on('submit', function(e) {
                var nama = $('#nama').val().trim();
                var harga = $('#harga').val().trim();
                var deskripsi = $('#deskripsi').val().trim();

                if (nama === '' || harga === '' || deskripsi === '') {
                    alert('Semua field harus diisi!');
                    e.preventDefault();
                    return;
                }
                if (isNaN(harga) || harga < 0) {
                    alert('Harga harus berupa angka positif!');
                    e.preventDefault();
                    return;
                }
                if(nama.length > 100) {
                    alert('Nama tidak boleh lebih dari 100 karakter!');
                    e.preventDefault();
                    return;
                }
                if(deskripsi.length > 500) {
                    alert('Deskripsi tidak boleh lebih dari 500 karakter!');
                    e.preventDefault();
                    return;
                }
            });
        });
    </script>
</body>
</html>