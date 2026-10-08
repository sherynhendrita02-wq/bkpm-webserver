<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Prodi</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<div class="container mt-5">

    <h2 class="mb-4">Tambah Data Prodi</h2>

    <div class="card">
        <div class="card-body">

            <form action="/bkpm-webserver/acara10/public/prodi" method="POST">

                <div class="mb-3">
                    <label class="form-label">Kode Prodi</label>

                    <input
                        type="text"
                        name="kode"
                        class="form-control"
                        maxlength="10"
                        required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nama Prodi</label>

                    <input
                        type="text"
                        name="nama"
                        class="form-control"
                        required>
                </div>

                <a
                    href="/bkpm-webserver/acara10/public/prodi"
                    class="btn btn-secondary">
                    Kembali
                </a>

                <button
                    type="submit"
                    class="btn btn-primary">
                    Simpan
                </button>

            </form>

        </div>
    </div>

</div>

</body>
</html>