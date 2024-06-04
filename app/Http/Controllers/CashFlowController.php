<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Kas;
use App\Models\Rkat;
use App\Models\User;
use App\Models\CashFlow;
use Illuminate\Http\Request;
use App\Exports\CashFlowExport;
use App\Imports\CashFlowImport;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;

class CashFlowController extends Controller
{
    public function index()
    {
        $today = now()->format('Y-m-d'); // Get the current date in 'Y-m-d' format

        $cashflows = CashFlow::with(['user:id,nama', 'rkat:id,kode_rkat'])
            ->whereDate('tanggal', $today)
            ->orderByDesc('id') // Sort by 'id' in descending order
            ->get();

        // Calculate total debit and total kredit
        $totalDebit = $cashflows->sum('debit');
        $totalKredit = $cashflows->sum('kredit');

        // Get the latest periode from the rkat table
        $latestPeriode = Rkat::max('periode');
        // Get the list of kode_rkat options for the latest periode
        $rkatOptions = Rkat::where('periode', $latestPeriode)->pluck('kode_rkat', 'id');
        $rkatDescriptions = Rkat::where('periode', $latestPeriode)->pluck('keterangan', 'id');
        
        // Fetch the value of "kas" from the "uang_kas" table
        $kasModel = Kas::first(); // Ambil record pertama
        // Access the "kas" field from the model
        $totalKas = $kasModel ? $kasModel->kas : 0;

        return view('menu.cashflow.index', [
            'title' => 'Cash Flow',
            'section' => 'Menu',
            'active' => 'Cash Flow',
            'cashflows' => $cashflows,
            'rkatOptions' => $rkatOptions,
            'rkatDescriptions' => $rkatDescriptions,
            'totalKas' => $totalKas,
            'totalDebit' => $totalDebit,
            'totalKredit' => $totalKredit,
        ]);
    }

    public function store(Request $request)
    {
        // validasi input yang didapatkan dari request
        $validator = Validator::make($request->all(), [
            'tanggal' => 'required|date',
            'no_bukti' => 'required|string|max:100',
            'pic' => 'required|string|max:255',
            'nama' => 'nullable|string|max:255',
            'kode_anggaran' => 'nullable|integer',
            'transaksi' => 'required|string|max:255',
            'ref' => 'nullable|string|max:100',
            'debit' => 'required|integer',
            'kredit' => 'required|integer',
        ]);
    
        // kalau ada error kembalikan error
        if ($validator->fails()) {
            $validatorErrors = implode('<br>', $validator->errors()->all());
            return redirect()->back()->with('validatorFail', $validatorErrors);
        }
    
        // simpan data ke database
        try {
            DB::beginTransaction();
    
            // Get the currently authenticated user
            $user = Auth::user();
    
            // insert ke tabel positions
            $cashFlow = CashFlow::create([
                'tanggal' => $request->tanggal,
                'no_bukti' => $request->no_bukti,
                'pic' => $request->pic,
                'nama' => $request->nama,
                'kode_anggaran' => $request->kode_anggaran,
                'transaksi' => $request->transaksi,
                'ref' => $request->ref,
                'debit' => $request->debit,
                'kredit' => $request->kredit,
                'id_accounting' => $user->id
            ]);
    
            // Update uang_kas accordingly
            $totalKas = Kas::findOrFail("1");
            if ($cashFlow->debit > 0) {
                $totalKas->kas = $totalKas->kas + $cashFlow->debit;
            } 
            if ($cashFlow->kredit > 0) {
                $totalKas->kas = $totalKas->kas - $cashFlow->kredit;
            }

            $totalKas->save();
    
            DB::commit();
    
            return redirect()->back()->with('insertSuccess', 'Data berhasil di Inputkan');
    
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('insertFail', $e->getMessage());
        }
    }    

