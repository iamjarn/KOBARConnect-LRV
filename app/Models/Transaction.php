<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    public function tour(){
        return $this->belongsTo(TourPlace::class, "id_tour_place");
    }
}
