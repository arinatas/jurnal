<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\Importable;
use App\Models\Jurnal;

class JurnalImport implements ToModel, WithHeadingRow, WithValidation
{
    use Importable;

    public function model(array $row)
    {
        // Logika untuk memasukkan data ke dalam model Jurnal
        return new Jurnal([
            'periode_jurnal' => $row['periode_jurnal'],
            'type_jurnal' => $row['type_jurnal'],
            'id_rkat' => $row['id_rkat'],
            'uraian' => $row['uraian'],
            'no_bukti' => $row['no_bukti'],
            'debit' => $row['debit'],
            'kredit' => $row['kredit'],
            'created_by' => $row['created_by']
        ]);
    }

    public function rules(): array
    {
        return [
            // Sesuaikan aturan validasi dengan kunci (header) yang Anda tetapkan
            'periode_jurnal' => 'required|date_format:Y-m-d',
            'type_jurnal' => 'required|string|max:100',
            'id_rkat' => 'required|integer',
            'uraian' => 'required|string|max:255',
            'no_bukti' => 'required|string|max:100',
            'debit' => 'required|integer',
            'kredit' => 'required|integer',
            'created_by' => 'required|integer'
        ];
    }
}
