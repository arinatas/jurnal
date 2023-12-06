<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Piutang {{ \Carbon\Carbon::parse(date("Y-m-d h:i:sa"))->format('j F Y'); }}</title>
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
    </head>

    <body>
        <div class="wrapper">
            <!-- Main content -->
            <section class="invoice">
                <!-- title row -->
                <div class="row">
                    <div class="col-2">
                        <img alt="Logo" class="" src="/assets/media/logos/univ.png" width="160px" />
                    </div>
                    <div class="col-9 text-center">
                        <h2>LIST DATA PIUTANG</h2>
                        <h1>PRIMAKARA UNIVERSITY</h1>
                        <h2>{{ \Carbon\Carbon::parse(date("Y-m-d h:i:sa"))->format('j F Y'); }}</h2>
                    </div>
                    <!-- /.col -->
                </div>

                <!-- Table row -->
                <div class="row">
                    <div class="col-12 table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead>
                                
                                <tr class="fw-semibold fs-6 text-bold">
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Nama</th>
                                    <th>Keterangan</th>
                                    <th>Realisasi</th>
                                    <th>Jumlah Piutang (Rp)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $no = 1; // Inisialisasi no
                                @endphp
                                @foreach ($piutang as $item)
                                    <tr>
                                        <td>{{ $no }}</td>
                                        <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('j F Y'); }}</td>
                                        <td>{{ $item->nama }}</td>
                                        <td>{{ $item->keterangan }}</td>
                                        <td>
                                            @if ($item->stts_reallisasi == 0)
                                            <span class="badge bg-warning text-dark">Menunggu</span>
                                            @elseif($item->stts_reallisasi == 1)
                                            <span class="badge bg-primary">Selesai</span>
                                            @endif
                                        </td>
                                        <td>@currency( $item->jumlah_piutang )</td>
                                    </tr>
                                @php
                                    $no++; // Tambahkan no setiap kali iterasi
                                @endphp
                                @endforeach
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td>
                                        <div>
                                            <b>Total Piutang</b>
                                        </div>
                                    </td>
                                    <td><u><b> Rp. @currency($totalPiutang)</b></u></td>
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
                            <p>Denpasar, {{ \Carbon\Carbon::parse(date("Y-m-d h:i:sa"))->format('j F Y'); }}</strong><br>
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
