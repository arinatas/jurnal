<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Divisi;
use Illuminate\Support\Facades\Hash;

class DivisiController extends Controller
{
    public function index()
    {
        $divisis = Divisi::all();
            return view('master.divisi.index', [
                'title' => 'Divisi',
                'section' => 'Master',
                'active' => 'Divisi',
                'divisis' => $divisis,
            ]);
    }

    public function store(Request $request)
    {
        // validasi input yang didapatkan dari request
        $validator = Validator::make($request->all(), [
            'nama_divisi' => 'required|string|max:255'
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
            Divisi::create([
                'nama_divisi' => $request->nama_divisi,
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
        $divisi = Divisi::find($id);

        if (!$divisi) {
            return redirect()->back()->with('dataNotFound', 'Data tidak ditemukan');
        }

        return view('master.divisi.edit', [
            'title' => 'Divisi',
            'secction' => 'Master',
            'active' => 'Divisi',
            'divisi' => $divisi,
        ]);
    }

    public function update(Request $request, $id)
    {
        $divisi = Divisi::find($id);

        if (!$divisi) {
            return redirect()->back()->with('dataNotFound', 'Data tidak ditemukan');
        }

        // validasi input yang didapatkan dari request
        $validator = Validator::make($request->all(), [
            'nama_divisi' => 'required|string|max:100'
        ]);

        // kalau ada error kembalikan error
        if ($validator->fails()) {
            $validatorErrors = implode('<br>', $validator->errors()->all());
            return redirect()->back()->with('validatorFail', $validatorErrors);
        }

        try {
            DB::beginTransaction();
    
            $divisi->nama_divisi = $request->nama_divisi;
    
            $divisi->save();
    
            DB::commit();

            return redirect('/divisi')->with('updateSuccess', 'Data berhasil di Update');
    
        } catch(Exception $e) {
            DB::rollBack();
            // dd($e->getMessage());
            return redirect()->back()->with('updateFail', 'Data gagal di Update');
        }
    }

    public function destroy($id)
    {
        // Cari data pengguna berdasarkan ID
        $divisi = Divisi::find($id);

        try {
            // Hapus data pengguna
            $divisi->delete();
            return redirect()->back()->with('deleteSuccess', 'Data berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()->with('deleteFail', $e->getMessage());
        }
    }

}
