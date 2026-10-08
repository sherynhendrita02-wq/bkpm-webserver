<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tambah Mahasiswa</title>

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

        <div class="row justify-content-center">

            <div class="col-md-8 col-lg-7">

                <div class="card shadow-sm border-0">

                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">
                            Tambah Data Mahasiswa
                        </h4>
                    </div>

                    <div class="card-body p-4">

                        <form>

                            <!-- NIM -->
                            <div class="mb-3">
                                <label for="nim" class="form-label">
                                    NIM
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    id="nim"
                                    name="nim"
                                    placeholder="Masukkan NIM">
                            </div>

                            <!-- Nama -->
                            <div class="mb-3">
                                <label for="nama" class="form-label">
                                    Nama Mahasiswa
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    id="nama"
                                    name="nama"
                                    placeholder="Masukkan nama mahasiswa">
                            </div>

                            <!-- Program Studi -->
                            <div class="mb-3">
                                <label for="prodi" class="form-label">
                                    Program Studi
                                </label>

                                <select
                                    class="form-select"
                                    id="prodi"
                                    name="prodi">

                                    <option value="">
                                        -- Pilih Program Studi --
                                    </option>

                                    <option value="Teknik Informatika">
                                        Teknik Informatika
                                    </option>

                                    <option value="Manajemen Informatika">
                                        Manajemen Informatika
                                    </option>

                                    <option value="Teknik Komputer">
                                        Teknik Komputer
                                    </option>

                                </select>
                            </div>

                            <!-- Tombol -->
                            <div class="d-flex gap-2">

                                <button
                                    type="submit"
                                    class="btn btn-primary">
                                    Simpan
                                </button>

                                <a
                                    href="index.php"
                                    class="btn btn-secondary">
                                    Kembali
                                </a>

                            </div>

                        </form>

                    </div>

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