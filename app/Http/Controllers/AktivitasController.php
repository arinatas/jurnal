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
        // Filter jurnalakuns for Pendapatan
        $pendapatan = JurnalAkun::where('parent', 4)
            ->get();
    
        // Calculate credit amount for each account in Pendapatan
        $pendapatanAmounts = $this->calculateCreditAmounts($pendapatan);
    
        // Filter jurnalakuns for Beban Sehubungan Program
        $bebanSehubunganProgram = JurnalAkun::where('parent', 5)
            ->get();
    
        // Calculate credit amount for each account in Beban Sehubungan Program
        $bebanSehubunganProgramAmounts = $this->calculateCreditAmounts($bebanSehubunganProgram);
    
        return view('menu.aktivitas.index', [
            'title' => 'Aktivitas',
            'section' => 'Menu',
            'active' => 'Aktivitas',
            'pendapatan' => $pendapatan,
            'pendapatanAmounts' => $pendapatanAmounts,
            'bebanSehubunganProgram' => $bebanSehubunganProgram,
            'bebanSehubunganProgramAmounts' => $bebanSehubunganProgramAmounts,
        ]);
    }
    
    // Add a new function to calculate credit amounts for multiple accounts
    private function calculateCreditAmounts($jurnalAkuns)
    {
        $creditAmounts = [];
    
        foreach ($jurnalAkuns as $item) {
            // Assuming you have a relationship between Jurnal and Rkat,
            // you can fetch the credit amount like this
            $creditAmount = Jurnal::whereHas('rkat', function ($query) use ($item) {
                $query->where('no_akun', $item->no_akun);
            })->sum('kredit');
    
            $creditAmounts[$item->no_akun] = $creditAmount;
        }
    
        return $creditAmounts;
    }
    
}

