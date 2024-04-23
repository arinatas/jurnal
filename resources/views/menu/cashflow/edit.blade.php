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
                            <form action="{{ route('update.cashflow', $cashflow->id ) }}" method="POST">
                                @csrf
								<div class="mb-10">
                                    <label for="exampleFormControlInput1" class="required form-label">Tanggal</label>
                                    <input type="date" value="{{$cashflow->tanggal}}" class="form-control form-control-solid" required name="tanggal"/>
                                </div>
								<div class="mb-10">
                                    <label for="exampleFormControlInput1" class="required form-label">No Bukti</label>
                                    <input type="text" value="{{$cashflow->no_bukti}}" class="form-control form-control-solid" required name="no_bukti"/>
                                </div>
								<div class="mb-10">
                                    <label for="exampleFormControlInput1" class="required form-label">PIC</label>
                                    <input type="text" value="{{$cashflow->pic}}" class="form-control form-control-solid" required name="pic"/>
                                </div>
								<div class="mb-10">
                                    <label for="exampleFormControlInput1" class="required form-label">Transaksi</label>
                                    <input type="text" value="{{$cashflow->transaksi}}" class="form-control form-control-solid" required name="transaksi"/>
                                </div>
								<div class="mb-10">
                                    <label for="exampleFormControlInput1" class="required form-label">Debit</label>
                                    <input type="text" value="{{$cashflow->debit}}" class="form-control form-control-solid" required name="debit"/>
                                </div>
								<div class="mb-10">
                                    <label for="exampleFormControlInput1" class="required form-label">Kredit</label>
                                    <input type="text" value="{{$cashflow->kredit}}" class="form-control form-control-solid" required name="kredit"/>
                                </div>
								<input type="hidden" class="form-control" id="start_date" name="start_date" value="{{ request()->input('start_date') }}">
								<input type="hidden" class="form-control" id="end_date" name="end_date" value="{{ request()->input('end_date') }}">
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
