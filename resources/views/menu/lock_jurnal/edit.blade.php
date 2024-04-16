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
						<div class="card-px pt-10">
							<!--begin::Title-->
							<div class="row">
								<div class="col">
									<h2 class="fs-2x fw-bolder mb-0">Edit {{ $title }}</h2>
								</div>
							</div>
							<!--end::Title-->
						</div>
						<!--end::Heading-->
						<!--begin::Table-->
                        <div class="mt-15">
                            <form action="{{ route('update.lockJurnal', $lockJurnal->id ) }}" method="POST">
                                @csrf
								<div class="mb-10">
									<label for="bulan" class="required form-label">Bulan</label>
									<select class="form-select form-select-solid" required name="bulan">
										<option value="">Pilih Bulan</option>
										<option value="01" {{$lockJurnal->bulan == '01' ? 'selected' : ''}}>Januari</option>
										<option value="02" {{$lockJurnal->bulan == '02' ? 'selected' : ''}}>Februari</option>
										<option value="03" {{$lockJurnal->bulan == '03' ? 'selected' : ''}}>Maret</option>
										<option value="04" {{$lockJurnal->bulan == '04' ? 'selected' : ''}}>April</option>
										<option value="05" {{$lockJurnal->bulan == '05' ? 'selected' : ''}}>Mei</option>
										<option value="06" {{$lockJurnal->bulan == '06' ? 'selected' : ''}}>Juni</option>
										<option value="07" {{$lockJurnal->bulan == '07' ? 'selected' : ''}}>Juli</option>
										<option value="08" {{$lockJurnal->bulan == '08' ? 'selected' : ''}}>Agustus</option>
										<option value="09" {{$lockJurnal->bulan == '09' ? 'selected' : ''}}>September</option>
										<option value="10" {{$lockJurnal->bulan == '10' ? 'selected' : ''}}>Oktober</option>
										<option value="11" {{$lockJurnal->bulan == '11' ? 'selected' : ''}}>November</option>
										<option value="12" {{$lockJurnal->bulan == '12' ? 'selected' : ''}}>Desember</option>
									</select>
								</div>
								<div class="mb-10">
									<label for="tahun" class="required form-label">Tahun</label>
									<select class="form-select form-select-solid" required name="tahun">
										<option value="">Pilih Tahun</option>
										@php
											$currentYear = date('Y');
											$startYear = 2023;
										@endphp
										@for ($year = $currentYear; $year >= $startYear; $year--)
											<option value="{{ $year }}" {{ $lockJurnal->tahun == $year ? 'selected' : '' }}>{{ $year }}</option>
										@endfor
									</select>
								</div>
								<div class="mb-10">
									<label for="status" class="required form-label">Status</label>
									<select class="form-select form-select-solid" required name="status">
										<option value="">Pilih Status</option>
										<option value="Lock" {{$lockJurnal->status == 'Lock' ? 'selected' : ''}}>Lock</option>
										<option value="Unlock" {{$lockJurnal->status == 'Unlock' ? 'selected' : ''}}>Unlock</option>
									</select>
								</div>
                                <div class="d-flex justify-content-end">
                                    <!--begin::Actions-->
                                    <a href="{{ route('lockJurnal') }}" class="btn btn-secondary">
                                        <span class="indicator-label">
                                            Cancel
                                        </span>
                                    </a>
                                    <button id="submit_form" type="submit" class="btn btn-primary" style="margin-left: 10px; margin-right: 10px;">
                                        <span class="indicator-label">
                                            Submit
                                        </span>
                                    </button>
                                    <!--end::Actions-->
                                </div>
                            </form>
                        </div>
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
@endsection
