<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TourImage extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "tour_place_image";
    protected $guarded = [];

}
