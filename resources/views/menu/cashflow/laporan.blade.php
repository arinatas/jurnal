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
                                                    <h2 class="fs-2x fw-bolder mb-0">{{$title}}</h2>
                                                </div>
                                                @if (request('start_date') && request('end_date'))
                                                    <div class="d-inline">
                                                        <a href="{{ route('exportcashflow', ['start_date' => request('start_date'), 'end_date' => request('end_date')]) }}" class="btn btn-sm btn-info" title="Export Excel">Export Excel</a>
                                                        <a href="{{ route('printcashflow', ['start_date' => request('start_date'), 'end_date' => request('end_date')]) }}" class="btn btn-sm btn-success" title="Unduh Laporan">Print Laporan</a> 
                                                    </div>
                                                @endif
                                            <!--end::Title-->
                                        </div>
                                        <!--end::Heading-->
                                        <!-- Form Filter -->
                                        <div class="card-px mt-10">                                            
                                            <form action="{{ route('lapcashflow') }}" method="GET">
                                                <div class="row mb-3">
                                                    <div class="col-md-3">
                                                        <label for="start_date" class="form-label">Tanggal Awal:</label>
                                                        <input type="date" class="form-control" id="start_date" name="start_date" value="{{ request('start_date') }}">
                                                   </div>
                                                    <div class="col-md-3">
                                                        <label for="end_date" class="form-label">Tanggal Akhir:</label>
                                                        <input type="date" class="form-control" id="end_date" name="end_date" value="{{ request('end_date') }}">
                                                    </div>
                                                   <div class="col-md-3 mt-4">
                                                        <button type="submit" class="btn btn-primary mt-4">Filter</button>
                                                     </div>
                                                </div>
                                            </form>
                                        </div>
                                        <!-- End Form Filter -->
                                        <!--begin::Table-->
                                        @if ($start_date && $end_date && $cashflows )
                                        <div class="table-responsive my-10 mx-8">
                                            <table class="table table-striped gy-7 gs-7">
                                                <thead>
                                                    <tr class="fw-bold fs-6 text-gray-800 border-bottom-2 border-gray-200">
                                                        <th class="min-w-50px" >No</th>
                                                        <th class="min-w-100px">Tanggal</th>
                                                        <th class="min-w-100px">No Bukti</th>
                                                        <th class="min-w-100px">PIC</th>
                                                        <th class="min-w-100px">Transaksi</th>
                                                        <th class="min-w-100px">Accounting</th>
                                                        <th class="min-w-150px">Debit</th>
                                                        <th class="min-w-150px">Kredit</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php
                                                        $no = 1; // Inisialisasi no
                                                    @endphp
                                                    @foreach ($cashflows as $item)
                                                    <tr>
                                                        <td>{{ $no }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('j F Y'); }}</td>
                                                        <td>{{ $item->no_bukti }}</td>
                                                        <td>{{ $item->pic }}</td>
                                                        <td>{{ $item->transaksi }}</td>
                                                        <td>{{ $item->user->nama }}</td>
                                                        <td>Rp. @currency($item->debit )</td>
                                                        <td>Rp. @currency($item->kredit )</td>
                                                    </tr>
                                                    @php
                                                        $no++; // Tambahkan no setiap kali iterasi
                                                    @endphp
                                                    @endforeach
                                                    <!-- Total rows after the loop -->
                                                    <tr class="fw-bold fs-6 text-gray-800">
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td><strong>Total</strong></td>
                                                        <td><strong>Rp. @currency($totalDebit)</strong></td>
                                                        <td><strong>Rp. @currency($totalKredit)</strong></td>
                                                    </tr>
                                                    <tr class="fw-bold fs-6 text-gray-800">
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td style="text-align: left;"><strong>Saldo Akhir</strong></td>
                                                        <td></td>
                                                        <td style="text-align: left;"><strong>Rp. @currency($totalKas)</strong></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        @endif
                                        <!--end::Table-->

                                        <!--begin::Notice-->
                                        @if (!$start_date || !$end_date)
                                        <div class="my-10 mx-15">
                                            <!--begin::Notice-->
                                            <div class="notice d-flex bg-light-warning rounded border-warning border border-dashed p-6">
                                                <!--begin::Icon-->
                                                <!--begin::Svg Icon | path: icons/duotune/general/gen048.svg-->
                                                <span class="svg-icon svg-icon-2tx svg-icon-warning me-4">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                                        fill="none">
                                                        <path opacity="0.3"
                                                            d="M20.5543 4.37824L12.1798 2.02473C12.0626 1.99176 11.9376 1.99176 11.8203 2.02473L3.44572 4.37824C3.18118 4.45258 3 4.6807 3 4.93945V13.569C3 14.6914 3.48509 15.8404 4.4417 16.984C5.17231 17.8575 6.18314 18.7345 7.446 19.5909C9.56752 21.0295 11.6566 21.912 11.7445 21.9488C11.8258 21.9829 11.9129 22 12.0001 22C12.0872 22 12.1744 21.983 12.2557 21.9488C12.3435 21.912 14.4326 21.0295 16.5541 19.5909C17.8169 18.7345 18.8277 17.8575 19.5584 16.984C20.515 15.8404 21 14.6914 21 13.569V4.93945C21 4.6807 20.8189 4.45258 20.5543 4.37824Z"
                                                            fill="black" />
                                                        <path
                                                            d="M10.5606 11.3042L9.57283 10.3018C9.28174 10.0065 8.80522 10.0065 8.51412 10.3018C8.22897 10.5912 8.22897 11.0559 8.51412 11.3452L10.4182 13.2773C10.8099 13.6747 11.451 13.6747 11.8427 13.2773L15.4859 9.58051C15.771 9.29117 15.771 8.82648 15.4859 8.53714C15.1948 8.24176 14.7183 8.24176 14.4272 8.53714L11.7002 11.3042C11.3869 11.6221 10.874 11.6221 10.5606 11.3042Z"
                                                            fill="black" />
                                                    </svg>
                                                </span>
                                                <!--end::Svg Icon-->
                                                <!--end::Icon-->
                                                <!--begin::Wrapper-->
                                                <div
                                                    class="d-flex flex-stack flex-grow-1 flex-wrap flex-md-nowrap">
                                                    <!--begin::Content-->
                                                    <div class="mb-3 mb-md-0 fw-bold">
                                                        <h4 class="text-gray-900 fw-bolder">Silakan filter terlebih dahulu berdasarkan tanggal awal & tanggal akhir
                                                        </h4>
                                                        <div class="fs-6 text-gray-700 pe-7">Pilih tanggal awal & tanggal akhir pada formulir di atas untuk melihat
                                                            data.</div>
                                                    </div>
                                                    <!--end::Content-->
                                                </div>
                                                <!--end::Wrapper-->
                                            </div>
                                        </div>
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
                        // Fungsi untuk menambahkan pemisah 3 digit pada input
                        function formatNumber(input) {
                            // Menghapus karakter selain angka
                            let value = input.value.replace(/\D/g, '');

                            // Menambahkan pemisah 3 digit
                            value = value.replace(/\B(?=(\d{3})+(?!\d))/g, ',');

                            // Memasukkan nilai yang telah diformat kembali ke input
                            input.value = value;
                        }

                        // Fungsi untuk mengembalikan nilai tanpa pemisah saat disubmit
                        function removeFormat(input) {
                            input.value = input.value.replace(/\D/g, '');
                        }
                        // Fungsi untuk menghapus angka nol di depan saat input difokuskan
                        function removeLeadingZeros(input) {
                            let value = input.value;
                            // Menghapus angka nol di depan
                            value = value.replace(/^0+/, '');
                            // Memasukkan nilai yang telah diformat kembali ke input
                            input.value = value;
                        }
                    </script>
@endsection
