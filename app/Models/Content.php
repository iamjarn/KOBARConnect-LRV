<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Content extends Model
{
    protected $table = "content";
    protected $fillable = [
        'path',
        'file_type',
        'message',
        'order',
    ];
    protected $appends = [
        "full_path"
    ];
    use HasFactory, SoftDeletes;

    public function getFullPathAttribute(){
        return env("APP_URL").$this->path;
    }
}
