<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Jurnal;
use App\Models\JurnalAkun;
use App\Models\Rkat;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class AktivitasController extends Controller
{
    public function index(Request $request)
    {
        // Get Data unique years dari field "periode_jurnal" 
        $years = Jurnal::distinct()->select(DB::raw('YEAR(periode_jurnal) as year'))->pluck('year');
    
        // Get bulan dan tahun dari filter
        $selectedYear = $request->input('tahun');
        $selectedMonth = $request->input('bulan');
    
        // Definisi daftar section beserta parent id-nya
        $sections = [
            'pendapatan' => 4,
            'bebanSehubunganProgram' => 5,
            'pendapatanLainlain' => 7,
            'bebanMarketing' => 601,
            'bebanKegiatan' => 602,
            'bebanGaji' => 603,
            'bebanOperasionalKantor' => 604,
            'bebanRumahTanggaKantor' => 605,
            'bebanSewa' => 606,
            'bebanPerawatan' => 607,
            'bebanYayasan' => 608,
            'bebanLainlain' => 609,
            'pajak' => 610,
            'depresiasi' => 611,
        ];
    
        // Inisialisasi data untuk view
        $data = [
            'title' => 'Aktivitas',
            'section' => 'Menu',
            'active' => 'Aktivitas',
            'years' => $years,
            'selectedYear' => $selectedYear,
            'selectedMonth' => $selectedMonth,
        ];
    
        // Looping untuk setiap section
        foreach ($sections as $sectionKey => $parentId) {
            // Filter jurnal akun untuk section tertentu
            $jurnalAkun = JurnalAkun::where('parent', $parentId)->get();
            // Hitung credit amount untuk masing-masing no akun pada section tertentu
            $amounts = $this->calculateCreditAmounts($jurnalAkun, $selectedYear, $selectedMonth);
    
            // Menambahkan data ke dalam array $data
            $data[$sectionKey] = $jurnalAkun;
            $data[$sectionKey . 'Amounts'] = $amounts;
        }
    
        return view('menu.aktivitas.index', $data);
    }
    
    
    // Function untuk menghitung credit amounts for multiple accounts
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

    // Metode untuk Print Aktivitas
    public function printAktivitas($selectedYear, $selectedMonth)
    {
        // Definisi daftar section beserta parent id-nya
        $sections = [
            'pendapatan' => 4,
            'bebanSehubunganProgram' => 5,
            'pendapatanLainlain' => 7,
            'bebanMarketing' => 601,
            'bebanKegiatan' => 602,
            'bebanGaji' => 603,
            'bebanOperasionalKantor' => 604,
            'bebanRumahTanggaKantor' => 605,
            'bebanSewa' => 606,
            'bebanPerawatan' => 607,
            'bebanYayasan' => 608,
            'bebanLainlain' => 609,
            'pajak' => 610,
            'depresiasi' => 611,
        ];
    
        // Inisialisasi data untuk view
        $data = [
            'title' => 'Aktivitas',
            'section' => 'Menu',
            'active' => 'Aktivitas',
            'selectedYear' => $selectedYear,
            'selectedMonth' => $selectedMonth,
        ];
    
        // Looping untuk setiap section
        foreach ($sections as $sectionKey => $parentId) {
            // Filter jurnal akun untuk section tertentu
            $jurnalAkun = JurnalAkun::where('parent', $parentId)->get();
            // Hitung credit amount untuk masing-masing no akun pada section tertentu
            $amounts = $this->calculateCreditAmounts($jurnalAkun, $selectedYear, $selectedMonth);
    
            // Menambahkan data ke dalam array $data
            $data[$sectionKey] = $jurnalAkun;
            $data[$sectionKey . 'Amounts'] = $amounts;
        }
    
        return view('menu.aktivitas.print_aktivitas', $data);
    }








    // // Metode untuk Print Aktivitas
    // public function printAktivitas($selectedYear, $selectedMonth)
    // {
    //     // Filter jurnal akun untuk section Pendapatan
    //     $pendapatan = JurnalAkun::where('parent', 4)->get();
    //     // Hitung credit amount untuk masing masing no akun Pendapatan
    //     $pendapatanAmounts = $this->calculateCreditAmounts($pendapatan, $selectedYear, $selectedMonth);
    
    //     // Filter jurnal akun untuk section Beban Sehubungan Program
    //     $bebanSehubunganProgram = JurnalAkun::where('parent', 5)->get();
    //     // Hitung credit amount untuk masing masing no akun Beban Sehubungan Program
    //     $bebanSehubunganProgramAmounts = $this->calculateCreditAmounts($bebanSehubunganProgram, $selectedYear, $selectedMonth);

    //     // Filter jurnal akun untuk section Pendapatan Lain lain
    //     $pendapatanLainlain = JurnalAkun::where('parent', 7)->get();
    //     // Hitung credit amount untuk masing masing no akun Pendapatan Lain lain
    //     $pendapatanLainlainAmounts = $this->calculateCreditAmounts($pendapatanLainlain, $selectedYear, $selectedMonth);

    //     // Filter jurnal akun untuk section Beban Marketing
    //     $bebanMarketing = JurnalAkun::where('parent', 601)->get();
    //     // Hitung credit amount untuk masing masing no akun Beban Marketing
    //     $bebanMarketingAmounts = $this->calculateCreditAmounts($bebanMarketing, $selectedYear, $selectedMonth);

    //     // Filter jurnal akun untuk section Beban Kegiatan
    //     $bebanKegiatan = JurnalAkun::where('parent', 602)->get();
    //     // Hitung credit amount untuk masing masing no akun Beban Kegiatan
    //     $bebanKegiatanAmounts = $this->calculateCreditAmounts($bebanKegiatan, $selectedYear, $selectedMonth);

    //     // Filter jurnal akun untuk section Beban Gaji
    //     $bebanGaji = JurnalAkun::where('parent', 603)->get();
    //     // Hitung credit amount untuk masing masing no akun Beban Gaji
    //     $bebanGajiAmounts = $this->calculateCreditAmounts($bebanGaji,$selectedYear, $selectedMonth);

    //     // Filter jurnal akun untuk section Beban Operasional Kantor
    //     $bebanOperasionalKantor = JurnalAkun::where('parent', 604)->get();
    //     // Hitung credit amount untuk masing masing no akun Beban Operasional Kantor
    //     $bebanOperasionalKantorAmounts = $this->calculateCreditAmounts($bebanOperasionalKantor, $selectedYear, $selectedMonth);

    //     // Filter jurnal akun untuk section Beban Rumah Tangga Kantor
    //     $bebanRumahTanggaKantor = JurnalAkun::where('parent', 605)->get();
    //     // Hitung credit amount untuk masing masing no akun Beban Rumah Tangga Kantor
    //     $bebanRumahTanggaKantorAmounts = $this->calculateCreditAmounts($bebanRumahTanggaKantor, $selectedYear, $selectedMonth);

    //     // Filter jurnal akun untuk section Beban Sewa
    //     $bebanSewa = JurnalAkun::where('parent', 606)->get();
    //     // Hitung credit amount untuk masing masing no akun Beban Sewa
    //     $bebanSewaAmounts = $this->calculateCreditAmounts($bebanSewa, $selectedYear, $selectedMonth);

    //     // Filter jurnal akun untuk section Beban Perawatan
    //     $bebanPerawatan = JurnalAkun::where('parent', 607)->get();
    //     // Hitung credit amount untuk masing masing no akun Beban Perawatan
    //     $bebanPerawatanAmounts = $this->calculateCreditAmounts($bebanPerawatan, $selectedYear, $selectedMonth);

    //     // Filter jurnal akun untuk section Beban Yayasan
    //     $bebanYayasan = JurnalAkun::where('parent', 608)->get();
    //     // Hitung credit amount untuk masing masing no akun Beban Yayasan
    //     $bebanYayasanAmounts = $this->calculateCreditAmounts($bebanYayasan, $selectedYear, $selectedMonth);

    //     // Filter jurnal akun untuk section Beban Lain lain
    //     $bebanLainlain = JurnalAkun::where('parent', 609)->get();
    //     // Hitung credit amount untuk masing masing no akun Beban Lainlain
    //     $bebanLainlainAmounts = $this->calculateCreditAmounts($bebanLainlain, $selectedYear, $selectedMonth);

    //     // Filter jurnal akun untuk section Pajak
    //     $pajak = JurnalAkun::where('parent', 610)->get();
    //     // Hitung credit amount untuk masing masing no akun Pajak
    //     $pajakAmounts = $this->calculateCreditAmounts($pajak, $selectedYear, $selectedMonth);

    //     // Filter jurnal akun untuk section depresiasi
    //     $depresiasi = JurnalAkun::where('parent', 611)->get();
    //     // Hitung credit amount untuk masing masing no akun depresiasi
    //     $depresiasiAmounts = $this->calculateCreditAmounts($depresiasi, $selectedYear, $selectedMonth);
    
    //     return view('menu.aktivitas.print_aktivitas', [
    //         'title' => 'Aktivitas',
    //         'section' => 'Menu',
    //         'active' => 'Aktivitas',
    //         'selectedYear' => $selectedYear, 
    //         'selectedMonth' => $selectedMonth,
    //         'pendapatan' => $pendapatan,
    //         'pendapatanAmounts' => $pendapatanAmounts,
    //         'bebanSehubunganProgram' => $bebanSehubunganProgram,
    //         'bebanSehubunganProgramAmounts' => $bebanSehubunganProgramAmounts,
    //         'pendapatanLainlain' => $pendapatanLainlain,
    //         'pendapatanLainlainAmounts' => $pendapatanLainlainAmounts,
    //         'bebanMarketing' => $bebanMarketing,
    //         'bebanMarketingAmounts' => $bebanMarketingAmounts,
    //         'bebanKegiatan' => $bebanKegiatan,
    //         'bebanKegiatanAmounts' => $bebanKegiatanAmounts,
    //         'bebanGaji' => $bebanGaji,
    //         'bebanGajiAmounts' => $bebanGajiAmounts,
    //         'bebanOperasionalKantor' => $bebanOperasionalKantor,
    //         'bebanOperasionalKantorAmounts' => $bebanOperasionalKantorAmounts,
    //         'bebanRumahTanggaKantor' => $bebanRumahTanggaKantor,
    //         'bebanRumahTanggaKantorAmounts' => $bebanRumahTanggaKantorAmounts,
    //         'bebanSewa' => $bebanSewa,
    //         'bebanSewaAmounts' => $bebanSewaAmounts,
    //         'bebanPerawatan' => $bebanPerawatan,
    //         'bebanPerawatanAmounts' => $bebanPerawatanAmounts,
    //         'bebanYayasan' => $bebanYayasan,
    //         'bebanYayasanAmounts' => $bebanYayasanAmounts,
    //         'bebanLainlain' => $bebanLainlain,
    //         'bebanLainlainAmounts' => $bebanLainlainAmounts,
    //         'pajak' => $pajak,
    //         'pajakAmounts' => $pajakAmounts,
    //         'depresiasi' => $depresiasi,
    //         'depresiasiAmounts' => $depresiasiAmounts,
    //     ]);
    // }
}

