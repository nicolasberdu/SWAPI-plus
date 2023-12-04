<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\StarshipModel;
//use Database\Seeders\Adapters\SWAPIAdapter;
use App\Adapters\SWAPIAdapter;
use Illuminate\Support\Facades\DB;

class StarshipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $baseUrl = env("APP_URL") . '/api/starships/';
        $baseNextUrl = $baseUrl . '?page=';
        $nextPage = null;

        
        try {
            DB::beginTransaction();
            do{

                $swapi = new SwAPIAdapter('starships', ['page' => $nextPage]);
                $swapi = $swapi->getResponse()->toArray();
    
                $nextPage = (isset($swapi['body']['next'])) ? (int) str_replace($baseNextUrl, '', $swapi['body']['next']) : null;
                $starships = (isset($swapi['body']['results'])) ? $swapi['body']['results']: [];
    
                foreach ($starships as $starship){
                    StarshipModel::create([
                        'SWAPI_ID' => (int) str_replace($baseUrl, '', $starship['url']),
                        'STARSHIP_CLASS' => $starship['starship_class']
                    ]);
                }
    
            }while ($nextPage!==null);
            DB::commit();
            StarshipModel::all()->dd();
        } catch (Exception $e) {
            DB::rollBack();
            dd($e);
        }
    }
}
