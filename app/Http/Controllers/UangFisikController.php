<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\UangFisik;
use App\Models\UangFisikDetail;
use App\Models\PecahanUang;

class UangFisikController extends Controller
{
    public function index()
    {
        $uangFisik = UangFisik::all();
        $pecahanUang = PecahanUang::where('status', 1)
                    ->orderBy('pecahan', 'desc')
                    ->get();

        // ddd($pecahanUang);
            return view('menu.uang_fisik.index', [
                'title' => 'Uang Fisik',
                'section' => 'Menu',
                'active' => 'Uang Fisik',
                'uangFisik' => $uangFisik,
                'pecahanUang' => $pecahanUang,
            ]);
    }

    public function store(Request $request)
    {
        // ambil pecahan untuk validasi request yg msk
        $pecahanUang = PecahanUang::where('status', 1)
                    ->orderBy('pecahan', 'desc')
                    ->get();
        
        $validateData = [];
        $validateData['tanggal'] = 'required|date';

        // lakukan perulangan dan masukan ke dalam array yg telah disediakan karena di validator ga bisa di foreach makanya di foreach diluar
        foreach ($pecahanUang as $item) {
            // Lakukan sesuatu dengan setiap elemen $item
            $validateData[$item->id . '_id_' . $item->pecahan] = 'required';
        }
                    
        // validasi input yang didapatkan dari request
        $validator = Validator::make($request->all(), $validateData);

        // kalau ada error kembalikan error
        if ($validator->fails()) {
            // return redirect()->back()->withErrors($validator)->withInput();
            return redirect()->back()->with('insertFail', 'Datanya Harus Lengkap Woy');
        }

        // ambil data dari request
        $data = $request->all();

        // ambil id pecahan uang
        $id_pecahan_uang = [];
        $jumlahUang = [];
        
        foreach ($data as $key => $value) {
            // Pecah kunci dengan delimiter "_"
            $keyParts = explode('_', $key);

            // Periksa apakah kunci memiliki format yang diharapkan
            if (count($keyParts) === 3 && is_numeric($keyParts[0])) {
                // Ambil angka di awal kunci dan tambahkan ke array $id_pecahan_uang
                $id_pecahan_uang[] = $keyParts[0];

                // Ambil angka setelah "_id_" dan tambahkan ke array $jumlahUang
                $jumlahUang[] = $keyParts[2] * $value;
            }
        }

        // mentotal seluruh jumlah uang yang ada dalam array jumlah uang
        $totalJumlahUang = array_sum($jumlahUang);

        // simpan input value dari user
        $inputJumlahUang = [];

        foreach ($data as $key => $value) {
            // Cek apakah kunci bukan "_token" atau "tanggal"
            if ($key !== "_token" && $key !== "tanggal") {
                // Ambil nilai dan simpan ke dalam array baru
                $inputJumlahUang[] = $value;
            }
        }

        // gabungkan seluruh array yang telah di proses untuk dimasukan ke tabel uang fisik detail
        $uangFisikDetails = [];
        for ($i = 0; $i < count($id_pecahan_uang); $i++) {
            $uangFisikDetails[] = [
                'id_pecahan_uang' => $id_pecahan_uang[$i],
                'jumlah' => $inputJumlahUang[$i],
            ];
        }

        // kalau jurnalDetail null tampilkan pesan error
        if ($uangFisikDetails == null) {
            return redirect()->back()->with('insertFail', 'Datanya ada yang kurang Woy');
        }

        // Simpan data ke database
        // mulai try catch untuk menangkap error jika terjadi error pada saat penginputan database
        try{
            DB::beginTransaction();
            // cek jika ada file upload

            // insert data pada tabel t_jurnal
            $uangFisik = UangFisik::create([
                'tanggal' => $request->tanggal,
                'total' => $totalJumlahUang,
            ]);

            // insert data pada tabel t_jurnal_detail
            $uangFisik->uangFisikDetails()->createMany($uangFisikDetails);

            DB::commit();

            return redirect('/uangFisik')->with('insertSuccess', 'Created successfully.');

        } catch (Exception $e) {
            DB::rollBack();
            // dd($e->getMessage());
            return redirect()->back()->with('insertFail', 'Failed to create.');
        }

        // // Mencari data menggunakan dua klausa where
        // $duplicateData = PecahanUang::where('jenis_uang', $request->jenis_uang)
        //                 ->where('pecahan', $pecahan)
        //                 ->first();

        // // cek jika ada data jenis pecahan dengan jumlah dan jenis yg sama maka lempar
        // if ($duplicateData) {
        //     return redirect()->back()->with('updateFail', 'Datanya udah ada Woy');
        // } else {
        //     // simpan data ke database
        //     try {
        //         DB::beginTransaction();
    
        //         // insert ke tabel positions
        //         PecahanUang::create([
        //             'jenis_uang' => $request->jenis_uang,
        //             'pecahan' => $pecahan,
        //             'status' => $request->status
        //         ]);
    
        //         DB::commit();
    
        //         return redirect()->back()->with('insertSuccess', 'Data berhasil di Inputkan');
    
        //     } catch(Exception $e) {
        //         DB::rollBack();
        //         // dd($e->getMessage());
        //         return redirect()->back()->with('insertFail', $e->getMessage());
        //     }
        // }

    }

