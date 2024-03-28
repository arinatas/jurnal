<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Laporan Penghasilan Komprehensif {{ \Carbon\Carbon::parse(date("Y-m-d h:i:sa"))->format('j F Y'); }}</title>
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
    
    <div >
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
                        <h2 style="margin-bottom: 10px; margin-top: 5px;">LAPORAN PENGHASILAN KOMPREHENSIF</h2>
                        @php
                            $formattedMonth = strtoupper(date('F', strtotime($selectedYear . '-' . $selectedMonth . '-01')));
                        @endphp
                        <h6>PERIODE {{ $formattedMonth }} {{ $selectedYear }}</h6>
                    </div>
                    <!-- /.col -->
                </div>

                <!-- Table for Pendapatan -->
                @if ($pendapatan->count() > 0)
                    <div class="table-responsive my-10 mx-8" style="margin-bottom: 35px;">
                        <h4><strong>Pendapatan</strong></h4>
                        <table class="table table-striped gy-7 gs-7">
                            <thead>
                                <tr class="fw-bold fs-6 text-gray-800 border-bottom-2 border-gray-200">
                                    <th class="min-w-50px">No</th>
                                    <th class="min-w-50px">No Akun</th>
                                    <th class="min-w-100px">Nama Akun</th>
                                    <th class="min-w-100px">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $no = 1; $totalPendapatan = 0; @endphp
                                @foreach ($pendapatan as $item)
                                    <tr>
                                        <td>{{ $no }}</td>
                                        <td>{{ $item->no_akun }}</td>
                                        <td>{{ $item->nama_akun }}</td>
                                        <td class="text-left">@if($pendapatanAmounts[$item->no_akun] != 0) Rp. @currency($pendapatanAmounts[$item->no_akun]) @else - @endif</td>
                                    </tr>
                                @php
                                    $no++;
                                    $totalPendapatan += $pendapatanAmounts[$item->no_akun];
                                @endphp
                                @endforeach
                            </tbody>
                            <!-- Display total row after the loop -->
                            <tr>
                                <td colspan="3">
                                    <span style="margin-left: 80px; font-weight: bold; font-size: 18px;">Total Pendapatan</span>
                                </td>
                                <td style="font-weight: bold; font-size: 18px;">Rp. @currency($totalPendapatan)</td>
                            </tr>
                        </table>
                    </div>
                @endif

                <!-- Table for Beban Sehubungan Program -->
                @if ($bebanSehubunganProgram->count() > 0)
                    <div class="table-responsive my-10 mx-8" style="margin-bottom: 35px;">
                        <h4><strong>Beban Sehubungan Program</strong></h4>
                            <table class="table table-striped gy-7 gs-7">
                                <thead>
                                    <tr class="fw-bold fs-6 text-gray-800 border-bottom-2 border-gray-200">
                                        <th class="min-w-50px">No</th>
                                        <th class="min-w-50px">No Akun</th>
                                        <th class="min-w-100px">Nama Akun</th>
                                        <th class="min-w-100px">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $no = 1; $totalBebanSehubunganProgram = 0; @endphp
                                    @foreach ($bebanSehubunganProgram as $item)
                                        <tr>
                                            <td>{{ $no }}</td>
                                            <td>{{ $item->no_akun }}</td>
                                            <td>{{ $item->nama_akun }}</td>
                                            <td class="text-left">@if($bebanSehubunganProgramAmounts[$item->no_akun] != 0) Rp. @currency($bebanSehubunganProgramAmounts[$item->no_akun]) @else - @endif</td>
                                        </tr>
                                    @php
                                        $no++;
                                        // Accumulate the total for Beban Sehubungan Program
                                        $totalBebanSehubunganProgram += $bebanSehubunganProgramAmounts[$item->no_akun];
                                    @endphp
                                    @endforeach
                                </tbody>
                                <!-- Display total row after the loop -->
                                    <tr>
                                        <td colspan="3">
                                            <span style="margin-left: 80px; font-weight: bold; font-size: 18px;">Total Beban Sehubungan Program</span>
                                        </td>
                                        <td style="font-weight: bold; font-size: 18px;">Rp. @currency($totalBebanSehubunganProgram)</td>
                                    </tr>
                                    <!-- Calculate and store Laba Kotor in a variable -->
                                    @php
                                        $labaKotor = $totalPendapatan - $totalBebanSehubunganProgram;
                                    @endphp
                                    <tr>
                                        <td colspan="3">
                                            <span style="margin-left: 80px; font-weight: bold; font-size: 18px;">Laba Kotor</span>
                                        </td>
                                        <td style="font-weight: bold; font-size: 18px;">Rp. @currency($labaKotor)</td>
                                    </tr>
                                </table>
                            </div>
                        @endif

                        <!-- Table for Pendapatan Lain Lain-->
                        @if ($pendapatanLainlain->count() > 0)
                            <div class="table-responsive my-10 mx-8" style="margin-bottom: 35px;">
                                <h4><strong>Pendapatan Lain Lain</strong></h4>
                                <table class="table table-striped gy-7 gs-7">
                                    <thead>
                                        <tr class="fw-bold fs-6 text-gray-800 border-bottom-2 border-gray-200">
                                            <th class="min-w-50px">No</th>
                                            <th class="min-w-50px">No Akun</th>
                                            <th class="min-w-100px">Nama Akun</th>
                                            <th class="min-w-100px">Jumlah</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $no = 1; $totalPendapatanLainlain = 0; @endphp
                                        @foreach ($pendapatanLainlain as $item)
                                            <tr>
                                                <td>{{ $no }}</td>
                                                <td>{{ $item->no_akun }}</td>
                                                <td>{{ $item->nama_akun }}</td>
                                                <td class="text-left">@if($pendapatanLainlainAmounts[$item->no_akun] != 0) Rp. @currency($pendapatanLainlainAmounts[$item->no_akun]) @else - @endif</td>
                                            </tr>
                                        @php
                                            $no++;
                                            $totalPendapatanLainlain += $pendapatanLainlainAmounts[$item->no_akun];
                                        @endphp
                                        @endforeach
                                    </tbody>
                                    <!-- Display total row after the loop -->
                                    <tr>
                                        <td colspan="3">
                                            <span style="margin-left: 80px; font-weight: bold; font-size: 18px;">Total Pendapatan Lain Lain</span>
                                        </td>
                                        <td style="font-weight: bold; font-size: 18px;">Rp. @currency($totalPendapatanLainlain)</td>
                                    </tr>
                                </table>
                            </div>
                        @endif

                        <!-- Table for Beban Marketing -->
                        @if ($bebanMarketing->count() > 0)
                            <div class="table-responsive my-10 mx-8" style="margin-bottom: 35px;">
                                <h4><strong>Beban Marketing</strong></h4>
                                <table class="table table-striped gy-7 gs-7">
                                    <thead>
                                        <tr class="fw-bold fs-6 text-gray-800 border-bottom-2 border-gray-200">
                                            <th class="min-w-50px">No</th>
                                            <th class="min-w-50px">No Akun</th>
                                            <th class="min-w-100px">Nama Akun</th>
                                            <th class="min-w-100px">Jumlah</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $no = 1; $totalBebanMarketing = 0; @endphp
                                        @foreach ($bebanMarketing as $item)
                                            <tr>
                                                <td>{{ $no }}</td>
                                                <td>{{ $item->no_akun }}</td>
                                                <td>{{ $item->nama_akun }}</td>
                                                <td class="text-left">@if($bebanMarketingAmounts[$item->no_akun] != 0) Rp. @currency($bebanMarketingAmounts[$item->no_akun]) @else - @endif</td>
                                            </tr>
                                        @php
                                            $no++;
                                            $totalBebanMarketing += $bebanMarketingAmounts[$item->no_akun];
                                        @endphp
                                        @endforeach
                                    </tbody>
                                    <!-- Display total row after the loop -->
                                    <tr>
                                        <td colspan="3">
                                            <span style="margin-left: 80px; font-weight: bold; font-size: 18px;">Total Beban Marketing</span>
                                        </td>
                                        <td style="font-weight: bold; font-size: 18px;">Rp. @currency($totalBebanMarketing)</td>
                                    </tr>
                                </table>
                            </div>
                        @endif

                        <!-- Table for Beban Kegiatan -->
                        @if ($bebanKegiatan->count() > 0)
                            <div class="table-responsive my-10 mx-8" style="margin-bottom: 35px;">
                                <h4><strong>Beban Kegiatan</strong></h4>
                                <table class="table table-striped gy-7 gs-7">
                                    <thead>
                                        <tr class="fw-bold fs-6 text-gray-800 border-bottom-2 border-gray-200">
                                            <th class="min-w-50px">No</th>
                                            <th class="min-w-50px">No Akun</th>
                                            <th class="min-w-100px">Nama Akun</th>
                                            <th class="min-w-100px">Jumlah</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $no = 1; $totalBebanKegiatan = 0; @endphp
                                        @foreach ($bebanKegiatan as $item)
                                            <tr>
                                                <td>{{ $no }}</td>
                                                <td>{{ $item->no_akun }}</td>
                                                <td>{{ $item->nama_akun }}</td>
                                                <td class="text-left">@if($bebanKegiatanAmounts[$item->no_akun] != 0) Rp. @currency($bebanKegiatanAmounts[$item->no_akun]) @else - @endif</td>
                                            </tr>
                                        @php
                                            $no++;
                                            $totalBebanKegiatan += $bebanKegiatanAmounts[$item->no_akun];
                                        @endphp
                                        @endforeach
                                    </tbody>
                                    <!-- Display total row after the loop -->
                                    <tr>
                                        <td colspan="3">
                                            <span style="margin-left: 80px; font-weight: bold; font-size: 18px;">Total Beban Kegiatan</span>
                                        </td>
                                        <td style="font-weight: bold; font-size: 18px;">Rp. @currency($totalBebanKegiatan)</td>
                                    </tr>
                                </table>
                            </div>
                        @endif

                        <!-- Table for Beban Gaji -->
                        @if ($bebanGaji->count() > 0)
                            <div class="table-responsive my-10 mx-8" style="margin-bottom: 35px;">
                            <h4><strong>Beban Gaji</strong></h4>
                                <table class="table table-striped gy-7 gs-7">
                                    <thead>
                                        <tr class="fw-bold fs-6 text-gray-800 border-bottom-2 border-gray-200">
                                            <th class="min-w-50px">No</th>
                                            <th class="min-w-50px">No Akun</th>
                                            <th class="min-w-100px">Nama Akun</th>
                                            <th class="min-w-100px">Jumlah</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $no = 1; $totalBebanGaji = 0; @endphp
                                        @foreach ($bebanGaji as $item)
                                            <tr>
                                                <td>{{ $no }}</td>
                                                <td>{{ $item->no_akun }}</td>
                                                <td>{{ $item->nama_akun }}</td>
                                                <td class="text-left">@if($bebanGajiAmounts[$item->no_akun] != 0) Rp. @currency($bebanGajiAmounts[$item->no_akun]) @else - @endif</td>
                                            </tr>
                                        @php
                                            $no++;
                                            $totalBebanGaji += $bebanGajiAmounts[$item->no_akun];
                                        @endphp
                                        @endforeach
                                    </tbody>
                                    <!-- Display total row after the loop -->
                                    <tr>
                                        <td colspan="3">
                                            <span style="margin-left: 80px; font-weight: bold; font-size: 18px;">Total Beban Gaji</span>
                                        </td>
                                        <td style="font-weight: bold; font-size: 18px;">Rp. @currency($totalBebanGaji)</td>
                                    </tr>
                                </table>
                            </div>
                        @endif

                        <!-- Table for Beban Operasional Kantor -->
                        @if ($bebanOperasionalKantor->count() > 0)
                            <div class="table-responsive my-10 mx-8" style="margin-bottom: 35px;">
                            <h4><strong>Beban Operasional Kantor</strong></h4>
                                <table class="table table-striped gy-7 gs-7">
                                    <thead>
                                        <tr class="fw-bold fs-6 text-gray-800 border-bottom-2 border-gray-200">
                                            <th class="min-w-50px">No</th>
                                            <th class="min-w-50px">No Akun</th>
                                            <th class="min-w-100px">Nama Akun</th>
                                            <th class="min-w-100px">Jumlah</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $no = 1; $totalBebanOperasionalKantor = 0; @endphp
                                        @foreach ($bebanOperasionalKantor as $item)
                                            <tr>
                                                <td>{{ $no }}</td>
                                                <td>{{ $item->no_akun }}</td>
                                                <td>{{ $item->nama_akun }}</td>
                                                <td class="text-left">@if($bebanOperasionalKantorAmounts[$item->no_akun] != 0) Rp. @currency($bebanOperasionalKantorAmounts[$item->no_akun]) @else - @endif</td>
                                            </tr>
                                        @php
                                            $no++;
                                            $totalBebanOperasionalKantor += $bebanOperasionalKantorAmounts[$item->no_akun];
                                        @endphp
                                        @endforeach
                                    </tbody>
                                    <!-- Display total row after the loop -->
                                        <tr>
                                            <td colspan="3">
                                                <span style="margin-left: 80px; font-weight: bold; font-size: 18px;">Total Beban Operasional Kantor</span>
                                            </td>
                                            <td style="font-weight: bold; font-size: 18px;">Rp. @currency($totalBebanOperasionalKantor)</td>
                                        </tr>
                                    </table>
                                </div>
                            @endif

                            <!-- Table for Beban Rumah Tangga Kantor -->
                            @if ($bebanRumahTanggaKantor->count() > 0)
                                <div class="table-responsive my-10 mx-8" style="margin-bottom: 35px;">
                                <h4><strong>Beban Rumah Tangga Kantor</strong></h4>
                                    <table class="table table-striped gy-7 gs-7">
                                        <thead>
                                            <tr class="fw-bold fs-6 text-gray-800 border-bottom-2 border-gray-200">
                                                <th class="min-w-50px">No</th>
                                                <th class="min-w-50px">No Akun</th>
                                                <th class="min-w-100px">Nama Akun</th>
                                                <th class="min-w-100px">Jumlah</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $no = 1; $totalBebanRumahTanggaKantor = 0; @endphp
                                            @foreach ($bebanRumahTanggaKantor as $item)
                                                <tr>
                                                    <td>{{ $no }}</td>
                                                    <td>{{ $item->no_akun }}</td>
                                                    <td>{{ $item->nama_akun }}</td>
                                                    <td class="text-left">@if($bebanRumahTanggaKantorAmounts[$item->no_akun] != 0) Rp. @currency($bebanRumahTanggaKantorAmounts[$item->no_akun]) @else - @endif</td>
                                                </tr>
                                            @php
                                                $no++;
                                                $totalBebanRumahTanggaKantor += $bebanRumahTanggaKantorAmounts[$item->no_akun];
                                            @endphp
                                            @endforeach
                                        </tbody>
                                        <!-- Display total row after the loop -->
                                        <tr>
                                            <td colspan="3">
                                                <span style="margin-left: 80px; font-weight: bold; font-size: 18px;">Total Beban Rumah Tangga Kantor</span>
                                            </td>
                                            <td style="font-weight: bold; font-size: 18px;">Rp. @currency($totalBebanRumahTanggaKantor)</td>
                                        </tr>
                                    </table>
                                </div>
                            @endif

                            <!-- Table for Beban Sewa -->
                            @if ($bebanSewa->count() > 0)
                                <div class="table-responsive my-10 mx-8" style="margin-bottom: 35px;">
                                    <h4><strong>Beban Sewa</strong></h4>
                                    <table class="table table-striped gy-7 gs-7">
                                        <thead>
                                            <tr class="fw-bold fs-6 text-gray-800 border-bottom-2 border-gray-200">
                                                <th class="min-w-50px">No</th>
                                                <th class="min-w-50px">No Akun</th>
                                                <th class="min-w-100px">Nama Akun</th>
                                                <th class="min-w-100px">Jumlah</th>
                                            </tr>
                                        </thead>
                                    <tbody>
                                        @php $no = 1; $totalBebanSewa = 0; @endphp
                                        @foreach ($bebanSewa as $item)
                                            <tr>
                                                <td>{{ $no }}</td>
                                                <td>{{ $item->no_akun }}</td>
                                                <td>{{ $item->nama_akun }}</td>
                                                <td class="text-left">@if($bebanSewaAmounts[$item->no_akun] != 0) Rp. @currency($bebanSewaAmounts[$item->no_akun]) @else - @endif</td>
                                            </tr>
                                        @php
                                            $no++;
                                            $totalBebanSewa += $bebanSewaAmounts[$item->no_akun];
                                        @endphp
                                        @endforeach
                                    </tbody>
                                    <!-- Display total row after the loop -->
                                        <tr>
                                            <td colspan="3">
                                                <span style="margin-left: 80px; font-weight: bold; font-size: 18px;">Total Beban Sewa</span>
                                            </td>
                                            <td style="font-weight: bold; font-size: 18px;">Rp. @currency($totalBebanSewa)</td>
                                        </tr>
                                    </table>
                                </div>
                            @endif

                            <!-- Table for Beban Perawatan -->
                            @if ($bebanPerawatan->count() > 0)
                                <div class="table-responsive my-10 mx-8" style="margin-bottom: 35px;">
                                    <h4><strong>Beban Perawatan</strong></h4>
                                    <table class="table table-striped gy-7 gs-7">
                                        <thead>
                                            <tr class="fw-bold fs-6 text-gray-800 border-bottom-2 border-gray-200">
                                                <th class="min-w-50px">No</th>
                                                <th class="min-w-50px">No Akun</th>
                                                <th class="min-w-100px">Nama Akun</th>
                                                <th class="min-w-100px">Jumlah</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $no = 1; $totalBebanPerawatan = 0; @endphp
                                            @foreach ($bebanPerawatan as $item)
                                                <tr>
                                                    <td>{{ $no }}</td>
                                                    <td>{{ $item->no_akun }}</td>
                                                    <td>{{ $item->nama_akun }}</td>
                                                    <td class="text-left">@if($bebanPerawatanAmounts[$item->no_akun] != 0) Rp. @currency($bebanPerawatanAmounts[$item->no_akun]) @else - @endif</td>
                                                </tr>
                                            @php
                                                $no++;
                                                $totalBebanPerawatan += $bebanPerawatanAmounts[$item->no_akun];
                                            @endphp
                                            @endforeach
                                        </tbody>
                                        <!-- Display total row after the loop -->
                                        <tr>
                                            <td colspan="3">
                                                <span style="margin-left: 80px; font-weight: bold;font-size: 18px; ">Total Beban Perawatan</span>
                                            </td>
                                            <td style="font-weight: bold; font-size: 18px;">Rp. @currency($totalBebanPerawatan)</td>
                                        </tr>
                                    </table>
                                </div>
                            @endif

                            <!-- Table for Beban Yayasan -->
                            @if ($bebanYayasan->count() > 0)
                                <div class="table-responsive my-10 mx-8" style="margin-bottom: 35px;">
                                    <h4><strong>Beban Yayasan</strong></h4>
                                        <table class="table table-striped gy-7 gs-7">
                                            <thead>
                                                <tr class="fw-bold fs-6 text-gray-800 border-bottom-2 border-gray-200">
                                                    <th class="min-w-50px">No</th>
                                                    <th class="min-w-50px">No Akun</th>
                                                    <th class="min-w-100px">Nama Akun</th>
                                                    <th class="min-w-100px">Jumlah</th>
                                                </tr>
                                            </thead>
                                        <tbody>
                                            @php $no = 1; $totalBebanYayasan = 0; @endphp
                                            @foreach ($bebanYayasan as $item)
                                                <tr>
                                                    <td>{{ $no }}</td>
                                                    <td>{{ $item->no_akun }}</td>
                                                    <td>{{ $item->nama_akun }}</td>
                                                    <td class="text-left">@if($bebanYayasanAmounts[$item->no_akun] != 0) Rp. @currency($bebanYayasanAmounts[$item->no_akun]) @else - @endif</td>
                                                </tr>
                                            @php
                                                $no++;
                                                $totalBebanYayasan += $bebanYayasanAmounts[$item->no_akun];
                                            @endphp
                                            @endforeach
                                        </tbody>
                                        <!-- Display total row after the loop -->
                                        <tr>
                                            <td colspan="3">
                                                <span style="margin-left: 80px; font-weight: bold;">Total Beban Yayasan</span>
                                            </td>
                                            <td style="font-weight: bold;">Rp. @currency($totalBebanYayasan)</td>
                                        </tr>
                                        <!-- Calculate and store TOTAL SEMUA BEBAN in a variable -->
                                        @php
                                            $allBeban = $totalBebanMarketing + $totalBebanKegiatan + $totalBebanGaji + $totalBebanOperasionalKantor + $totalBebanRumahTanggaKantor + $totalBebanSewa + $totalBebanPerawatan + $totalBebanYayasan;
                                            
                                            $labaRugiSebelumBunga = $labaKotor + $totalPendapatanLainlain - $allBeban;
                                        @endphp
                                        <tr>
                                            <td colspan="3">
                                                <span style="margin-left: 80px; font-weight: bold;">Total Semua Beban</span>
                                            </td>
                                            <td style="font-weight: bold;">Rp. @currency( $allBeban )</td>
                                        </tr>
                                        <tr>
                                            <td colspan="3">
                                                <span style="margin-left: 80px; font-weight: bold; font-size: 18px;">Laba (Rugi) Sebelum Bunga Pinjaman, Pajak, dan Depresiasi</span>
                                            </td>
                                            <td style="font-weight: bold; font-size: 18px;">Rp. @currency( $labaRugiSebelumBunga )</td>
                                        </tr>
                                    </table>
                                </div>
                            @endif

                            <!-- Table for Beban Lain lain -->
                            @if ($bebanLainlain->count() > 0)
                                <div class="table-responsive my-10 mx-8" style="margin-bottom: 35px;">
                                    <h4><strong>Beban Lain Lain</strong></h4>
                                    <table class="table table-striped gy-7 gs-7">
                                        <thead>
                                            <tr class="fw-bold fs-6 text-gray-800 border-bottom-2 border-gray-200">
                                                <th class="min-w-50px">No</th>
                                                <th class="min-w-50px">No Akun</th>
                                                <th class="min-w-100px">Nama Akun</th>
                                                <th class="min-w-100px">Jumlah</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $no = 1; $totalBebanLainlain = 0; @endphp
                                            @foreach ($bebanLainlain as $item)
                                                <tr>
                                                    <td>{{ $no }}</td>
                                                    <td>{{ $item->no_akun }}</td>
                                                    <td>{{ $item->nama_akun }}</td>
                                                    <td class="text-left">@if($bebanLainlainAmounts[$item->no_akun] != 0) Rp. @currency($bebanLainlainAmounts[$item->no_akun]) @else - @endif</td>
                                                </tr>
                                            @php
                                                $no++;
                                                $totalBebanLainlain += $bebanLainlainAmounts[$item->no_akun];
                                            @endphp
                                            @endforeach
                                        </tbody>
                                        <!-- Display total row after the loop -->
                                        <tr>
                                            <td colspan="3">
                                                <span style="margin-left: 80px; font-weight: bold; font-size: 18px;">Total Beban Lain Lain</span>
                                            </td>
                                            <td style="font-weight: bold; font-size: 18px;">Rp. @currency( $totalBebanLainlain )</td>
                                        </tr>
                                        <!-- Calculate and store Laba Rugi Sebelum pajak dan depresiasi in a variable -->
                                        @php
                                            $labaRugiSebelumPajak = $labaRugiSebelumBunga - $totalBebanLainlain;
                                        @endphp
                                        <tr>
                                            <td colspan="3">
                                                <span style="margin-left: 80px; font-weight: bold; font-size: 18px;">Laba (Rugi) Sebelum Pajak dan Depresiasi</span>
                                            </td>
                                            <td style="font-weight: bold; font-size: 18px;">Rp. @currency( $labaRugiSebelumPajak )</td>
                                        </tr>
                                    </table>
                                </div>
                            @endif

                            <!-- Table for Pajak -->
                            @if ($pajak->count() > 0)
                                <div class="table-responsive my-10 mx-8" style="margin-bottom: 35px;">
                                <h4><strong>Pajak</strong></h4>
                                    <table class="table table-striped gy-7 gs-7">
                                        <thead>
                                            <tr class="fw-bold fs-6 text-gray-800 border-bottom-2 border-gray-200">
                                                <th class="min-w-50px">No</th>
                                                <th class="min-w-50px">No Akun</th>
                                                <th class="min-w-100px">Nama Akun</th>
                                                <th class="min-w-100px">Jumlah</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $no = 1; $totalPajak = 0; @endphp
                                            @foreach ($pajak as $item)
                                                <tr>
                                                    <td>{{ $no }}</td>
                                                    <td>{{ $item->no_akun }}</td>
                                                    <td>{{ $item->nama_akun }}</td>
                                                    <td class="text-left">@if($pajakAmounts[$item->no_akun] != 0) Rp. @currency($pajakAmounts[$item->no_akun]) @else - @endif</td>
                                                </tr>
                                            @php
                                                $no++;
                                                $totalPajak += $pajakAmounts[$item->no_akun];
                                            @endphp
                                            @endforeach
                                        </tbody>
                                         <!-- Display total row after the loop -->
                                            <tr>
                                                <td colspan="3">
                                                    <span style="margin-left: 80px; font-weight: bold; font-size: 18px;">Total Pajak</span>
                                                </td>
                                                <td style="font-weight: bold; font-size: 18px;">Rp. @currency( $totalPajak )</td>
                                            </tr>
                                            <!-- Calculate and store Laba rugi sebelum depresiasi in a variable -->
                                            @php
                                                $labaRugiSebelumDepresiasi = $labaRugiSebelumPajak - $totalPajak;
                                            @endphp
                                            <tr>
                                                <td colspan="3">
                                                    <span style="margin-left: 80px; font-weight: bold; font-size: 18px;">Laba (Rugi) Sebelum Depresiasi</span>
                                                </td>
                                                <td style="font-weight: bold; font-size: 18px;">Rp. @currency($labaRugiSebelumDepresiasi)</td>
                                            </tr>
                                        </table>
                                    </div>
                                @endif

                                <!-- Table for Depresiasi -->
                                @if ($depresiasi->count() > 0)
                                    <div class="table-responsive my-10 mx-8" style="margin-bottom: 35px;">
                                        <h4><strong>Depresiasi</strong></h4>
                                        <table class="table table-striped gy-7 gs-7">
                                            <thead>
                                                <tr class="fw-bold fs-6 text-gray-800 border-bottom-2 border-gray-200">
                                                    <th class="min-w-50px">No</th>
                                                    <th class="min-w-50px">No Akun</th>
                                                    <th class="min-w-100px">Nama Akun</th>
                                                    <th class="min-w-100px">Jumlah</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php $no = 1; $totalDepresiasi = 0; @endphp
                                                @foreach ($depresiasi as $item)
                                                    <tr>
                                                        <td>{{ $no }}</td>
                                                        <td>{{ $item->no_akun }}</td>
                                                        <td>{{ $item->nama_akun }}</td>
                                                        <td class="text-left">@if($depresiasiAmounts[$item->no_akun] != 0) Rp. @currency($depresiasiAmounts[$item->no_akun]) @else - @endif</td>
                                                    </tr>
                                                @php
                                                    $no++;
                                                    $totalDepresiasi += $depresiasiAmounts[$item->no_akun];
                                                @endphp
                                                @endforeach
                                            </tbody>
                                            <!-- Display total row after the loop -->
                                            <tr>
                                                <td colspan="3">
                                                    <span style="margin-left: 80px; font-weight: bold; font-size: 18px;">Total Depresiasi</span>
                                                </td>
                                                <td style="font-weight: bold; font-size: 18px;">Rp. @currency( $totalDepresiasi )</td>
                                            </tr>
                                            <!-- Calculate and store Kenaikan (Penurunan) Aset Netto in a variable -->
                                            @php
                                                $kenaikanPenurunanAsetNettoTidakTerikat = $labaRugiSebelumDepresiasi - $totalDepresiasi;
                                            @endphp
                                            <tr>
                                                <td colspan="3">
                                                    <span style="margin-left: 80px; font-weight: bold; font-size: 18px;">Kenaikan (Penurunan) Aset Netto Tidak Terikat</span>
                                                </td>
                                                <td style="font-weight: bold; font-size: 18px;">Rp. @currency( $kenaikanPenurunanAsetNettoTidakTerikat )</td>
                                            </tr>
                                            <tr>
                                                <td colspan="3">
                                                    <span style="margin-left: 80px; font-weight: bold; font-size: 18px;">Perubahan Aset Netto Terikat Temporer</span>
                                                </td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td colspan="3">
                                                    <span style="margin-left: 120px; font-weight: bold; font-size: 18px;">Kenaikan (Penurunan) Aset Netto Terikat Temporer</span>
                                                </td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td colspan="3">
                                                    <span style="margin-left: 80px; font-weight: bold; font-size: 18px;">Perubahan Aset Netto Terikat Permanen</span>
                                                </td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td colspan="3">
                                                    <span style="margin-left: 120px; font-weight: bold; font-size: 18px;">Kenaikan (Penurunan) Aset Netto Terikat Permanen</span>
                                                </td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td colspan="3">
                                                    <span style="margin-left: 80px; font-weight: bold; font-size: 18px;">Kenaikan (Penurunan) Aset Netto</span>
                                                </td>
                                                <td style="font-weight: bold; font-size: 18px;">Rp. @currency( $kenaikanPenurunanAsetNettoTidakTerikat )</td>
                                            </tr>
                                            <tr>
                                                <td colspan="3">
                                                    <span style="margin-left: 120px; font-weight: bold; font-size: 18px;">Aset Netto Awal</span>
                                                </td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td colspan="3">
                                                    <span style="margin-left: 120px; font-weight: bold; font-size: 18px;">Aset Netto Akhir</span>
                                                </td>
                                                <td></td>
                                            </tr>
                                        </table>
                                    </div>
                                @endif
                        <!-- /.row -->

                        <!-- /.row -->
                        <div class="row">
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
