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
    public function index()
    {
        $kass = Kas::all();
            return view('master.kas.index', [
                'title' => 'Uang Kas',
                'section' => 'Master',
                'active' => 'Uang Kas',
                'kass' => $kass,
            ]);
    }

}
