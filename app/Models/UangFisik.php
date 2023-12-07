<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UangFisik extends Model
{
    use HasFactory;

    protected $table = 'uang_fisik';
    protected $primaryKey = 'id';

    protected $fillable = [
        'tanggal',
        'total',
    ];

    public $timestamps = true;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public function uangFisikDetails()
    {
        return $this->hasMany(UangFisikDetail::class, 'id_uang_fisik', 'id');
    }

}

