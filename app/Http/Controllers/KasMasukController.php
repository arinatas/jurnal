<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Jurnal;
use App\Models\JurnalAkun;
use App\Models\Divisi;
use Illuminate\Support\Facades\Auth;
use App\Imports\JurnalImport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class KasMasukController extends Controller
{
    public function index()
    {
        // Get the current date
        $today = Carbon::now()->format('Y-m-d');
    
        // Fetch Jurnal entries created today
        $jurnals = Jurnal::with('divisi')
            ->with('jurnalAkun')
            ->whereDate('created_at', $today)
            ->paginate(10); // Menambahkan pagination untuk 50 data perhalaman

        // Calculate total debit and total kredit
        // Fetch Jurnal entries created today without pagination
        $jurnalsAll = Jurnal::with('divisi')
            ->with('jurnalAkun')
            ->whereDate('created_at', $today)
            ->get();

        $totalDebit = $jurnalsAll->sum('debit');
        $totalKredit = $jurnalsAll->sum('kredit');
    
        // Get the list of kode_rkat options
        $jurnalAkunOptions = JurnalAkun::pluck('nama_akun', 'no_akun');
    
        return view('menu.kas_masuk.index', [
            'title' => 'Kas Masuk',
            'section' => 'Menu',
            'active' => 'Kas Masuk',
            'jurnals' => $jurnals,
            'jurnalAkunOptions' => $jurnalAkunOptions,
            'totalDebit' => $totalDebit,
            'totalKredit' => $totalKredit,
        ]);
    }    

    public function input()
    {
        // Fetch Jurnal yang diinput 
        $jurnals = Jurnal::with('divisi')
            ->with('jurnalAkun')
            ->get();
        
        // Mengambil data kode akun dan nama akun untuk option form
        $jurnalAkunOptions = JurnalAkun::pluck('no_akun', 'no_akun');
        $jurnalAkunDesc = JurnalAkun::pluck('nama_akun', 'no_akun');

        // Mengambil data divisi untuk option form
        $dataDivisi = Divisi::pluck('nama_divisi', 'id');

        // Mengambil data kode akun dan nama akun dengan subtype "kas"
        $jurnalAkunOptionsKas = JurnalAkun::where('sub_type', 'kas & bank')->pluck('no_akun', 'no_akun');
        $jurnalAkunDescKas = JurnalAkun::where('sub_type', 'kas & bank')->pluck('nama_akun', 'no_akun');

        return view('menu.kas_masuk.inputKasMasuk', [
            'title' => 'Input Kas Masuk',
            'section' => 'Menu',
            'active' => 'Input Kas Masuk',
            'jurnals' => $jurnals,
            'jurnalAkunOptions' => $jurnalAkunOptions,
            'jurnalAkunDesc' => $jurnalAkunDesc,
            'dataDivisi' => $dataDivisi,
            'jurnalAkunOptionsKas' => $jurnalAkunOptionsKas,
            'jurnalAkunDescKas' => $jurnalAkunDescKas,
        ]);
    }

    public function storeKasMasuk(Request $request)
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

