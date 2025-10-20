<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tagihan extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'pelanggan_id',
        'bulan_id',
        'tarif_id',
        'meter_awal',
        'meter_akhir',
        'pemakaian',
        'total_tagihan',
        'denda',
        'total_bayar',
        'tanggal_jatuh_tempo',
        'status',
    ];

    /**
     * Get the pelanggan that owns the Tagihan
     */
    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }

    /**
     * Get the bulan that owns the Tagihan
     */
    public function bulan()
    {
        return $this->belongsTo(Bulan::class);
    }

    /**
     * Get the tarif that owns the Tagihan
     */
    public function tarif()
    {
        return $this->belongsTo(Tarif::class);
    }
}
