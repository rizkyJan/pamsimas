<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tagihan extends Model
{
    use HasFactory;

    protected $fillable = [
    'pelanggan_id',
    'bulan_id',
    'tarif_id',
    'meter_awal',
    'meter_akhir',
    'pemakaian',
    'total_tagihan',
    'tanggal_jatuh_tempo',
    'status',
];


    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }

    public function bulan()
    {
        return $this->belongsTo(Bulan::class);
    }

    public function tarif()
    {
        return $this->belongsTo(Tarif::class);
    }
}
