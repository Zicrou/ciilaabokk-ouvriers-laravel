<?php

namespace App\Models;

use App\Models\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Diplome extends Model
{
    use HasFactory, HasUuid;
    protected $table = 'diplomes';

    protected $fillable = ['nom'];
    public function ouvrier()
    {
        return $this->belongsToMany(Ouvrier::class );
    }
}
