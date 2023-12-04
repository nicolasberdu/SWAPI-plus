<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Adapters\SWAPIAdapter;
use App\Http\Controllers\Controller;

class PeopleController extends Controller
{
    public function index(Request $request){
        $params = $request->toArray();

        $swapi = new SWAPIAdapter('people', $params);
        return $swapi->getResponse();
    }

    public function show(Request $request){
        $params = $request->toArray();
        $id = (isset($request['person'])) ? $request['person'] : null;

        $swapi = new SWAPIAdapter('people', $params, $id);
        return $swapi->getResponse();
    }
}
