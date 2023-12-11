<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jurnal extends Model
{
    use HasFactory;

    protected $table = 'jurnal';
    protected $primaryKey = 'id';

    protected $fillable = [
        'periode_jurnal',
        'type_jurnal',
        'id_rkat',
        'uraian',
        'no_bukti',
        'debit',
        'kredit',
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

}

