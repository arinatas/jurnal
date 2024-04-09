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
    <h2>Aktivitas Report</h2>
    <p>Generated on: {{ now()->format('Y-m-d H:i:s') }}</p><br>

    <table>
        <thead>
            <tr>
                <th style="text-align: center;">No Akun</th>
                <th style="text-align: center;">Nama Akun</th>
                <th style="text-align: center;">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pendapatan as $akun)
                <tr>
                    <td style="text-align: center;">{{  $akun->no_akun }}</td>
                    <td style="text-align: center;">{{ $akun->nama_akun }}</td>
                    <td style="text-align: center;">{{ $pendapatanAmounts[$akun->no_akun] ?? 0 }}</td>
                </tr>
            @endforeach
            @foreach($bebanSehubunganProgram as $akun)
                <tr>
                    <td style="text-align: center;">{{  $akun->no_akun }}</td>
                    <td style="text-align: center;">{{ $akun->nama_akun }}</td>
                    <td style="text-align: center;">{{ $bebanSehubunganProgramAmounts[$akun->no_akun] ?? 0 }}</td>
                </tr>
            @endforeach
            @foreach($pendapatanLainlain as $akun)
                <tr>
                    <td style="text-align: center;">{{  $akun->no_akun }}</td>
                    <td style="text-align: center;">{{ $akun->nama_akun }}</td>
                    <td style="text-align: center;">{{ $pendapatanLainlainAmounts[$akun->no_akun] ?? 0 }}</td>
                </tr>
            @endforeach
            @foreach($bebanMarketing as $akun)
                <tr>
                    <td style="text-align: center;">{{  $akun->no_akun }}</td>
                    <td style="text-align: center;">{{ $akun->nama_akun }}</td>
                    <td style="text-align: center;">{{ $bebanMarketingAmounts[$akun->no_akun] ?? 0 }}</td>
                </tr>
            @endforeach
            @foreach($bebanKegiatan as $akun)
                <tr>
                    <td style="text-align: center;">{{  $akun->no_akun }}</td>
                    <td style="text-align: center;">{{ $akun->nama_akun }}</td>
                    <td style="text-align: center;">{{ $bebanKegiatanAmounts[$akun->no_akun] ?? 0 }}</td>
                </tr>
            @endforeach
            @foreach($bebanGaji as $akun)
                <tr>
                    <td style="text-align: center;">{{  $akun->no_akun }}</td>
                    <td style="text-align: center;">{{ $akun->nama_akun }}</td>
                    <td style="text-align: center;">{{ $bebanGajiAmounts[$akun->no_akun] ?? 0 }}</td>
                </tr>
            @endforeach
            @foreach($bebanOperasionalKantor as $akun)
                <tr>
                    <td style="text-align: center;">{{  $akun->no_akun }}</td>
                    <td style="text-align: center;">{{ $akun->nama_akun }}</td>
                    <td style="text-align: center;">{{ $bebanOperasionalKantorAmounts[$akun->no_akun] ?? 0 }}</td>
                </tr>
            @endforeach
            @foreach($bebanRumahTanggaKantor as $akun)
                <tr>
                    <td style="text-align: center;">{{  $akun->no_akun }}</td>
                    <td style="text-align: center;">{{ $akun->nama_akun }}</td>
                    <td style="text-align: center;">{{ $bebanRumahTanggaKantorAmounts[$akun->no_akun] ?? 0 }}</td>
                </tr>
            @endforeach
            @foreach($bebanSewa as $akun)
                <tr>
                    <td style="text-align: center;">{{  $akun->no_akun }}</td>
                    <td style="text-align: center;">{{ $akun->nama_akun }}</td>
                    <td style="text-align: center;">{{ $bebanSewaAmounts[$akun->no_akun] ?? 0 }}</td>
                </tr>
            @endforeach
            @foreach($bebanPerawatan as $akun)
                <tr>
                    <td style="text-align: center;">{{  $akun->no_akun }}</td>
                    <td style="text-align: center;">{{ $akun->nama_akun }}</td>
                    <td style="text-align: center;">{{ $bebanPerawatanAmounts[$akun->no_akun] ?? 0 }}</td>
                </tr>
            @endforeach
            @foreach($bebanYayasan as $akun)
                <tr>
                    <td style="text-align: center;">{{  $akun->no_akun }}</td>
                    <td style="text-align: center;">{{ $akun->nama_akun }}</td>
                    <td style="text-align: center;">{{ $bebanYayasanAmounts[$akun->no_akun] ?? 0 }}</td>
                </tr>
            @endforeach
            @foreach($bebanLainlain as $akun)
                <tr>
                    <td style="text-align: center;">{{  $akun->no_akun }}</td>
                    <td style="text-align: center;">{{ $akun->nama_akun }}</td>
                    <td style="text-align: center;">{{ $bebanLainlainAmounts[$akun->no_akun] ?? 0 }}</td>
                </tr>
            @endforeach
            @foreach($pajak as $akun)
                <tr>
                    <td style="text-align: center;">{{  $akun->no_akun }}</td>
                    <td style="text-align: center;">{{ $akun->nama_akun }}</td>
                    <td style="text-align: center;">{{ $pajakAmounts[$akun->no_akun] ?? 0 }}</td>
                </tr>
            @endforeach
            @foreach($depresiasi as $akun)
                <tr>
                    <td style="text-align: center;">{{  $akun->no_akun }}</td>
                    <td style="text-align: center;">{{ $akun->nama_akun }}</td>
                    <td style="text-align: center;">{{ $depresiasiAmounts[$akun->no_akun] ?? 0 }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
