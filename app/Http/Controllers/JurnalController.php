<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Jurnal;
use App\Models\JurnalAkun;
use App\Models\Rkat;
use App\Models\Divisi;
use Illuminate\Support\Facades\Auth;
use App\Imports\JurnalImport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class JurnalController extends Controller
{
    public function index()
    {
        // Get the current date
        $today = Carbon::now()->format('Y-m-d');
    
        // Fetch Jurnal entries created today
        $jurnals = Jurnal::with('dataDivisi')
            ->with('akun')
            ->whereDate('created_at', $today)
            ->paginate(10); // Menambahkan pagination untuk 50 data perhalaman
    
        // Calculate total debit and total kredit
        // Fetch Jurnal entries created today without pagination
        $jurnalsAll = Jurnal::with('dataDivisi')
        ->with('akun')
        ->whereDate('created_at', $today)
        ->get();

        $totalDebit = $jurnalsAll->sum('debit');
        $totalKredit = $jurnalsAll->sum('kredit');
    
        // Get the list of kode_akun options
        $jurnalAkunOptions = JurnalAkun::pluck('nama_akun', 'no_akun');
    
        return view('menu.jurnal.index', [
            'title' => 'Jurnal',
            'section' => 'Menu',
            'active' => 'Jurnal',
            'jurnals' => $jurnals,
            'jurnalAkunOptions' => $jurnalAkunOptions,
            'totalDebit' => $totalDebit,
            'totalKredit' => $totalKredit,
        ]);
    }    

    public function store(Request $request)
    {
        // validasi input yang didapatkan dari request
        $validator = Validator::make($request->all(), [
            'periode_jurnal' => 'required|date',
            'type_jurnal' => 'required|string|max:100',
            'id_rkat' => 'required|integer',
            'uraian' => 'required|string|max:255',
            'no_bukti' => 'required|string|max:100',
            'debit' => 'required|integer',
            'kredit' => 'required|integer',
            'tt' => 'nullable|string|max:100',
            'korek' => 'nullable|string|max:255',
            'ku' => 'nullable|string|max:100',
            'unit_usaha' => 'nullable|string|max:100',
        ]);
    
        // kalau ada error kembalikan error
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    
        // simpan data ke database
        try {
            DB::beginTransaction();

            // Get the currently authenticated user
            $user = Auth::user();
    
            // insert ke tabel positions
            Jurnal::create([
                'periode_jurnal' => $request->periode_jurnal,
                'type_jurnal' => $request->type_jurnal,
                'id_rkat' => $request->id_rkat,
                'uraian' => $request->uraian,
                'no_bukti' => $request->no_bukti,
                'debit' => $request->debit,
                'kredit' => $request->kredit,
                'tt' => $request->tt,
                'korek' => $request->korek,
                'ku' => $request->ku,
                'unit_usaha' => $request->unit_usaha,
                'created_by' => $user->id
            ]);
    
            DB::commit();
    
            return redirect()->back()->with('insertSuccess', 'Data berhasil di Inputkan');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('insertFail', $e->getMessage());
        }
    }

    public function showImportForm()
    {
        return view('import'); // Menampilkan tampilan untuk mengunggah file Excel
    }
    
    public function downloadExampleExcel()
    {
        $filePath = public_path('contoh-excel/jurnal.xlsx'); // Sesuaikan dengan path file Excel contoh Anda
    
        if (file_exists($filePath)) {
            $headers = [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ];
    
            return response()->download($filePath, 'jurnal.xlsx', $headers);
        } else {
            return redirect()->back()->with('downloadFail', 'File contoh tidak ditemukan.');
        }
    } 

    public function importExcel(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'excel_file' => 'required|mimes:xls,xlsx',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {
            $import = new JurnalImport(auth()->user());

            // Import data Excel
            Excel::import($import, $request->file('excel_file'));

            // Get total debit and credit after import
            $totalDebit = $import->getTotalDebit();
            $totalKredit = $import->getTotalKredit();

            // Check if totals are balanced
            if ($totalDebit !== $totalKredit) {
                DB::rollBack();
                return redirect()->back()->with('importError', 'Data total debit dan kredit belum balance.');
            }

            DB::commit();

            return redirect()->back()->with('importSuccess', 'Data berhasil diimpor.');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            DB::rollBack();
            $failures = $e->failures();
            $errorMessages = [];

            foreach ($failures as $failure) {
                $rowNumber = $failure->row();
                $column = $failure->attribute();
                $errorMessages[] = "Baris $rowNumber, Kolom $column: " . implode(', ', $failure->errors());
            }

            return redirect()->back()
                ->with('importValidationFailures', $failures)
                ->with('importErrors', $errorMessages)
                ->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            $errorMessage = $e->getMessage();

            \Log::error($errorMessage);

            return redirect()->back()->with('importError', $errorMessage);
        }
    }

    public function input()
    {
        // Fetch Jurnal entries created today
        $jurnals = Jurnal::with('dataDivisi')
            ->with('akun')
            ->get();
        
        // Mengambil data kode akun dan nama akun untuk option form
        $jurnalAkunOptions = JurnalAkun::pluck('no_akun', 'no_akun');
        $jurnalAkunDesc = JurnalAkun::pluck('nama_akun', 'no_akun');

        // Mengambil data divisi untuk option form
        $dataDivisi = Divisi::pluck('nama_divisi', 'id');

        return view('menu.jurnal.inputJurnal', [
            'title' => 'Input Jurnal',
            'section' => 'Menu',
            'active' => 'Jurnal',
            'jurnals' => $jurnals,
            'jurnalAkunOptions' => $jurnalAkunOptions,
            'jurnalAkunDesc' => $jurnalAkunDesc,
            'dataDivisi' => $dataDivisi,
        ]);
    }

    public function storeJurnal(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'periode_jurnal' => 'required|date',
            'type_jurnal' => 'required|string|max:100',
            'kode_akun1' => 'required|string|max:100',
            'kode_akun2' => 'required|string|max:100',
            'divisi1' => 'required|integer',
            'divisi2' => 'required|integer',
            'uraian' => 'required|string|max:255',
            'keterangan_rkat1' => 'nullable|string|max:100',
            'keterangan_rkat2' => 'nullable|string|max:100',
            'no_bukti1' => 'required|string|max:100',
            'debit1' => 'required|integer',
            'kredit1' => 'required|integer',
            'no_bukti2' => 'required|string|max:100',
            'debit2' => 'required|integer',
            'kredit2' => 'required|integer',
            'tt' => 'nullable|string|max:100',
            'korek' => 'nullable|string|max:255',
            'ku' => 'nullable|string|max:100',
            'unit_usaha' => 'nullable|string|max:100',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('error', 'Data Masih ada yang kosong');
        }

        try {
            DB::beginTransaction();

            $user = Auth::user();

            // Mendapatkan total debit dan kredit dari formulir
            $totalDebit = $request->input('debit1') + $request->input('debit2');
            $totalKredit = $request->input('kredit1') + $request->input('kredit2');

            // Memeriksa apakah total debit dan kredit seimbang
            if ($totalDebit != $totalKredit) {
                return redirect()->back()->withInput()->with('error', 'Total Debit dan Kredit harus seimbang.');
            }
            // Insert the first row into the jurnal table
            Jurnal::create([
                'periode_jurnal' => $request->periode_jurnal,
                'type_jurnal' => $request->type_jurnal,
                'kode_akun' => $request->kode_akun1,
                'divisi' => $request->divisi1,
                'uraian' => $request->uraian,
                'keterangan_rkat' => $request->keterangan_rkat1,
                'no_bukti' => $request->no_bukti1,
                'debit' => $request->debit1, 
                'kredit' => $request->kredit1, 
                'tt' => $request->tt,
                'korek' => $request->korek,
                'ku' => $request->ku,
                'unit_usaha' => $request->unit_usaha,
                'created_by' => $user->id
            ]);

            // Insert the second row into the jurnal table
            Jurnal::create([
                'periode_jurnal' => $request->periode_jurnal,
                'type_jurnal' => $request->type_jurnal,
                'kode_akun' => $request->kode_akun2,
                'divisi' => $request->divisi2,
                'uraian' => $request->uraian,
                'keterangan_rkat' => $request->keterangan_rkat2,
                'no_bukti' => $request->no_bukti2,
                'debit' => $request->debit2, 
                'kredit' => $request->kredit2,
                'tt' => $request->tt,
                'korek' => $request->korek,
                'ku' => $request->ku,
                'unit_usaha' => $request->unit_usaha,
                'created_by' => $user->id
            ]);

            DB::commit();

            return redirect()->back()->with('insertSuccess', 'Data berhasil di Inputkan');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('insertFail', $e->getMessage());
        }
    }

    public function laporanBukuBesar(Request $request)
    {
        // get all jurnal account
        $jurnalakuns = JurnalAkun::all();

        // get all jurnal account
        $divisis = Divisi::all();

        // Get the unique years from the "periode_jurnal" field
        $years = Jurnal::distinct()->select(DB::raw('YEAR(periode_jurnal) as year'))->pluck('year');
    
        // Get the selected year and month from the request
        $selectedYear = $request->input('tahun');
        $selectedMonth = $request->input('bulan');
        $selectedJurnalAccount = $request->input('jurnal_akun');
        $selectedDivisi = $request->input('divisi');
    
        // Fetch Jurnal entries based on selected month and year
        $jurnalsQuery = Jurnal::with('dataDivisi')
            ->with('akun');
    
        if ($selectedYear) {
            $jurnalsQuery->whereYear('periode_jurnal', $selectedYear);
        }
    
        if ($selectedMonth) {
            $jurnalsQuery->whereMonth('periode_jurnal', $selectedMonth);
        }

        if ($selectedJurnalAccount) {
            $jurnalsQuery->whereHas('akun', function ($query) use ($selectedJurnalAccount) {
                $query->where('no_akun', $selectedJurnalAccount);
            });
        }

        if ($selectedDivisi) {
            $jurnalsQuery->where('divisi', $selectedDivisi);
        }
    
        $jurnals = $jurnalsQuery->get();

        // Calculate total debit and total kredit
        $totalDebit = $jurnals->sum('debit');
        $totalKredit = $jurnals->sum('kredit');
    
        // Get the list of kode_rkat options
        $rkatOptions = Rkat::pluck('kode_rkat', 'id');
        $rkatDescriptions = Rkat::pluck('keterangan', 'id');
    
        return view('menu.jurnal.buku_besar', [
            'title' => 'Buku Besar',
            'section' => 'Laporan',
            'active' => 'Buku Besar',
            'jurnals' => $jurnals,
            'jurnalakuns' => $jurnalakuns,
            'rkatOptions' => $rkatOptions,
            'rkatDescriptions' => $rkatDescriptions,
            'totalDebit' => $totalDebit,
            'totalKredit' => $totalKredit,
            'years' => $years,
            'selectedYear' => $selectedYear, 
            'selectedMonth' => $selectedMonth,
            'selectedJurnalAccount' => $selectedJurnalAccount,
            'selectedDivisi' => $selectedDivisi, 
            'divisis' => $divisis,
        ]);
    }   

    // Metode untuk Print Buku Besar
    public function printBukuBesar($selectedYear, $selectedMonth, $selectedJurnalAccount)
    {
        // Query for CashFlows with optional date filter
        $jurnalsQuery = Jurnal::with('dataDivisi')
            ->with('akun');
    
            if ($selectedYear) {
                $jurnalsQuery->whereYear('periode_jurnal', $selectedYear);
            }
        
            if ($selectedMonth) {
                $jurnalsQuery->whereMonth('periode_jurnal', $selectedMonth);
            }

            if ($selectedJurnalAccount) {
                $jurnalsQuery->whereHas('akun', function ($query) use ($selectedJurnalAccount) {
                    $query->where('no_akun', $selectedJurnalAccount);
                });
            }

        // Execute the query
        $jurnals = $jurnalsQuery->get();
    
        // Calculate total debit and total kredit
        $totalDebit = $jurnals->sum('debit');
        $totalKredit = $jurnals->sum('kredit');

    
        return view('menu.jurnal.printbukubesar', [
            'title' => 'Laporan Jurnal',
            'section' => 'Laporan',
            'active' => 'Laporan Jurnal',
            'jurnals' => $jurnals,
            'totalDebit' => $totalDebit,
            'totalKredit' => $totalKredit,
            'selectedYear' => $selectedYear, 
            'selectedMonth' => $selectedMonth, 
        ]);
    }

    // Metode untuk Print Jurnal
    public function printJurnal($selectedYear, $selectedMonth)
    {
        // Query for CashFlows with optional date filter
        $jurnalsQuery = Jurnal::with('dataDivisi')
            ->with('akun');
    
            if ($selectedYear) {
                $jurnalsQuery->whereYear('periode_jurnal', $selectedYear);
            }
        
            if ($selectedMonth) {
                $jurnalsQuery->whereMonth('periode_jurnal', $selectedMonth);
            }

        // Execute the query
        $jurnals = $jurnalsQuery->get();
    
        // Calculate total debit and total kredit
        $totalDebit = $jurnals->sum('debit');
        $totalKredit = $jurnals->sum('kredit');
    
        return view('menu.jurnal.printlaporan', [
            'title' => 'Laporan Jurnal',
            'section' => 'Laporan',
            'active' => 'Laporan Jurnal',
            'jurnals' => $jurnals,
            'totalDebit' => $totalDebit,
            'totalKredit' => $totalKredit,
            'selectedYear' => $selectedYear, 
            'selectedMonth' => $selectedMonth, 
        ]);
    }

    // Metode untuk Print Jurnal Divisi
    public function printJurnalDivisi($selectedYear, $selectedMonth, $selectedDivisi)
    {
        // Query for CashFlows with optional date filter
        $jurnalsQuery = Jurnal::with('dataDivisi')
            ->with('akun');
    
            if ($selectedYear) {
                $jurnalsQuery->whereYear('periode_jurnal', $selectedYear);
            }
        
            if ($selectedMonth) {
                $jurnalsQuery->whereMonth('periode_jurnal', $selectedMonth);
            }

            if ($selectedDivisi) {
                $jurnalsQuery->where('divisi', $selectedDivisi);
            }

        // Execute the query
        $jurnals = $jurnalsQuery->get();
    
        // Calculate total debit and total kredit
        $totalDebit = $jurnals->sum('debit');
        $totalKredit = $jurnals->sum('kredit');
    
        return view('menu.jurnal.printlaporan', [
            'title' => 'Laporan Jurnal',
            'section' => 'Laporan',
            'active' => 'Laporan Jurnal',
            'jurnals' => $jurnals,
            'totalDebit' => $totalDebit,
            'totalKredit' => $totalKredit,
            'selectedYear' => $selectedYear, 
            'selectedMonth' => $selectedMonth,
            'selectedDivisi' => $selectedDivisi, 
        ]);
    }

    // Metode untuk Print Buku Besar Divisi
    public function printBukuBesarDivisi($selectedYear, $selectedMonth, $selectedJurnalAccount, $selectedDivisi)
    {
        // Query for CashFlows with optional date filter
        $jurnalsQuery = Jurnal::with('dataDivisi')
            ->with('akun');
    
            if ($selectedYear) {
                $jurnalsQuery->whereYear('periode_jurnal', $selectedYear);
            }
        
            if ($selectedMonth) {
                $jurnalsQuery->whereMonth('periode_jurnal', $selectedMonth);
            }

            if ($selectedJurnalAccount) {
                $jurnalsQuery->whereHas('akun', function ($query) use ($selectedJurnalAccount) {
                    $query->where('no_akun', $selectedJurnalAccount);
                });
            }

            if ($selectedDivisi) {
                $jurnalsQuery->where('divisi', $selectedDivisi);
            }

        // Execute the query
        $jurnals = $jurnalsQuery->get();
    
        // Calculate total debit and total kredit
        $totalDebit = $jurnals->sum('debit');
        $totalKredit = $jurnals->sum('kredit');

    
        return view('menu.jurnal.printbukubesar', [
            'title' => 'Laporan Jurnal',
            'section' => 'Laporan',
            'active' => 'Laporan Jurnal',
            'jurnals' => $jurnals,
            'totalDebit' => $totalDebit,
            'totalKredit' => $totalKredit,
            'selectedYear' => $selectedYear, 
            'selectedMonth' => $selectedMonth,
            'selectedDivisi' => $selectedDivisi,
        ]);
    }
}

