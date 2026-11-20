<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Concerns\HasUuid;
class Departement extends Model
{
    use SoftDeletes, HasUuid;

    protected $fillable = [
        'nom',
        'region_id'
    ];

    public function region(){
        return $this->belongsTo(Region::class);
    }
    public function ouvriers(){
        return $this->hasMany(Ouvrier::class);
    }
}
