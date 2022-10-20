<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TourPlace extends Model
{
    protected $table = "tour_places";
    protected $appends =[
        "generated_images"
    ];
    protected $guarded = [];

    use HasFactory, SoftDeletes;

    public function category()
    {
        return $this->belongsTo(Category::class, 'id_category', 'id');
    }

    public function images()
    {
        return $this->hasMany(TourImage::class, 'id_tour_place', 'id');
    }

    public function getGeneratedImagesAttribute($value)
    {
        $new_list = [];
        foreach($this->images as $image){
            $new_list[] = env('APP_URL').$image->path ?? null;
        }
        return $new_list;
    }

    public function events(){
        return $this->hasMany(Event::class, 'id_tour_place', 'id');
    }
}
