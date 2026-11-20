<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Concerns\HasUuid;
class Region extends Model
{
    use SoftDeletes, HasUuid;
    protected $fillable =[
        'nom'
    ];

    public function departement(){
        return $this->hasMany(Departement::class);
    }
    public function ouvriers(){
        return $this->hasMany(Ouvrier::class);
    }
    
}
