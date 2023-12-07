<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\JurnalAkun;
use App\Models\Rkat;
use Illuminate\Support\Facades\Hash;

class RkatController extends Controller
{
    public function index(Request $request)
    {
        // Ambil data RKAT dengan relasi JurnalAkun
        $rkats = Rkat::with('jurnalAkun');

        // Ambil data periode unik dari tabel RKAT
        $periodes = Rkat::distinct('periode')->pluck('periode');
        // Menerima input periode
        $periodeFilter = $request->input('periode');
        
        // Filter berdasarkan periode jika disediakan
        if ($periodeFilter) {
            $rkats->where('periode', $periodeFilter);
        }
        // Dapatkan data RKAT setelah penerapan filter
        $filteredRkats = $rkats->get();
    
        // Ambil data untuk select options
        $jurnalAkunOptions = JurnalAkun::pluck('nama_akun', 'no_akun');
    
        return view('master.rkat.index', [
            'title' => 'RKAT',
            'section' => 'Master',
            'active' => 'RKAT',
            'rkats' => $filteredRkats,
            'jurnalAkunOptions' => $jurnalAkunOptions,
            'periodes' => $periodes,
            'periodeFilter' => $periodeFilter,
        ]);
    }    

    public function store(Request $request)
    {
        // validasi input yang didapatkan dari request
        $validator = Validator::make($request->all(), [
            'kode_rkat' => 'required|string|max:100',
            'no_akun' => 'required|string|max:100',
            'keterangan' => 'required|string|max:255',
            'periode' => 'required|string|max:100',
        ]);
    
        // kalau ada error kembalikan error
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Check if kode_rkat already exists for the given periode
        $existingRkat = Rkat::where('kode_rkat', $request->kode_rkat)
        ->where('periode', $request->periode)
        ->exists();

        // If kode_rkat already exists, show an error message
        if ($existingRkat) {
            $errorMessage = 'Kode RKAT ' . $request->kode_rkat . ' sudah digunakan dalam periode yang sama. Silakan gunakan Kode RKAT lain.';
            return redirect()->back()->with('insertFail', $errorMessage);
        }
    
        // simpan data ke database
        try {
            DB::beginTransaction();
    
            // insert ke tabel positions
            Rkat::create([
                'kode_rkat' => $request->kode_rkat,
                'no_akun' => $request->no_akun,
                'keterangan' => $request->keterangan,
                'periode' => $request->periode,
            ]);
    
            DB::commit();
    
            return redirect()->back()->with('insertSuccess', 'Data berhasil di Inputkan');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('insertFail', $e->getMessage());
        }
    }

    public function edit($id)
    {
        $rkat = Rkat::find($id);
        // Ambil data untuk select options
        $jurnalAkunOptions = JurnalAkun::pluck('nama_akun', 'no_akun');

        if (!$rkat) {
            return redirect()->back()->with('dataNotFound', 'Data tidak ditemukan');
        }

        return view('master.rkat.edit', [
            'title' => 'RKAT',
            'section' => 'Master',
            'active' => 'RKAT',
            'rkat' => $rkat,
            'jurnalAkunOptions' => $jurnalAkunOptions,
        ]);
    }

    public function update(Request $request, $id)
    {
        $rkat = Rkat::find($id);

        if (!$rkat) {
            return redirect()->back()->with('dataNotFound', 'Data tidak ditemukan');
        }

        // validasi input yang didapatkan dari request
        $validator = Validator::make($request->all(), [
            'kode_rkat' => 'required|string|max:100',
            'no_akun' => 'required|string|max:100',
            'keterangan' => 'required|string|max:255',
            'periode' => 'required|string|max:100',
        ]);

        // kalau ada error kembalikan error
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Check if kode_rkat already exists for the given periode excluding the current record
        $existingRkat = Rkat::where('kode_rkat', $request->kode_rkat)
            ->where('periode', $request->periode)
            ->where('id', '!=', $id) // Exclude the current record
            ->exists();

        // If kode_rkat already exists, show an error message
        if ($existingRkat) {
            $errorMessage = 'Kode RKAT ' . $request->kode_rkat . ' sudah digunakan dalam periode yang sama. Silakan gunakan Kode RKAT lain.';
            return redirect()->back()->with('updateFail', $errorMessage);
        }

        try{
            $rkat->kode_rkat = $request->kode_rkat;
            $rkat->no_akun = $request->no_akun;
            $rkat->keterangan = $request->keterangan;
            $rkat->periode = $request->periode;

            $rkat->save();

            return redirect('/rkat')->with('updateSuccess', 'Data berhasil di Update');
        } catch(Exception $e) {
            dd($e);
            return redirect()->back()->with('updateFail', 'Data gagal di Update');
        }
    }

    public function destroy($id)
    {
        // Cari data pengguna berdasarkan ID
        $position = Rkat::find($id);

        try {
            // Hapus data pengguna
            $position->delete();
            return redirect()->back()->with('deleteSuccess', 'Data berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()->with('deleteFail', $e->getMessage());
        }
    }
    

}
