<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UangFisikDetail extends Model
{
    use HasFactory;

    protected $table = 'uang_fisik_detail';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id_uang_fisik',
        'id_pecahan_uang',
        'jumlah',
    ];

    public $timestamps = true;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public function uangFisik()
    {
        return $this->belongsTo(UangFisik::class);
    }

    public function pecahanDetails()
    {
        return $this->belongsTo(PecahanUang::class, 'id', 'id_pecahan_uang');
    }
}

