<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Piutang;
use App\Models\TotalPiutang;
use Illuminate\Support\Facades\Hash;

class PiutangController extends Controller
{
    public function index()
    {
        $piutang = Piutang::all();
        $totalPiutang = TotalPiutang::findOrFail("1");
            return view('menu.piutang.index', [
                'title' => 'Piutang',
                'section' => 'Menu',
                'active' => 'Piutang',
                'piutang' => $piutang,
                'totalPiutang' => $totalPiutang->total_piutang,
            ]);
    }

    public function storePiutang(Request $request) {
        
        // validasi input yang didapatkan dari request
        $validator = Validator::make($request->all(), [
            'tanggal' => 'required|date',
            'nama' => 'required|string',
            'jumlah_piutang' => 'required',
            'keterangan' => 'required|string',
            'id_total_piutang' => 'required',
            'id_accounting' => 'required',
            'stts_reallisasi' => 'required',
        ]);

        // kalau ada error kembalikan error
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Menghilangkan karakter non-digit
        $jumlah_piutang = (int)preg_replace('/[^0-9]/', '', $request->jumlah_piutang);

        // Simpan data ke database
        // mulai try catch untuk menangkap error jika terjadi error pada saat penginputan database
        try{
            DB::beginTransaction();
            // cek jika ada file upload
           
            // insert data pada tabel t_jurnal
            $piutang = Piutang::create([
                'tanggal' => $request->tanggal,
                'nama' => $request->nama,
                'jumlah_piutang' => $jumlah_piutang,
                'keterangan' => $request->keterangan,
                'id_total_piutang' => $request->id_total_piutang,
                'id_accounting' => $request->id_accounting,
                'stts_reallisasi' => $request->stts_reallisasi,
            ]);

            $totalPiutang = TotalPiutang::findOrFail("1");
            $totalPiutang->total_piutang = $totalPiutang->total_piutang + $jumlah_piutang;
            $totalPiutang->save();

            DB::commit();

            return redirect('/piutang')->with('insertSuccess', 'Request created successfully.');

        } catch (Exception $e) {
            DB::rollBack();
            // dd($e->getMessage());
            return redirect()->back()->with('insertFail', 'Failed to create request.');
        }
    }

}
