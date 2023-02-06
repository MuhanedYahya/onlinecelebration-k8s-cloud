<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function creators(){

        return $this->hasMany(Creator::class, 'TypeID', 'id');

    }

    // public function name(){
    //     return $this->name . "_" . App::getLocale();
    // }

    // public function message(){
    //     return $this->name . "_" . App::getLocale();
    // }
}
