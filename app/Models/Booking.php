<?php

namespace App\Models;

use App\Models\Armada;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $table = 'bookings';
    protected $fillable = ['armada_id','tanggal_pemesanan','detail_barang'];

    public function armada()
    {
        return $this->belongsTo(Armada::class);
    }
}
