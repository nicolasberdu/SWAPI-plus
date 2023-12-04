<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\StarshipModel;
use App\Adapters\SWAPIAdapter;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;

class StarshipController extends Controller
{
    public function index(Request $request){
        $params = $request->toArray();
        
        $swapi = new SWAPIAdapter('starships', $params);
        $swapiResponse = $swapi->getResponse();

        if($swapiResponse['status'] != 200){
            return response($swapiResponse['body'], $swapiResponse['status'])
                ->withHeaders(['Content-Type'=>'application/json']);
        }

        $starships = StarshipModel::whereIn('SWAPI_ID', $swapi->getIds())
            ->get()
            ->map(function ($item) use ($swapi){
                $starship = $swapi->filterById($item['SWAPI_ID']);
                return $item->mergeWithSwapiItem($starship);
            })->toArray();

        if(empty($starships)){
            $starships = $swapiResponse['body'];
        }

        return response($starships, 200)
            ->withHeaders(['Content-Type'=>'application/json']);        
    }

    public function show(Request $request){
        $params = $request->toArray();
        $id = (isset($request['starship'])) ? $request['starship'] : null;

        $swapiStarship = new SWAPIAdapter('starships', $params, $id);
        $swapiStarship = $swapiStarship->getResponse();
        
        if($swapiStarship['status'] != 200){
            return response($swapiStarship['body'], $swapiStarship['status'])
                ->withHeaders(['Content-Type'=>'application/json']);
        }

        $starship = StarshipModel::where('SWAPI_ID', $id)
            ->first()
            ?->mergeWithSwapiItem($swapiStarship['body']);
        
        if($starship==null){
            $starship = $swapiStarship['body'];
        }

        return response($starship, 200)
            ->withHeaders(['Content-Type'=>'application/json']);
    }

    public function update(Request $request){
        $data = $request->all();
        try {
            $data = request()->validate([
                'count' => 'required|int|min:0|max:4294967294',
            ]);

            $starship = StarshipModel::where('SWAPI_ID', $request['starship'])
                ->update(['COUNT' => $data['count']]);

            return response(['detail'=>'Created'], 201)
                ->withHeaders(['Content-Type'=>'application/json']);
        } catch (ValidationException $e) {
            return response()->json(['error' => 'Bad request', 'detalles' => $e->getMessage()], 400);
        } catch (Exception $e){
            return response()->json([
                'error' => 'Internal Server Error', 
                'detalles' => "The server has encountered a situation that it doesn't know how to handle."
            ], 500);
        }
    }

    public function increase(Request $request, $id){
        try {
            $starship = StarshipModel::where('SWAPI_ID', $id)->first();

            $data = $request->all();
            $data = request()->validate([
                'increaseBy' => 'required|int|min:0|max:' . 4294967294 - $vehicle['COUNT'],
            ]);

            $starship->update(['COUNT'=> $starship['COUNT']+$data['increaseBy']]);

            return response(['detail'=>'Created'], 201)
                ->withHeaders(['Content-Type'=>'application/json']);
        
        } catch (ValidationException $e) {
            return response()->json(['error' => 'Bad request', 'detalles' => $e->getMessage()], 400);
        } catch (Exception $e){
            return response()->json([
                'error' => 'Internal Server Error', 
                'detalles' => "The server has encountered a situation that it doesn't know how to handle."
            ], 500);
        }
    }

    public function decrease(Request $request, $id){
        try {
            $data = $request->all();
            $data = request()->validate([
                'decreaseBy' => 'required|int|min:0|max:' . $vehicle['COUNT'],
            ]);

            $starship = StarshipModel::where('SWAPI_ID', $id)->first();
            $starship->update(['COUNT'=> $starship['COUNT']-$data['decreaseBy']]);
        
            return response(['detail'=>'Created'], 201)
                ->withHeaders(['Content-Type'=>'application/json']);
        
        } catch (ValidationException $e) {
            return response()->json(['error' => 'Bad request', 'detalles' => $e->getMessage()], 400);
        } catch (Exception $e){
            return response()->json([
                'error' => 'Internal Server Error', 
                'detalles' => "The server has encountered a situation that it doesn't know how to handle."
            ], 500);
        }
    }
}
