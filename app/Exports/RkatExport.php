<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize; // Add ShouldAutoSize
use App\Models\Rkat;
use App\Models\JurnalAkun;

class RkatExport implements FromView, WithHeadings, ShouldAutoSize
{
    protected $periode;

    public function __construct($periode)
    {
        $this->periode = $periode;
    }

    public function view(): View
    {
        $rkats = Rkat::query()
            ->when($this->periode, function ($query) {
                $query->where('periode', $this->periode);
            })
            ->with('jurnalAkun')
            ->get();

        return view('master.rkat.export', [
            'rkats' => $rkats,
        ]);
    }

    public function headings(): array
    {
        return [
            'ID RKAT',
            'Kode RKAT',
            'Keterangan',
            'No Akun',
            'Nama Akun',
            'Periode',
        ];
    }
}


