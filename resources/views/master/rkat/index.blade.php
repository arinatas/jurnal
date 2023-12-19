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
                                                    <a href="#" class="btn btn-sm btn-primary fs-6" data-bs-toggle="modal" data-bs-target="#kt_modal_rkat">Tambah</a>
                                                    <a href="{{ route('download.example.excel.jurnal') }}" class="btn btn-sm btn-secondary">Download Contoh Excel</a>
                                                </div>
                                            <!--end::Title-->
                                        </div>
                                        <!--end::Heading-->
                                        <!--begin::Import Form-->
                                        <div class="card-px mt-10 mt-5">
                                            <h3 class="fs-4 fw-bolder mb-4">Import Data Excel</h3>
                                            <form action="{{ route('import.rkat') }}" method="POST" enctype="multipart/form-data">
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
                                        <!-- Form Filter -->
                                        <div class="card-px mt-10">
                                            <form action="{{ route('rkat') }}" method="GET">
                                                <div class="row mb-3">
                                                    <div class="col-md-3">
                                                        <label for="periode" class="form-label">Pilih Periode</label>
                                                        <select class="form-select" id="periode" name="periode">
                                                            <option value="" selected>Silahkan Pilih Periode</option>
                                                            @foreach($periodes as $periode)
                                                                <option value="{{ $periode }}" {{ $periode == request('periode') ? 'selected' : '' }}>
                                                                    {{ $periode }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3 mt-4">
                                                        <button type="submit" class="btn btn-primary mt-4">Filter</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <!-- End Form Filter -->
                                        <!--begin::Table-->
                                        @if ($rkats && $periodeFilter)
                                        <div class="table-responsive my-10 mx-8">
                                            <table class="table table-striped gy-7 gs-7">
                                                <thead>
                                                    <tr class="fw-bold fs-6 text-gray-800 border-bottom-2 border-gray-200">
                                                        <th class="min-w-50px text-center">No</th>
                                                        <th class="min-w-100px text-center">ID RKAT</th>
                                                        <th class="min-w-100px text-center">Kode RKAT</th>
                                                        <th class="min-w-100px text-center">Keterangan</th>
                                                        <th class="min-w-100px text-center">No Akun</th>
                                                        <th class="min-w-100px text-center">Nama Akun</th>
                                                        <th class="min-w-100px text-center">Periode</th>
                                                        <th class="min-w-100px text-center">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php
                                                        $no = 1; // Inisialisasi no
                                                    @endphp
                                                    @foreach ($rkats as $item)
                                                    <tr>
                                                        <td class="text-center">{{ $no }}</td>
                                                        <td class="text-center">{{ $item->id }}</td>
                                                        <td class="text-center">{{ $item->kode_rkat }}</td>
                                                        <td class="text-left">{{ $item->keterangan }}</td>
                                                        <td class="text-center">{{ $item->no_akun }}</td>
                                                        <td class="text-center">{{ $item->jurnalAkun->nama_akun }}</td>
                                                        <td class="text-center">{{ $item->periode }}</td>
                                                        <td>
                                                            <a href="{{ route('edit.rkat', $item->id ) }}" class="btn btn-sm btn-primary btn-action" data-toggle="tooltip" title="Edit"><i class="fas fa-pencil-alt"></i></a>
                                                            <form id="form-delete" action="{{ route('destroy.rkat', $item->id ) }}" method="POST"
                                                            class="d-inline-block">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button id="submit-btn" type="submit" data-toggle="tooltip" data-original-title="Hapus bagian"
                                                                class="btn btn-sm btn-danger btn-action" onclick="confirmDelete(event)"
                                                                ><i class="fas fa-trash"></i></i></button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                    @php
                                                        $no++; // Tambahkan no setiap kali iterasi
                                                    @endphp
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        @endif
                                        <!--end::Table-->
                                        <!--begin::Notice-->
                                        @if (!$periodeFilter || !$rkats)
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
                                                        <h4 class="text-gray-900 fw-bolder">Silakan filter terlebih dahulu berdasarkan periode
                                                        </h4>
                                                        <div class="fs-6 text-gray-700 pe-7">Pilih periode pada formulir di atas untuk melihat
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
                                <!--begin::Modal-->
                                <div class="modal fade" id="kt_modal_rkat" tabindex="-1" aria-hidden="true">
                                    <!--begin::Modal dialog-->
                                    <div class="modal-dialog modal-dialog-centered mw-650px">
                                        <!--begin::Modal content-->
                                        <div class="modal-content">
                                            <!--begin::Modal header-->
                                            <div class="modal-header">
                                                <!--begin::Modal title-->
                                                <h2>Tambah {{ $title }}</h2>
                                                <!--end::Modal title-->
                                                <!--begin::Close-->
                                                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                                                    <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                                                    <span class="svg-icon svg-icon-1">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                            <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="black" />
                                                            <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="black" />
                                                        </svg>
                                                    </span>
                                                    <!--end::Svg Icon-->
                                                </div>
                                                <!--end::Close-->
                                            </div>
                                            <!--end::Modal header-->
                                            <!--begin::Modal body-->
                                            <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                                                <!--begin::Form-->
                                                <form action="{{ route('insert.rkat') }}" method="POST">
                                                    @csrf
                                                    <!--begin::Input group-->
                                                    <div class="d-flex flex-column mb-7 fv-row">
                                                        <!--begin::Label-->
                                                        <label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                                                            <span class="required">Kode RKAT</span>
                                                        </label>
                                                        <!--end::Label-->
                                                        <input class="form-control form-control-solid" type="text" name="kode_rkat" required value=""/>
                                                    </div>
                                                    <div class="d-flex flex-column mb-7 fv-row">
                                                        <!--begin::Label-->
                                                        <label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                                                            <span class="required">Keterangan</span>
                                                        </label>
                                                        <!--end::Label-->
                                                        <input class="form-control form-control-solid" type="text" name="keterangan" required value=""/>
                                                    </div>
                                                    <div class="d-flex flex-column mb-7 fv-row">
                                                        <!-- begin::Label -->
                                                        <label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                                                            <span class="required">No Akun</span>
                                                        </label>
                                                        <!-- end::Label -->
                                                        <select class="form-select form-select-solid" name="no_akun" required>
                                                            <option value="" disabled selected>Silahkan pilih jurnal akun</option>
                                                            @foreach($jurnalAkunOptions as $no_akun => $nama_akun)
                                                                <option value="{{ $no_akun }}">{{ $no_akun }} - {{ $nama_akun }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="d-flex flex-column mb-7 fv-row">
                                                        <!--begin::Label-->
                                                        <label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                                                            <span class="required">Periode</span>
                                                        </label>
                                                        <!--end::Label-->
                                                        <input class="form-control form-control-solid" type="text" name="periode" required value=""/>
                                                    </div>
                                                    <!--end::Input group-->
                                                    <!--begin::Actions-->
                                                    <div class="text-center pt-15">
                                                        <button type="reset" data-bs-dismiss="modal" class="btn btn-light me-3">Cancel</button>
                                                        <button type="submit" onclick="submitForm(this)" class="btn btn-primary">
                                                            <span class="indicator-label">Submit</span>
                                                        </button>
                                                    </div>
                                                    <!--end::Actions-->
                                                </form>
                                                <!--end::Form-->
                                            </div>
                                            <!--end::Modal body-->
                                        </div>
                                        <!--end::Modal content-->
                                    </div>
                                    <!--end::Modal dialog-->
                                </div>
                                <!--end::Modal-->
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

                            // Script to initialize Select2
                            $(document).ready(function() {
                                $('select[name="no_akun"]').select2({
                                    placeholder: 'Silahkan pilih jurnal akun',
                                    allowClear: true, // Adds a clear button
                                });
                            });
                    </script>
@endsection
