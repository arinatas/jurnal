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


class JurnalController extends Controller
{
    public function index()
    {
        $jurnals = Jurnal::with('rkat:id,kode_rkat')->with('jurnalAkun')->get();
    
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
}
