<?php

namespace App\Models;

use App\Models\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;
class Metier extends Model
{
    use HasFactory, HasUuid, HasApiTokens, SoftDeletes;
    protected $table = 'metiers';

    protected $fillable = ['nom', 'domaine_id'];

    public function domaine()
    {
        return $this->belongsTo(Domaine::class);
    }

    public function ouvriers()
    {
        return $this->belongsToMany(Ouvrier::class );
    }
}
