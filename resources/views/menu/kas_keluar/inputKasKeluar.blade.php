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
					<div class="card-px text-center pt-10">
						<!--begin::Title-->
						<div class="d-flex flex-row justify-content-between">
							<div>
								<h1>{{ $title }}</h1>
							</div>
						</div>
						<!--end::Title-->
					</div>
					<div class="card-body pb-10">
						<!--Begin::Table-->
						<form id="my-form" action="{{ route('store.kasKeluar') }}" enctype="multipart/form-data" method="POST">
							@csrf
							<!--begin::Row-->
							<div class="row">
									<!--begin::Input group-->
									<div class="d-flex flex-column mb-7 col-lg-6">
										<!--begin::Label-->
										<label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
											<span class="required">Periode</span>
										</label>
										<!--end::Label-->
										<!--begin::Input-->
										<div class="position-relative d-flex align-items-center">
											<!--begin::Icon-->
											<!--begin::Svg Icon | path: icons/duotune/general/gen014.svg-->
											<span class="svg-icon svg-icon-2 position-absolute mx-4">
												<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
													<path opacity="0.3" d="M21 22H3C2.4 22 2 21.6 2 21V5C2 4.4 2.4 4 3 4H21C21.6 4 22 4.4 22 5V21C22 21.6 21.6 22 21 22Z" fill="black" />
													<path d="M6 6C5.4 6 5 5.6 5 5V3C5 2.4 5.4 2 6 2C6.6 2 7 2.4 7 3V5C7 5.6 6.6 6 6 6ZM11 5V3C11 2.4 10.6 2 10 2C9.4 2 9 2.4 9 3V5C9 5.6 9.4 6 10 6C10.6 6 11 5.6 11 5ZM15 5V3C15 2.4 14.6 2 14 2C13.4 2 13 2.4 13 3V5C13 5.6 13.4 6 14 6C14.6 6 15 5.6 15 5ZM19 5V3C19 2.4 18.6 2 18 2C17.4 2 17 2.4 17 3V5C17 5.6 17.4 6 18 6C18.6 6 19 5.6 19 5Z" fill="black" />
													<path d="M8.8 13.1C9.2 13.1 9.5 13 9.7 12.8C9.9 12.6 10.1 12.3 10.1 11.9C10.1 11.6 10 11.3 9.8 11.1C9.6 10.9 9.3 10.8 9 10.8C8.8 10.8 8.59999 10.8 8.39999 10.9C8.19999 11 8.1 11.1 8 11.2C7.9 11.3 7.8 11.4 7.7 11.6C7.6 11.8 7.5 11.9 7.5 12.1C7.5 12.2 7.4 12.2 7.3 12.3C7.2 12.4 7.09999 12.4 6.89999 12.4C6.69999 12.4 6.6 12.3 6.5 12.2C6.4 12.1 6.3 11.9 6.3 11.7C6.3 11.5 6.4 11.3 6.5 11.1C6.6 10.9 6.8 10.7 7 10.5C7.2 10.3 7.49999 10.1 7.89999 10C8.29999 9.90003 8.60001 9.80003 9.10001 9.80003C9.50001 9.80003 9.80001 9.90003 10.1 10C10.4 10.1 10.7 10.3 10.9 10.4C11.1 10.5 11.3 10.8 11.4 11.1C11.5 11.4 11.6 11.6 11.6 11.9C11.6 12.3 11.5 12.6 11.3 12.9C11.1 13.2 10.9 13.5 10.6 13.7C10.9 13.9 11.2 14.1 11.4 14.3C11.6 14.5 11.8 14.7 11.9 15C12 15.3 12.1 15.5 12.1 15.8C12.1 16.2 12 16.5 11.9 16.8C11.8 17.1 11.5 17.4 11.3 17.7C11.1 18 10.7 18.2 10.3 18.3C9.9 18.4 9.5 18.5 9 18.5C8.5 18.5 8.1 18.4 7.7 18.2C7.3 18 7 17.8 6.8 17.6C6.6 17.4 6.4 17.1 6.3 16.8C6.2 16.5 6.10001 16.3 6.10001 16.1C6.10001 15.9 6.2 15.7 6.3 15.6C6.4 15.5 6.6 15.4 6.8 15.4C6.9 15.4 7.00001 15.4 7.10001 15.5C7.20001 15.6 7.3 15.6 7.3 15.7C7.5 16.2 7.7 16.6 8 16.9C8.3 17.2 8.6 17.3 9 17.3C9.2 17.3 9.5 17.2 9.7 17.1C9.9 17 10.1 16.8 10.3 16.6C10.5 16.4 10.5 16.1 10.5 15.8C10.5 15.3 10.4 15 10.1 14.7C9.80001 14.4 9.50001 14.3 9.10001 14.3C9.00001 14.3 8.9 14.3 8.7 14.3C8.5 14.3 8.39999 14.3 8.39999 14.3C8.19999 14.3 7.99999 14.2 7.89999 14.1C7.79999 14 7.7 13.8 7.7 13.7C7.7 13.5 7.79999 13.4 7.89999 13.2C7.99999 13 8.2 13 8.5 13H8.8V13.1ZM15.3 17.5V12.2C14.3 13 13.6 13.3 13.3 13.3C13.1 13.3 13 13.2 12.9 13.1C12.8 13 12.7 12.8 12.7 12.6C12.7 12.4 12.8 12.3 12.9 12.2C13 12.1 13.2 12 13.6 11.8C14.1 11.6 14.5 11.3 14.7 11.1C14.9 10.9 15.2 10.6 15.5 10.3C15.8 10 15.9 9.80003 15.9 9.70003C15.9 9.60003 16.1 9.60004 16.3 9.60004C16.5 9.60004 16.7 9.70003 16.8 9.80003C16.9 9.90003 17 10.2 17 10.5V17.2C17 18 16.7 18.4 16.2 18.4C16 18.4 15.8 18.3 15.6 18.2C15.4 18.1 15.3 17.8 15.3 17.5Z" fill="black" />
												</svg>
											</span>
											<!--end::Svg Icon-->
											<!--end::Icon-->
											<!--begin::Datepicker-->
											<input class="form-control form-control-solid ps-12" required type="date" placeholder="Select a date" id="periode_jurnal" name="periode_jurnal" value="{{ old('periode_jurnal') }}"/>
											<!--end::Datepicker-->
										</div>
										<!--end::Input-->
									</div>
									<!--end::Input group-->
									<!--begin::Input group-->
									<div class="d-flex flex-column mb-7 col-lg-6">
										<label class="required fs-6 fw-bold mb-2">Type Jurnal</label>
										<select class="form-select form-select-solid" required data-control="select2" data-hide-search="true" data-placeholder="Type Jurnal" name="type_jurnal">
											<option value="ju" {{ old('type_jurnal') == 'ju' ? 'selected' : '' }}>Jurnal Umum</option>
											<option value="jp" {{ old('type_jurnal') == 'jp' ? 'selected' : '' }}>Jurnal Penyesuaian</option>
										</select>
									</div>
									<!--end::Input group-->
									<!--begin::Input group-->
									<div class="d-flex flex-column mb-7">
										<label class="required fs-6 fw-bold mb-2">Pilih Kas/Bank</label>
										<select class="form-select form-select-solid" required data-control="select2" data-hide-search="true" data-placeholder="Kas/Bank" name="kode_akun1">
											<option value="">Pilih Kas/Bank</option>
											@foreach($jurnalAkunOptionsKas as $id => $no_akun)
												<option value="{{ $id }}" {{ old('kode_akun1') == $id ? 'selected' : '' }}>{{ $no_akun }}-{{$jurnalAkunDescKas[$id]}}</option>
											@endforeach
										</select>
									</div>
									<!--end::Input group-->
									<!--begin::Input group-->
									<div class="d-flex flex-column mb-7">
										<label class="required fs-6 fw-bold mb-2">Uraian</label>
										<textarea class="form-control form-control-solid" required rows="3" name="uraian" placeholder="Tuliskan Uraian">{{ old('uraian') }}</textarea>
									</div>
									<!--end::Input group-->
								<!--begin::Col-->
								<div class="col-lg-12 mb-10 mb-lg-0">
									<!--begin::table-->
									<div class="table-responsive">
										<table id="input-table" class="table table-row-bordered gy-5">
											<thead>
												<tr class="fw-semibold fs-6 text-muted">
													<th class="text-dark required fs-6 fw-bold mb-2">Jurnal Akun</th>
													<th class="text-dark required fs-6 fw-bold mb-2">Divisi</th>
													<th class="text-dark fs-6 fw-bold mb-2">Ket. RKAT</th>
													<th class="text-dark required fs-6 fw-bold mb-2">No Bukti</th>
													<th class="text-dark required fs-6 fw-bold mb-2">Debit (Db)</th>
												</tr>
											</thead>
											<tbody id="input-fields">
											<!-- Set Data Pertama -->
											<tr id="row-1">
												<td>
													<select name="kode_akun2" required class="form-select form-select-solid" data-control="select2" data-hide-search="false" data-placeholder="Pilih Akun">
														<option value="">Pilih Akun</option>
														@foreach($jurnalAkunOptions as $id => $no_akun)
															<option value="{{ $id }}" {{ old('kode_akun2') == $id ? 'selected' : '' }}>{{ $no_akun }}-{{$jurnalAkunDesc[$id]}}</option>
														@endforeach
													</select>
												</td>
												<td>
													<select name="divisi1" required class="form-select form-select-solid" data-control="select2" data-hide-search="false" data-placeholder="Pilih Divisi" onchange="updateHiddenValues()">
														<option value="">Pilih Divisi</option>
														@foreach($dataDivisi as $id => $nama_divisi)
															<option value="{{ $id }}" {{ old('divisi1') == $id ? 'selected' : '' }}>{{ $nama_divisi }}</option>
														@endforeach
													</select>
												</td>
												<td>
													<input type="text" class="form-control form-control-solid" placeholder="Ket. RKAT" name="keterangan_rkat1" id="keterangan_rkat1" value="{{ old('keterangan_rkat1') }}" onchange="updateHiddenValues()" />
												</td>
												<td>
													<input type="text" class="form-control form-control-solid" placeholder="No Bukti" name="no_bukti1" id="no_bukti1" value="{{ old('no_bukti1') }}" onchange="updateHiddenValues()" />
												</td>
												<td>
													<div class="input-group">
														<span class="input-group-text">Rp</span>
														<input class="form-control form-control-solid" type="text" name="debit2" required onchange="updateHiddenValues()" value="{{ old('debit2') }}"/>
													</div>
												</td>
												<!-- Input tipe hidden untuk setiap nilai -->
												<input type="hidden" name="keterangan_rkat2" id="hidden_keterangan_rkat" />
												<input type="hidden" name="no_bukti2" id="hidden_no_bukti" />
												<input type="hidden" name="divisi2" id="hidden_divisi" />
												<input type="hidden" name="debit1" value="0" />
												<input type="hidden" name="kredit1" id="hidden_kredit" />
												<input type="hidden" name="kredit2" value="0" />
											</tr>
											</tbody>
										</table>
									</div>
									<!--end::table-->
								</div>
								<!--end::Col-->
							</div>
							<!--end::Row-->
							<!--end::Plans-->
							<!--begin::Actions-->
							<div class="d-flex flex-center flex-row-fluid pt-12">
								<a href="{{url('kasKeluar')}}" class="btn btn-warning me-3">Kembali</a>
								<button type="submit" onclick="submitForm(this)" class="btn btn-primary">
                                    <span class="indicator-label">Submit</span>
                                </button>
							</div>
							<!--end::Actions-->
						</form>
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
		function updateHiddenValues() {
			// Ambil nilai dari input 'keterangan_rkat1' dan 'no_bukti1'
			var keterangan_rkat_value = document.getElementById('keterangan_rkat1').value;
			var no_bukti_value = document.getElementById('no_bukti1').value;
			var divisi_value = document.getElementsByName('divisi1')[0].value;
			var kredit_value = document.getElementsByName('debit2')[0].value;

			// Set nilai input tipe hidden dengan nilai yang sesuai
			document.getElementById('hidden_keterangan_rkat').value = keterangan_rkat_value;
			document.getElementById('hidden_no_bukti').value = no_bukti_value;
			document.getElementById('hidden_divisi').value = divisi_value;
			document.getElementById('hidden_kredit').value = kredit_value;
		}
	</script>

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
