<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Jurnal;
use App\Models\User;
use App\Models\Rkat;
use Illuminate\Support\Facades\Hash;
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
        $jurnals = Jurnal::with('rkat:id,kode_rkat')
            ->with('jurnalAkun')
            ->whereDate('created_at', $today)
            ->get();
    
        // Calculate total debit and total kredit
        $totalDebit = $jurnals->sum('debit');
        $totalKredit = $jurnals->sum('kredit');
    
        // Get the list of kode_rkat options
        $rkatOptions = Rkat::pluck('kode_rkat', 'id');
        $rkatDescriptions = Rkat::pluck('keterangan', 'id');
    
        return view('menu.jurnal.index', [
            'title' => 'Jurnal',
            'section' => 'Menu',
            'active' => 'Jurnal',
            'jurnals' => $jurnals,
            'rkatOptions' => $rkatOptions,
            'rkatDescriptions' => $rkatDescriptions,
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
        $jurnals = Jurnal::with('rkat:id,kode_rkat')
            ->with('jurnalAkun')
            ->get();
        
        // Get the latest periode from the rkat table
        $latestPeriode = Rkat::max('periode');
        // Get the list of kode_rkat options for the latest periode
        $rkatOptions = Rkat::where('periode', $latestPeriode)->pluck('kode_rkat', 'id');
        $rkatDescriptions = Rkat::where('periode', $latestPeriode)->pluck('keterangan', 'id');

        return view('menu.jurnal.inputJurnal', [
            'title' => 'Input Jurnal',
            'section' => 'Menu',
            'active' => 'Jurnal',
            'jurnals' => $jurnals,
            'rkatOptions' => $rkatOptions,
            'rkatDescriptions' => $rkatDescriptions,
        ]);
    }

    public function storeJurnal(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'periode_jurnal' => 'required|date',
            'type_jurnal' => 'required|string|max:100',
            'id_rkat1' => 'required|integer',
            'id_rkat2' => 'required|integer',
            'uraian' => 'required|string|max:255',
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
                'id_rkat' => $request->id_rkat1,
                'uraian' => $request->uraian,
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
                'id_rkat' => $request->id_rkat2,
                'uraian' => $request->uraian,
                'no_bukti' => $request->no_bukti2,
                'debit' => $request->debit2, // Use the appropriate field name for the second row
                'kredit' => $request->kredit2, // Use the appropriate field name for the second row
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
}

