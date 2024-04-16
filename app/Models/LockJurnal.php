<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LockJurnal extends Model
{
    use HasFactory;

    protected $table = 'lock_jurnal';
    protected $primaryKey = 'id';

    protected $fillable = [
        'bulan',
        'tahun',
        'status',
    ];

    public $timestamps = true;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

}

