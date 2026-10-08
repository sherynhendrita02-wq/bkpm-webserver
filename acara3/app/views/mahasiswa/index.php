<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Data Mahasiswa</title>

    <!-- Bootstrap 5 -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">
</head>

<body class="bg-light">

    <!-- Navbar -->
    <nav class="navbar navbar-dark bg-primary">
        <div class="container">
            <span class="navbar-brand mb-0 h1">
                Sistem Informasi Akademik
            </span>
        </div>
    </nav>

    <!-- Content -->
    <div class="container py-5">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold">Data Mahasiswa</h2>
                <p class="text-muted mb-0">
                    Daftar mahasiswa Sistem Informasi Akademik
                </p>
            </div>

            <a href="create.php" class="btn btn-primary">
                + Tambah Mahasiswa
            </a>
        </div>

        <!-- Card -->
        <div class="card shadow-sm border-0">

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-bordered table-hover align-middle mb-0">

                        <thead class="table-primary">
                            <tr>
                                <th width="60">No</th>
                                <th>NIM</th>
                                <th>Nama</th>
                                <th>Program Studi</th>
                            </tr>
                        </thead>

                        <tbody>

                            <tr>
                                <td>1</td>
                                <td>E41250604</td>
                                <td>Sheryn Febrylia Hendrita</td>
                                <td>Teknik Informatika</td>
                            </tr>

                            <tr>
                                <td>2</td>
                                <td>E41250657</td>
                                <td>Ingka Jivanda Gayshela</td>
                                <td>Teknik Informatika</td>
                            </tr>

                            <tr>
                                <td>3</td>
                                <td>E41250218</td>
                                <td>Dinda Febiola Rachmawati</td>
                                <td>Teknik Informatika</td>
                            </tr>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

    <!-- Bootstrap JS -->
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
    </script>

</body>

</html>