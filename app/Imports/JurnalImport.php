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
            'id_rkat' => $row['id_rkat'],
            'uraian' => $row['uraian'],
            'no_bukti' => $row['no_bukti'],
            'debit' => $debit,
            'kredit' => $kredit,
            'created_by' => $this->user->id,
        ]);
    }

    public function rules(): array
    {
        return [
            // Validation rules
            'periode_jurnal' => 'required|date_format:Y-m-d',
            'type_jurnal' => 'required|string|max:100',
            'id_rkat' => 'required|integer',
            'uraian' => 'required|string|max:255',
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

// class JurnalImport implements ToModel, WithHeadingRow, WithValidation
// {
//     use Importable;

//     protected $user;

//     public function __construct($user)
//     {
//         $this->user = $user;
//     }

//     public function model(array $row)
//     {
//         // Logic to insert data into the Jurnal model
//         return new Jurnal([
//             'periode_jurnal' => $row['periode_jurnal'],
//             'type_jurnal' => $row['type_jurnal'],
//             'id_rkat' => $row['id_rkat'],
//             'uraian' => $row['uraian'],
//             'no_bukti' => $row['no_bukti'],
//             'debit' => $row['debit'],
//             'kredit' => $row['kredit'],
//             'created_by' => $this->user->id,
//         ]);
//     }

//     public function rules(): array
//     {
//         return [
//             // Validation rules
//             'periode_jurnal' => 'required|date_format:Y-m-d',
//             'type_jurnal' => 'required|string|max:100',
//             'id_rkat' => 'required|integer',
//             'uraian' => 'required|string|max:255',
//             'no_bukti' => 'required|string|max:100',
//             'debit' => 'required|integer',
//             'kredit' => 'required|integer',
//         ];
//     }
// }

