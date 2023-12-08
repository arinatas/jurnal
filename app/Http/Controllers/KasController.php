<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Kas;
use Illuminate\Support\Facades\Hash;

class KasController extends Controller
{
    public static function terbilang($angka)
    {
        $angka = (float)$angka;
        $bilangan = [
            '',
            'Satu',
            'Dua',
            'Tiga',
            'Empat',
            'Lima',
            'Enam',
            'Tujuh',
            'Delapan',
            'Sembilan',
            'Sepuluh',
            'Sebelas'
        ];
    
        $temp = "";
        if ($angka < 12) {
            $temp = $bilangan[$angka];
        } elseif ($angka < 20) {
            $temp = static::terbilang($angka - 10) . " Belas";
        } elseif ($angka < 100) {
            $temp = static::terbilang($angka / 10) . " Puluh " . static::terbilang($angka % 10);
        } elseif ($angka < 200) {
            $temp = "Seratus " . static::terbilang($angka - 100);
        } elseif ($angka < 1000) {
            $temp = static::terbilang($angka / 100) . " Ratus " . static::terbilang($angka % 100);
        } elseif ($angka < 2000) {
            $temp = "Seribu " . static::terbilang($angka - 1000);
        } elseif ($angka < 1000000) {
            $temp = static::terbilang($angka / 1000) . " Ribu " . static::terbilang($angka % 1000);
        } elseif ($angka < 1000000000) {
            $temp = static::terbilang($angka / 1000000) . " Juta " . static::terbilang($angka % 1000000);
        } elseif ($angka < 1000000000000) {
            $temp = static::terbilang($angka / 1000000000) . " Miliar " . static::terbilang($angka % 1000000000);
        } elseif ($angka < 1000000000000000) {
            $temp = static::terbilang($angka / 1000000000000) . " Triliun " . static::terbilang($angka % 1000000000000);
        }
    
        return $temp;
    }    
    
    public function index()
    {
        $kass = Kas::all();
        
        $data = [
            'title' => 'Uang Kas',
            'section' => 'Master',
            'active' => 'Uang Kas',
            'kass' => $kass,
            'terbilang' => function ($angka) {
                return static::terbilang($angka);
            },
        ];

        return view('master.kas.index', $data);
    }

}
