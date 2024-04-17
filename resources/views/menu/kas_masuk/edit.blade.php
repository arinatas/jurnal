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
                            <form action="{{ route('update.kasMasuk', $kasMasuk->id ) }}" method="POST">
                                @csrf
								<div class="mb-10">
                                    <label for="exampleFormControlInput1" class="required form-label">Periode Jurnal</label>
                                    <input type="date" value="{{$kasMasuk->periode_jurnal}}" class="form-control form-control-solid" required name="periode_jurnal"/>
                                </div>
								<div class="mb-10">
                                    <label for="exampleFormControlInput1" class="required form-label">Type Jurnal</label>
									<select class="form-select form-select-solid" required data-control="select2" data-hide-search="true" data-placeholder="Pilih Type Jurnal" name="type_jurnal">
										<option value="">Pilih Type Jurnal</option>
										<option value="ju" {{$kasMasuk->type_jurnal == "ju" ? 'selected' : ''}}>Jurnal Umum</option>
										<option value="jp" {{$kasMasuk->type_jurnal == "jp" ? 'selected' : ''}}>Jurnal Penyesuaian</option>
									</select>
                                </div>
								<div class="mb-10">
									<label for="exampleFormControlSelect1" class="required form-label">Divisi</label>
									<select class="form-select form-control-solid" name="divisi" required>
										<option value="">Pilih Divisi</option>
										@foreach($divisions as $division)
											<option value="{{$division->id}}" {{$kasMasuk->divisi == $division->id ? 'selected' : ''}}>{{$division->nama_divisi}}</option>
										@endforeach
									</select>
								</div>
								<div class="mb-10">
									<label for="exampleFormControlSelect1" class="required form-label">Kode Akun</label>
									<select class="form-select form-control-solid" name="kode_akun" required>
										<option value="">Pilih Kode Akun</option>
										@foreach($jurnalAkuns as $jurnalAkun)
											<option value="{{$jurnalAkun->no_akun}}" {{$kasMasuk->kode_akun == $jurnalAkun->no_akun ? 'selected' : ''}}>{{$jurnalAkun->no_akun}} - {{$jurnalAkun->nama_akun}}</option>
										@endforeach
									</select>
								</div>
								<div class="mb-10">
                                    <label for="exampleFormControlInput1" class="required form-label">Uraian</label>
                                    <input type="text" value="{{$kasMasuk->uraian}}" class="form-control form-control-solid" required name="uraian"/>
                                </div>
								<div class="mb-10">
                                    <label for="exampleFormControlInput1" class="required form-label">No Bukti</label>
                                    <input type="text" value="{{$kasMasuk->no_bukti}}" class="form-control form-control-solid" required name="no_bukti"/>
                                </div>
								<div class="mb-10">
                                    <label for="exampleFormControlInput1" class="required form-label">Debit</label>
                                    <input type="text" value="{{$kasMasuk->debit}}" class="form-control form-control-solid" required name="debit"/>
                                </div>
								<div class="mb-10">
                                    <label for="exampleFormControlInput1" class="required form-label">Kredit</label>
                                    <input type="text" value="{{$kasMasuk->kredit}}" class="form-control form-control-solid" required name="kredit"/>
                                </div>
								<div class="mb-10">
                                    <label for="exampleFormControlInput1" class="required form-label">Keterangan RKAT</label>
                                    <input type="text" value="{{$kasMasuk->keterangan_rkat}}" class="form-control form-control-solid" required name="keterangan_rkat"/>
                                </div>
                                <div class="d-flex justify-content-end">
                                    <!--begin::Actions-->
									<a href="{{ url()->previous() }}" class="btn btn-secondary">
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
