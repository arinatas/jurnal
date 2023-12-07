<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JurnalAkun extends Model
{
    use HasFactory;

    protected $table = 'jurnal_akun';
    protected $primaryKey = 'no_akun';
    protected $keyType = 'string';

    protected $fillable = [
        'no_akun',
        'parent',
        'nama_akun',
        'type_neraca',
        'lvl',
        'tipe_akun',
    ];

    public $timestamps = true;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

}