    // public function edit($id)
    // {
    //     $pecahanUang = PecahanUang::find($id);

    //     if (!$pecahanUang) {
    //         return redirect()->back()->with('dataNotFound', 'Data tidak ditemukan');
    //     }

    //     return view('master.pecahan_uang.edit', [
    //         'title' => 'Uang Fisik',
    //         'secction' => 'Master',
    //         'active' => 'Uang Fisik',
    //         'pecahanUang' => $pecahanUang,
    //     ]);
    // }

    // public function update(Request $request, $id)
    // {
    //     $pecahanUang = PecahanUang::find($id);

    //     if (!$pecahanUang) {
    //         return redirect()->back()->with('dataNotFound', 'Data tidak ditemukan');
    //     }

    //     // validasi input yang didapatkan dari request
    //     $validator = Validator::make($request->all(), [
    //         'jenis_uang' => 'required|integer|between:0,1',
    //         'pecahan' => 'required',
    //         'status' => 'required|integer|between:0,1'
    //     ]);

    //     // kalau ada error kembalikan error
    //     if ($validator->fails()) {
    //         return redirect()->back()->withErrors($validator)->withInput();
    //     }

    //     // Menghilangkan karakter non-digit
    //     $pecahan = (int)preg_replace('/[^0-9]/', '', $request->pecahan);

    //     // Mencari data menggunakan dua klausa where
    //     $duplicateData = PecahanUang::where('jenis_uang', $request->jenis_uang)
    //                     ->where('pecahan', $pecahan)
    //                     ->first();

    //     // cek jika ada data jenis pecahan dengan jumlah dan jenis yg sama maka lempar
    //     if ($duplicateData) {
    //         return redirect()->back()->with('updateFail', 'Datanya udah ada Woy');
    //     } else {
    //         // simpan data ke database
    //         try {
    //             DB::beginTransaction();
    
    //             $pecahanUang->jenis_uang = $request->jenis_uang;
    //             $pecahanUang->pecahan = $pecahan ;
    //             $pecahanUang->status = $request->status;
    
    //             $pecahanUang->save();
    
    //             DB::commit();

    //             return redirect('/pecahan')->with('updateSuccess', 'Data berhasil di Update');
    
    //         } catch(Exception $e) {
    //             DB::rollBack();
    //             // dd($e->getMessage());
    //             return redirect()->back()->with('updateFail', 'Data gagal di Update');
    //         }
    //     }
    // }

    // public function destroy($id)
    // {
    //     // Cari data pengguna berdasarkan ID
    //     $pecahanUang = PecahanUang::find($id);

    //     try {
    //         // Hapus data pengguna
    //         $pecahanUang->delete();
    //         return redirect()->back()->with('deleteSuccess', 'Data berhasil dihapus!');
    //     } catch (\Exception $e) {
    //         return redirect()->back()->with('deleteFail', $e->getMessage());
    //     }
    // }

}
