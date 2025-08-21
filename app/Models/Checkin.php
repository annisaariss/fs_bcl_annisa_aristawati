<?php

namespace App\Models;

use App\Models\Armada;
use Illuminate\Database\Eloquent\Model;

class Checkin extends Model
{
    protected $table = 'checkins';
    protected $fillable = ['armada_id','latitude','longitude','waktu_checkin'];

    public function armada()
    {
        return $this->belongsTo(Armada::class);
    }
}
