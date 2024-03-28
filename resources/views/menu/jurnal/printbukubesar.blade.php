<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Laporan Buku Besar {{ \Carbon\Carbon::parse(date("Y-m-d h:i:sa"))->format('j F Y'); }}</title>
		<link rel="shortcut icon" href="/assets/media/logos/smallprimakara.png">

        <!-- Tell the browser to be responsive to screen width -->
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- Bootstrap 4 -->

        <!-- Font Awesome -->
        <!-- Ionicons -->
        <!-- adminlte css-->
        <link rel="stylesheet" href="/css/adminlte.min.css">

        <!-- Google Font: Source Sans Pro -->
        <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    </head>

    <body>
    
    <div class="slip-gaji">
        <div class="wrapper">
            <!-- Main content -->
            <section class="invoice">
                <!-- title row -->
                <div class="row">
                    <div class="col-2">
                        <img alt="Logo" class="mt-5" src="/assets/media/logos/univ.png" width="200px" />
                    </div>
                    <div class="col-9 text-center">
                        <h2>YAYASAN PRIMAKARA</h2>
                        <h1 style="margin-bottom: 5px; margin-top: 5px;">LAPORAN BUKU BESAR</h1>
                        @php
                            $formattedMonth = strtoupper(date('F', strtotime($selectedYear . '-' . $selectedMonth . '-01')));
                        @endphp
                        <h6>PERIODE {{ $formattedMonth }} {{ $selectedYear }}</h6>
                    </div>
                    <!-- /.col -->
                </div>

                <!-- Table row -->
                <div class="row mt-5">
                    <div class="col-12 table-responsive">
                        <table class="table table-sm table-bordered">

                            <thead>
                                <tr class="fw-bold fs-6 text-gray-800 border-bottom-2">
                                    <th class="min-w-50px text-center">No</th>
                                    <th class="min-w-100px text-center">Periode</th>
                                    <th class="min-w-50px text-center">Tipe Jurnal</th>
                                    <th class="min-w-100px text-center">Uraian</th>
                                    <th class="min-w-100px text-center">Divisi</th>
                                    <th class="min-w-100px text-center">Kode Akun</th>
                                    <th class="min-w-100px text-center">Nama Akun</th>
                                    <th class="min-w-100px text-center">No Bukti</th>
                                    <th class="min-w-150px text-center">Debit</th>
                                    <th class="min-w-150px text-center">Kredit</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $no = 1; // Inisialisasi no
                                @endphp
                                @foreach ($jurnals as $item)
                                <tr>
                                    <td style="text-align: center;">{{ $no }}</td>
                                    <td style="text-align: center;">{{ \Carbon\Carbon::parse($item->periode_jurnal)->format('j F Y'); }}</td>
                                    <td style="text-align: center;">
                                        @if($item->type_jurnal == 'ju')
                                            <span>Umum</span>
                                        @elseif($item->type_jurnal == 'jp')
                                            <span>Penyesuaian</span>
                                        @else
                                            <span>-</span>
                                        @endif
                                    </td>
                                    <td style="text-align: center;">{{ $item->uraian }}</td>
                                    <td class="text-center">{{ $item->dataDivisi->nama_divisi }}</td>
                                    <td class="text-center">{{ $item->akun->no_akun }}</td>
                                    <td>{{ $item->akun->nama_akun }}</td>
                                    <td class="text-center">{{ $item->no_bukti }}</td>
                                    <td class="text-center">@if($item->debit != 0) Rp. @currency($item->debit) @else - @endif</td>
                                    <td class="text-center">@if($item->kredit != 0) Rp. @currency($item->kredit) @else - @endif</td>
                                </tr>
                                @php
                                    $no++; // Tambahkan no setiap kali iterasi
                                @endphp
                                @endforeach
                                <!-- Total rows after the loop -->
                                <tr class="fw-bold fs-6 text-gray-800">
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td><strong>Total</strong></td>
                                    <td><strong>Rp. @currency($totalDebit)</strong></td>
                                    <td><strong>Rp. @currency($totalKredit)</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->

                <!-- /.row -->
                <div class="row">
                    <div class="col-sm-4 invoice-col mt-5">
                        <address style="float: inline-end;" class="mr-5">
                            Mengetahui,<br>
                            Warek II Bidang Keuangan & Sumber Daya</p>
                            <!-- <img alt="Logo" class="" src="/assets/media/logos/ttd.png" width="160px" /> -->
                            <br><br><br>
                            <strong><u>I Made Sudama, S.E., M.M. </u></strong><br>
                            <strong>NIK. 2013091005</strong><br>
                        </address>
                    </div>
                    <!-- /.col -->

                    <div class="col-sm-4 invoice-col mt-5">
                        <address style="float: inline-end;" class="mr-5">
                            Diperiksa Oleh,<br>
                            Direktorat Keuangan</p>
                            <!-- <img alt="Logo" class="" src="/assets/media/logos/ttd.png" width="160px" /> -->
                            <br><br><br>
                            <strong><u>I Gusti Ayu Agung Dina Purnama Sari, S.Tr. Akt</u></strong><br>
                            <strong>NIK. 2017070364</strong><br>
                        </address>
                    </div>
                    <!-- /.col -->

                    <div class="col-sm-4 invoice-col mt-4">
                        <address style="float: inline-end;" class="mr-5">
                            <p>Denpasar, {{ date('d F Y') }}<br>
                            Dibuat Oleh,<br>
                            Accounting</p>
                            <!-- <img alt="Logo" class="" src="/assets/media/logos/ttd.png" width="160px" /> -->
                            <br><br><br>
                            <strong><u>Anak Agung Kompiang Ari Purnamayani, S.Ak/u></strong><br>
                            <strong>NIK. 2024022208</strong><br>
                        </address>
                    </div>
                    <!-- /.col -->
                </div>
            </div>
        </div>
        <!-- ./wrapper -->

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var dateElements = document.querySelectorAll('[data-date]');

                dateElements.forEach(function(element) {
                    var dateValue = element.getAttribute('data-date');
                    var formattedDate = new Date(dateValue).toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' });

                    element.textContent = formattedDate;
                });
            });
        </script>

        <script type="text/javascript">
            window.addEventListener("load", window.print());
        </script>
    </body>
</html>
