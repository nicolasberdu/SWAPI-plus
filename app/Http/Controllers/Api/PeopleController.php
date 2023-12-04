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
        $swapi = $swapi->getResponse();

        return response($swapi['body'], $swapi['status'])
            ->withHeaders(['Content-Type'=>'application/json']);
    }

    public function show(Request $request){
        $params = $request->toArray();
        $id = (isset($request['person'])) ? $request['person'] : null;

        $swapi = new SWAPIAdapter('people', $params, $id);
        $swapi = $swapi->getResponse();

        return response($swapi['body'], $swapi['status'])
            ->withHeaders(['Content-Type'=>'application/json']);
    }
}
