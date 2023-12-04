<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Adapters\SWAPIAdapter;
use App\Http\Controllers\Controller;

class SpecieController extends Controller
{
    public function index(Request $request){
        $params = $request->toArray();

        $swapi = new SWAPIAdapter('species', $params);
        return $swapi->getResponse();
    }

    public function show(Request $request){
        $params = $request->toArray();
        $id = (isset($request['specie'])) ? $request['specie'] : null;

        $swapi = new SWAPIAdapter('species', $params, $id);
        return $swapi->getResponse();
    }
}
