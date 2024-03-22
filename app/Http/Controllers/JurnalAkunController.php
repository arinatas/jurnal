<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
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
            'type_neraca' => [
                'required',
                'string',
                'max:30',
                Rule::in(['AKTIVA', 'PASIVA', 'LIABILITAS', 'EKUITAS', 'LABA-RUGI']),
            ],
            'sub_type' => [
                'string',
                'max:30',
                Rule::in(['Kas & Bank', 'Piutang', 'Liabilitas Jangka Pendek', 'Liabilitas Jangka Panjang', 'Pendapatan', 'Beban Sehubungan Program', 'Pendapatan Lain-Lain', 'Beban Marketing', 'Beban Kegiatan', 'Beban Gaji', 'Beban Operasional Kantor', 'Beban Rumah Tangga Kantor', 'Beban Sewa', 'Beban Perawatan', 'Beban Yayasan', 'Beban Lain-Lain']),
            ],
            'lvl' => 'required|integer',
            'tipe_akun' => 'required',
        ]);
    
        // kalau ada error kembalikan error
        if ($validator->fails()) {
            $validatorErrors = implode('<br>', $validator->errors()->all());
            return redirect()->back()->with('validatorFail', $validatorErrors);
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
                'sub_type' => $request->sub_type,
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

    public function edit($id)
    {
        $jurnalakun = JurnalAkun::find($id);

        if (!$jurnalakun) {
            return redirect()->back()->with('dataNotFound', 'Data tidak ditemukan');
        }

        return view('master.jurnal_akun.edit', [
            'title' => 'Jurnal Akun',
            'section' => 'Master',
            'active' => 'Jurnal Akun',
            'jurnalakun' => $jurnalakun,
        ]);
    }

    public function update(Request $request, $id)
    {
        $jurnalakun = JurnalAkun::find($id);

        if (!$jurnalakun) {
            return redirect()->back()->with('dataNotFound', 'Data tidak ditemukan');
        }

        // validasi input yang didapatkan dari request
        $validator = Validator::make($request->all(), [
            'no_akun' => 'required|string|max:100',
            'parent' => 'required|string|max:100',
            'nama_akun' => 'required|string|max:255',
            'type_neraca' => [
                'required',
                'string',
                'max:30',
                Rule::in(['AKTIVA', 'PASIVA', 'LIABILITAS', 'EKUITAS', 'LABA-RUGI']),
            ],
            'sub_type' => [
                'string',
                'max:30',
                Rule::in(['Kas & Bank', 'Piutang', 'Liabilitas Jangka Pendek', 'Liabilitas Jangka Panjang', 'Pendapatan', 'Beban Sehubungan Program', 'Pendapatan Lain-Lain', 'Beban Marketing', 'Beban Kegiatan', 'Beban Gaji', 'Beban Operasional Kantor', 'Beban Rumah Tangga Kantor', 'Beban Sewa', 'Beban Perawatan', 'Beban Yayasan', 'Beban Lain-Lain']),
            ],
            'lvl' => 'required|integer',
            'tipe_akun' => 'required',
        ]);

        // kalau ada error kembalikan error
        if ($validator->fails()) {
            $validatorErrors = implode('<br>', $validator->errors()->all());
            return redirect()->back()->with('validatorFail', $validatorErrors);
        }

        try{
            $jurnalakun->no_akun = $request->no_akun;
            $jurnalakun->parent = $request->parent;
            $jurnalakun->nama_akun = $request->nama_akun;
            $jurnalakun->type_neraca = $request->type_neraca;
            $jurnalakun->sub_type = $request->sub_type;
            $jurnalakun->lvl = $request->lvl;
            $jurnalakun->tipe_akun = $request->tipe_akun;

            $jurnalakun->save();

            return redirect('/jurnalakun')->with('updateSuccess', 'Data berhasil di Update');
        } catch(Exception $e) {
            dd($e);
            return redirect()->back()->with('updateFail', 'Data gagal di Update');
        }
    }

    public function destroy($id)
    {
        // Cari data pengguna berdasarkan ID
        $position = JurnalAkun::find($id);

        try {
            // Hapus data pengguna
            $position->delete();
            return redirect()->back()->with('deleteSuccess', 'Data berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()->with('deleteFail', $e->getMessage());
        }
    }
    

}
