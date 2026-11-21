<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use \App\Models\Region;
use Laravel\Sanctum\HasApiTokens;

class Countries extends Model
{
    use HasFactory, HasUuid, HasApitokens, softDeletes; 
    protected $table = 'countries';

    protected $fillable = ['nom'];

    public function regions()
    {
        return $this->hasMany(Region::class);
    }
}
