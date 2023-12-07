<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PecahanUang extends Model
{
    use HasFactory;

    protected $table = 'pecahan_uang';
    protected $primaryKey = 'id';

    protected $fillable = [
        'jenis_uang',
        'pecahan',
        'status',
    ];

    public $timestamps = true;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

}

