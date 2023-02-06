<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Creator extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function template(){
        return $this->belongsTo(Template::class,'TemplateID', 'id');
    }

    public function type(){
        return $this->belongsTo(Type::class,'TypeID', 'id');
    }

    public function audio(){
        return $this->belongsTo(Audio::class,'AudioID', 'id');
    }
}
