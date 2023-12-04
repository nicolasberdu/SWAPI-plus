<?php


namespace Database\Seeders;

use Exception;
use App\Models\VehicleModel;
//use Database\Seeders\Adapters\SWAPIAdapter;
use App\Adapters\SWAPIAdapter;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class VehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $baseUrl = env("APP_URL") . '/api/vehicles/';
        $baseNextUrl = $baseUrl . '?page=';
        $nextPage = null;

        
        try {
            DB::beginTransaction();
            do{

                $swapi = new SwAPIAdapter('vehicles', ['page' => $nextPage]);
                $swapi = $swapi->getResponse()->toArray();
    
                $nextPage = (isset($swapi['body']['next'])) ? (int) str_replace($baseNextUrl, '', $swapi['body']['next']) : null;
                $vehicles = (isset($swapi['body']['results'])) ? $swapi['body']['results']: [];
    
                foreach ($vehicles as $vehicle){
                    VehicleModel::create([
                        'SWAPI_ID' => (int) str_replace($baseUrl, '', $vehicle['url']),
                        'VEHICLE_CLASS' => $vehicle['vehicle_class']
                    ]);
                }
    
            }while ($nextPage!==null);
            DB::commit();
            VehicleModel::all()->dd();
        } catch (Exception $e) {
            DB::rollBack();
            dd($e);
        }
    }
}
