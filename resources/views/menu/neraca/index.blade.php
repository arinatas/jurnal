@extends('layouts.main')

@section('content')
	<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
						<!--begin::Toolbar-->
						@include('partials.toolbar')
						<!--end::Toolbar-->
						<div class="post d-flex flex-column-fluid" id="kt_post">
							<!--begin::Container-->
							<div id="kt_content_container" class="container-xxl">
								<!--begin::Card-->
								<div class="card">
						            <!--begin::Heading-->
										<div class="card-px pt-10 d-flex justify-content-between">
                                            <!--begin::Title-->
                                                <div class="d-inline mt-2">
                                                    <h2 class="fs-2x fw-bolder mb-0">{{ $title }}</h2>
                                                </div>
												@if (request('bulan') && request('tahun'))
													<div class="d-inline">
														<a href="{{ route('exportNeracaFilter', ['selectedYear' => request('tahun'), 'selectedMonth' => request('bulan')]) }}" class="btn btn-sm btn-dark" title="Export Excel">Export Excel</a>
													</div>
												@else
													<div class="d-inline">
														<a href="{{ route('exportNeraca') }}" class="btn btn-sm btn-info" title="Export Excel">Export Excel</a>
													</div>
												@endif
                                            <!--end::Title-->
                                        </div>
                                        <!--end::Heading-->
                                        <!-- Form Filter -->
                                        <div class="card-px mt-10">                                            
                                            <form action="{{ route('neraca') }}" method="GET">
                                                <div class="row mb-3">
                                                    <div class="col-md-2">
                                                        <label for="bulan" class="form-label">Bulan :</label>
                                                        <select class="form-control" id="bulan" name="bulan" data-control="select2" data-hide-search="false">
                                                            <option value="">Pilih Bulan</option>
                                                            @for ($i = 1; $i <= 12; $i++)
                                                                <option value="{{ $i }}" {{ request('bulan') == $i ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $i, 1)) }}</option>
                                                            @endfor
                                                        </select>
                                                    </div>
													<div class="col-md-2">
														<label for="tahun" class="form-label">Tahun :</label>
														<select class="form-control" id="tahun" name="tahun" data-control="select2" data-hide-search="false">
															<option value="">Pilih Tahun</option>
															@foreach($years as $year)
																<option value="{{ $year }}" {{ request('tahun') == $year ? 'selected' : '' }}>{{ $year }}</option>
															@endforeach
														</select>
													</div>
													<div class="col-md-2 mt-4">
														<button type="submit" class="btn btn-primary mt-4">Filter</button>
													</div>
												</div>
                                            </form>
                                        </div>
                                        <!-- End Form Filter -->
									</div>
								</div>
							</div>
							<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
						<div class="post d-flex flex-column-fluid" id="kt_post">
							<!--begin::Container-->
							<div id="kt_content_container" class="container-xxl">
								<div class="card">
									<!--begin::Card body-->
									<div class="card-body pb-5">
                                        <!--begin::Table-->
                                        @if ($selectedMonth && $selectedYear)
										<!--begin::Card body-->
										<div class="card-body pb-0">
											<!--begin::Row-->
											<div class="row g-5 g-xl-8">
												<h1 class="text-center text-uppercase mt-10">YAYASAN PRIMAKARA <br> LAPORAN POSISI KEUANGAN <br> PERIODE {{ \Carbon\Carbon::parse(date("Y-m-d h:i:sa"))->format('F Y') }}</h1>
												<hr>
												<div class="col-xl-6">
													<h3>ASET LANCAR</h3>
													<table class="table table-striped gy-7 gs-7">
														<thead>
															<tr class="fw-bold fs-6 text-gray-800 border-bottom-2 border-gray-200">
																<th class="min-w-100px">Kas & Bank</th>
																<th class="min-w-50px text-center">Total</th>
															</tr>
														</thead>
														<tbody>
															@foreach ($kasDanBank as $item)
																<tr>
																	<td>{{ $item->no_akun }} {{ $item->nama_akun }}</td>
																	<td class="text-center">@currency($item->total_neraca)</td>
																</tr>
															@endforeach
														</tbody>
													</table>
													<table class="table table-striped gy-7 gs-7">
														<thead>
															<tr class="fw-bold fs-6 text-gray-800 border-bottom-2 border-gray-200">
																<th class="min-w-100px">Piutang</th>
																<th class="min-w-50px text-center">Total</th>
															</tr>
														</thead>
														<tbody>
															@foreach ($piutang as $item)
																<tr>
																	<td>{{ $item->no_akun }} {{ $item->nama_akun }}</td>
																	<td class="text-center">@currency($item->total_neraca)</td>
																</tr>
															@endforeach
														</tbody>
													</table>
													<h3 class="text-center mb-10 mt-10">Sub Total @currency($subTotalAsetLancar)</h3>
												</div>
												<div class="col-xl-6">
													<h3>LIABILITAS</h3>
													<table class="table table-striped gy-7 gs-7">
														<thead>
															<tr class="fw-bold fs-6 text-gray-800 border-bottom-2 border-gray-200">
																<th class="min-w-100px">Liabilitas Jangka Pendek</th>
																<th class="min-w-50px text-center">Total</th>
															</tr>
														</thead>
														<tbody>
															@foreach ($lljPendek as $item)
																<tr>
																	<td>{{ $item->no_akun }} {{ $item->nama_akun }}</td>
																	<td class="text-center">@currency($item->total_neraca)</td>
																</tr>
															@endforeach
														</tbody>
													</table>
													<table class="table table-striped gy-7 gs-7">
														<thead>
															<tr class="fw-bold fs-6 text-gray-800 border-bottom-2 border-gray-200">
																<th class="min-w-100px">Liabilitas Jangka Panjang</th>
																<th class="min-w-50px text-center">Total</th>
															</tr>
														</thead>
														<tbody>
															@foreach ($lljPanjang as $item)
																<tr>
																	<td>{{ $item->no_akun }} {{ $item->nama_akun }}</td>
																	<td class="text-center">@currency($item->total_neraca)</td>
																</tr>
															@endforeach
														</tbody>
													</table>
													<h3 class="text-center mb-10 mt-10">Sub Total @currency($subTotalLiabilitas)</h3>
												</div>
												<div class="col-xl-6">
													<h3>ASET TIDAK LANCAR</h3>
													<table class="table table-striped gy-7 gs-7">
														<thead>
															<tr class="fw-bold fs-6 text-gray-800 border-bottom-2 border-gray-200">
																<th class="min-w-100px">Aset Tidak Lancar</th>
																<th class="min-w-50px text-center">Total</th>
															</tr>
														</thead>
														<tbody>
															@foreach ($asetTidakLancar as $item)
																<tr>
																	<td>{{ $item->no_akun }} {{ $item->nama_akun }}</td>
																	<td class="text-center">@currency($item->total_neraca)</td>
																</tr>
															@endforeach
														</tbody>
													</table>
													<h3 class="text-center mt-10">Sub Total @currency($subTotalAsetTidakLancar)</h3>
												</div>
												<div class="col-xl-6">
													<h3>EKUITAS</h3>
													<table class="table table-striped gy-7 gs-7">
														<thead>
															<tr class="fw-bold fs-6 text-gray-800 border-bottom-2 border-gray-200">
																<th class="min-w-100px">Ekuitas</th>
																<th class="min-w-50px text-center">Total</th>
															</tr>
														</thead>
														<tbody>
															@foreach ($ekuitas as $item)
																<tr>
																	<td>{{ $item->no_akun }} {{ $item->nama_akun }}</td>
																	<td class="text-center">@currency($item->total_neraca)</td>
																</tr>
															@endforeach
														</tbody>
													</table>
													<h3 class="text-center mt-10">Sub Total @currency($subTotalEkuitas)</h3>
												</div>
												<div class="col-xl-6">
													<div class="bg-light-primary rounded border-primary border border-dashed p-5 mb-15 mt-5">
														<!--begin::Wrapper-->
														<h2 class="text-gray-900 fw-bolder" style="text-align: center;">Grand Total @currency($grandTotalAsset)</h2>
														<!--end::Wrapper-->
													</div>
												</div>
												<div class="col-xl-6">
													<div class="bg-light-primary rounded border-primary border border-dashed p-5 mb-15 mt-5">
														<!--begin::Wrapper-->
														<h2 class="text-gray-900 fw-bolder" style="text-align: center;">Grand Total @currency($grandTotalLiabilDanEkuitas)</h2>
														<!--end::Wrapper-->
													</div>
												</div>
												<div class="col-xl-12">
													<div class="d-grid gap-2 col-6 mx-auto mb-15">
														<a href="{{ route('printNeracaBlnThn', ['selectedYear' => request('tahun'), 'selectedMonth' => request('bulan')]) }}" target="blank" class="btn btn-lg btn-success fs-6">Cetak</a>
													</div>
												</div>
											</div>
											<!--end::Row-->
										</div>
										<!--end::Card body-->
                                        @else
										<!--begin::Card body-->
										<div class="card-body pb-0">
											<!--begin::Row-->
											<div class="row g-5 g-xl-8">
												<h1 class="text-center text-uppercase mt-10">YAYASAN PRIMAKARA <br> LAPORAN POSISI KEUANGAN <br> PERIODE {{ \Carbon\Carbon::parse(date("Y-m-d h:i:sa"))->format('F Y') }}</h1>
												<hr>
												<div class="col-xl-6">
													<h3>ASET LANCAR</h3>
													<table class="table table-striped gy-7 gs-7">
														<thead>
															<tr class="fw-bold fs-6 text-gray-800 border-bottom-2 border-gray-200">
																<th class="min-w-100px">Kas & Bank</th>
																<th class="min-w-50px text-center">Total</th>
															</tr>
														</thead>
														<tbody>
															@foreach ($kasDanBank as $item)
																<tr>
																	<td>{{ $item->no_akun }} {{ $item->nama_akun }}</td>
																	<td class="text-center">@currency($item->total_neraca)</td>
																</tr>
															@endforeach
														</tbody>
													</table>
													<table class="table table-striped gy-7 gs-7">
														<thead>
															<tr class="fw-bold fs-6 text-gray-800 border-bottom-2 border-gray-200">
																<th class="min-w-100px">Piutang</th>
																<th class="min-w-50px text-center">Total</th>
															</tr>
														</thead>
														<tbody>
															@foreach ($piutang as $item)
																<tr>
																	<td>{{ $item->no_akun }} {{ $item->nama_akun }}</td>
																	<td class="text-center">@currency($item->total_neraca)</td>
																</tr>
															@endforeach
														</tbody>
													</table>
													<h3 class="text-center mb-10 mt-10">Sub Total @currency($subTotalAsetLancar)</h3>
												</div>
												<div class="col-xl-6">
													<h3>LIABILITAS</h3>
													<table class="table table-striped gy-7 gs-7">
														<thead>
															<tr class="fw-bold fs-6 text-gray-800 border-bottom-2 border-gray-200">
																<th class="min-w-100px">Liabilitas Jangka Pendek</th>
																<th class="min-w-50px text-center">Total</th>
															</tr>
														</thead>
														<tbody>
															@foreach ($lljPendek as $item)
																<tr>
																	<td>{{ $item->no_akun }} {{ $item->nama_akun }}</td>
																	<td class="text-center">@currency($item->total_neraca)</td>
																</tr>
															@endforeach
														</tbody>
													</table>
													<table class="table table-striped gy-7 gs-7">
														<thead>
															<tr class="fw-bold fs-6 text-gray-800 border-bottom-2 border-gray-200">
																<th class="min-w-100px">Liabilitas Jangka Panjang</th>
																<th class="min-w-50px text-center">Total</th>
															</tr>
														</thead>
														<tbody>
															@foreach ($lljPanjang as $item)
																<tr>
																	<td>{{ $item->no_akun }} {{ $item->nama_akun }}</td>
																	<td class="text-center">@currency($item->total_neraca)</td>
																</tr>
															@endforeach
														</tbody>
													</table>
													<h3 class="text-center mb-10 mt-10">Sub Total @currency($subTotalLiabilitas)</h3>
												</div>
												<div class="col-xl-6">
													<h3>ASET TIDAK LANCAR</h3>
													<table class="table table-striped gy-7 gs-7">
														<thead>
															<tr class="fw-bold fs-6 text-gray-800 border-bottom-2 border-gray-200">
																<th class="min-w-100px">Aset Tidak Lancar</th>
																<th class="min-w-50px text-center">Total</th>
															</tr>
														</thead>
														<tbody>
															@foreach ($asetTidakLancar as $item)
																<tr>
																	<td>{{ $item->no_akun }} {{ $item->nama_akun }}</td>
																	<td class="text-center">@currency($item->total_neraca)</td>
																</tr>
															@endforeach
														</tbody>
													</table>
													<h3 class="text-center mt-10">Sub Total @currency($subTotalAsetTidakLancar)</h3>
												</div>
												<div class="col-xl-6">
													<h3>EKUITAS</h3>
													<table class="table table-striped gy-7 gs-7">
														<thead>
															<tr class="fw-bold fs-6 text-gray-800 border-bottom-2 border-gray-200">
																<th class="min-w-100px">Ekuitas</th>
																<th class="min-w-50px text-center">Total</th>
															</tr>
														</thead>
														<tbody>
															@foreach ($ekuitas as $item)
																<tr>
																	<td>{{ $item->no_akun }} {{ $item->nama_akun }}</td>
																	<td class="text-center">@currency($item->total_neraca)</td>
																</tr>
															@endforeach
														</tbody>
													</table>
													<h3 class="text-center mt-10">Sub Total @currency($subTotalEkuitas)</h3>
												</div>
												<div class="col-xl-6">
													<div class="bg-light-primary rounded border-primary border border-dashed p-5 mb-15 mt-5">
														<!--begin::Wrapper-->
														<h2 class="text-gray-900 fw-bolder" style="text-align: center;">Grand Total @currency($grandTotalAsset)</h2>
														<!--end::Wrapper-->
													</div>
												</div>
												<div class="col-xl-6">
													<div class="bg-light-primary rounded border-primary border border-dashed p-5 mb-15 mt-5">
														<!--begin::Wrapper-->
														<h2 class="text-gray-900 fw-bolder" style="text-align: center;">Grand Total @currency($grandTotalLiabilDanEkuitas)</h2>
														<!--end::Wrapper-->
													</div>
												</div>
												<div class="col-xl-12">
													<div class="d-grid gap-2 col-6 mx-auto mb-15">
														<a href="{{ route('printNeraca') }}" target="blank" class="btn btn-lg btn-danger fs-6">Cetak</a>
													</div>
												</div>
											</div>
											<!--end::Row-->
										</div>
										<!--end::Card body-->
										@endif
                                        <!--end::Notice-->
                                    </div>
                                    <!--end::Card body-->
								</div>
								<!--end::Card-->
							</div>
							<!--end::Container-->
						</div>
						<!--end::Post-->
					</div>
@endsection
