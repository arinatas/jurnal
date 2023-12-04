<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Piutang extends Model
{
    use HasFactory;

    protected $table = 'piutang';
    protected $primaryKey = 'id';
    protected $keyType = 'string';

    protected $fillable = [
        'tanggal',
        'nama',
        'jumlah_piutang',
        'keterangan',
        'id_total_piutang',
        'id_accounting',
        'stts_reallisasi',
        'uang_realisasi',

    ];

    public $timestamps = true;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

}

