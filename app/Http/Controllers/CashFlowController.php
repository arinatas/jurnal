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
        $cashflows = CashFlow::with(['user:id,nama', 'rkat:id,kode_rkat'])->get();
        $rkatOptions = Rkat::pluck('kode_rkat', 'id'); // Get the list of kode_rkat options
        $rkatDescriptions = Rkat::pluck('keterangan', 'id');
        
        return view('menu.cashflow.index', [
            'title' => 'Cash Flow',
            'section' => 'Menu',
            'active' => 'Cash Flow',
            'cashflows' => $cashflows,
            'rkatOptions' => $rkatOptions,
            'rkatDescriptions' => $rkatDescriptions,
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
    
}
