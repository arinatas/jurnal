<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Laporan Cash Flow</title>
		<link rel="shortcut icon" href="/assets/media/logos/smallprimakara.png" />

        <!-- Tell the browser to be responsive to screen width -->
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- Bootstrap 4 -->

        <!-- Font Awesome -->
        <!-- Ionicons -->
        <!-- adminlte css-->
        <link rel="stylesheet" href="/css/adminlte.min.css">

        <!-- Google Font: Source Sans Pro -->
        <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
        <style>
            .slip-gaji {
                page-break-before: always; /* Membuat halaman baru setiap slip gaji */
            }

            .slip-gaji:first-of-type {
                page-break-before: auto; /* Tidak ada page break pada elemen pertama */
            }
    </style>
    </head>

    <body>
    
    <div class="slip-gaji">
        <div class="wrapper">
            <!-- Main content -->
            <section class="invoice">
                <!-- title row -->
                <div class="row">
                    <div class="col-2">
                        <img alt="Logo" class="" src="/assets/media/logos/univ.png" width="160px" />
                    </div>
                    <div class="col-9 text-center">
                        <h2>LAPORAN CASH FLOW</h2>
                        <h1>PRIMAKARA UNIVERSITY</h1>
                    </div>
                    <!-- /.col -->
                </div>

                <!-- Table row -->
                <div class="row">
                    <div class="col-12 table-responsive">
                        <table class="table table-sm table-bordered">

                            <thead>
                                <tr class="fw-bold fs-6 text-gray-800 border-bottom-2">
                                    <th class="min-w-50px">No</th>
                                    <th class="min-w-100px">Tanggal</th>
                                    <th class="min-w-100px">No Bukti</th>
                                    <th class="min-w-100px">PIC</th>
                                    <th class="min-w-100px">Kode Anggaran</th>
                                    <th class="min-w-100px">Transaksi</th>
                                    <th class="min-w-50px">Ref</th>
                                    <th class="min-w-150px">Debit</th>
                                    <th class="min-w-150px">Kredit</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $no = 1; // Inisialisasi no
                                @endphp
                                @foreach ($cashflows as $item)
                                <tr>
                                    <td>{{ $no }}</td>
                                    <td>{{ $item->tanggal }}</td>
                                    <td>{{ $item->no_bukti }}</td>
                                    <td>{{ $item->pic }}</td>
                                    <td>{{ $item->rkat->kode_rkat }}</td>
                                    <td>{{ $item->transaksi }}</td>
                                    <td>{{ $item->ref }}</td>
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

                <!-- info row -->
                <div class="row">
                    <div class="col-sm-12 invoice-col mt-5">
                        <address style="float: inline-end;" class="mr-5">
                            <p>Denpasar, {{ date('d F Y', strtotime($item->created_at)) }}</strong><br>
                            <img alt="Logo" class="" src="/assets/media/logos/ttd.png" width="160px" />
                            <br>
                            <strong><u>I Made Artana, S.Kom.,M.M.</u></strong><br>
                            <strong>Rektor Primakara University</strong><br>
                        </address>
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
                </section>
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
