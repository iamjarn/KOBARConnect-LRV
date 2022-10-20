<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Log extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "log";
    protected $guarded = [];

    public function user(){
        return $this->belongsTo(User::class, 'id_user', 'id');
    }
}
