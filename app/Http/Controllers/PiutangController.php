<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Piutang;
use App\Models\TotalPiutang;
use App\Models\Kas;
use Illuminate\Support\Facades\Hash;

class PiutangController extends Controller
{
    public function index()
    {
        $piutang = Piutang::where('stts_reallisasi', 0)->paginate(10);
        $totalPiutang = TotalPiutang::findOrFail("1");
            return view('menu.piutang.index', [
                'title' => 'Piutang',
                'section' => 'Menu',
                'active' => 'Piutang',
                'piutang' => $piutang,
                'totalPiutang' => $totalPiutang->total_piutang,
            ]);
    }
    
    public function printPiutang()
    {
        $piutang = Piutang::where('stts_reallisasi', 0)->get();
        $totalPiutang = TotalPiutang::findOrFail("1");
            return view('print.piutang', [
                'title' => 'Piutang',
                'section' => 'Menu',
                'active' => 'Piutang',
                'piutang' => $piutang,
                'totalPiutang' => $totalPiutang->total_piutang,
            ]);
    }

    public function riwayatPiutang(){
        $piutang = Piutang::where('stts_reallisasi', 1)
        ->orderBy('tanggal', 'desc')
        ->paginate(10);
            return view('menu.piutang.riwayat', [
                'title' => 'Riwayat Piutang',
                'section' => 'Menu',
                'active' => 'Piutang',
                'piutang' => $piutang,

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

            // menambahkan piutang ke tabel total piutang
            $totalPiutang = TotalPiutang::findOrFail("1");
            $totalPiutang->total_piutang = $totalPiutang->total_piutang + $jumlah_piutang;
            $totalPiutang->save();
            
            // mengurangi jumlah kas se jumlah piutang
            $totalKas = Kas::findOrFail("1");
            $totalKas->kas = $totalKas->kas - $jumlah_piutang;
            $totalKas->save();

            DB::commit();

            return redirect('/piutang')->with('insertSuccess', 'Request created successfully.');

        } catch (Exception $e) {
            DB::rollBack();
            // dd($e->getMessage());
            return redirect()->back()->with('insertFail', 'Failed to create request.');
        }
    }

    public function realisasiPiutang($id){
        $piutang = Piutang::find($id);
    
        if (!$piutang) {
            return redirect()->back()->with('dataNotFound', 'Data tidak ditemukan');
        }
        if ($piutang->stts_reallisasi == 1) {
            return redirect()->back()->with('dataNotFound', 'Data Telah Ter-realisasi');
        }

        return view('menu.piutang.realisasi', [
            'title' => 'Piutang',
            'section' => 'Menu',
            'active' => 'Piutang',
            'piutang' => $piutang,
        ]);
    }

    public function realisasi(Request $request, $id){
        $piutang = Piutang::find($id);
    
        if (!$piutang) {
            return redirect()->back()->with('dataNotFound', 'Data tidak ditemukan');
        }
        if ($piutang->stts_reallisasi == 1) {
            return redirect()->back()->with('dataNotFound', 'Data Telah Ter-realisasi');
        }

        // validasi input yang didapatkan dari request
        $validator = Validator::make($request->all(), [
            'stts_reallisasi' => 'required|integer|between:0,1',
            'jumlah_piutang' => 'required'
        ]);

        // kalau ada error kembalikan error
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $jumlah_piutang = (int)$request->jumlah_piutang;

        try{
            DB::beginTransaction();
            
            $piutang->stts_reallisasi = $request->stts_reallisasi;
            $piutang->save();

            // mengurangi jumlah piutang pada tabel piutang
            $totalPiutang = TotalPiutang::findOrFail("1");
            $totalPiutang->total_piutang = $totalPiutang->total_piutang - $jumlah_piutang;
            $totalPiutang->save();
            
            // menambahkan ke kas sejumlah piutang (dan nanti untuk pengurangannya bakal di catat di cashflow)
            $totalKas = Kas::findOrFail("1");
            $totalKas->kas = $totalKas->kas + $jumlah_piutang;
            $totalKas->save();

            DB::commit();

            return redirect('/piutang')->with('updateSuccess', 'Data berhasil di Update');
        } catch(Exception $e) {
            DB::rollBack();

            dd($e);
            return redirect()->back()->with('updateFail', 'Data gagal di Update');
        }
    }

}
