<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Divisi;
use App\Models\Jurnal;
use App\Models\JurnalAkun;
use App\Models\LockJurnal;
use Illuminate\Http\Request;
use App\Imports\KasKeluarImport;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;

class KasKeluarController extends Controller
{
    public function index(Request $request)
    {
        // Get the start and end dates from the request, if available
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
    
        // Fetch Jurnal entries created today
        $jurnalsQuery = Jurnal::with('akun')
            ->with('dataDivisi')
            ->where('asal_input', 2);
    
        // Calculate total debit and total kredit
        $jurnalsAllQuery = clone $jurnalsQuery;

        // Add date filter if start and end dates are provided
        if ($startDate && $endDate) {
            $jurnalsQuery->whereBetween('periode_jurnal', [$startDate, $endDate]);
            $jurnalsAllQuery->whereBetween('periode_jurnal', [$startDate, $endDate]);
        }
    
        // Order by ID in descending order
        $jurnalsQuery->orderBy('id', 'desc');
        
        $jurnals = $jurnalsQuery->paginate(10);
        $jurnalsAll = $jurnalsAllQuery->get();

        $totalDebit = $jurnalsAll->sum('debit');
        $totalKredit = $jurnalsAll->sum('kredit');
    
        // Get the list of kode_akun options
        $jurnalAkunOptions = JurnalAkun::pluck('nama_akun', 'no_akun');

        // Fetch lock status for the corresponding month and year
        $lockStatus = LockJurnal::where('bulan', date('m', strtotime($startDate)))
            ->where('tahun', date('Y', strtotime($startDate)))
            ->first();
    
        return view('menu.kas_keluar.index', [
            'title' => 'Kas Keluar',
            'section' => 'Menu',
            'active' => 'Kas Keluar',
            'jurnals' => $jurnals,
            'jurnalAkunOptions' => $jurnalAkunOptions,
            'totalDebit' => $totalDebit,
            'totalKredit' => $totalKredit,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'lockStatus' => $lockStatus,
        ]);
    }    

    public function input()
    {
        // Fetch Jurnal yang diinput 
        $jurnals = Jurnal::with('dataDivisi')
            ->with('akun')
            ->get();
        
        // Mengambil data kode akun dan nama akun untuk option form
        $jurnalAkunOptions = JurnalAkun::pluck('no_akun', 'no_akun');
        $jurnalAkunDesc = JurnalAkun::pluck('nama_akun', 'no_akun');

        // Mengambil data divisi untuk option form
        $dataDivisi = Divisi::pluck('nama_divisi', 'id');

        // Mengambil data kode akun dan nama akun dengan subtype "kas"
        $jurnalAkunOptionsKas = JurnalAkun::where('sub_type', 'kas & bank')->pluck('no_akun', 'no_akun');
        $jurnalAkunDescKas = JurnalAkun::where('sub_type', 'kas & bank')->pluck('nama_akun', 'no_akun');

        return view('menu.kas_keluar.inputKasKeluar', [
            'title' => 'Input Kas Keluar',
            'section' => 'Menu',
            'active' => 'Input Kas Keluar',
            'jurnals' => $jurnals,
            'jurnalAkunOptions' => $jurnalAkunOptions,
            'jurnalAkunDesc' => $jurnalAkunDesc,
            'dataDivisi' => $dataDivisi,
            'jurnalAkunOptionsKas' => $jurnalAkunOptionsKas,
            'jurnalAkunDescKas' => $jurnalAkunDescKas,
        ]);
    }

    public function storeKasKeluar(Request $request)
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

            // Check apakah periode yang di input locked
            $lockedPeriod = LockJurnal::where('bulan', date('m', strtotime($request->periode_jurnal)))
                ->where('tahun', date('Y', strtotime($request->periode_jurnal)))
                ->first();

            if ($lockedPeriod && $lockedPeriod->status === 'Lock') {
                return redirect()->back()->withInput()->with('error', 'Bulan dan tahun periode tersebut terkunci untuk input data.');
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
                'asal_input' => 2,
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
                'debit' => $request->debit2, // Use the appropriate field name for the second row
                'kredit' => $request->kredit2, // Use the appropriate field name for the second row
                'tt' => $request->tt,
                'korek' => $request->korek,
                'ku' => $request->ku,
                'unit_usaha' => $request->unit_usaha,
                'asal_input' => 2,
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
            $import = new KasKeluarImport(auth()->user());

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

    public function edit($id)
    {
        $kasKeluar = Jurnal::find($id);
        $divisions = Divisi::all();
        $jurnalAkuns = JurnalAkun::all();

        if (!$kasKeluar) {
            return redirect()->back()->with('dataNotFound', 'Data tidak ditemukan');
        }

        return view('menu.kas_keluar.edit', [
            'title' => 'Kas Keluar',
            'secction' => 'Menu',
            'active' => 'Kas Keluar',
            'kasKeluar' => $kasKeluar,
            'divisions' => $divisions, 
            'jurnalAkuns' => $jurnalAkuns, 
        ]);
    }

    public function update(Request $request, $id)
    {
        $kasKeluar = Jurnal::find($id);

        if (!$kasKeluar) {
            return redirect()->back()->with('dataNotFound', 'Data tidak ditemukan');
        }

        // validasi input yang didapatkan dari request
        $validator = Validator::make($request->all(), [
            'periode_jurnal' => 'required|date',
            'type_jurnal' => 'required|string|max:100',
            'kode_akun' => 'required|string|max:100',
            'divisi' => 'required|integer',
            'uraian' => 'required|string|max:255',
            'keterangan_rkat' => 'nullable|string|max:100',
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
            $validatorErrors = implode('<br>', $validator->errors()->all());
            return redirect()->back()->with('validatorFail', $validatorErrors);
        }

        try {
            DB::beginTransaction();
    
            $kasKeluar->periode_jurnal = $request->periode_jurnal;
            $kasKeluar->type_jurnal = $request->type_jurnal;
            $kasKeluar->kode_akun = $request->kode_akun;
            $kasKeluar->divisi = $request->divisi;
            $kasKeluar->uraian = $request->uraian;
            $kasKeluar->no_bukti = $request->no_bukti;
            $kasKeluar->debit = $request->debit;
            $kasKeluar->kredit = $request->kredit;
            $kasKeluar->tt = $request->tt;
            $kasKeluar->korek = $request->korek;
            $kasKeluar->ku = $request->ku;
            $kasKeluar->unit_usaha = $request->unit_usaha;
            $kasKeluar->keterangan_rkat = $request->keterangan_rkat;
    
            $kasKeluar->save();
    
            DB::commit();

            return redirect('/kasKeluar')->with('updateSuccess', 'Data berhasil di Update');
    
        } catch(Exception $e) {
            DB::rollBack();
            // dd($e->getMessage());
            return redirect()->back()->with('updateFail', 'Data gagal di Update');
        }
    }

    public function destroy($id)
    {
        // Cari data pengguna berdasarkan ID
        $kasKeluar = Jurnal::find($id);

        try {
            // Hapus data pengguna
            $kasKeluar->delete();
            return redirect()->back()->with('deleteSuccess', 'Data berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()->with('deleteFail', $e->getMessage());
        }
    }
}

