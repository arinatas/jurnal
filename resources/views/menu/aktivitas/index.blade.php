@extends('layouts.main')

@section('content')
	<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
						<!--begin::Toolbar-->
                        @include('partials.toolbar')
                        <!--end::Toolbar-->
						<!--begin::Post-->
						<div class="post d-flex flex-column-fluid" id="kt_post">
							<!--begin::Container-->
							<div id="kt_content_container" class="container-xxl">
                                <!--begin::Card-->
                                <div class="card">
                                    <!--begin::Card body-->
                                    <div class="card-body pb-5">
                                        <!--begin::Heading-->
                                        <div class="card-px pt-10 d-flex justify-content-between">
                                            <!--begin::Title-->
                                                <div class="d-inline mt-2">
                                                    <h2 class="fs-2x fw-bolder mb-0">{{ $title }}</h2>
                                                </div>
                                            <!--end::Title-->
                                        </div>
                                        <!--end::Heading-->
                                        <!--begin::Table-->
                                        <!-- Table for Pendapatan -->
                                        @if ($pendapatan->count() > 0)
                                            <div class="table-responsive my-10 mx-8">
                                                <h6 class="fs-2x fw-bolder mb-4">Pendapatan</h6>
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
                                                                <td>{{ $pendapatanAmounts[$item->no_akun] }}</td>
                                                            </tr>
                                                            @php
                                                                $no++;
                                                                $totalPendapatan += $pendapatanAmounts[$item->no_akun];
                                                            @endphp
                                                        @endforeach
                                                    </tbody>
                                                    <!-- Display total row after the loop -->
                                                    <tfoot>
                                                        <tr class="fw-bold fs-6 text-gray-800">
                                                            <td colspan="3">Total Pendapatan</td>
                                                            <td>{{ $totalPendapatan }}</td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        @endif

                                        <!-- Table for Beban Sehubungan Program -->
                                        @if ($bebanSehubunganProgram->count() > 0)
                                            <div class="table-responsive my-10 mx-8">
                                                <h6 class="fs-2x fw-bolder mb-4">Beban Sehubungan Program</h6>
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
                                                                <!-- Add the corresponding total amount -->
                                                                <td>{{ $bebanSehubunganProgramAmounts[$item->no_akun] }}</td>
                                                            </tr>
                                                            @php
                                                                $no++;
                                                                // Accumulate the total for Beban Sehubungan Program
                                                                $totalBebanSehubunganProgram += $bebanSehubunganProgramAmounts[$item->no_akun];
                                                            @endphp
                                                        @endforeach
                                                    </tbody>
                                                    <!-- Display total row after the loop -->
                                                    <tfoot>
                                                        <tr class="fw-bold fs-6 text-gray-800">
                                                            <td colspan="3">Total Beban Sehubungan Program</td>
                                                            <td>{{ $totalBebanSehubunganProgram }}</td>
                                                        </tr>
                                                        <tr class="fw-bold fs-6 text-gray-800">
                                                            <td colspan="3">Laba Kotor</td>
                                                            <td>{{ $totalPendapatan - $totalBebanSehubunganProgram }}</td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        @endif

                                        <!-- Table for Pendapatan Lain Lain-->
                                        @if ($pendapatanLainlain->count() > 0)
                                            <div class="table-responsive my-10 mx-8">
                                                <h6 class="fs-2x fw-bolder mb-4">Pendapatan Lain Lain</h6>
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
                                                                <td>{{ $pendapatanLainlainAmounts[$item->no_akun] }}</td>
                                                            </tr>
                                                            @php
                                                                $no++;
                                                                $totalPendapatanLainlain += $pendapatanLainlainAmounts[$item->no_akun];
                                                            @endphp
                                                        @endforeach
                                                    </tbody>
                                                    <!-- Display total row after the loop -->
                                                    <tfoot>
                                                        <tr class="fw-bold fs-6 text-gray-800">
                                                            <td colspan="3">Total Pendapatan Lain Lain</td>
                                                            <td>{{ $totalPendapatanLainlain }}</td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        @endif

                                        <!-- Table for Beban Marketing -->
                                        @if ($bebanMarketing->count() > 0)
                                            <div class="table-responsive my-10 mx-8">
                                                <h6 class="fs-2x fw-bolder mb-4">Beban Marketing</h6>
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
                                                                <td>{{ $bebanMarketingAmounts[$item->no_akun] }}</td>
                                                            </tr>
                                                            @php
                                                                $no++;
                                                                $totalBebanMarketing += $bebanMarketingAmounts[$item->no_akun];
                                                            @endphp
                                                        @endforeach
                                                    </tbody>
                                                    <!-- Display total row after the loop -->
                                                    <tfoot>
                                                        <tr class="fw-bold fs-6 text-gray-800">
                                                            <td colspan="3">Total Beban Marketing</td>
                                                            <td>{{ $totalBebanMarketing }}</td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        @endif

                                        <!-- Table for Beban Kegiatan -->
                                        @if ($bebanKegiatan->count() > 0)
                                            <div class="table-responsive my-10 mx-8">
                                                <h6 class="fs-2x fw-bolder mb-4">Beban Kegiatan</h6>
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
                                                                <td>{{ $bebanKegiatanAmounts[$item->no_akun] }}</td>
                                                            </tr>
                                                            @php
                                                                $no++;
                                                                $totalBebanKegiatan += $bebanKegiatanAmounts[$item->no_akun];
                                                            @endphp
                                                        @endforeach
                                                    </tbody>
                                                    <!-- Display total row after the loop -->
                                                    <tfoot>
                                                        <tr class="fw-bold fs-6 text-gray-800">
                                                            <td colspan="3">Total Beban Kegiatan</td>
                                                            <td>{{ $totalBebanKegiatan }}</td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        @endif

                                        <!-- Table for Beban Gaji -->
                                        @if ($bebanGaji->count() > 0)
                                            <div class="table-responsive my-10 mx-8">
                                                <h6 class="fs-2x fw-bolder mb-4">Beban Gaji</h6>
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
                                                                <td>{{ $bebanGajiAmounts[$item->no_akun] }}</td>
                                                            </tr>
                                                            @php
                                                                $no++;
                                                                $totalBebanGaji += $bebanGajiAmounts[$item->no_akun];
                                                            @endphp
                                                        @endforeach
                                                    </tbody>
                                                    <!-- Display total row after the loop -->
                                                    <tfoot>
                                                        <tr class="fw-bold fs-6 text-gray-800">
                                                            <td colspan="3">Total Beban Gaji</td>
                                                            <td>{{ $totalBebanGaji }}</td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        @endif

                                        <!-- Table for Beban Operasional Kantor -->
                                        @if ($bebanOperasionalKantor->count() > 0)
                                            <div class="table-responsive my-10 mx-8">
                                                <h6 class="fs-2x fw-bolder mb-4">Beban Operasional Kantor</h6>
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
                                                                <td>{{ $bebanOperasionalKantorAmounts[$item->no_akun] }}</td>
                                                            </tr>
                                                            @php
                                                                $no++;
                                                                $totalBebanOperasionalKantor += $bebanOperasionalKantorAmounts[$item->no_akun];
                                                            @endphp
                                                        @endforeach
                                                    </tbody>
                                                    <!-- Display total row after the loop -->
                                                    <tfoot>
                                                        <tr class="fw-bold fs-6 text-gray-800">
                                                            <td colspan="3">Total Beban Operasional Kantor</td>
                                                            <td>{{ $totalBebanOperasionalKantor }}</td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        @endif

                                        <!-- Table for Beban Rumah Tangga Kantor -->
                                        @if ($bebanRumahTanggaKantor->count() > 0)
                                            <div class="table-responsive my-10 mx-8">
                                                <h6 class="fs-2x fw-bolder mb-4">Beban Rumah Tangga Kantor</h6>
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
                                                                <td>{{ $bebanRumahTanggaKantorAmounts[$item->no_akun] }}</td>
                                                            </tr>
                                                            @php
                                                                $no++;
                                                                $totalBebanRumahTanggaKantor += $bebanRumahTanggaKantorAmounts[$item->no_akun];
                                                            @endphp
                                                        @endforeach
                                                    </tbody>
                                                    <!-- Display total row after the loop -->
                                                    <tfoot>
                                                        <tr class="fw-bold fs-6 text-gray-800">
                                                            <td colspan="3">Total Beban Rumah Tangga Kantor</td>
                                                            <td>{{ $totalBebanRumahTanggaKantor }}</td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        @endif

                                        <!-- Table for Beban Sewa -->
                                        @if ($bebanSewa->count() > 0)
                                            <div class="table-responsive my-10 mx-8">
                                                <h6 class="fs-2x fw-bolder mb-4">Beban Sewa</h6>
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
                                                                <td>{{ $bebanSewaAmounts[$item->no_akun] }}</td>
                                                            </tr>
                                                            @php
                                                                $no++;
                                                                $totalBebanSewa += $bebanSewaAmounts[$item->no_akun];
                                                            @endphp
                                                        @endforeach
                                                    </tbody>
                                                    <!-- Display total row after the loop -->
                                                    <tfoot>
                                                        <tr class="fw-bold fs-6 text-gray-800">
                                                            <td colspan="3">Total Beban Sewa</td>
                                                            <td>{{ $totalBebanSewa }}</td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        @endif

                                        <!-- Table for Beban Perawatan -->
                                        @if ($bebanPerawatan->count() > 0)
                                            <div class="table-responsive my-10 mx-8">
                                                <h6 class="fs-2x fw-bolder mb-4">Beban Perawatan</h6>
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
                                                                <td>{{ $bebanPerawatanAmounts[$item->no_akun] }}</td>
                                                            </tr>
                                                            @php
                                                                $no++;
                                                                $totalBebanPerawatan += $bebanPerawatanAmounts[$item->no_akun];
                                                            @endphp
                                                        @endforeach
                                                    </tbody>
                                                    <!-- Display total row after the loop -->
                                                    <tfoot>
                                                        <tr class="fw-bold fs-6 text-gray-800">
                                                            <td colspan="3">Total Beban Perawatan</td>
                                                            <td>{{ $totalBebanPerawatan }}</td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        @endif

                                        <!-- Table for Beban Yayasan -->
                                        @if ($bebanYayasan->count() > 0)
                                            <div class="table-responsive my-10 mx-8">
                                                <h6 class="fs-2x fw-bolder mb-4">Beban Yayasan</h6>
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
                                                                <td>{{ $bebanYayasanAmounts[$item->no_akun] }}</td>
                                                            </tr>
                                                            @php
                                                                $no++;
                                                                $totalBebanYayasan += $bebanYayasanAmounts[$item->no_akun];
                                                            @endphp
                                                        @endforeach
                                                    </tbody>
                                                    <!-- Display total row after the loop -->
                                                    <tfoot>
                                                        <tr class="fw-bold fs-6 text-gray-800">
                                                            <td colspan="3">Total Beban Yayasan</td>
                                                            <td>{{ $totalBebanYayasan }}</td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        @endif

                                        <!-- Table for Beban Lain lain -->
                                        @if ($bebanLainlain->count() > 0)
                                            <div class="table-responsive my-10 mx-8">
                                                <h6 class="fs-2x fw-bolder mb-4">Beban Lain Lain</h6>
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
                                                                <td>{{ $bebanLainlainAmounts[$item->no_akun] }}</td>
                                                            </tr>
                                                            @php
                                                                $no++;
                                                                $totalBebanLainlain += $bebanLainlainAmounts[$item->no_akun];
                                                            @endphp
                                                        @endforeach
                                                    </tbody>
                                                    <!-- Display total row after the loop -->
                                                    <tfoot>
                                                        <tr class="fw-bold fs-6 text-gray-800">
                                                            <td colspan="3">Total Beban Lain Lain</td>
                                                            <td>{{ $totalBebanLainlain }}</td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        @endif

                                        <!-- Table for Pajak -->
                                        @if ($pajak->count() > 0)
                                            <div class="table-responsive my-10 mx-8">
                                                <h6 class="fs-2x fw-bolder mb-4">Pajak</h6>
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
                                                                <td>{{ $pajakAmounts[$item->no_akun] }}</td>
                                                            </tr>
                                                            @php
                                                                $no++;
                                                                $totalPajak += $pajakAmounts[$item->no_akun];
                                                            @endphp
                                                        @endforeach
                                                    </tbody>
                                                    <!-- Display total row after the loop -->
                                                    <tfoot>
                                                        <tr class="fw-bold fs-6 text-gray-800">
                                                            <td colspan="3">Total Pajak</td>
                                                            <td>{{ $totalPajak }}</td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        @endif

                                        <!-- Table for Depresiasi -->
                                        @if ($depresiasi->count() > 0)
                                            <div class="table-responsive my-10 mx-8">
                                                <h6 class="fs-2x fw-bolder mb-4">Depresiasi</h6>
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
                                                                <td>{{ $depresiasiAmounts[$item->no_akun] }}</td>
                                                            </tr>
                                                            @php
                                                                $no++;
                                                                $totalDepresiasi += $depresiasiAmounts[$item->no_akun];
                                                            @endphp
                                                        @endforeach
                                                    </tbody>
                                                    <!-- Display total row after the loop -->
                                                    <tfoot>
                                                        <tr class="fw-bold fs-6 text-gray-800">
                                                            <td colspan="3">Total Depresiasi</td>
                                                            <td>{{ $totalDepresiasi }}</td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        @endif

                                        @if (!$pendapatan)
                                        <div class="my-10 mx-15">
                                            <!--begin::Notice-->
                                            <div class="notice d-flex bg-light-warning rounded border-warning border border-dashed p-6">
                                                <!--begin::Icon-->
                                                <!--begin::Svg Icon | path: icons/duotune/general/gen048.svg-->
                                                <span class="svg-icon svg-icon-2tx svg-icon-warning me-4">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                        <path opacity="0.3" d="M20.5543 4.37824L12.1798 2.02473C12.0626 1.99176 11.9376 1.99176 11.8203 2.02473L3.44572 4.37824C3.18118 4.45258 3 4.6807 3 4.93945V13.569C3 14.6914 3.48509 15.8404 4.4417 16.984C5.17231 17.8575 6.18314 18.7345 7.446 19.5909C9.56752 21.0295 11.6566 21.912 11.7445 21.9488C11.8258 21.9829 11.9129 22 12.0001 22C12.0872 22 12.1744 21.983 12.2557 21.9488C12.3435 21.912 14.4326 21.0295 16.5541 19.5909C17.8169 18.7345 18.8277 17.8575 19.5584 16.984C20.515 15.8404 21 14.6914 21 13.569V4.93945C21 4.6807 20.8189 4.45258 20.5543 4.37824Z" fill="black" />
                                                        <path d="M10.5606 11.3042L9.57283 10.3018C9.28174 10.0065 8.80522 10.0065 8.51412 10.3018C8.22897 10.5912 8.22897 11.0559 8.51412 11.3452L10.4182 13.2773C10.8099 13.6747 11.451 13.6747 11.8427 13.2773L15.4859 9.58051C15.771 9.29117 15.771 8.82648 15.4859 8.53714C15.1948 8.24176 14.7183 8.24176 14.4272 8.53714L11.7002 11.3042C11.3869 11.6221 10.874 11.6221 10.5606 11.3042Z" fill="black" />
                                                    </svg>
                                                </span>
                                                <!--end::Svg Icon-->
                                                <!--end::Icon-->
                                                <!--begin::Wrapper-->
                                                <div class="d-flex flex-stack flex-grow-1 flex-wrap flex-md-nowrap">
                                                    <!--begin::Content-->
                                                    <div class="mb-3 mb-md-0 fw-bold">
                                                        <h4 class="text-gray-900 fw-bolder">Belum ada data</h4>
                                                        <div class="fs-6 text-gray-700 pe-7">Belum ada data yang diinputkan</div>
                                                    </div>
                                                    <!--end::Content-->
                                                </div>
                                                <!--end::Wrapper-->
                                            </div>
                                            <!--end::Notice-->
                                        </div>
                                        @endif
                                        <!--end::Table-->
                                    </div>
                                    <!--end::Card body-->
                                </div>
                                <!--end::Card-->
							</div>
							<!--end::Container-->
						</div>
						<!--end::Post-->
					</div>
                    <script>
                        function confirmDelete(event) {
                            event.preventDefault(); // Menghentikan tindakan penghapusan asli
                            if (confirm("Apakah Anda yakin ingin menghapus?")) {
                                // Jika pengguna menekan OK dalam konfirmasi, lanjutkan dengan penghapusan
                                event.target.form.submit();
                            }
                        }
                        function submitForm(button) {
                            button.disabled = true;
                                    button.innerHTML = 'Submitting...';

                                    // Mencegah pengiriman berulang
                                    button.form.submit();
                        }
                    </script>
@endsection
