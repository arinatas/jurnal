<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TotalPiutang extends Model
{
    use HasFactory;

    protected $table = 'total_piutang';
    protected $primaryKey = 'id';
    protected $keyType = 'string';

    protected $fillable = [
        'total_piutang'
    ];

    public $timestamps = true;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

}

