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
                            <form action="{{ route('update.pecahan', $pecahanUang->id ) }}" method="POST">
                                @csrf
								<div class="mb-10">
									<label for="exampleFormControlInput1" class="required form-label">Jenis Uang</label>
									<select class="form-select form-select-solid" data-control="select2" data-hide-search="true" data-placeholder="Pilih Jenis Uang" name="jenis_uang" required>
										<option value="1" {{$pecahanUang->jenis_uang == 1 ? 'selected' : ''}}>Kertas</option>
										<option value="0" {{$pecahanUang->jenis_uang == 0 ? 'selected' : ''}}>Logam</option>
									</select>
								</div>
								<div class="mb-10">
                                    <label for="exampleFormControlInput1" class="required form-label">Nominal Pecahan</label>
                                    <input type="text" value="Rp. @currency($pecahanUang->pecahan)" oninput="formatCurrency(this)" class="form-control form-control-solid" required name="pecahan"/>
                                </div>
								<div class="mb-10">
									<label for="exampleFormControlInput1" class="required form-label">Status</label>
									<select class="form-select form-select-solid" data-control="select2" data-hide-search="true" data-placeholder="Pilih Status" name="status" required>
										<option value="1" {{$pecahanUang->status == 1 ? 'selected' : ''}}>Aktif</option>
										<option value="0" {{$pecahanUang->status == 0 ? 'selected' : ''}}>Nonaktif</option>
									</select>
								</div>
                                <div class="d-flex justify-content-end">
                                    <!--begin::Actions-->
                                    <a href="{{ route('pecahan') }}" class="btn btn-secondary">
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
		function formatCurrency(input) {
			// Mendapatkan nilai numerik dari input
			const numericValue = parseFloat(input.value.replace(/[^0-9]/g, ''));

			// Mengonversi nilai numerik menjadi format mata uang yang diinginkan
			const formattedValue = new Intl.NumberFormat('id-ID', {
				style: 'currency',
				currency: 'IDR',
				minimumFractionDigits: 0,
				maximumFractionDigits: 0
			}).format(numericValue);

			// Memasukkan nilai yang telah diformat ke dalam input box
			input.value = formattedValue;
		}
	</script>
@endsection
