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

class CashFlowController extends Controller
{
    public function index()
    {
        $today = now()->format('Y-m-d'); // Get the current date in 'Y-m-d' format

        $cashflows = CashFlow::with(['user:id,nama', 'rkat:id,kode_rkat'])
            ->whereDate('tanggal', $today)
            ->get();

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
            'kode_anggaran' => 'required|integer',
            'transaksi' => 'required|string|max:255',
            'ref' => 'required|string|max:100',
            'debit' => 'required|integer',
            'kredit' => 'required|integer',
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
            $cashFlow = CashFlow::create([
                'tanggal' => $request->tanggal,
                'no_bukti' => $request->no_bukti,
                'pic' => $request->pic,
                'kode_anggaran' => $request->kode_anggaran,
                'transaksi' => $request->transaksi,
                'ref' => $request->ref,
                'debit' => $request->debit,
                'kredit' => $request->kredit,
                'id_accounting' => $user->id
            ]);
    
            // Check if debit or kredit is greater than 0 and update uang_kas accordingly
            if ($cashFlow->debit > 0) {
                Kas::increment('kas', $cashFlow->debit);
            } elseif ($cashFlow->kredit > 0) {
                Kas::decrement('kas', $cashFlow->kredit);
            }
    
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
    
        // Execute the query
        $cashflows = $cashflowsQuery->get();
    
        // Calculate total debit and total kredit
        $totalDebit = $cashflows->sum('debit');
        $totalKredit = $cashflows->sum('kredit');
    
        // Get the list of kode_rkat options
        $rkatOptions = Rkat::pluck('kode_rkat', 'id');
        $rkatDescriptions = Rkat::pluck('keterangan', 'id');
    
        return view('menu.cashflow.laporan', [
            'title' => 'Laporan Cash Flow',
            'section' => 'Menu',
            'active' => 'Laporan Cash Flow',
            'cashflows' => $cashflows,
            'rkatOptions' => $rkatOptions,
            'rkatDescriptions' => $rkatDescriptions,
            'totalDebit' => $totalDebit,
            'totalKredit' => $totalKredit,
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
    
            return view('menu.cashflow.printlaporan', [
                'title' => 'Laporan Cash Flow',
                'section' => 'Menu',
                'active' => 'Laporan Cash Flow',
                'cashflows' => $cashflows,
                'rkatOptions' => $rkatOptions,
                'rkatDescriptions' => $rkatDescriptions,
                'totalDebit' => $totalDebit,
                'totalKredit' => $totalKredit,
            ]);
        }
    
}
