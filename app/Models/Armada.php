<?php

namespace App\Models;

use App\Models\Booking;
use App\Models\Checkin;
use App\Models\Shipment;
use Illuminate\Database\Eloquent\Model;

class Armada extends Model
{
    protected $table = 'armadas';
    protected $fillable = ['nomor_armada','jenis_kendaraan','status_ketersediaan','kapasitas_muatan'];

    public function shipments()
    {
        return $this->hasMany(Shipment::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function checkins()
    {
        return $this->hasMany(Checkin::class);
    }
}
