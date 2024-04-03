<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\Importable;
use App\Models\CashFlow;

class CashFlowImport implements ToModel, WithHeadingRow, WithValidation
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

        return new CashFlow([
            'tanggal' => $row['tanggal'],
            'no_bukti' => $row['no_bukti'],
            'pic' => $row['pic'],
            'transaksi' => $row['transaksi'],
            'debit' => $debit,
            'kredit' => $kredit,
            'id_accounting' => $this->user->id,
        ]);
    }

    public function rules(): array
    {
        return [
            // Validation rules
            'tanggal' => 'required|date_format:Y-m-d',
            'no_bukti' => 'required|string|max:100',
            'pic' => 'nullable|string|max:255',
            'transaksi' => 'required|string|max:255',
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

