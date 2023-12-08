<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Piutang;
use App\Models\TotalPiutang;
use App\Models\UangFisik;
use App\Models\Kas;
use Carbon\Carbon;


class CompareController extends Controller
{
    public function index()
    {
        $totalKas = Kas::findOrFail("1");
        $totalKasbon = TotalPiutang::findOrFail("1");

        // ambil tanggal hari ini
        $dateNow = Carbon::now()->toDateString();
        $uangFisikToday = UangFisik::where('tanggal', $dateNow)
                    ->first();

        $totalKasDitangan = $totalKas->kas - $totalKasbon->total_piutang;

        if($uangFisikToday) {
            $totalUangFisik = $uangFisikToday->total;
        } else {
            $totalUangFisik = 0;
        }

        $selisih = $totalKasDitangan - $totalUangFisik;

            return view('menu.compare.index', [
                'title' => 'Laporan Compare',
                'section' => 'Menu',
                'active' => 'Laporan Compare',
                'totalKas' => $totalKas->kas,
                'totalKasbon' => $totalKasbon->total_piutang,
                'uangFisikToday' => $uangFisikToday,
                'totalUangFisik' => $totalUangFisik,
                'totalKasDitangan' => $totalKasDitangan,
                'selisih' => $selisih,

            ]);
    }
    
    public function printCompare()
    {
        $totalKas = Kas::findOrFail("1");
        $totalKasbon = TotalPiutang::findOrFail("1");

        // ambil tanggal hari ini
        $dateNow = Carbon::now()->toDateString();
        $uangFisikToday = UangFisik::where('tanggal', $dateNow)
                    ->first();

        $totalKasDitangan = $totalKas->kas - $totalKasbon->total_piutang;

        if($uangFisikToday) {
            $totalUangFisik = $uangFisikToday->total;
        } else {
            $totalUangFisik = 0;
        }

        $selisih = $totalKasDitangan - $totalUangFisik;

            return view('print.compare', [
                'title' => 'Laporan Compare',
                'section' => 'Menu',
                'active' => 'Laporan Compare',
                'totalKas' => $totalKas->kas,
                'totalKasbon' => $totalKasbon->total_piutang,
                'uangFisikToday' => $uangFisikToday,
                'totalUangFisik' => $totalUangFisik,
                'totalKasDitangan' => $totalKasDitangan,
                'selisih' => $selisih,

            ]);
    }

}
