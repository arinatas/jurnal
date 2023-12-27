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
        // Get the unique years from the "periode_jurnal" field
        $years = Jurnal::distinct()->select(DB::raw('YEAR(periode_jurnal) as year'))->pluck('year');
    
        // Get the selected year and month from the request
        $selectedYear = $request->input('tahun');
        $selectedMonth = $request->input('bulan');
    
        // Fetch Jurnal entries based on selected month and year
        $jurnalsQuery = Jurnal::with('rkat:id,kode_rkat')
        ->with('jurnalAkun')
        ->whereHas('jurnalAkun', function ($query) {
            // Filter jurnalAkun based on type_neraca
            $query->where('type_neraca', 'LABA-RUGI');
        });
    
        if ($selectedYear) {
            $jurnalsQuery->whereYear('periode_jurnal', $selectedYear);
        }
    
        if ($selectedMonth) {
            $jurnalsQuery->whereMonth('periode_jurnal', $selectedMonth);
        }
        
        // Order by id in ascending order
        $jurnalsQuery->orderBy('id');
    
        $jurnals = $jurnalsQuery->get();

        // Calculate total debit and total kredit
        $totalDebit = $jurnals->sum('debit');
        $totalKredit = $jurnals->sum('kredit');
    
        // Get the list of kode_rkat options
        $rkatOptions = Rkat::pluck('kode_rkat', 'id');
        $rkatDescriptions = Rkat::pluck('keterangan', 'id');
    
        return view('menu.aktivitas.index', [
            'title' => 'Aktivitas',
            'section' => 'Laporan',
            'active' => 'Aktivitas',
            'jurnals' => $jurnals,
            'rkatOptions' => $rkatOptions,
            'rkatDescriptions' => $rkatDescriptions,
            'totalDebit' => $totalDebit,
            'totalKredit' => $totalKredit,
            'years' => $years,
            'selectedYear' => $selectedYear, 
            'selectedMonth' => $selectedMonth,
        ]);
    }   
}

