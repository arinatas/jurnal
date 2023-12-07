<!-- resources/views/exports/cashflows.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cash Flows Report</title>
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
    <h2>Cash Flows Report</h2>
    <p>Generated on: {{ now()->format('Y-m-d H:i:s') }}</p><br>

    <table>
        <thead>
            <tr>
                <th style="text-align: center;">Tanggal</th>
                <th style="text-align: center;">No Bukti</th>
                <th style="text-align: center;">PIC</th>
                <th style="text-align: center;">Nama</th>
                <th style="text-align: center;">Kode Anggaran</th>
                <th style="text-align: center;">Transaksi</th>
                <th style="text-align: center;">Ref</th>
                <th style="text-align: center;">Debit</th>
                <th style="text-align: center;">Kredit</th>
                <th style="text-align: center;">ID Accounting</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cashflows as $cashflow)
                <tr>
                    <td style="text-align: center;">{{ $cashflow->tanggal }}</td>
                    <td style="text-align: center;">{{ $cashflow->no_bukti }}</td>
                    <td style="text-align: center;">{{ $cashflow->pic }}</td>
                    <td style="text-align: center;">{{ $cashflow->nama }}</td>
                    <td style="text-align: center;">{{ $cashflow->kode_anggaran }}</td>
                    <td style="text-align: left;">{{ $cashflow->transaksi }}</td>
                    <td style="text-align: left;">{{ $cashflow->ref }}</td>
                    <td style="text-align: left;">{{ $cashflow->debit }}</td>
                    <td style="text-align: left;">{{ $cashflow->kredit }}</td>
                    <td style="text-align: center;">{{ $cashflow->id_accounting }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
