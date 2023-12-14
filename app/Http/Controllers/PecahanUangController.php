<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\PecahanUang;
use Illuminate\Support\Facades\Hash;

class PecahanUangController extends Controller
{
    public function index()
    {
        $pecahanUang = PecahanUang::orderBy('pecahan', 'desc')->get();
            return view('master.pecahan_uang.index', [
                'title' => 'Pecahan Uang',
                'section' => 'Master',
                'active' => 'Pecahan Uang',
                'pecahanUang' => $pecahanUang,
            ]);
    }

    public function store(Request $request)
    {
        // validasi input yang didapatkan dari request
        $validator = Validator::make($request->all(), [
            'jenis_uang' => 'required|integer|between:0,1',
            'pecahan' => 'required',
            'status' => 'required|integer|between:0,1'
        ]);

        // kalau ada error kembalikan error
        if ($validator->fails()) {
            $validatorErrors = implode('<br>', $validator->errors()->all());
            return redirect()->back()->with('validatorFail', $validatorErrors);
        }

        // Menghilangkan karakter non-digit
        $pecahan = (int)preg_replace('/[^0-9]/', '', $request->pecahan);

        // Mencari data menggunakan dua klausa where
        $duplicateData = PecahanUang::where('jenis_uang', $request->jenis_uang)
                        ->where('pecahan', $pecahan)
                        ->first();

        // cek jika ada data jenis pecahan dengan jumlah dan jenis yg sama maka lempar
        if ($duplicateData) {
            return redirect()->back()->with('updateFail', 'Datanya udah ada Woy');
        } else {
            // simpan data ke database
            try {
                DB::beginTransaction();
    
                // insert ke tabel positions
                PecahanUang::create([
                    'jenis_uang' => $request->jenis_uang,
                    'pecahan' => $pecahan,
                    'status' => $request->status
                ]);
    
                DB::commit();
    
                return redirect()->back()->with('insertSuccess', 'Data berhasil di Inputkan');
    
            } catch(Exception $e) {
                DB::rollBack();
                // dd($e->getMessage());
                return redirect()->back()->with('insertFail', $e->getMessage());
            }
        }

    }

    public function edit($id)
    {
        $pecahanUang = PecahanUang::find($id);

        if (!$pecahanUang) {
            return redirect()->back()->with('dataNotFound', 'Data tidak ditemukan');
        }

        return view('master.pecahan_uang.edit', [
            'title' => 'Pecahan Uang',
            'secction' => 'Master',
            'active' => 'Pecahan Uang',
            'pecahanUang' => $pecahanUang,
        ]);
    }

    public function update(Request $request, $id)
    {
        $pecahanUang = PecahanUang::find($id);

        if (!$pecahanUang) {
            return redirect()->back()->with('dataNotFound', 'Data tidak ditemukan');
        }

        // validasi input yang didapatkan dari request
        $validator = Validator::make($request->all(), [
            'jenis_uang' => 'required|integer|between:0,1',
            'pecahan' => 'required',
            'status' => 'required|integer|between:0,1'
        ]);

        // kalau ada error kembalikan error
        if ($validator->fails()) {
            $validatorErrors = implode('<br>', $validator->errors()->all());
            return redirect()->back()->with('validatorFail', $validatorErrors);
        }

        // Menghilangkan karakter non-digit
        $pecahan = (int)preg_replace('/[^0-9]/', '', $request->pecahan);

        // Mencari data menggunakan dua klausa where
        $duplicateData = PecahanUang::where('jenis_uang', $request->jenis_uang)
                        ->where('pecahan', $pecahan)
                        ->where('status', $request->status)
                        ->first();

        // cek jika ada data jenis pecahan dengan jumlah dan jenis yg sama maka lempar
        if ($duplicateData) {
            return redirect()->back()->with('updateFail', 'Datanya udah ada Woy');
        } else {
            // simpan data ke database
            try {
                DB::beginTransaction();
    
                $pecahanUang->jenis_uang = $request->jenis_uang;
                $pecahanUang->pecahan = $pecahan ;
                $pecahanUang->status = $request->status;
    
                $pecahanUang->save();
    
                DB::commit();

                return redirect('/pecahan')->with('updateSuccess', 'Data berhasil di Update');
    
            } catch(Exception $e) {
                DB::rollBack();
                // dd($e->getMessage());
                return redirect()->back()->with('updateFail', 'Data gagal di Update');
            }
        }
    }

    public function destroy($id)
    {
        // Cari data pengguna berdasarkan ID
        $pecahanUang = PecahanUang::find($id);

        try {
            // Hapus data pengguna
            $pecahanUang->delete();
            return redirect()->back()->with('deleteSuccess', 'Data berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()->with('deleteFail', $e->getMessage());
        }
    }

}