    public function laporan(Request $request)
    {
        // Get the start and end dates from the request, if available
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
    
        // Query for CashFlows with optional date filter
        $cashflowsQuery = CashFlow::with(['user:id,nama', 'rkat:id,kode_rkat']);
    
        // Add date filter if start and end dates are provided
        if ($startDate && $endDate) {
            $cashflowsQuery->whereBetween('tanggal', [$startDate, $endDate]);
        }
    
        // Execute the query Sort the $cashflows collection by 'id' in descending order
        $cashflows = $cashflowsQuery->get();
    
        // Calculate total debit and total kredit
        $totalDebit = $cashflows->sum('debit');
        $totalKredit = $cashflows->sum('kredit');
    
        // Get the list of kode_rkat options
        $rkatOptions = Rkat::pluck('kode_rkat', 'id');
        $rkatDescriptions = Rkat::pluck('keterangan', 'id');

        // Fetch the value of "kas" from the "uang_kas" table
        $kasModel = Kas::first(); // Ambil record pertama
        // Access the "kas" field from the model
        $totalKas = $kasModel ? $kasModel->kas : 0;
    
        return view('menu.cashflow.laporan', [
            'title' => 'Laporan Cash Flow',
            'section' => 'Menu',
            'active' => 'Laporan Cash Flow',
            'cashflows' => $cashflows,
            'rkatOptions' => $rkatOptions,
            'rkatDescriptions' => $rkatDescriptions,
            'totalDebit' => $totalDebit,
            'totalKredit' => $totalKredit,
            'totalKas' => $totalKas,
            'start_date' => $startDate,
            'end_date' => $endDate,
        ]);
    }

    // Metode untuk Print PDF
    public function printCashFlow($startDate, $endDate)
    {
        // Query for CashFlows with optional date filter
        $cashflowsQuery = CashFlow::with(['user:id,nama', 'rkat:id,kode_rkat']);
    
        // Add date filter if start and end dates are provided
        if ($startDate && $endDate) {
            $cashflowsQuery->whereBetween('tanggal', [$startDate, $endDate]);
        }
    
        // Execute the query
        $cashflows = $cashflowsQuery->get();
    
        // Calculate total debit and total kredit
        $totalDebit = $cashflows->sum('debit');
        $totalKredit = $cashflows->sum('kredit');
    
        // Get the list of kode_rkat options
        $rkatOptions = Rkat::pluck('kode_rkat', 'id');
        $rkatDescriptions = Rkat::pluck('keterangan', 'id');

        // Fetch the value of "kas" from the "uang_kas" table
        $kasModel = Kas::first(); // Ambil record pertama
        // Access the "kas" field from the model
        $totalKas = $kasModel ? $kasModel->kas : 0;
    
        return view('menu.cashflow.printlaporan', [
            'title' => 'Laporan Cash Flow',
            'section' => 'Menu',
            'active' => 'Laporan Cash Flow',
            'cashflows' => $cashflows,
            'rkatOptions' => $rkatOptions,
            'rkatDescriptions' => $rkatDescriptions,
            'totalDebit' => $totalDebit,
            'totalKredit' => $totalKredit,
            'totalKas' => $totalKas,
            'start_date' => $startDate,
            'end_date' => $endDate,
        ]);
    }

    // Metode untuk Export ke Excel
    public function exportCashFlow(Request $request, $startDate, $endDate)
    {
        $export = new CashFlowExport($startDate, $endDate);

        $currentDate = Carbon::now()->format('d-m-y'); // Format the current date as desired

        $fileName = 'laporan_cashflow_' . $currentDate . '.xlsx';

        return Excel::download($export, $fileName);
    }
    
    public function showImportForm()
    {
        return view('import'); // Menampilkan tampilan untuk mengunggah file Excel
    }
    
