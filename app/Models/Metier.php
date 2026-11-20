<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Concerns\HasUuid;
class Metier extends Model
{
    use HasUuid, softDeletes;
    
    protected $fillable =  [
        'nom',
        'domaine_id'
    ];

    public function domaine(){
        return $this->belongsTo(Domaine::class);
    }
    public function ouvriers(){
        return $this->belongsToMany(Ouvrier::class, 'ouvrier_metier');
    }

}
