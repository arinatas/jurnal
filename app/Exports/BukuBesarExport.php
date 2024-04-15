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
    protected $selectedDivisi;

    public function __construct($selectedYear, $selectedMonth, $selectedDivisi)
    {
        $this->selectedYear = $selectedYear;
        $this->selectedMonth = $selectedMonth;
        $this->selectedDivisi = $selectedDivisi;
    }

    public function view(): View
    {
        $query = Jurnal::query()
            ->whereYear('periode_jurnal', $this->selectedYear)
            ->whereMonth('periode_jurnal', $this->selectedMonth);

        if ($this->selectedDivisi !== null) {
            $query->where('divisi', $this->selectedDivisi);
        }

        $bukubesars = $query->get();


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


