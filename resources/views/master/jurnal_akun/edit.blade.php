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
                            <form action="{{ route('update.jurnalakun', $jurnalakun->no_akun ) }}" method="POST">
                                @csrf
								<div class="mb-10">
									<label for="exampleFormControlInput1" class="required form-label">No Akun</label>
									<input type="text" value="{{ $jurnalakun->no_akun }}" class="form-control form-control-solid" required name="no_akun" readonly/>
								</div>
								<div class="mb-10">
                                    <label for="exampleFormControlInput1" class="required form-label">Parent</label>
                                    <input type="text" value="{{$jurnalakun->parent}}" class="form-control form-control-solid" required name="parent"/>
                                </div>
								<div class="mb-10">
                                    <label for="exampleFormControlInput1" class="required form-label">Nama Akun</label>
                                    <input type="text" value="{{$jurnalakun->nama_akun}}" class="form-control form-control-solid" required name="nama_akun"/>
                                </div>
								<div class="mb-10">
                                    <label for="exampleFormControlInput1" class="required form-label">Type Neraca</label>
									<select class="form-select form-select-solid" required data-control="select2" data-hide-search="true" data-placeholder="Pilih Type Neraca" name="type_neraca">
										<option value="">Pilih Type Neraca</option>
										<option value="AKTIVA" {{$jurnalakun->type_neraca == "AKTIVA" ? 'selected' : ''}}>AKTIVA</option>
										<option value="PASIVA" {{$jurnalakun->type_neraca == "PASIVA" ? 'selected' : ''}}>PASIVA</option>
										<option value="LIABILITAS" {{$jurnalakun->type_neraca == "LIABILITAS" ? 'selected' : ''}}>LIABILITAS</option>
										<option value="EKUITAS" {{$jurnalakun->type_neraca == "EKUITAS" ? 'selected' : ''}}>EKUITAS</option>
									</select>
                                </div>
								<div class="mb-10">
                                    <label for="exampleFormControlInput1" class="required form-label">Sub Type</label>
									<select class="form-select form-select-solid" data-control="select2" data-hide-search="true" data-placeholder="Pilih Sub Type" name="sub_type">
										<option value="">Pilih Sub Type</option>
										<option value="Kas & Bank" {{$jurnalakun->sub_type == "Kas & Bank" ? 'selected' : ''}}>Kas & Bank</option>
										<option value="Piutang" {{$jurnalakun->sub_type == "Piutang" ? 'selected' : ''}}>Piutang</option>
										<option value="Liabilitas Jangka Pendek" {{$jurnalakun->sub_type == "Liabilitas Jangka Pendek" ? 'selected' : ''}}>Liabilitas Jangka Pendek</option>
										<option value="Liabilitas Jangka Panjang" {{$jurnalakun->sub_type == "Liabilitas Jangka Panjang" ? 'selected' : ''}}>Liabilitas Jangka Panjang</option>
									</select>
                                </div>
								<div class="mb-10">
                                    <label for="exampleFormControlInput1" class="required form-label">Level</label>
                                    <input type="text" value="{{$jurnalakun->lvl}}" class="form-control form-control-solid" required name="lvl"/>
                                </div>
								<div class="mb-10">
                                    <label for="exampleFormControlInput1" class="required form-label">Tipe Akun</label>
									<select class="form-select form-select-solid" required data-control="select2" data-hide-search="true" data-placeholder="Pilih Tipe Akun" name="tipe_akun">
										<option value="">Pilih Tipe Akun</option>
										<option value="HEADER" {{$jurnalakun->tipe_akun == "HEADER" ? 'selected' : ''}}>HEADER</option>
										<option value="DETAIL" {{$jurnalakun->tipe_akun == "DETAIL" ? 'selected' : ''}}>DETAIL</option>
									</select>
                                </div>
                                <div class="d-flex justify-content-end">
                                    <!--begin::Actions-->
                                    <a href="{{ route('jurnalakun') }}" class="btn btn-secondary">
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
	<script>
		document.getElementById('submit-btn').addEventListener('click', confirmDelete);

		function confirmDelete(event) {
		event.preventDefault();

		Swal.fire({
			title: 'Anda yakin ingin menghapus data ini?',
			text: 'Pastikan semua data sudah benar sebelum menekan tombol OK',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'OK'
		}).then((result) => {
			if (result.isConfirmed) {
			event.target.form.submit();
			}
		});
		}
	</script>
@endsection
