<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StarshipModel extends Model
{
    use HasFactory;

    protected $table = 'STARSHIPS';
    protected $primaryKey = 'SWAPI_ID';
    public $timestamps = false;
    
    protected $fillable = [
        'SWAPI_ID',
        'STARSHIP_CLASS',
        'COUNT'
    ];

    public function mergeWithSwapiItem($swapiStarship){
        $starship = $swapiStarship;
        $starship['count'] = $this['COUNT'];
        return $starship;
    }
}
