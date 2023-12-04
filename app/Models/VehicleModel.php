<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleModel extends Model
{
    use HasFactory;

    protected $table = 'VEHICLES';
    public $timestamps = false;
    protected $fillable = [
        'SWAPI_ID',
        'VEHICLE_CLASS',
        'COUNT'
    ];

    public function mergeWithSwapiItem($swapiVehicle){
        $vehicle = $swapiVehicle;
        $vehicle['count'] = $this['COUNT'];
        return $vehicle;
    }
}
