<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Adapters\SWAPIAdapter;




class FilmController extends Controller
{
     
    public function index(Request $request){
        $params = $request->toArray();

        $swapi = new SWAPIAdapter('films', $params);
        $swapi = $swapi->getResponse();
        
        return response($swapi['body'], $swapi['status'])
            ->withHeaders(['Content-Type'=>'application/json']);
    }

    public function show(Request $request){
        $params = $request->toArray();
        $id = (isset($request['film'])) ? $request['film'] : null;

        $swapi = new SWAPIAdapter('films', $params, $id);
        $swapi = $swapi->getResponse();

        return response($swapi['body'], $swapi['status'])
            ->withHeaders(['Content-Type'=>'application/json']);
    }
}
