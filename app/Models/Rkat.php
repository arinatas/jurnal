<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rkat extends Model
{
    use HasFactory;

    protected $table = 'rkat';
    protected $primaryKey = 'id';
    protected $keyType = 'string';

    protected $fillable = [
        'kode_rkat',
        'no_akun',
        'keterangan',
        'periode',
    ];

    public $timestamps = true;

    // Define the relationship
    public function jurnalAkun()
    {
        return $this->belongsTo(JurnalAkun::class, 'no_akun', 'no_akun');
    }

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

}

