<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\JurnalAkun;
use Illuminate\Support\Facades\Hash;

class JurnalAkunController extends Controller
{
    public function index()
    {
        $jurnalakuns = JurnalAkun::all();
            return view('master.jurnal_akun.index', [
                'title' => 'Jurnal Akun',
                'section' => 'Master',
                'active' => 'Jurnal Akun',
                'jurnalakuns' => $jurnalakuns,
            ]);
    }

    public function store(Request $request)
    {
        // validasi input yang didapatkan dari request
        $validator = Validator::make($request->all(), [
            'no_akun' => 'required|string|max:100',
            'parent' => 'required|string|max:100',
            'nama_akun' => 'required|string|max:255',
            'type_neraca' => 'required|string|max:30',
            'lvl' => 'required|integer',
            'tipe_akun' => 'required|integer',
        ]);
    
        // kalau ada error kembalikan error
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    
        // check if no_akun already exists
        $existingNoAkun = JurnalAkun::where('no_akun', $request->no_akun)->exists();

        if ($existingNoAkun) {
            $errorMessage = 'No Akun ' . $request->no_akun . ' sudah digunakan. Silakan gunakan No Akun lain.';
            return redirect()->back()->with('insertFail', $errorMessage);
        }
    
        // simpan data ke database
        try {
            DB::beginTransaction();
    
            // insert ke tabel positions
            JurnalAkun::create([
                'no_akun' => $request->no_akun,
                'parent' => $request->parent,
                'nama_akun' => $request->nama_akun,
                'type_neraca' => $request->type_neraca,
                'lvl' => $request->lvl,
                'tipe_akun' => $request->tipe_akun,
            ]);
    
            DB::commit();
    
            return redirect()->back()->with('insertSuccess', 'Data berhasil di Inputkan');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('insertFail', $e->getMessage());
        }
    }
    

}
