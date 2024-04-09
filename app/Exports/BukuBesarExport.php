<?php

namespace App\Exports;

use App\Models\Jurnal;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize; // Add ShouldAutoSize

class BukuBesarExport implements FromView, WithHeadings, ShouldAutoSize
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
        $bukubesars = Jurnal::query()
            ->whereYear('periode_jurnal', $this->selectedYear)
            ->whereMonth('periode_jurnal', $this->selectedMonth)
            ->get();

        return view('menu.jurnal.export', [
            'bukubesars' => $bukubesars,
        ]);
    }

    public function headings(): array
    {
        return [
            'Periode',
            'Tipe Jurnal',
            'Uraian',
            'Divisi',
            'Kode Akun',
            'Nama Akun',
            'No Bukti',
            'Debit',
            'Kredit',
            'Ket. RKAT',
        ];
    }
}


