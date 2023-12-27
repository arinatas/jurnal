<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Neraca;
use Carbon\Carbon;


class NeracaController extends Controller
{
    public function index()
    {
        $neraca = Neraca::all();

        // Pisahkan berdasarkan type_neraca
        $aktiva = $neraca->where('type_neraca', 'AKTIVA');
        $kasDanBank = $aktiva->where('sub_type', 'Kas & Bank');
        $piutang = $aktiva->where('sub_type', 'Piutang');
        $asetTidakLancar = $aktiva->where('sub_type', 'Aset Tidak Lancar');

        $passiva = $neraca->where('type_neraca', 'PASSIVA');
        $lljPendek = $passiva->where('sub_type', 'Liabilitas Jangka Pendek');
        $lljPanjang = $passiva->where('sub_type', 'Liabilitas Jangka Panjang');

        $ekuitas = $neraca->where('type_neraca', 'EKUITAS');

        // total asset lancar
        $totalKasDanBank = $kasDanBank->sum('total_neraca');
        $totalPiutang = $piutang->sum('total_neraca');
        $subTotalAsetLancar = $totalKasDanBank + $totalPiutang;

        // total liabilitas
        $totalLLJPendek = $lljPendek->sum('total_neraca');
        $totalLLJPanjang = $lljPanjang->sum('total_neraca');
        $subTotalLiabilitas = $totalLLJPendek + $totalLLJPanjang;

        // total asset tidak lancar
        $subTotalAsetTidakLancar = $asetTidakLancar->sum('total_neraca');

        // total ekuitas
        $subTotalEkuitas = $ekuitas->sum('total_neraca');

        // Grand Total Asset
        $grandTotalAsset = $subTotalAsetLancar + $subTotalAsetTidakLancar;
        $grandTotalLiabilDanEkuitas = $subTotalLiabilitas - $subTotalEkuitas;


            return view('menu.neraca.index', [
                'title' => 'Laporan Neraca',
                'section' => 'Menu',
                'active' => 'Laporan Neraca',
                'kasDanBank' => $kasDanBank,
                'piutang' => $piutang,
                'asetTidakLancar' => $asetTidakLancar,
                'lljPendek' => $lljPendek,
                'lljPanjang' => $lljPanjang,
                'ekuitas' => $ekuitas,
                'subTotalAsetLancar' => $subTotalAsetLancar,
                'subTotalLiabilitas' => $subTotalLiabilitas,
                'subTotalAsetTidakLancar' => $subTotalAsetTidakLancar,
                'subTotalEkuitas' => $subTotalEkuitas,
                'grandTotalAsset' => $grandTotalAsset,
                'grandTotalLiabilDanEkuitas' => $grandTotalLiabilDanEkuitas,
            ]);
    }
    
    public function printNeraca()
    {

        $neraca = Neraca::all();

        // Pisahkan berdasarkan type_neraca
        $aktiva = $neraca->where('type_neraca', 'AKTIVA');
        $kasDanBank = $aktiva->where('sub_type', 'Kas & Bank');
        $piutang = $aktiva->where('sub_type', 'Piutang');
        $asetTidakLancar = $aktiva->where('sub_type', 'Aset Tidak Lancar');

        $passiva = $neraca->where('type_neraca', 'PASSIVA');
        $lljPendek = $passiva->where('sub_type', 'Liabilitas Jangka Pendek');
        $lljPanjang = $passiva->where('sub_type', 'Liabilitas Jangka Panjang');

        $ekuitas = $neraca->where('type_neraca', 'EKUITAS');

        // total asset lancar
        $totalKasDanBank = $kasDanBank->sum('total_neraca');
        $totalPiutang = $piutang->sum('total_neraca');
        $subTotalAsetLancar = $totalKasDanBank + $totalPiutang;

        // total liabilitas
        $totalLLJPendek = $lljPendek->sum('total_neraca');
        $totalLLJPanjang = $lljPanjang->sum('total_neraca');
        $subTotalLiabilitas = $totalLLJPendek + $totalLLJPanjang;

        // total asset tidak lancar
        $subTotalAsetTidakLancar = $asetTidakLancar->sum('total_neraca');

        // total ekuitas
        $subTotalEkuitas = $ekuitas->sum('total_neraca');

        // Grand Total Asset
        $grandTotalAsset = $subTotalAsetLancar + $subTotalAsetTidakLancar;
        $grandTotalLiabilDanEkuitas = $subTotalLiabilitas - $subTotalEkuitas;

            return view('print.neraca', [
                'title' => 'Laporan Neraca',
                'section' => 'Menu',
                'active' => 'Laporan Neraca',
                'kasDanBank' => $kasDanBank,
                'piutang' => $piutang,
                'asetTidakLancar' => $asetTidakLancar,
                'lljPendek' => $lljPendek,
                'lljPanjang' => $lljPanjang,
                'ekuitas' => $ekuitas,
                'subTotalAsetLancar' => $subTotalAsetLancar,
                'subTotalLiabilitas' => $subTotalLiabilitas,
                'subTotalAsetTidakLancar' => $subTotalAsetTidakLancar,
                'subTotalEkuitas' => $subTotalEkuitas,
                'grandTotalAsset' => $grandTotalAsset,
                'grandTotalLiabilDanEkuitas' => $grandTotalLiabilDanEkuitas,
            ]);
    }

}
