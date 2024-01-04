<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow; // row 1 sebagai heading
use Maatwebsite\Excel\Concerns\WithValidation; // validasi string / integer
use Maatwebsite\Excel\Concerns\Importable;
use App\Models\Rkat;

class RkatImport implements ToModel, WithHeadingRow, WithValidation // Gunakan WithHeadingRow
{
    use Importable;

    public function model(array $row)
    {
        // Logika untuk memasukkan data ke dalam model Tendik
        return new Rkat([
            'kode_rkat' => $row['kode_rkat'],
            'no_akun' => $row['no_akun'],
            'keterangan' => $row['keterangan'],
            'periode' => $row['periode']
        ]);
    }

    public function rules(): array
    {
        return [
            // Sesuaikan aturan validasi dengan kunci (header) yang Anda tetapkan
            'kode_rkat' => 'required',
            'no_akun' => 'required|max:100',
            'keterangan' => 'required|string',
            'periode' => 'required|string|max:100',
        ];
    }
}
