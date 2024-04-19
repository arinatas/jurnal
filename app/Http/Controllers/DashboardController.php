<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Kas;
use App\Models\Piutang;
use App\Models\UangFisik;
use App\Models\TotalPiutang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{

    public function index()
    {
        // query uang kas total
        $kass = Kas::all();

        // query total piutang
        $piutang = Piutang::where('stts_reallisasi', 0)->paginate(10);
        $totalPiutang = TotalPiutang::findOrFail("1");

        // ambil tanggal hari ini
        $dateNow = Carbon::now()->toDateString();
        // query data uang fisik
        $uangFisik = UangFisik::with('uangFisikDetails', 'uangFisikDetails.pecahanDetails')
                    ->where('tanggal', $dateNow)
                    ->get();
        
        // dd($uangFisik); 
            return view('dashboard.index', [
                'title' => 'Dashboard',
                'secction' => 'Dashboard',
                'active' => 'Dashboard',
                'kass' => $kass,
                'totalPiutang' => $totalPiutang->total_piutang,
                'uangFisik' => $uangFisik,
            ]);
    }
}
