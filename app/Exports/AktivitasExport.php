<?php

namespace App\Exports;

use App\Models\Jurnal;
use App\Models\CashFlow;
use App\Models\JurnalAkun;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize; // Add ShouldAutoSize

class AktivitasExport implements FromView, WithHeadings, ShouldAutoSize
{
    protected $sections;
    protected $selectedYear;
    protected $selectedMonth;

    public function __construct($sections, $selectedYear, $selectedMonth)
    {
        $this->sections = $sections;
        $this->selectedYear = $selectedYear;
        $this->selectedMonth = $selectedMonth;
    }

    public function view(): View
    {
        $data = [];
        foreach ($this->sections as $sectionKey => $parentId) {
            $jurnalAkun = JurnalAkun::where('parent', $parentId)->get();
            $amounts = $this->calculateCreditAmounts($jurnalAkun, $this->selectedYear, $this->selectedMonth);
            $data[$sectionKey] = $jurnalAkun;
            $data[$sectionKey . 'Amounts'] = $amounts;
        }
        return view('menu.aktivitas.export', $data);
    }

    private function calculateCreditAmounts($jurnalAkuns, $selectedYear = null, $selectedMonth = null)
    {
        $creditAmounts = [];

        foreach ($jurnalAkuns as $item) {
            $creditAmount = Jurnal::whereHas('akun', function ($query) use ($item) {
                $query->where('no_akun', $item->no_akun);
            })
                ->whereYear('periode_jurnal', $selectedYear)
                ->whereMonth('periode_jurnal', $selectedMonth)
                ->sum('debit');

            $creditAmounts[$item->no_akun] = $creditAmount;
        }

        return $creditAmounts;
    }

    public function headings(): array
    {
        return [
            'No Akun',
            'Nama Akun',
            'Jumlah',
        ];
    }
}


