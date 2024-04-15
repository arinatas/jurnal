<!-- resources/views/exports/cashflows.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buku Besar Report</title>
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
    <h2>Buku Besar Report</h2>
    <p>Generated on: {{ now()->format('Y-m-d H:i:s') }}</p><br>

    <table>
        <thead>
            <tr>
                <th style="text-align: center;">Periode</th>
                <th style="text-align: center;">Tipe Jurnal</th>
                <th style="text-align: center;">Uraian</th>
                <th style="text-align: center;">Divisi</th>
                <th style="text-align: center;">Kode Akun</th>
                <th style="text-align: center;">Nama Akun</th>
                <th style="text-align: center;">No Bukti</th>
                <th style="text-align: center;">Debit</th>
                <th style="text-align: center;">Kredit</th>
                <th style="text-align: center;">Ket. RKAT</th>
            </tr>
        </thead>

        <tbody>
            @foreach($bukubesars as $bukubesar)
                <tr>
                    <td style="text-align: center;">{{ $bukubesar->periode_jurnal }}</td>
                    <td style="text-align: center;">{{ $bukubesar->type_jurnal }}</td>
                    <td style="text-align: center;">{{ $bukubesar->uraian }}</td>
                    <td style="text-align: left;">{{ $bukubesar->dataDivisi->nama_divisi }}</td>
                    <td style="text-align: left;">{{ $bukubesar->kode_akun }}</td>
                    <td style="text-align: left;">{{ $bukubesar->akun->nama_akun }}</td>
                    <td style="text-align: center;">{{ $bukubesar->no_bukti }}</td>
                    <td style="text-align: center;">{{ $bukubesar->debit }}</td>
                    <td style="text-align: center;">{{ $bukubesar->kredit }}</td>
                    <td style="text-align: center;">{{ $bukubesar->keterangan_rkat }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
