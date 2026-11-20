<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ouvrier extends Model
{
    use HasFactory, HasUuid; 
    protected $table = 'ouvriers';


    protected $fillable = [
        'nom',
        'region_id',
        'departement_id',
        'telephone',
        'email',
        'address',
        'telephone_2',
        'photo',
        'annees_experience',
        'entreprises',
    ];

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function departement()
    {
        return $this->belongsTo(Departement::class);
    }

    public function metiers()
    {
        return $this->belongsToMany(Metier::class, 'ouvrier_metier');
    }

    public function portfolios()
    {
        return $this->hasMany(Portfolio::class);
    }

    public function diplomes()
    {
        return $this->belongsToMany(Diplome::class);
    }

    public function entreprises()
    {
        return $this->belongsToMany(Entreprise::class);
    }
}
