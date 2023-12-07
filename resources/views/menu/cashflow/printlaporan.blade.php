<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Laporan Cash Flow</title>
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
                        <h2>LAPORAN CASH FLOW</h2>
                        <h1 style="margin-bottom: 5px; margin-top: 5px;">PRIMAKARA UNIVERSITY</h1>
                        @if ($start_date == $end_date)
                            <h6>Periode: {{ date('d F Y', strtotime($start_date)) }}</h6>
                        @else
                            <h6>Periode: {{ date('d F Y', strtotime($start_date)) }} - {{ date('d F Y', strtotime($end_date)) }}</h6>
                        @endif
                        
                    </div>
                    <!-- /.col -->
                </div>

                <!-- Table row -->
                <div class="row mt-5">
                    <div class="col-12 table-responsive">
                        <table class="table table-sm table-bordered">

                            <thead>
                                <tr class="fw-bold fs-6 text-gray-800 border-bottom-2">
                                    <th class="min-w-50px" style="text-align: center;">No</th>
                                    <th class="min-w-100px" style="text-align: center;">Tanggal</th>
                                    <th class="min-w-100px" style="text-align: center;">No Bukti</th>
                                    <th class="min-w-100px" style="text-align: center;">PIC</th>
                                    <th class="min-w-100px" style="text-align: center;">Nama</th>
                                    <th class="min-w-50px" style="text-align: center;">Kode Anggaran</th>
                                    <th class="min-w-100px" style="text-align: center;">Transaksi</th>
                                    <th class="min-w-50px" style="text-align: center;">Ref</th>
                                    <th class="min-w-150px" style="text-align: center;">Debit</th>
                                    <th class="min-w-150px" style="text-align: center;">Kredit</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $no = 1; // Inisialisasi no
                                @endphp
                                @foreach ($cashflows as $item)
                                <tr>
                                    <td style="text-align: center;">{{ $no }}</td>
                                    <td style="text-align: center;">{{ $item->tanggal }}</td>
                                    <td style="text-align: center;">{{ $item->no_bukti }}</td>
                                    <td style="text-align: center;">{{ $item->pic }}</td>
                                    <td style="text-align: center;">{{ $item->nama }}</td>
                                    <td style="text-align: center; max-width: 50px;">{{ $item->rkat->kode_rkat }}</td>
                                    <td style="max-width: 150px;">{{ $item->transaksi }}</td>
                                    <td style="max-width: 50px;">{{ $item->ref }}</td>
                                    <td>Rp. @currency($item->debit )</td>
                                    <td>Rp. @currency($item->kredit )</td>
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
                                <tr class="fw-bold fs-6 text-gray-800">
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td style="text-align: left;"><strong>Saldo Akhir</strong></td>
                                    <td></td>
                                    <td style="text-align: left;"><strong>Rp. @currency($totalKas)</strong></td>
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
                            Waka II Bidang Keuangan & Sumber Daya</p>
                            <img alt="Logo" class="" src="/assets/media/logos/ttd.png" width="160px" />
                            <br>
                            <strong><u>I Made Sudama, S.E., M.M. </u></strong><br>
                            <strong>NIK. 2013091005</strong><br>
                        </address>
                    </div>
                    <!-- /.col -->

                    <div class="col-sm-4 invoice-col mt-5">
                        <address style="float: inline-end;" class="mr-5">
                            Diperiksa Oleh,<br>
                            Direktorat Keuangan</p>
                            <img alt="Logo" class="" src="/assets/media/logos/ttd.png" width="160px" />
                            <br>
                            <strong><u>I Gusti Ayu Agung Dina Purnama Sari, S.Tr. Akt</u></strong><br>
                            <strong>NIK. 2017070364</strong><br>
                        </address>
                    </div>
                    <!-- /.col -->

                    <div class="col-sm-4 invoice-col mt-4">
                        <address style="float: inline-end;" class="mr-5">
                        <p>Denpasar, {{ date('d F Y', strtotime($item->created_at)) }}<br>
                            Dibuat Oleh,<br>
                            Accounting</p>
                            <img alt="Logo" class="" src="/assets/media/logos/ttd.png" width="160px" />
                            <br>
                            <strong><u>Ida Ayu Shanti Dharmasari, S.E., Ak.</u></strong><br>
                            <strong>NIK. 2021112212</strong><br>
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
