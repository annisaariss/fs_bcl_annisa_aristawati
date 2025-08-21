<?php

namespace App\Models;

use App\Models\Armada;
use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    protected $table = 'shipments';
    protected $fillable = ['nomor_pengiriman','tanggal_pengiriman','lokasi_asal','lokasi_tujuan','status','detail_barang','armada_id'];

    public function armada()
    {
        return $this->belongsTo(Armada::class);
    }
}
