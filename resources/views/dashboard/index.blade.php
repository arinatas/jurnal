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
									<div class="card-body pb-0">
										<!--begin::Heading-->
										<!--begin::Row-->
										<div class="row g-5 g-xl-8">
											<div class="col-xl-4">
												<!--begin::Statistics Widget 5-->
												<a href="{{ url('kas') }}" class="card bg-info hoverable card-xl-stretch mb-xl-8">
													<!--begin::Body-->
													<div class="card-body">
														<span class="svg-icon svg-icon-white svg-icon-3x ms-n1">
															<i class="bi bi-bank fs-1"></i>
														</span>
														<!--end::Svg Icon-->
														@foreach ($kass as $item)
															<div class="text-white fw-bolder fs-2 mb-2 mt-5">Rp. @currency($item->kas),-</div>
                                                		@endforeach
														<div class="fw-bold text-white">Total KAS</div>
													</div>
													<!--end::Body-->
												</a>
												<!--end::Statistics Widget 5-->
											</div>
											<div class="col-xl-4">
												<!--begin::Statistics Widget 5-->
												<a href="{{ url('piutang') }}" class="card bg-success hoverable card-xl-stretch mb-xl-8">
													<!--begin::Body-->
													<div class="card-body">
														<!--begin::Svg Icon | path: icons/duotune/ecommerce/ecm008.svg-->
														<span class="svg-icon svg-icon-white svg-icon-3x ms-n1">
															<i class="bi bi-cash-stack fs-1"></i>
														</span>
														<!--end::Svg Icon-->
														<div class="text-white fw-bolder fs-2 mb-2 mt-5">Rp. @currency($totalPiutang),-</div>
														<div class="fw-bold text-white">Total Piutang</div>
													</div>
													<!--end::Body-->
												</a>
												<!--end::Statistics Widget 5-->
											</div>
											<div class="col-xl-4">
												<!--begin::Statistics Widget 5-->
												<a href="{{ url('uangFisik') }}" class="card bg-warning hoverable card-xl-stretch mb-5 mb-xl-8">
													<!--begin::Body-->
													<div class="card-body">
														<!--begin::Svg Icon | path: icons/duotune/graphs/gra005.svg-->
														<span class="svg-icon svg-icon-white svg-icon-3x ms-n1">
															<i class="bi bi-wallet2 fs-1"></i>
														</span>
														<!--end::Svg Icon-->
														@if($uangFisik->isEmpty())
															<div class="text-white fw-bolder fs-2 mb-2 mt-5">Rp. 0,-</div>
														@else
															@foreach ($uangFisik as $item)
																<div class="text-white fw-bolder fs-2 mb-2 mt-5">Rp. @currency($item->total),-</div>
															@endforeach
														@endif
														<div class="fw-bold text-white">Total Uang Fisik Hari Ini</div>
													</div>
													<!--end::Body-->
												</a>
												<!--end::Statistics Widget 5-->
											</div>
										</div>
										<!--end::Row-->
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
