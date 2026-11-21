<?php

namespace App\Models;

use App\Models\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;

class Domaine extends Model
{
    use HasFactory, HasUuid, HasApiTokens, SoftDeletes;
    protected $table = 'domaines';

    protected $fillable = ['nom'];
    
    public function metiers()
    {
        return $this->hasMany(Metier::class);
    }

    public function ouvrier()
    {
        return $this->belongsToMany(Ouvrier::class );
    }
}
