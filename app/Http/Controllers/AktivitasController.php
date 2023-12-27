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
    public function index()
    {
        // Filter jurnal akun untuk section Pendapatan
        $pendapatan = JurnalAkun::where('parent', 4)->get();
        // Hitung credit amount untuk masing masing no akun Pendapatan
        $pendapatanAmounts = $this->calculateCreditAmounts($pendapatan);
    
        // Filter jurnal akun untuk section Beban Sehubungan Program
        $bebanSehubunganProgram = JurnalAkun::where('parent', 5)->get();
        // Hitung credit amount untuk masing masing no akun Beban Sehubungan Program
        $bebanSehubunganProgramAmounts = $this->calculateCreditAmounts($bebanSehubunganProgram);

        // Filter jurnal akun untuk section Pendapatan Lain lain
        $pendapatanLainlain = JurnalAkun::where('parent', 7)->get();
        // Hitung credit amount untuk masing masing no akun Pendapatan Lain lain
        $pendapatanLainlainAmounts = $this->calculateCreditAmounts($pendapatanLainlain);

        // Filter jurnal akun untuk section Beban Marketing
        $bebanMarketing = JurnalAkun::where('parent', 601)->get();
        // Hitung credit amount untuk masing masing no akun Beban Marketing
        $bebanMarketingAmounts = $this->calculateCreditAmounts($bebanMarketing);

        // Filter jurnal akun untuk section Beban Kegiatan
        $bebanKegiatan = JurnalAkun::where('parent', 602)->get();
        // Hitung credit amount untuk masing masing no akun Beban Kegiatan
        $bebanKegiatanAmounts = $this->calculateCreditAmounts($bebanKegiatan);

        // Filter jurnal akun untuk section Beban Gaji
        $bebanGaji = JurnalAkun::where('parent', 603)->get();
        // Hitung credit amount untuk masing masing no akun Beban Gaji
        $bebanGajiAmounts = $this->calculateCreditAmounts($bebanGaji);

        // Filter jurnal akun untuk section Beban Operasional Kantor
        $bebanOperasionalKantor = JurnalAkun::where('parent', 604)->get();
        // Hitung credit amount untuk masing masing no akun Beban Operasional Kantor
        $bebanOperasionalKantorAmounts = $this->calculateCreditAmounts($bebanOperasionalKantor);

        // Filter jurnal akun untuk section Beban Rumah Tangga Kantor
        $bebanRumahTanggaKantor = JurnalAkun::where('parent', 605)->get();
        // Hitung credit amount untuk masing masing no akun Beban Rumah Tangga Kantor
        $bebanRumahTanggaKantorAmounts = $this->calculateCreditAmounts($bebanRumahTanggaKantor);

        // Filter jurnal akun untuk section Beban Sewa
        $bebanSewa = JurnalAkun::where('parent', 606)->get();
        // Hitung credit amount untuk masing masing no akun Beban Sewa
        $bebanSewaAmounts = $this->calculateCreditAmounts($bebanSewa);

        // Filter jurnal akun untuk section Beban Perawatan
        $bebanPerawatan = JurnalAkun::where('parent', 607)->get();
        // Hitung credit amount untuk masing masing no akun Beban Perawatan
        $bebanPerawatanAmounts = $this->calculateCreditAmounts($bebanPerawatan);

        // Filter jurnal akun untuk section Beban Yayasan
        $bebanYayasan = JurnalAkun::where('parent', 608)->get();
        // Hitung credit amount untuk masing masing no akun Beban Yayasan
        $bebanYayasanAmounts = $this->calculateCreditAmounts($bebanYayasan);

        // Filter jurnal akun untuk section Beban Lain lain
        $bebanLainlain = JurnalAkun::where('parent', 609)->get();
        // Hitung credit amount untuk masing masing no akun Beban Lainlain
        $bebanLainlainAmounts = $this->calculateCreditAmounts($bebanLainlain);

        // Filter jurnal akun untuk section Pajak
        $pajak = JurnalAkun::where('parent', 610)->get();
        // Hitung credit amount untuk masing masing no akun Pajak
        $pajakAmounts = $this->calculateCreditAmounts($pajak);

        // Filter jurnal akun untuk section depresiasi
        $depresiasi = JurnalAkun::where('parent', 611)->get();
        // Hitung credit amount untuk masing masing no akun depresiasi
        $depresiasiAmounts = $this->calculateCreditAmounts($depresiasi);
    
        return view('menu.aktivitas.index', [
            'title' => 'Aktivitas',
            'section' => 'Menu',
            'active' => 'Aktivitas',
            'pendapatan' => $pendapatan,
            'pendapatanAmounts' => $pendapatanAmounts,
            'bebanSehubunganProgram' => $bebanSehubunganProgram,
            'bebanSehubunganProgramAmounts' => $bebanSehubunganProgramAmounts,
            'pendapatanLainlain' => $pendapatanLainlain,
            'pendapatanLainlainAmounts' => $pendapatanLainlainAmounts,
            'bebanMarketing' => $bebanMarketing,
            'bebanMarketingAmounts' => $bebanMarketingAmounts,
            'bebanKegiatan' => $bebanKegiatan,
            'bebanKegiatanAmounts' => $bebanKegiatanAmounts,
            'bebanGaji' => $bebanGaji,
            'bebanGajiAmounts' => $bebanGajiAmounts,
            'bebanOperasionalKantor' => $bebanOperasionalKantor,
            'bebanOperasionalKantorAmounts' => $bebanOperasionalKantorAmounts,
            'bebanRumahTanggaKantor' => $bebanRumahTanggaKantor,
            'bebanRumahTanggaKantorAmounts' => $bebanRumahTanggaKantorAmounts,
            'bebanSewa' => $bebanSewa,
            'bebanSewaAmounts' => $bebanSewaAmounts,
            'bebanPerawatan' => $bebanPerawatan,
            'bebanPerawatanAmounts' => $bebanPerawatanAmounts,
            'bebanYayasan' => $bebanYayasan,
            'bebanYayasanAmounts' => $bebanYayasanAmounts,
            'bebanLainlain' => $bebanLainlain,
            'bebanLainlainAmounts' => $bebanLainlainAmounts,
            'pajak' => $pajak,
            'pajakAmounts' => $pajakAmounts,
            'depresiasi' => $depresiasi,
            'depresiasiAmounts' => $depresiasiAmounts,
        ]);
    }
    
    // Function untuk menghitung credit amounts for multiple accounts
    private function calculateCreditAmounts($jurnalAkuns)
    {
        $creditAmounts = [];
    
        foreach ($jurnalAkuns as $item) {
            $creditAmount = Jurnal::whereHas('rkat', function ($query) use ($item) {
                $query->where('no_akun', $item->no_akun);
            })->sum('kredit');
    
            $creditAmounts[$item->no_akun] = $creditAmount;
        }
    
        return $creditAmounts;
    }
    
}

