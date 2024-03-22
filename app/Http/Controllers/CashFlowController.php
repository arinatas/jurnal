<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\CashFlow;
use App\Models\Kas;
use App\Models\User;
use App\Models\Rkat;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Exports\CashFlowExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

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
            'nama' => 'required|string|max:255',
            'kode_anggaran' => 'required|integer',
            'transaksi' => 'required|string|max:255',
            'ref' => 'required|string|max:100',
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
            } elseif ($cashFlow->kredit > 0) {
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
    
}
