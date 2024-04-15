<?php

namespace App\Exports;

use App\Models\Jurnal;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize; // Add ShouldAutoSize

class BukuBesarExportJurnal implements FromView, WithHeadings, ShouldAutoSize
{
    protected $selectedYear;
    protected $selectedMonth;
    protected $selectedJurnalAccount;

    public function __construct($selectedYear, $selectedMonth, $selectedJurnalAccount)
    {
        $this->selectedYear = $selectedYear;
        $this->selectedMonth = $selectedMonth;
        $this->selectedJurnalAccount = $selectedJurnalAccount;
    }

    public function view(): View
    {
        $query = Jurnal::query()
            ->whereYear('periode_jurnal', $this->selectedYear)
            ->whereMonth('periode_jurnal', $this->selectedMonth);

        if ($this->selectedJurnalAccount !== null) {
            $query->where('kode_akun', $this->selectedJurnalAccount);
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


