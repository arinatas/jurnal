<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\Importable;
use App\Models\Jurnal;

class KasKeluarImport implements ToModel, WithHeadingRow, WithValidation
{
    use Importable;

    protected $user;
    protected $totalDebit = 0;
    protected $totalKredit = 0;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function model(array $row)
    {
        // Logic to insert data into the Jurnal model
        $debit = $row['debit'];
        $kredit = $row['kredit'];

        $this->totalDebit += $debit;
        $this->totalKredit += $kredit;

        return new Jurnal([
            'periode_jurnal' => $row['periode_jurnal'],
            'type_jurnal' => $row['type_jurnal'],
            'keterangan_rkat' => $row['keterangan_rkat'],
            'divisi' => $row['divisi'],
            'kode_akun' => $row['kode_akun'],
            'uraian' => $row['uraian'],
            'no_bukti' => $row['no_bukti'],
            'debit' => $debit,
            'kredit' => $kredit,
            'created_by' => $this->user->id,
            'asal_input' => 2,
        ]);
    }

    public function rules(): array
    {
        return [
            // Validation rules
            'periode_jurnal' => 'required|date_format:Y-m-d',
            'type_jurnal' => 'required|string|max:100',
            'keterangan_rkat' => 'nullable|string|max:100',
            'divisi' => 'required|integer',
            'kode_akun' => 'required|string',
            'uraian' => 'required|string',
            'no_bukti' => 'required|string|max:100',
            'debit' => 'required|integer',
            'kredit' => 'required|integer',
        ];
    }

    public function getTotalDebit(): int
    {
        return $this->totalDebit;
    }

    public function getTotalKredit(): int
    {
        return $this->totalKredit;
    }
}


