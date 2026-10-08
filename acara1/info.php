<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informasi Server</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f8;
            padding: 40px;
        }

        .container {
            max-width: 700px;
            margin: auto;
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #000000;
            margin-bottom: 25px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #000000;
            color: white;
        }

        th:first-child {
            width: 35%;
        }

        tr:nth-child(even) {
            background-color: #f8f9fa;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Informasi Server</h1>

        <table>
            <tr>
                <th>Informasi</th>
                <th>Keterangan</th>
            </tr>

        <table>
            <tr>
                <th>Nama</th>
                <td>Sheryn Febrylia Hendrita</td>
            </tr>

            <tr>
                <th>NIM</th>
                <td>E41250604</td>
            </tr>

            <tr>
                <th>Waktu Server</th>
                <td>
                    <?php
                    echo date('Y-m-d H:i:s');
                    ?>
                </td>
            </tr>

            <tr>
                <th>Versi PHP</th>
                <td>
                    <?php
                    echo phpversion();
                    ?>
                </td>
            </tr>

            <tr>
                <th>Sistem Operasi Server</th>
                <td>
                    <?php
                    echo PHP_OS;
                    ?>
                </td>
            </tr>
        </table>
    </div>
</body>

</html>