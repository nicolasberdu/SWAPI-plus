<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Adapters\SWAPIAdapter;
use App\Http\Controllers\Controller;

class PlanetController extends Controller
{
    public function index(Request $request){
        $params = $request->toArray();
        
        $swapi = new SWAPIAdapter('planets', $params);
        return $swapi->getResponse();
    }

    public function show(Request $request){
        $params = $request->toArray();
        $id = (isset($request['planet'])) ? $request['planet'] : null;

        $swapi = new SWAPIAdapter('planets', $params, $id);
        return $swapi->getResponse();
    }
}
