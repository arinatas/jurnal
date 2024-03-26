<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jurnal extends Model
{
    use HasFactory;

    protected $table = 'jurnal';
    protected $primaryKey = 'id';

    // protected $fillable = [
    //     'periode_jurnal',
    //     'type_jurnal',
    //     'id_rkat',
    //     'uraian',
    //     'no_bukti',
    //     'debit',
    //     'kredit',
    //     'korek',
    //     'ku',
    //     'unit_usaha',
    //     'created_by',
    // ];

    protected $fillable = [
        'periode_jurnal',
        'type_jurnal',
        'keterangan_rkat',
        'divisi',
        'kode_akun',
        'uraian',
        'no_bukti',
        'debit',
        'kredit',
        'tt',
        'korek',
        'ku',
        'unit_usaha',
        'created_by',
    ];

    public $timestamps = true;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    // Define the relationship with the Rkat model
    public function rkat()
    {
        return $this->belongsTo(Rkat::class, 'id_rkat', 'id');
    }

    // Define the relationship with the Rkat model
    public function divisi()
    {
        return $this->belongsTo(Divisi::class, 'devisi', 'id');
    }
    public function jurnalAkun()
    {
        return $this->hasManyThrough(
            JurnalAkun::class,
            Rkat::class,
            'id',   // Foreign key on the rkat table
            'no_akun',   // Foreign key on the jurnal_akun table
            'id_rkat',        // Local key on the jurnal table
            'no_akun'    // Local key on the rkat table
        );
    }

}

