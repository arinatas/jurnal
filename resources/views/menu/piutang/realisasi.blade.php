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
									<h2 class="fs-2x fw-bolder mb-0">Realisasi {{ $title }}</h2>
								</div>
							</div>
							<!--end::Title-->
                            <!--begin::Table-->
                            <div class="mt-15">
                                <form action="{{ route('update.realisasi', $piutang->id ) }}" method="POST">
                                    @csrf
                                    <div class="row">
                                        <div style="display: none;">
                                            <input type="text" name="id" value="{{ $piutang->id }}">
                                            <input type="text" name="jumlah_piutang" value="{{ $piutang->jumlah_piutang }}">
                                        </div>
                                        <div class="mb-10 col-lg-4">
                                            <label for="exampleFormControlInput1" class="form-label">Tanggal</label>
                                            <input type="text" value="{{ \Carbon\Carbon::parse($piutang->tanggal)->format('j F Y'); }}" class="form-control form-control-solid" disabled name="tanggal"/>
                                        </div>
                                        <div class="mb-10  col-lg-4">
                                            <label for="exampleFormControlInput1" class="form-label">Nama</label>
                                            <input type="text" value="{{ $piutang->nama }}" class="form-control form-control-solid" disabled name="nama"/>
                                        </div>
                                        <div class="mb-10  col-lg-4">
                                            <label for="exampleFormControlInput1" class="form-label">Jumlah Piutang</label>
                                            <input type="text" value="Rp. @currency( $piutang->jumlah_piutang)" class="form-control form-control-solid" disabled name="jumlah_piutang"/>
                                        </div>
                                        <div class="mb-10">
                                            <label for="exampleFormControlInput1" class="form-label">Keterangan</label>
                                            <input type="text" value="{{ $piutang->keterangan }}" class="form-control form-control-solid" disabled name="keterangan"/>
                                        </div>
                                        <div class="mb-10">
                                            <label for="exampleFormControlInput1" class="required form-label">Status Realisasi</label>
                                            <select class="form-select form-select-solid" required data-control="select2" data-hide-search="true" data-placeholder="Pilih Status" name="stts_reallisasi">
                                                <option value="">Pilih Status</option>
                                                <option value="1"> Selesai</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="d-flex justify-content-end">
                                        <!--begin::Actions-->
                                        <a href="{{ route('piutang') }}" class="btn btn-secondary">
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
						<!--end::Heading-->
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
