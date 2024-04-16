<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\LockJurnal;
use Illuminate\Support\Facades\Hash;

class LockJurnalController extends Controller
{
    public function index()
    {
        // $lockJurnals = LockJurnal::all();
        // $lockJurnals = LockJurnal::orderBy('tahun')->orderBy('bulan')->get();
        $lockJurnals = LockJurnal::orderByDesc('tahun')->orderByDesc('bulan')->paginate(10);
            return view('menu.lock_jurnal.index', [
                'title' => 'Lock Jurnal',
                'section' => 'Menu',
                'active' => 'Lock Jurnal',
                'lockJurnals' => $lockJurnals,
            ]);
    }

    public function store(Request $request)
    {
        // validasi input yang didapatkan dari request
        $validator = Validator::make($request->all(), [
            'bulan' => 'required|string|max:100',
            'tahun' => 'required|string|max:100',
            'status' => 'required|string|max:100'
        ]);

        // kalau ada error kembalikan error
        if ($validator->fails()) {
            $validatorErrors = implode('<br>', $validator->errors()->all());
            return redirect()->back()->with('validatorFail', $validatorErrors);
        }

        // simpan data ke database
        try {
            DB::beginTransaction();
    
            // insert ke tabel positions
            LockJurnal::create([
                'bulan' => $request->bulan,
                'tahun' => $request->tahun,
                'status' => $request->status,
            ]);
    
            DB::commit();
    
            return redirect()->back()->with('insertSuccess', 'Data berhasil di Inputkan');
    
        } catch(Exception $e) {
            DB::rollBack();
            // dd($e->getMessage());
            return redirect()->back()->with('insertFail', $e->getMessage());
        }

    }

    public function edit($id)
    {
        $lockJurnal = LockJurnal::find($id);

        if (!$lockJurnal) {
            return redirect()->back()->with('dataNotFound', 'Data tidak ditemukan');
        }

        return view('menu.lock_jurnal.edit', [
            'title' => 'Lock Jurnal',
            'secction' => 'Menu',
            'active' => 'Lock Jurnal',
            'lockJurnal' => $lockJurnal,
        ]);
    }

    public function update(Request $request, $id)
    {
        $lockJurnal = LockJurnal::find($id);

        if (!$lockJurnal) {
            return redirect()->back()->with('dataNotFound', 'Data tidak ditemukan');
        }

        // validasi input yang didapatkan dari request
        $validator = Validator::make($request->all(), [
            'bulan' => 'required|string|max:100',
            'tahun' => 'required|string|max:100',
            'status' => 'required|string|max:100'
        ]);

        // kalau ada error kembalikan error
        if ($validator->fails()) {
            $validatorErrors = implode('<br>', $validator->errors()->all());
            return redirect()->back()->with('validatorFail', $validatorErrors);
        }

        try {
            DB::beginTransaction();
    
            $lockJurnal->bulan = $request->bulan;
            $lockJurnal->tahun = $request->tahun;
            $lockJurnal->status = $request->status;
    
            $lockJurnal->save();
    
            DB::commit();

            return redirect('/lockJurnal')->with('updateSuccess', 'Data berhasil di Update');
    
        } catch(Exception $e) {
            DB::rollBack();
            // dd($e->getMessage());
            return redirect()->back()->with('updateFail', 'Data gagal di Update');
        }
    }

    public function destroy($id)
    {
        // Cari data pengguna berdasarkan ID
        $lockJurnal = LockJurnal::find($id);

        try {
            // Hapus data pengguna
            $lockJurnal->delete();
            return redirect()->back()->with('deleteSuccess', 'Data berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()->with('deleteFail', $e->getMessage());
        }
    }

}
