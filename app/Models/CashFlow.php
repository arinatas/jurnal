<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashFlow extends Model
{
    use HasFactory;

    protected $table = 'cash_flow';
    protected $primaryKey = 'id';
    protected $keyType = 'string';

    protected $fillable = [
        'tanggal',
        'no_bukti',
        'pic',
        'nama',
        'kode_anggaran',
        'transaksi',
        'ref',
        'debit',
        'kredit',
        'id_accounting'
    ];

    public $timestamps = true;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    // Define the relationship with the User model
    public function User()
    {
        return $this->belongsTo(User::class, 'id_accounting', 'id');
    }

    // Define the relationship with the Rkat model
    public function rkat()
    {
        return $this->belongsTo(Rkat::class, 'kode_anggaran', 'id');
    }


}

