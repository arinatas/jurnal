<?php

namespace App\Exports;

use App\Models\Neraca;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize; // Add ShouldAutoSize

class NeracaExport implements FromView, WithHeadings, ShouldAutoSize
{
    protected $selectedYear;
    protected $selectedMonth;

    public function __construct($selectedYear, $selectedMonth)
    {
        $this->selectedYear = $selectedYear;
        $this->selectedMonth = $selectedMonth;
    }

    public function view(): View
    {
        $neraca = Neraca::all();

        return view('menu.neraca.export', [
            'neraca' => $neraca,
        ]);
    }

    public function headings(): array
    {
        return [
            'No Akun',
            'Nama Akun',
            'Type Neraca',
            'Sub Type',
            'Tipe Akun',
            'Periode Jurnal',
            'Total Debit',
            'Total Kredit',
            'Total Neraca',
        ];
    }
}


