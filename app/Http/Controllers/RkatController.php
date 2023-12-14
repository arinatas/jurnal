<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\JurnalAkun;
use App\Models\Rkat;
use Illuminate\Support\Facades\Hash;
use App\Imports\RkatImport;
use Maatwebsite\Excel\Facades\Excel;

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
            $validatorErrors = implode('<br>', $validator->errors()->all());
            return redirect()->back()->with('validatorFail', $validatorErrors);
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
            $validatorErrors = implode('<br>', $validator->errors()->all());
            return redirect()->back()->with('validatorFail', $validatorErrors);
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

    public function showImportForm()
    {
        return view('import'); // Menampilkan tampilan untuk mengunggah file Excel
    }

    public function importExcel(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'excel_file' => 'required|mimes:xls,xlsx',
        ]);
    
        if ($validator->fails()) {
            $validatorErrors = implode('<br>', $validator->errors()->all());
            return redirect()->back()->with('validatorFail', $validatorErrors);
        }
    
        $file = $request->file('excel_file');

        // Validasi Data duplikat atau dengan email & bulan & tahun yang sama sebelum di impor
        $import = new RkatImport;
        $rows = Excel::toCollection($import, $file)->first();

        $duplicateEntries = [];

        foreach ($rows as $row) {
            $kode_rkat = $row['kode_rkat'];
            $periode = $row['periode'];

            // Periksa apakah kombinasi email, bulan, dan tahun sudah ada di database
            if (Rkat::where('kode_rkat', $kode_rkat)->where('periode', $periode)->exists()) {
                $duplicateEntries[] = "Kode RKAT: $kode_rkat, Periode: $periode";
            }
        }

        if (!empty($duplicateEntries)) {
            $errorMessage = 'Data Kode RKAT dengan Periode yang sama sudah ada:';
            foreach ($duplicateEntries as $entry) {
                $errorMessage .= "$entry";
            }

            return redirect()->back()->with('importError', $errorMessage);
        }
        // END Validasi Data duplikat atau dengan email & bulan & tahun yang sama sebelum di impor
    
        DB::beginTransaction(); // Memulai transaksi database
    
        try {
            Excel::import($import, $file);
    
            DB::commit(); // Jika tidak ada kesalahan, lakukan commit untuk menyimpan perubahan ke database
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            DB::rollBack(); // Rollback jika terjadi kesalahan validasi
            $failures = $e->failures();
            $errorMessages = [];
    
            foreach ($failures as $failure) {
                $rowNumber = $failure->row();
                $column = $failure->attribute();
                $errorMessages[] = "Baris $rowNumber, Kolom $column: " . implode(', ', $failure->errors());
            }
            // Simpan detail kesalahan validasi dalam sesi
            return redirect()->back()
                ->with('importValidationFailures', $failures)
                ->with('importErrors', $errorMessages)
                ->withInput();
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback jika terjadi kesalahan umum selama impor
            return redirect()->back()->with('importError', 'Terjadi kesalahan selama impor. Silakan coba lagi.');
        }
    
        return redirect()->back()->with('importSuccess', 'Data berhasil diimpor.');
    }

    public function downloadExampleExcel()
    {
        $filePath = public_path('contoh-excel/rkat.xlsx'); // Sesuaikan dengan path file Excel contoh Anda
    
        if (file_exists($filePath)) {
            $headers = [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ];
    
            return response()->download($filePath, 'rkat.xlsx', $headers);
        } else {
            return redirect()->back()->with('downloadFail', 'File contoh tidak ditemukan.');
        }
    } 
    
}
