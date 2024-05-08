<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Laporan Neraca {{ \Carbon\Carbon::parse(date("Y-m-d h:i:sa"))->format('j F Y'); }}</title>
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
                    <div class="col-9 text-center text-uppercase">
                        <h2>YAYASAN PRIMAKARA</h2>
                        <h1>LAPORAN POSISI KEUANGAN</h1>
                        <!-- <h2>PERIODE {{ \Carbon\Carbon::parse(date("Y-m-d h:i:sa"))->format('F Y') }}</h2> -->
                        <h2>PERIODE {{ \Carbon\Carbon::create($selectedYear, $selectedMonth, 1)->format('F Y') }}</h2>
                    </div>
                    <!-- /.col -->
                </div>

                <!-- Table row -->
                <div class="row">
                    <div class="col-6 table-responsive">
                        <h4><b>ASET LANCAR</b></h4>
                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr class="fw-semibold fs-6 text-bold">
                                    <th>Kas & Bank</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($kasDanBank as $item)
                                    <tr>
                                        <td>{{ $item->no_akun }} {{ $item->nama_akun }}</td>
                                        <td>@currency($item->total_neraca)</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr class="fw-semibold fs-6 text-bold">
                                    <th>Piutang</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($piutang as $item)
                                    <tr>
                                        <td>{{ $item->no_akun }} {{ $item->nama_akun }}</td>
                                        <td>@currency($item->total_neraca)</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
						<h3 class="text-center" style="margin-bottom: 50px"> Sub Total @currency($subTotalAsetLancar)</h3>

                    </div>
                    <div class="col-6 table-responsive">
                        <h4><b>LIABILITAS</b></h4>
                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr class="fw-semibold fs-6 text-bold">
                                    <th>Liabilitas Jangka Pendek</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($lljPendek as $item)
                                    <tr>
                                        <td>{{ $item->no_akun }} {{ $item->nama_akun }}</td>
                                        <td>@currency($item->total_neraca)</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr class="fw-semibold fs-6 text-bold">
                                    <th>Liabilitas Jangka Panjang </th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($lljPanjang as $item)
                                    <tr>
                                        <td>{{ $item->no_akun }} {{ $item->nama_akun }}</td>
                                        <td>@currency($item->total_neraca)</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
						<h3 class="text-center" style="margin-bottom: 50px"> Sub Total @currency($subTotalLiabilitas)</h3>

                    </div>
                    <div class="col-6 table-responsive">
                        <h4><b>ASET TIDAK LANCAR</b></h4>
                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr class="fw-semibold fs-6 text-bold">
                                    <th>Aset Tidak Lancar</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($asetTidakLancar as $item)
                                    <tr>
                                        <td>{{ $item->no_akun }} {{ $item->nama_akun }}</td>
                                        <td>@currency($item->total_neraca)</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
						<h3 class="text-center" style="margin-bottom: 50px"> Sub Total @currency($subTotalAsetTidakLancar)</h3>

                    </div>
                    <div class="col-6 table-responsive">
                        <h4><b>EKUITAS</b></h4>
                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr class="fw-semibold fs-6 text-bold">
                                    <th>Ekuitas</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($ekuitas as $item)
                                    <tr>
                                        <td>{{ $item->no_akun }} {{ $item->nama_akun }}</td>
                                        <td class="text-center">
											@if ($item->no_akun == '3-30200')
											    @currency($item->total_neraca + $kenaikanPenurunanAsetNettoTidakTerikat)
											@else
												@currency($item->total_neraca)
											@endif
										</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
						<!-- <h3 class="text-center" style="margin-bottom: 50px"> Sub Total @currency($subTotalEkuitasAktivitas)</h3> -->
                        @if ($subTotalEkuitas != null)
							<h3 class="text-center mt-10">Sub Total @currency($subTotalEkuitasAktivitas)</h3>
						@else
							<h3 class="text-center mt-10">Sub Total 0</h3>
						@endif
                    </div>
                    <div class="col-6">
                        <div class="bg-light-dark rounded border-dark border border-dashed p-3">
                            <!--begin::Wrapper-->
                            <h2 class="text-gray-900 fw-bolder" style="text-align: center;">Grand Total @currency($grandTotalAsset)</h2>
                            <!--end::Wrapper-->
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="bg-light-dark rounded border-dark border border-dashed p-3">
                            <!--begin::Wrapper-->
                            <h2 class="text-gray-900 fw-bolder" style="text-align: center;">Grand Total @currency($grandTotalLiabilDanEkuitasAktivitas)</h2>
                            <!--end::Wrapper-->
                        </div>
                    </div>
                </div>
                <!-- /.row -->
                <!-- /.row -->
                    <div class="row" style="margin-top: 50px">
                        <div class="col-sm-4 invoice-col mt-4">
                            <address style="float: inline-end;" class="mr-5">
                                Disetujui,<br></p>
                                <!-- <img alt="Logo" class="" src="/assets/media/logos/ttd.png" width="160px" /> -->
                                <br><br><br>
                                <strong><u>Juniwati, S.Kom. </u></strong><br>
                                <strong>Ketua Yayasan</strong><br>
                            </address>
                        </div>
                        <!-- /.col -->

                        <div class="col-sm-4 invoice-col mt-4">
                            <address style="float: inline-end;" class="mr-5">
                                Diketahui,<br></p>
                                <!-- <img alt="Logo" class="" src="/assets/media/logos/ttd.png" width="160px" /> -->
                                <br><br><br>
                                <strong><u>I Made Artana, S.Kom., M.M.</u></strong><br>
                                <strong>Rektor Primakara University</strong><br>
                            </address>
                        </div>
                        <!-- /.col -->

                        <div class="col-sm-4 invoice-col mt-4">
                            <address style="float: inline-end;" class="mr-5">
                                <p>Denpasar, {{ date('d F Y') }}<br></p>
                                <!-- <img alt="Logo" class="" src="/assets/media/logos/ttd.png" width="160px" /> -->
                                <br><br><br>
                                <strong><u>I Made Sudama, S.E., M.M.</u></strong><br>
                                <strong>WAREK II Bidang Sumber Daya</strong><br>
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
