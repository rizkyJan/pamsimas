<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Pelanggan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nama_pelanggan',
        'alamat',
        'no_hp',
        'status',
    ];

    public function user()
    {

        return $this->belongsTo(User::class);
    }
    public function tagihans()
    {
        return $this->hasMany(\App\Models\Tagihan::class, 'pelanggan_id');
    }
}
