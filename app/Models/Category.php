<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    protected $table = "category";
    protected $guarded = [];
    use HasFactory, SoftDeletes;

    protected $casts = [
        "is_enable_ticket"  => 'boolean'
    ];

    public function tours(){
        return $this->hasMany(TourPlace::class, 'id_category', 'id');
    }

    public function tours_recommend(){
        return $this->hasMany(TourPlace::class, 'id_category', 'id');
    }
}
