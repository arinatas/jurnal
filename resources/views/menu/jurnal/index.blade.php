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
                                                    <a href="#" class="btn btn-sm btn-primary fs-6" data-bs-toggle="modal" data-bs-target="#kt_modal_new_jurnal">Tambah</a>
                                                    <a href="{{ route('download.example.excel.jurnal') }}" class="btn btn-sm btn-secondary">Download Contoh Excel</a>
                                                </div>
                                            <!--end::Title-->
                                        </div>
                                        <!--end::Heading-->
                                        <!--begin::Import Form-->
                                        <div class="card-px mt-10 mt-5">
                                            <h3 class="fs-4 fw-bolder mb-4">Import Data Excel</h3>
                                            <form action="{{ route('import.jurnal') }}" method="POST" enctype="multipart/form-data">
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
                                        @if ($jurnals )
                                        <div class="table-responsive my-10 mx-8">
                                            <table class="table table-striped gy-7 gs-7">
                                                <thead>
                                                    <tr class="fw-bold fs-6 text-gray-800 border-bottom-2 border-gray-200">
                                                        <th class="min-w-50px">No</th>
                                                        <th class="min-w-100px">Periode</th>
                                                        <th class="min-w-50px">Tipe Jurnal</th>
                                                        <th class="min-w-100px">Uraian</th>
                                                        <th class="min-w-100px">RKAT</th>
                                                        <th class="min-w-100px">Kode Rekening</th>
                                                        <th class="min-w-100px">Nama Rekening</th>
                                                        <th class="min-w-100px">No Bukti</th>
                                                        <th class="min-w-100px">Debit</th>
                                                        <th class="min-w-100px">Kredit</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php
                                                        $no = 1; // Inisialisasi no
                                                    @endphp
                                                    @foreach ($jurnals as $item)
                                                    <tr>
                                                        <td>{{ $no }}</td>
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
                                                        <td>{{ $item->rkat->kode_rkat }}</td>
                                                        @foreach ($item->jurnalAkun as $jurnalAkun)
                                                            <td>{{ $jurnalAkun->no_akun }}</td>
                                                            <td>{{ $jurnalAkun->nama_akun }}</td>
                                                        @endforeach
                                                        <td>{{ $item->no_bukti }}</td>
                                                        <td>{{ $item->debit }}</td>
                                                        <td>{{ $item->kredit }}</td>
                                                        <!-- <td>
                                                            @foreach ($item->jurnalAkun as $jurnalAkun)
                                                                {{ $jurnalAkun->no_akun }} - {{ $jurnalAkun->nama_akun }}<br>
                                                            @endforeach
                                                        </td> -->
                                                    </tr>
                                                    @php
                                                        $no++; // Tambahkan no setiap kali iterasi
                                                    @endphp
                                                    @endforeach
                                                </tbody>
                                            </table>
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
                                                        <div class="fs-6 text-gray-700 pe-7">Belum ada data yang diinputkan</div>
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
                                <!--begin::Modal-->
                                <div class="modal fade" id="kt_modal_new_jurnal" tabindex="-1" aria-hidden="true">
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
                                                <form action="{{ route('insert.jurnal') }}" method="POST">
                                                    @csrf
                                                    <!--begin::Input group-->
                                                    <div class="d-flex flex-column mb-7 fv-row">
                                                        <!--begin::Label-->
                                                        <label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                                                            <span class="required">Periode</span>
                                                        </label>
                                                        <!--end::Label-->
                                                        <input class="form-control form-control-solid" type="date" name="periode_jurnal" required value=""/>
                                                    </div>
                                                    <div class="d-flex flex-column mb-7 fv-row">
                                                        <!--begin::Label-->
                                                        <label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                                                            <span class="required">Tipe Jurnal</span>
                                                        </label>
                                                        <!--end::Label-->
                                                        <select class="form-select form-select-solid" name="type_jurnal" required>
                                                            <option value="ju">Jurnal Umum</option>
                                                            <option value="jp">Jurnal Penyesuaian</option>
                                                        </select>
                                                    </div>
                                                    <div class="d-flex flex-column mb-7 fv-row">
                                                        <!--begin::Label-->
                                                        <label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                                                            <span class="required">RAK</span>
                                                        </label>
                                                        <!--end::Label-->
                                                        <select class="form-control form-control-solid" name="id_rkat" required>
                                                            <option value="">Pilih Kode Anggaran</option>
                                                            @foreach($rkatOptions as $id => $kode_rkat)
                                                                <option value="{{ $id }}">{{ $kode_rkat }} - {{ $rkatDescriptions[$id] }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="d-flex flex-column mb-7 fv-row">
                                                        <!--begin::Label-->
                                                        <label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                                                            <span class="required">Uraian</span>
                                                        </label>
                                                        <!--end::Label-->
                                                        <input class="form-control form-control-solid" type="text" name="uraian" required value=""/>
                                                    </div>
                                                    <div class="d-flex flex-column mb-7 fv-row">
                                                        <!--begin::Label-->
                                                        <label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                                                            <span class="required">No Bukti</span>
                                                        </label>
                                                        <!--end::Label-->
                                                        <input class="form-control form-control-solid" type="text" name="no_bukti" required value=""/>
                                                    </div>
                                                    <div class="d-flex flex-column mb-7 fv-row">
                                                        <!--begin::Label-->
                                                        <label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                                                            <span class="required">Debit</span>
                                                        </label>
                                                        <!--end::Label-->
                                                        <div class="input-group">
                                                            <span class="input-group-text">Rp</span>
                                                            <input class="form-control form-control-solid" type="text" name="debit" required oninput="formatNumber(this)" onblur="removeFormat(this)"  onfocus="removeLeadingZeros(this)" value="0"/>
                                                        </div>
                                                    </div>

                                                    <div class="d-flex flex-column mb-7 fv-row">
                                                        <!--begin::Label-->
                                                        <label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                                                            <span class="required">Kredit</span>
                                                        </label>
                                                        <!--end::Label-->
                                                        <div class="input-group">
                                                            <span class="input-group-text">Rp</span>
                                                            <input class="form-control form-control-solid" type="text" name="kredit" required oninput="formatNumber(this)" onblur="removeFormat(this)" onfocus="removeLeadingZeros(this)" value="0"/>
                                                        </div>
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
