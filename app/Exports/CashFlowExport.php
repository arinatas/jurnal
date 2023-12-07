<?php

namespace App\Exports;

use App\Models\CashFlow;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize; // Add ShouldAutoSize

class CashFlowExport implements FromView, WithHeadings, ShouldAutoSize
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function view(): View
    {
        $cashflows = CashFlow::query()
            ->when($this->startDate && $this->endDate, function ($query) {
                $query->whereBetween('tanggal', [$this->startDate, $this->endDate]);
            })
            ->with(['user:id,nama', 'rkat:id,kode_rkat'])
            ->get();

        return view('menu.cashflow.export', [
            'cashflows' => $cashflows,
        ]);
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'No Bukti',
            'PIC',
            'Nama',
            'Kode Anggaran',
            'Transaksi',
            'Ref',
            'Debit',
            'Kredit',
            'ID Accounting',
        ];
    }
}


