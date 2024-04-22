<!-- resources/views/exports/cashflows.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Neraca Report</title>
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
    <h2>Neraca Report</h2>
    <p>Generated on: {{ now()->format('Y-m-d H:i:s') }}</p><br>

    <table>
        <thead>
            <tr>
                <th style="text-align: center;">No Akun</th>
                <th style="text-align: center;">Nama Akun</th>
                <th style="text-align: center;">Type Neraca</th>
                <th style="text-align: center;">Sub Type</th>
                <th style="text-align: center;">Tipe Akun</th>
                <th style="text-align: center;">Periode Jurnal</th>
                <th style="text-align: center;">Total Debit</th>
                <th style="text-align: center;">Total Kredit</th>
                <th style="text-align: center;">Total Neraca</th>
            </tr>
        </thead>

        <tbody>
            @foreach($neraca as $item)
                <tr>
                    <td style="text-align: center;">{{ $item->no_akun }}</td>
                    <td style="text-align: center;">{{ $item->nama_akun }}</td>
                    <td style="text-align: center;">{{ $item->type_neraca }}</td>
                    <td style="text-align: left;">{{ $item->sub_type }}</td>
                    <td style="text-align: left;">{{ $item->tipe_akun }}</td>
                    <td style="text-align: center;">{{ $item->periode_jurnal }}</td>
                    <td style="text-align: center;">{{ $item->total_debit }}</td>
                    <td style="text-align: center;">{{ $item->total_kredit }}</td>
                    <td style="text-align: center;">{{ $item->total_neraca }}</td>
                </tr>
            @endforeach
            @foreach($neracaKredit as $item)
                <tr>
                    <td style="text-align: center;">{{ $item->no_akun }}</td>
                    <td style="text-align: center;">{{ $item->nama_akun }}</td>
                    <td style="text-align: center;">{{ $item->type_neraca }}</td>
                    <td style="text-align: left;">{{ $item->sub_type }}</td>
                    <td style="text-align: left;">{{ $item->tipe_akun }}</td>
                    <td style="text-align: center;">{{ $item->periode_jurnal }}</td>
                    <td style="text-align: center;">{{ $item->total_debit }}</td>
                    <td style="text-align: center;">{{ $item->total_kredit }}</td>
                    <td style="text-align: center;">{{ $item->total_neraca }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
