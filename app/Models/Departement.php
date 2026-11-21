<?php

namespace App\Models;

use App\Models\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;

class Departement extends Model
{
    use HasFactory, HasUuid, softdeletes, HasApiTokens;
    protected $table = 'departements';

    protected $fillable = ['nom', 'region_id'];

    public function region()
    {
        return $this->belongsTo(Region::class);
    }
}
