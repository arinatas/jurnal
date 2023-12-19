<!-- resources/views/exports/cashflows.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RKAT Export</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h2>RKAT Export</h2>
    <p>Generated on: {{ now()->format('Y-m-d H:i:s') }}</p><br>

    <table>
        <thead>
            <tr>
                <th style="text-align: center;">ID RKAT</th>
                <th style="text-align: center;">Kode RKAT</th>
                <th style="text-align: center;">Keterangan</th>
                <th style="text-align: center;">No Akun</th>
                <th style="text-align: center;">Nama Akun</th>
                <th style="text-align: center;">Periode</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rkats as $rkat)
                <tr>
                    <td style="text-align: center;">{{ $rkat->id }}</td>
                    <td style="text-align: center;">{{ $rkat->kode_rkat }}</td>
                    <td style="text-align: center;">{{ $rkat->keterangan }}</td>
                    <td style="text-align: center;">{{ $rkat->no_akun }}</td>
                    <td style="text-align: center;">{{ $rkat->jurnalAkun->nama_akun }}</td>
                    <td style="text-align: center;">{{ $rkat->periode }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
