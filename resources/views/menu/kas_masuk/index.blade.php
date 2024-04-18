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
                                                    <h2 class="fs-2x fw-bolder mb-0">{{ $title }}</h2>
                                                </div>
                                                <div class="d-inline">
                                                    <a href="{{ route('input.kasMasuk') }}" class="btn btn-sm btn-primary fs-6">Input Kas Masuk</a>
                                                    <a href="{{ route('download.example.excel.jurnal') }}" class="btn btn-sm btn-secondary">Download Contoh Excel</a>
                                                </div>
                                            <!--end::Title-->
                                        </div>
                                        <!--end::Heading-->
                                        <!-- Form Filter -->
                                        <div class="card-px mt-10">                                            
                                            <form action="{{ route('kasMasuk') }}" method="GET">
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
                                        <!--begin::Import Form-->
                                        <div class="card-px mt-10 mt-5">
                                            <h3 class="fs-4 fw-bolder mb-4">Import Data Excel</h3>
                                            <form action="{{ route('import.kasMasuk') }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <div class="mb-3">
                                                    <label for="excel_file" class="form-label">Pilih File Excel:</label>
                                                    <div class="input-group">
                                                        <input type="file" class="form-control" name="excel_file" id="excel_file" accept=".xls, .xlsx">
                                                        <div style="margin-left: 10px;"> <!-- Tambahkan margin di sini -->
                                                            <button type="submit" class="btn btn-primary">Import Data</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                            @if (session('importSuccess'))
                                                <div class="alert alert-success mt-4">
                                                    {{ session('importSuccess') }}
                                                </div>
                                            @endif

                                            @if (session('importError'))
                                                <div class="alert alert-danger mt-4">
                                                    {{ session('importError') }}
                                                </div>
                                            @endif

                                            @if (session('importErrors'))
                                                <div class="alert alert-danger mt-4">
                                                    <ul>
                                                        @foreach(session('importErrors') as $errorMessage)
                                                            <li>{{ $errorMessage }}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif
                                            @if (session('importValidationFailures'))
                                                <div class="alert alert-danger mt-4">
                                                    <p>Detail Kesalahan:</p>
                                                    <ul>
                                                        @foreach(session('importValidationFailures') as $failure)
                                                            <li>Baris: {{ $failure->row() }}, Kolom: {{ $failure->attribute() }}, Pesan: {{ implode(', ', $failure->errors()) }}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif
                                        </div>
                                        <!--End::Import Form-->
                                        <!--begin::Table-->
                                        @if ($start_date && $end_date && $jurnals->count())
                                        <div class="table-responsive my-10 mx-8">
                                            <table class="table table-striped gy-7 gs-7">
                                                <thead>
                                                    <tr class="fw-bold fs-6 text-gray-800 border-bottom-2 border-gray-200">
                                                        <th class="min-w-100px text-center">Action</th>
                                                        <th class="min-w-50px text-center">No</th>
                                                        <th class="min-w-100px text-center">Periode</th>
                                                        <th class="min-w-50px text-center">Tipe Jurnal</th>
                                                        <th class="min-w-100px text-center">Uraian</th>
                                                        <th class="min-w-100px text-center">Divisi</th>
                                                        <th class="min-w-100px text-center">Kode Akun</th>
                                                        <th class="min-w-100px text-center">Nama Akun</th>
                                                        <th class="min-w-100px text-center">No Bukti</th>
                                                        <th class="min-w-150px text-center">Debit</th>
                                                        <th class="min-w-150px text-center">Kredit</th>
                                                        <th class="min-w-100px text-center">Ket. RKAT</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($jurnals as $item)
                                                    <tr>
                                                        <td>
                                                        @if($lockStatuses[$item->id] == 'Lock')
                                                            <!-- Tombol di-hide jika status terkunci -->
                                                        @else
                                                            <!-- <a href="{{ route('edit.kasMasuk', $item->id ) }}" class="btn btn-sm btn-primary btn-action" data-toggle="tooltip" title="Edit"><i class="fas fa-pencil-alt"></i></a> -->
                                                            <a href="{{ route('edit.kasMasuk', ['id' => $item->id, 'start_date' => $start_date, 'end_date' => $end_date]) }}" class="btn btn-sm btn-primary btn-action" data-toggle="tooltip" title="Edit"><i class="fas fa-pencil-alt"></i></a>
                                                            <form id="form-delete" action="{{ route('destroy.kasMasuk', $item->id ) }}" method="POST"
                                                            class="d-inline-block">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button id="submit-btn" type="submit" data-toggle="tooltip" data-original-title="Hapus bagian"
                                                                class="btn btn-sm btn-danger btn-action" onclick="confirmDelete(event)"
                                                                ><i class="fas fa-trash"></i></i></button>
                                                            </form>
                                                        @endif
                                                        </td>
                                                        <td class="text-center">{{ $jurnals->firstItem() + $loop->index }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($item->periode_jurnal)->format('j F Y'); }}</td>
                                                        <td class="text-center">
                                                        @if($item->type_jurnal == 'ju')
                                                            <span class="badge bg-success">Umum</span>
                                                        @elseif($item->type_jurnal == 'jp')
                                                            <span class="badge bg-warning text-dark">Penyesuaian</span>
                                                        @else
                                                            <span class="badge bg-danger">-</span>
                                                        @endif
                                                        </td>
                                                        <td>{{ $item->uraian }}</td>
                                                        <td class="text-center">{{ $item->dataDivisi->nama_divisi }}</td>
                                                        <td class="text-center">{{ $item->akun->no_akun }}</td>
                                                        <td>{{ $item->akun->nama_akun }}</td>

                                                        <td class="text-center">{{ $item->no_bukti }}</td>
                                                        <td class="text-center">@if($item->debit != 0) Rp. @currency($item->debit) @else - @endif</td>
                                                        <td class="text-center">@if($item->kredit != 0) Rp. @currency($item->kredit) @else - @endif</td>
                                                        <td>{{ $item->keterangan_rkat ?? '-' }}</td>

                                                        <!-- <td>
                                                            @foreach ($item->jurnalAkun as $jurnalAkun)
                                                                {{ $jurnalAkun->no_akun }} - {{ $jurnalAkun->nama_akun }}<br>
                                                            @endforeach
                                                        </td> -->
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <!-- Add pagination links below the table -->
                                        <div class="d-flex justify-content-center">
                                            {{ $jurnals->appends(request()->input())->links() }}
                                        </div>
                                        <div class="row mt-8">
                                            <div class="col-lg-6">
                                                <!--begin::Alert-->
                                                <div class="alert alert-primary">
                                                    <!--begin::Wrapper-->
                                                    <div class="d-flex flex-column">
                                                        <!--begin::Title-->
                                                        <h3 class="my-1 text-dark text-center">Total Debit : @currency($totalDebit)</h3>
                                                        <!--end::Title-->
                                                    </div>
                                                    <!--end::Wrapper-->
                                                </div>
                                                <!--end::Alert-->
                                            </div>
                                            <div class="col-lg-6">
                                                <!--begin::Alert-->
                                                <div class="alert alert-primary">
                                                    <!--begin::Wrapper-->
                                                    <div class="d-flex flex-column">
                                                        <!--begin::Title-->
                                                        <h3 class="my-1 text-dark text-center">Total Kredit : @currency($totalKredit) </h3>
                                                        <!--end::Title-->
                                                    </div>
                                                    <!--end::Wrapper-->
                                                </div>
                                                <!--end::Alert-->
                                            </div>
                                        </div>
                                        
                                        @elseif (!$start_date || !$end_date)
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
                                        @else
                                        <div class="my-10 mx-15">
                                            <!--begin::Notice-->
                                            <div class="notice d-flex bg-light-warning rounded border-warning border border-dashed p-6">
                                                <!--begin::Icon-->
                                                <!--begin::Svg Icon | path: icons/duotune/general/gen048.svg-->
                                                <span class="svg-icon svg-icon-2tx svg-icon-warning me-4">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                        <path opacity="0.3" d="M20.5543 4.37824L12.1798 2.02473C12.0626 1.99176 11.9376 1.99176 11.8203 2.02473L3.44572 4.37824C3.18118 4.45258 3 4.6807 3 4.93945V13.569C3 14.6914 3.48509 15.8404 4.4417 16.984C5.17231 17.8575 6.18314 18.7345 7.446 19.5909C9.56752 21.0295 11.6566 21.912 11.7445 21.9488C11.8258 21.9829 11.9129 22 12.0001 22C12.0872 22 12.1744 21.983 12.2557 21.9488C12.3435 21.912 14.4326 21.0295 16.5541 19.5909C17.8169 18.7345 18.8277 17.8575 19.5584 16.984C20.515 15.8404 21 14.6914 21 13.569V4.93945C21 4.6807 20.8189 4.45258 20.5543 4.37824Z" fill="black" />
                                                        <path d="M10.5606 11.3042L9.57283 10.3018C9.28174 10.0065 8.80522 10.0065 8.51412 10.3018C8.22897 10.5912 8.22897 11.0559 8.51412 11.3452L10.4182 13.2773C10.8099 13.6747 11.451 13.6747 11.8427 13.2773L15.4859 9.58051C15.771 9.29117 15.771 8.82648 15.4859 8.53714C15.1948 8.24176 14.7183 8.24176 14.4272 8.53714L11.7002 11.3042C11.3869 11.6221 10.874 11.6221 10.5606 11.3042Z" fill="black" />
                                                    </svg>
                                                </span>
                                                <!--end::Svg Icon-->
                                                <!--end::Icon-->
                                                <!--begin::Wrapper-->
                                                <div class="d-flex flex-stack flex-grow-1 flex-wrap flex-md-nowrap">
                                                    <!--begin::Content-->
                                                    <div class="mb-3 mb-md-0 fw-bold">
                                                        <h4 class="text-gray-900 fw-bolder">Belum ada data</h4>
                                                        <div class="fs-6 text-gray-700 pe-7">Belum ada data yang diinputkan hari ini</div>
                                                    </div>
                                                    <!--end::Content-->
                                                </div>
                                                <!--end::Wrapper-->
                                            </div>
                                            <!--end::Notice-->
                                        </div>
                                        @endif
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
