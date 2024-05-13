<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Jurnal;
use App\Models\Neraca;
use App\Models\NeracaKredit;
use Illuminate\Http\Request;
use App\Exports\NeracaExport;
use Illuminate\Support\Facades\DB;
use App\Exports\NeracaExportFilter;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;


class NeracaController extends Controller
{
    public function index(Request $request)
    {
        // Get the unique years from the "periode_jurnal" field
        $years = Jurnal::distinct()->select(DB::raw('YEAR(periode_jurnal) as year'))->pluck('year');

        // Get the selected year and month from the request
        $selectedYear = $request->input('tahun');
        $selectedMonth = $request->input('bulan');

        // $neracaQuery = Neraca::all();
        $neracaQuery = Neraca::query();

        if ($selectedYear) {
            $neracaQuery->whereYear('periode_jurnal', $selectedYear);
        }

        if ($selectedMonth) {
            $neracaQuery->whereMonth('periode_jurnal', $selectedMonth);
        }

        $neraca = $neracaQuery->get();

        // get data dari view neraca kredit
        $neracaKreditQuery = NeracaKredit::query();

        if ($selectedYear) {
            $neracaKreditQuery->whereYear('periode_jurnal', $selectedYear);
        }

        if ($selectedMonth) {
            $neracaKreditQuery->whereMonth('periode_jurnal', $selectedMonth);
        }

        $neracaKredit = $neracaKreditQuery->get();

        // Define a reusable function untuk build query data jurnal untuk menghitung aktivitas total
        function getJurnalsQuery($paretData) {
            return Jurnal::with('akun')
                ->whereHas('akun', function ($query) {
                    $query->whereColumn('jurnal.kode_akun', 'jurnal_akun.no_akun');
                })
                ->whereHas('akun', function ($query) use ($paretData) {
                    $query->where('parent', '=', $paretData);
                });
        }

        // Get jurnals berdasarkan data where
        $pendapatanAll = getJurnalsQuery(4)->get();
        $bebanSehubunganProgramAll = getJurnalsQuery(5)->get();
        $pendapatanLainLainAll = getJurnalsQuery(7)->get();
        $bebanMarketingAll = getJurnalsQuery(601)->get();
        $bebanKegiatanAll = getJurnalsQuery(602)->get();
        $bebanGajiAll = getJurnalsQuery(603)->get();
        $bebanOperasionalKantorAll = getJurnalsQuery(604)->get();
        $bebanRumahTanggaKantorAll = getJurnalsQuery(605)->get();
        $bebanSewaAll = getJurnalsQuery(606)->get();
        $bebanPerawatanAll = getJurnalsQuery(607)->get();
        $bebanYayasanAll = getJurnalsQuery(608)->get();
        $bebanLainlainAll = getJurnalsQuery(609)->get();
        $pajakAll = getJurnalsQuery(610)->get();
        $depresiasiAll = getJurnalsQuery(611)->get();

        // Calculate sum data jurnal
        $pendapatanSumKredit = $pendapatanAll->sum('kredit');
        $pendapatanSumDebit = $pendapatanAll->sum('debit');
        $totPendapatan = $pendapatanSumKredit - $pendapatanSumDebit;

        $bebanSehubunganProgramSumKredit = $bebanSehubunganProgramAll->sum('kredit');
        $bebanSehubunganProgramSumDebit = $bebanSehubunganProgramAll->sum('debit');
        $totbebanSehubunganProgram = $bebanSehubunganProgramSumDebit - $bebanSehubunganProgramSumKredit;

        $pendapatanLainLainSumKredit = $pendapatanLainLainAll->sum('kredit');
        $pendapatanLainLainSumDebit = $pendapatanLainLainAll->sum('debit');
        $totPendapatanLainLain = $pendapatanLainLainSumKredit - $pendapatanLainLainSumDebit;

        $bebanMarketingSumKredit = $bebanMarketingAll->sum('kredit');
        $bebanMarketingSumDebit = $bebanMarketingAll->sum('debit');
        $totBebanMarketing = $bebanMarketingSumDebit - $bebanMarketingSumKredit;

        $bebanKegiatanSumKredit = $bebanKegiatanAll->sum('kredit');
        $bebanKegiatanSumDebit = $bebanKegiatanAll->sum('debit');
        $totBebanKegiatanSumKredit = $bebanKegiatanSumDebit - $bebanKegiatanSumKredit;

        $bebanGajiSumKredit = $bebanGajiAll->sum('kredit');
        $bebanGajiSumDebit = $bebanGajiAll->sum('debit');
        $totbebanGaji = $bebanGajiSumDebit - $bebanGajiSumKredit;

        $bebanOperasionalKantorSumKredit = $bebanOperasionalKantorAll->sum('kredit');
        $bebanOperasionalKantorSumDebit = $bebanOperasionalKantorAll->sum('debit');
        $totbebanOperasionalKantor = $bebanOperasionalKantorSumDebit - $bebanOperasionalKantorSumKredit;

        $bebanRumahTanggaKantorSumKredit = $bebanRumahTanggaKantorAll->sum('kredit');
        $bebanRumahTanggaKantorSumDebit = $bebanRumahTanggaKantorAll->sum('debit');
        $totbebanRumahTanggaKantor = $bebanRumahTanggaKantorSumDebit - $bebanRumahTanggaKantorSumKredit;

        $bebanSewaSumKredit = $bebanSewaAll->sum('kredit');
        $bebanSewaSumDebit = $bebanSewaAll->sum('debit');
        $totbebanSewa = $bebanSewaSumDebit - $bebanSewaSumKredit;

        $bebanPerawatanSumKredit = $bebanPerawatanAll->sum('kredit');
        $bebanPerawatanSumDebit = $bebanPerawatanAll->sum('debit');
        $totbebanPerawatan = $bebanPerawatanSumDebit - $bebanPerawatanSumKredit;

        $bebanYayasanSumKredit = $bebanYayasanAll->sum('kredit');
        $bebanYayasanSumDebit = $bebanYayasanAll->sum('debit');
        $totbebanYayasan = $bebanYayasanSumDebit - $bebanYayasanSumKredit;

        $bebanLainlainSumKredit = $bebanLainlainAll->sum('kredit');
        $bebanLainlainSumDebit = $bebanLainlainAll->sum('debit');
        $totbebanLainlain = $bebanLainlainSumDebit - $bebanLainlainSumKredit;

        $pajakSumKredit = $pajakAll->sum('kredit');
        $pajakSumDebit = $pajakAll->sum('debit');
        $totpajak = $pajakSumDebit - $pajakSumKredit;

        $depresiasiSumKredit = $depresiasiAll->sum('kredit');
        $depresiasiSumDebit = $depresiasiAll->sum('debit');
        $totdepresiasi = $depresiasiSumDebit - $depresiasiSumKredit;

        $labaKotor = $totPendapatan - $totbebanSehubunganProgram;
        $allBeban = $totBebanMarketing + $totBebanKegiatanSumKredit + $totbebanGaji + $totbebanOperasionalKantor + $totbebanRumahTanggaKantor + $totbebanSewa + $totbebanPerawatan + $totbebanYayasan;
        $labaRugiSebelumBunga = $labaKotor + $totPendapatanLainLain - $allBeban;
        $labaRugiSebelumPajak = $labaRugiSebelumBunga - $totbebanLainlain;
        $labaRugiSebelumDepresiasi = $labaRugiSebelumPajak - $totpajak;
        $kenaikanPenurunanAsetNettoTidakTerikat = $labaRugiSebelumDepresiasi - $totdepresiasi;
        // dd($kenaikanPenurunanAsetNettoTidakTerikat);



        // Pisahkan berdasarkan type_neraca
        $aktiva = $neraca->where('type_neraca', 'AKTIVA');
        $kasDanBank = $aktiva->where('sub_type', 'Kas & Bank');
        $piutang = $aktiva->where('sub_type', 'Piutang');
        $asetTidakLancar = $aktiva->where('sub_type', 'Aset Tidak Lancar');

        $passiva = $neracaKredit->where('type_neraca', 'PASSIVA');
        $lljPendek = $passiva->where('sub_type', 'Liabilitas Jangka Pendek');
        $lljPanjang = $passiva->where('sub_type', 'Liabilitas Jangka Panjang');

        $ekuitas = $neracaKredit->where('type_neraca', 'EKUITAS');

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
        $subTotalEkuitasAktivitas = $subTotalEkuitas + $kenaikanPenurunanAsetNettoTidakTerikat;

        // Grand Total Asset
        $grandTotalAsset = $subTotalAsetLancar + $subTotalAsetTidakLancar;
        $grandTotalLiabilDanEkuitas = $subTotalLiabilitas + $subTotalEkuitas;
        $grandTotalLiabilDanEkuitasAktivitas = $subTotalLiabilitas + $subTotalEkuitasAktivitas;

        // dd($subTotalEkuitas);

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
            'years' => $years,
            'selectedYear' => $selectedYear,
            'selectedMonth' => $selectedMonth,
            'kenaikanPenurunanAsetNettoTidakTerikat' => $kenaikanPenurunanAsetNettoTidakTerikat,
            'subTotalEkuitasAktivitas' => $subTotalEkuitasAktivitas,
            'grandTotalLiabilDanEkuitasAktivitas' => $grandTotalLiabilDanEkuitasAktivitas
        ]);
    }

    public function printNeracaBlnThn($selectedYear, $selectedMonth)
    {

        // $neraca = Neraca::all();
        $neracaQuery = Neraca::query();

        if ($selectedYear) {
            $neracaQuery->whereYear('periode_jurnal', $selectedYear);
        }

        if ($selectedMonth) {
            $neracaQuery->whereMonth('periode_jurnal', $selectedMonth);
        }

        $neraca = $neracaQuery->get();

         // get data neraca passiva
        $neracaKreditQuery = NeracaKredit::query();

        if ($selectedYear) {
            $neracaKreditQuery->whereYear('periode_jurnal', $selectedYear);
        }

        if ($selectedMonth) {
            $neracaKreditQuery->whereMonth('periode_jurnal', $selectedMonth);
        }

        $neracaKredit = $neracaKreditQuery->get();


        // Define a reusable function untuk build query data jurnal untuk menghitung aktivitas total
        function getJurnalsQuery($paretData) {
            return Jurnal::with('akun')
                ->whereHas('akun', function ($query) {
                    $query->whereColumn('jurnal.kode_akun', 'jurnal_akun.no_akun');
                })
                ->whereHas('akun', function ($query) use ($paretData) {
                    $query->where('parent', '=', $paretData);
                });
        }

        // Get jurnals berdasarkan data where
        $pendapatanAll = getJurnalsQuery(4)->get();
        $bebanSehubunganProgramAll = getJurnalsQuery(5)->get();
        $pendapatanLainLainAll = getJurnalsQuery(7)->get();
        $bebanMarketingAll = getJurnalsQuery(601)->get();
        $bebanKegiatanAll = getJurnalsQuery(602)->get();
        $bebanGajiAll = getJurnalsQuery(603)->get();
        $bebanOperasionalKantorAll = getJurnalsQuery(604)->get();
        $bebanRumahTanggaKantorAll = getJurnalsQuery(605)->get();
        $bebanSewaAll = getJurnalsQuery(606)->get();
        $bebanPerawatanAll = getJurnalsQuery(607)->get();
        $bebanYayasanAll = getJurnalsQuery(608)->get();
        $bebanLainlainAll = getJurnalsQuery(609)->get();
        $pajakAll = getJurnalsQuery(610)->get();
        $depresiasiAll = getJurnalsQuery(611)->get();

        // Calculate sum data jurnal
        $pendapatanSumKredit = $pendapatanAll->sum('kredit');
        $pendapatanSumDebit = $pendapatanAll->sum('debit');
        $totPendapatan = $pendapatanSumKredit - $pendapatanSumDebit;

        $bebanSehubunganProgramSumKredit = $bebanSehubunganProgramAll->sum('kredit');
        $bebanSehubunganProgramSumDebit = $bebanSehubunganProgramAll->sum('debit');
        $totbebanSehubunganProgram = $bebanSehubunganProgramSumDebit - $bebanSehubunganProgramSumKredit;

        $pendapatanLainLainSumKredit = $pendapatanLainLainAll->sum('kredit');
        $pendapatanLainLainSumDebit = $pendapatanLainLainAll->sum('debit');
        $totPendapatanLainLain = $pendapatanLainLainSumKredit - $pendapatanLainLainSumDebit;

        $bebanMarketingSumKredit = $bebanMarketingAll->sum('kredit');
        $bebanMarketingSumDebit = $bebanMarketingAll->sum('debit');
        $totBebanMarketing = $bebanMarketingSumDebit - $bebanMarketingSumKredit;

        $bebanKegiatanSumKredit = $bebanKegiatanAll->sum('kredit');
        $bebanKegiatanSumDebit = $bebanKegiatanAll->sum('debit');
        $totBebanKegiatanSumKredit = $bebanKegiatanSumDebit - $bebanKegiatanSumKredit;

        $bebanGajiSumKredit = $bebanGajiAll->sum('kredit');
        $bebanGajiSumDebit = $bebanGajiAll->sum('debit');
        $totbebanGaji = $bebanGajiSumDebit - $bebanGajiSumKredit;

        $bebanOperasionalKantorSumKredit = $bebanOperasionalKantorAll->sum('kredit');
        $bebanOperasionalKantorSumDebit = $bebanOperasionalKantorAll->sum('debit');
        $totbebanOperasionalKantor = $bebanOperasionalKantorSumDebit - $bebanOperasionalKantorSumKredit;

        $bebanRumahTanggaKantorSumKredit = $bebanRumahTanggaKantorAll->sum('kredit');
        $bebanRumahTanggaKantorSumDebit = $bebanRumahTanggaKantorAll->sum('debit');
        $totbebanRumahTanggaKantor = $bebanRumahTanggaKantorSumDebit - $bebanRumahTanggaKantorSumKredit;

        $bebanSewaSumKredit = $bebanSewaAll->sum('kredit');
        $bebanSewaSumDebit = $bebanSewaAll->sum('debit');
        $totbebanSewa = $bebanSewaSumDebit - $bebanSewaSumKredit;

        $bebanPerawatanSumKredit = $bebanPerawatanAll->sum('kredit');
        $bebanPerawatanSumDebit = $bebanPerawatanAll->sum('debit');
        $totbebanPerawatan = $bebanPerawatanSumDebit - $bebanPerawatanSumKredit;

        $bebanYayasanSumKredit = $bebanYayasanAll->sum('kredit');
        $bebanYayasanSumDebit = $bebanYayasanAll->sum('debit');
        $totbebanYayasan = $bebanYayasanSumDebit - $bebanYayasanSumKredit;

        $bebanLainlainSumKredit = $bebanLainlainAll->sum('kredit');
        $bebanLainlainSumDebit = $bebanLainlainAll->sum('debit');
        $totbebanLainlain = $bebanLainlainSumDebit - $bebanLainlainSumKredit;

        $pajakSumKredit = $pajakAll->sum('kredit');
        $pajakSumDebit = $pajakAll->sum('debit');
        $totpajak = $pajakSumDebit - $pajakSumKredit;

        $depresiasiSumKredit = $depresiasiAll->sum('kredit');
        $depresiasiSumDebit = $depresiasiAll->sum('debit');
        $totdepresiasi = $depresiasiSumDebit - $depresiasiSumKredit;

        $labaKotor = $totPendapatan - $totbebanSehubunganProgram;
        $allBeban = $totBebanMarketing + $totBebanKegiatanSumKredit + $totbebanGaji + $totbebanOperasionalKantor + $totbebanRumahTanggaKantor + $totbebanSewa + $totbebanPerawatan + $totbebanYayasan;
        $labaRugiSebelumBunga = $labaKotor + $totPendapatanLainLain - $allBeban;
        $labaRugiSebelumPajak = $labaRugiSebelumBunga - $totbebanLainlain;
        $labaRugiSebelumDepresiasi = $labaRugiSebelumPajak - $totpajak;
        $kenaikanPenurunanAsetNettoTidakTerikat = $labaRugiSebelumDepresiasi - $totdepresiasi;

        // Pisahkan berdasarkan type_neraca
        $aktiva = $neraca->where('type_neraca', 'AKTIVA');
        $kasDanBank = $aktiva->where('sub_type', 'Kas & Bank');
        $piutang = $aktiva->where('sub_type', 'Piutang');
        $asetTidakLancar = $aktiva->where('sub_type', 'Aset Tidak Lancar');

        $passiva = $neracaKredit->where('type_neraca', 'PASSIVA');
        $lljPendek = $passiva->where('sub_type', 'Liabilitas Jangka Pendek');
        $lljPanjang = $passiva->where('sub_type', 'Liabilitas Jangka Panjang');

        $ekuitas = $neracaKredit->where('type_neraca', 'EKUITAS');

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
        $subTotalEkuitasAktivitas = $subTotalEkuitas + $kenaikanPenurunanAsetNettoTidakTerikat;

        // Grand Total Asset
        $grandTotalAsset = $subTotalAsetLancar + $subTotalAsetTidakLancar;
        $grandTotalLiabilDanEkuitas = $subTotalLiabilitas + $subTotalEkuitas;
        $grandTotalLiabilDanEkuitasAktivitas = $subTotalLiabilitas + $subTotalEkuitasAktivitas;

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
                'selectedYear' => $selectedYear,
                'selectedMonth' => $selectedMonth,
                'kenaikanPenurunanAsetNettoTidakTerikat' => $kenaikanPenurunanAsetNettoTidakTerikat,
                'subTotalEkuitasAktivitas' => $subTotalEkuitasAktivitas,
                'grandTotalLiabilDanEkuitasAktivitas' => $grandTotalLiabilDanEkuitasAktivitas
            ]);
    }

    public function printNeraca()
    {

        $neraca = Neraca::all();
        $neracaKredit = NeracaKredit::all();

                // Define a reusable function untuk build query data jurnal untuk menghitung aktivitas total
        function getJurnalsQuery($paretData) {
            return Jurnal::with('akun')
                ->whereHas('akun', function ($query) {
                    $query->whereColumn('jurnal.kode_akun', 'jurnal_akun.no_akun');
                })
                ->whereHas('akun', function ($query) use ($paretData) {
                    $query->where('parent', '=', $paretData);
                });
        }

        // Get jurnals berdasarkan data where
        $pendapatanAll = getJurnalsQuery(4)->get();
        $bebanSehubunganProgramAll = getJurnalsQuery(5)->get();
        $pendapatanLainLainAll = getJurnalsQuery(7)->get();
        $bebanMarketingAll = getJurnalsQuery(601)->get();
        $bebanKegiatanAll = getJurnalsQuery(602)->get();
        $bebanGajiAll = getJurnalsQuery(603)->get();
        $bebanOperasionalKantorAll = getJurnalsQuery(604)->get();
        $bebanRumahTanggaKantorAll = getJurnalsQuery(605)->get();
        $bebanSewaAll = getJurnalsQuery(606)->get();
        $bebanPerawatanAll = getJurnalsQuery(607)->get();
        $bebanYayasanAll = getJurnalsQuery(608)->get();
        $bebanLainlainAll = getJurnalsQuery(609)->get();
        $pajakAll = getJurnalsQuery(610)->get();
        $depresiasiAll = getJurnalsQuery(611)->get();

        // Calculate sum data jurnal
        $pendapatanSumKredit = $pendapatanAll->sum('kredit');
        $pendapatanSumDebit = $pendapatanAll->sum('debit');
        $totPendapatan = $pendapatanSumKredit - $pendapatanSumDebit;

        $bebanSehubunganProgramSumKredit = $bebanSehubunganProgramAll->sum('kredit');
        $bebanSehubunganProgramSumDebit = $bebanSehubunganProgramAll->sum('debit');
        $totbebanSehubunganProgram = $bebanSehubunganProgramSumDebit - $bebanSehubunganProgramSumKredit;

        $pendapatanLainLainSumKredit = $pendapatanLainLainAll->sum('kredit');
        $pendapatanLainLainSumDebit = $pendapatanLainLainAll->sum('debit');
        $totPendapatanLainLain = $pendapatanLainLainSumKredit - $pendapatanLainLainSumDebit;

        $bebanMarketingSumKredit = $bebanMarketingAll->sum('kredit');
        $bebanMarketingSumDebit = $bebanMarketingAll->sum('debit');
        $totBebanMarketing = $bebanMarketingSumDebit - $bebanMarketingSumKredit;

        $bebanKegiatanSumKredit = $bebanKegiatanAll->sum('kredit');
        $bebanKegiatanSumDebit = $bebanKegiatanAll->sum('debit');
        $totBebanKegiatanSumKredit = $bebanKegiatanSumDebit - $bebanKegiatanSumKredit;

        $bebanGajiSumKredit = $bebanGajiAll->sum('kredit');
        $bebanGajiSumDebit = $bebanGajiAll->sum('debit');
        $totbebanGaji = $bebanGajiSumDebit - $bebanGajiSumKredit;

        $bebanOperasionalKantorSumKredit = $bebanOperasionalKantorAll->sum('kredit');
        $bebanOperasionalKantorSumDebit = $bebanOperasionalKantorAll->sum('debit');
        $totbebanOperasionalKantor = $bebanOperasionalKantorSumDebit - $bebanOperasionalKantorSumKredit;

        $bebanRumahTanggaKantorSumKredit = $bebanRumahTanggaKantorAll->sum('kredit');
        $bebanRumahTanggaKantorSumDebit = $bebanRumahTanggaKantorAll->sum('debit');
        $totbebanRumahTanggaKantor = $bebanRumahTanggaKantorSumDebit - $bebanRumahTanggaKantorSumKredit;

        $bebanSewaSumKredit = $bebanSewaAll->sum('kredit');
        $bebanSewaSumDebit = $bebanSewaAll->sum('debit');
        $totbebanSewa = $bebanSewaSumDebit - $bebanSewaSumKredit;

        $bebanPerawatanSumKredit = $bebanPerawatanAll->sum('kredit');
        $bebanPerawatanSumDebit = $bebanPerawatanAll->sum('debit');
        $totbebanPerawatan = $bebanPerawatanSumDebit - $bebanPerawatanSumKredit;

        $bebanYayasanSumKredit = $bebanYayasanAll->sum('kredit');
        $bebanYayasanSumDebit = $bebanYayasanAll->sum('debit');
        $totbebanYayasan = $bebanYayasanSumDebit - $bebanYayasanSumKredit;

        $bebanLainlainSumKredit = $bebanLainlainAll->sum('kredit');
        $bebanLainlainSumDebit = $bebanLainlainAll->sum('debit');
        $totbebanLainlain = $bebanLainlainSumDebit - $bebanLainlainSumKredit;

        $pajakSumKredit = $pajakAll->sum('kredit');
        $pajakSumDebit = $pajakAll->sum('debit');
        $totpajak = $pajakSumDebit - $pajakSumKredit;

        $depresiasiSumKredit = $depresiasiAll->sum('kredit');
        $depresiasiSumDebit = $depresiasiAll->sum('debit');
        $totdepresiasi = $depresiasiSumDebit - $depresiasiSumKredit;

        $labaKotor = $totPendapatan - $totbebanSehubunganProgram;
        $allBeban = $totBebanMarketing + $totBebanKegiatanSumKredit + $totbebanGaji + $totbebanOperasionalKantor + $totbebanRumahTanggaKantor + $totbebanSewa + $totbebanPerawatan + $totbebanYayasan;
        $labaRugiSebelumBunga = $labaKotor + $totPendapatanLainLain - $allBeban;
        $labaRugiSebelumPajak = $labaRugiSebelumBunga - $totbebanLainlain;
        $labaRugiSebelumDepresiasi = $labaRugiSebelumPajak - $totpajak;
        $kenaikanPenurunanAsetNettoTidakTerikat = $labaRugiSebelumDepresiasi - $totdepresiasi;

        // Pisahkan berdasarkan type_neraca
        $aktiva = $neraca->where('type_neraca', 'AKTIVA');
        $kasDanBank = $aktiva->where('sub_type', 'Kas & Bank');
        $piutang = $aktiva->where('sub_type', 'Piutang');
        $asetTidakLancar = $aktiva->where('sub_type', 'Aset Tidak Lancar');

        $passiva = $neracaKredit->where('type_neraca', 'PASSIVA');
        $lljPendek = $passiva->where('sub_type', 'Liabilitas Jangka Pendek');
        $lljPanjang = $passiva->where('sub_type', 'Liabilitas Jangka Panjang');

        $ekuitas = $neracaKredit->where('type_neraca', 'EKUITAS');

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
        $subTotalEkuitasAktivitas = $subTotalEkuitas + $kenaikanPenurunanAsetNettoTidakTerikat;

        // Grand Total Asset
        $grandTotalAsset = $subTotalAsetLancar + $subTotalAsetTidakLancar;
        $grandTotalLiabilDanEkuitas = $subTotalLiabilitas + $subTotalEkuitas;
        $grandTotalLiabilDanEkuitasAktivitas = $subTotalLiabilitas + $subTotalEkuitasAktivitas;

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
                'kenaikanPenurunanAsetNettoTidakTerikat' => $kenaikanPenurunanAsetNettoTidakTerikat,
                'subTotalEkuitasAktivitas' => $subTotalEkuitasAktivitas,
                'grandTotalLiabilDanEkuitasAktivitas' => $grandTotalLiabilDanEkuitasAktivitas
            ]);
    }

    // Metode untuk Export ke Excel
    public function exportNeraca(Request $request, $selectedYear = null, $selectedMonth = null)
    {
        $export = new NeracaExport($selectedYear, $selectedMonth);

        $currentDate = Carbon::now()->format('d-m-y');

        $fileName = 'laporan_neraca_' . $currentDate . '.xlsx';

        return Excel::download($export, $fileName);
    }

    public function exportNeracaFilter(Request $request, $selectedYear = null, $selectedMonth = null)
    {
        $export = new NeracaExportFilter($selectedYear, $selectedMonth);

        $currentDate = Carbon::now()->format('d-m-y');

        $fileName = 'laporan_neraca_' . $currentDate . '.xlsx';

        return Excel::download($export, $fileName);
    }

}
