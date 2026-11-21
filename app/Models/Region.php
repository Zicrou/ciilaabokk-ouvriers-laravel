<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;

class Region extends Model
{
     use HasFactory, HasUuid, SoftDeletes, HasApiTokens; 
    protected $table = 'regions';

    protected $fillable = ['nom', 'country_id'];

    public function country()
    {
        return $this->belongsTo(Countries::class);
    }

    public function departements()
    {
        return $this->hasMany(Departement::class);
    }
}
