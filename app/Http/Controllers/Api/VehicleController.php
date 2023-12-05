<?php

namespace App\Http\Controllers\Api;

use App\Models\VehicleModel;
use Illuminate\Http\Request;
use App\Adapters\SWAPIAdapter;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;

class VehicleController extends Controller
{
    public function index(Request $request){
        $params = $request->toArray();

        $swapi = new SWAPIAdapter('vehicles', $params);
        $swapiResponse = $swapi->getResponse();
        
        if($swapiResponse['status'] != 200){
            return response($swapiResponse['body'], $swapiResponse['status'])
                ->withHeaders(['Content-Type'=>'application/json']);
        }

        $vehicles = VehicleModel::whereIn('SWAPI_ID', $swapi->getIds())
            ->get()
            ->map(function ($item) use ($swapi){
                $vehicle = $swapi->filterById($item['SWAPI_ID']);
                return $item->mergeWithSwapiItem($vehicle);
            })->toArray();

        if(empty($vehicles)){
            $vehicles = $swapiResponse['body'];
        }

        return response($vehicles, 200)
            ->withHeaders(['Content-Type'=>'application/json']);   
    }

    public function show(Request $request){
        $params = $request->toArray();
        $id = (isset($request['vehicle'])) ? $request['vehicle'] : null;

        $swapi = new SWAPIAdapter('vehicles', $params, $id);
        $swapi = $swapi->getResponse();
        
        if($swapi['status'] != 200){
            return response($swapi['body'], $swapi['status'])
                ->withHeaders(['Content-Type'=>'application/json']);
        }

        $vehicle = VehicleModel::where('SWAPI_ID', $id)
            ->first()
            ?->mergeWithSwapiItem($swapi['body']);
        
        if($vehicle==null){
            $vehicle = $swapi['body'];
        }

        return response($vehicle, 200)
            ->withHeaders(['Content-Type'=>'application/json']);
    }

    public function update(Request $request){
        $data = $request->all();
        try {
            $data = request()->validate([
                'count' => 'required|int|min:0|max:4294967294',
            ]);

            $vehicle = VehicleModel::where('SWAPI_ID', $request['vehicle'])
                ->update(['COUNT' => $data['count']]);

            return response(['detail'=>'Created'], 201)
                ->withHeaders(['Content-Type'=>'application/json']);
        } catch (ValidationException $e) {
            return response()->json(['error' => 'Bad request', 'detail' => $e->getMessage()], 400);
        } catch (Exception $e){
            return response()->json([
                'error' => 'Internal Server Error', 
                'detail' => "The server has encountered a situation that it doesn't know how to handle."
            ], 500);
        }
    }

    public function increase(Request $request, $id){
        try {
            $vehicle = VehicleModel::where('SWAPI_ID', $id)->first();

            $data = $request->all();
            $data = request()->validate([
                'increaseBy' => 'required|int|min:0|max:' . 4294967294 - $vehicle['COUNT'],
            ]);

            $vehicle->update(['COUNT'=> $vehicle['COUNT']+$data['increaseBy']]);

            return response(['detail'=>'Created'], 201)
                ->withHeaders(['Content-Type'=>'application/json']);
        
        } catch (ValidationException $e) {
            return response()->json(['error' => 'Bad request', 'detail' => $e->getMessage()], 400);
        }  catch (Exception $e){
            return response()->json([
                'error' => 'Internal Server Error', 
                'detail' => "The server has encountered a situation that it doesn't know how to handle."
            ], 500);
        }
    }

    public function decrease(Request $request, $id){
        try {
            $vehicle = VehicleModel::where('SWAPI_ID', $id)->first();

            $data = $request->all();
            $data = request()->validate([
                'decreaseBy' => 'required|int|min:0|max:' . $vehicle['COUNT'],
            ]);

            $vehicle->update(['COUNT'=> $vehicle['COUNT']-$data['decreaseBy']]);

            return response(['detail'=>'Created'], 201)
                ->withHeaders(['Content-Type'=>'application/json']);
        
        } catch (ValidationException $e) {
            return response()->json(['error' => 'Bad request', 'detail' => $e->getMessage()], 400);
        } catch (Exception $e){
            return response()->json([
                'error' => 'Internal Server Error', 
                'detail' => "The server has encountered a situation that it doesn't know how to handle."
            ], 500);
        }
    }
}
