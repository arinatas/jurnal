<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Piutang;
use Illuminate\Support\Facades\Hash;

class PiutangController extends Controller
{
    public function index()
    {
        $piutang = Piutang::all();
            return view('menu.piutang.index', [
                'title' => 'Piutang',
                'section' => 'Menu',
                'active' => 'Piutang',
                'piutang' => $piutang,
            ]);
    }

}