    public function downloadExampleExcel()
    {
        $filePath = public_path('contoh-excel/cashflow.xlsx');
    
        if (file_exists($filePath)) {
            $headers = [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ];
    
            return response()->download($filePath, 'cashflow.xlsx', $headers);
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
            $import = new CashFlowImport(auth()->user());

            // Import data Excel
            Excel::import($import, $request->file('excel_file'));

            // Update uang_kas 
            $totalKas = Kas::findOrFail("1");
            $totalKas->kas += $import->getTotalDebit();
            $totalKas->kas -= $import->getTotalKredit();
            $totalKas->save();

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

    public function edit($id, Request $request)
    {
        $cashflow = CashFlow::find($id);

        if (!$cashflow) {
            return redirect()->back()->with('dataNotFound', 'Data tidak ditemukan');
        }

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        return view('menu.cashflow.edit', [
            'title' => 'Cash Flow',
            'secction' => 'Menu',
            'active' => 'Cash Flow',
            'cashflow' => $cashflow,
            'start_date' => $startDate,
            'end_date' => $endDate,
        ]);
    }

    public function update(Request $request, $id)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $cashflow = CashFlow::find($id);

        if (!$cashflow) {
            return redirect()->back()->with('dataNotFound', 'Data tidak ditemukan');
        }

        // validasi input yang didapatkan dari request
        $validator = Validator::make($request->all(), [
            'tanggal' => 'required|date',
            'no_bukti' => 'required|string|max:100',
            'pic' => 'required|string|max:255',
            'nama' => 'nullable|string|max:255',
            'kode_anggaran' => 'nullable|integer',
            'transaksi' => 'required|string|max:255',
            'ref' => 'nullable|string|max:100',
            'debit' => 'required|integer',
            'kredit' => 'required|integer',
        ]);

        // kalau ada error kembalikan error
        if ($validator->fails()) {
            $validatorErrors = implode('<br>', $validator->errors()->all());
            return redirect()->back()->with('validatorFail', $validatorErrors);
        }

        try {
            DB::beginTransaction();
    
            // Temukan entitas Kas dengan id 1 atau lemparkan exception jika tidak ditemukan
            $totalKas = Kas::findOrFail(1);

            // Periksa apakah nilai debit lebih besar dari 0
            if ($request->debit > 0) {
                // Jalankan rumus untuk debit
                $totalKas->kas = $totalKas->kas - $cashflow->debit + $request->debit;
            } else {
                // Jika debit diubah menjadi 0, kurangi kembali nilai debit yang sebelumnya dikurangi
                $totalKas->kas = $totalKas->kas - $cashflow->debit;
            }

            // Periksa apakah nilai kredit lebih besar dari 0
            if ($request->kredit > 0) {
                // Jalankan rumus untuk kredit
                $totalKas->kas = $totalKas->kas + $cashflow->kredit - $request->kredit;
            } else {
                // Jika kredit diubah menjadi 0, tambahkan kembali nilai kredit yang sebelumnya ditambahkan
                $totalKas->kas = $totalKas->kas + $cashflow->kredit;
            }

            // Simpan perubahan ke dalam database
            $totalKas->save();


            $cashflow->tanggal = $request->tanggal;
            $cashflow->no_bukti = $request->no_bukti;
            $cashflow->pic = $request->pic;
            $cashflow->nama = $request->nama;
            $cashflow->kode_anggaran = $request->kode_anggaran;
            $cashflow->transaksi = $request->transaksi;
            $cashflow->ref = $request->ref;
            $cashflow->debit = $request->debit;
            $cashflow->kredit = $request->kredit;
    
            $cashflow->save();
    
            DB::commit();

            return redirect('/lapcashflow?start_date='.$request->start_date.'&end_date='.$request->end_date)->with('updateSuccess', 'Data berhasil di Update');
    
        } catch(Exception $e) {
            DB::rollBack();
            // dd($e->getMessage());
            return redirect()->back()->with('updateFail', 'Data gagal di Update');
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
    
            // Cari data cashflow berdasarkan ID
            $cashflow = CashFlow::find($id);

            // Update uang_kas accordingly
            $totalKas = Kas::findOrFail("1");
            if ($cashflow->debit > 0) {
                $totalKas->kas = $totalKas->kas - $cashflow->debit;
            }
            if ($cashflow->kredit > 0) {
                $totalKas->kas = $totalKas->kas + $cashflow->kredit;
            }
            $totalKas->save();
    
            // Hapus data cashflow
            $cashflow->delete();
    
            DB::commit();
    
            return redirect()->back()->with('deleteSuccess', 'Data berhasil dihapus!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('deleteFail', $e->getMessage());
        }
    }    
}
