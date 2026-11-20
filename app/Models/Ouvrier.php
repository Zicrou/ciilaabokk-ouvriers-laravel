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
        'country_id',
        'departement_id',
        'telephone',
        'address',
        'telephone_2',
        'user_id',
    ];

    public function country()
    {
        return $this->belongsTo(Countries::class);
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function departement()
    {
        return $this->belongsTo(Departement::class);
    }

    public function domaines()
    {
        return $this->belongsToMany(Domaine::class );
    }

    public function metiers()
    {
        return $this->belongsToMany(Metier::class );
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
