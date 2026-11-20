<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Concerns\HasUuid;
class Domaine extends Model
{
    use SoftDeletes, HasUuid;
    protected $fillable =[
        'nom'
    ];

    public function metier(){
        return $this->hasMany(Metier::class);
    }
    public function ouvriers(){
        return $this->hasMany(Ouvrier::class);
    }
}
