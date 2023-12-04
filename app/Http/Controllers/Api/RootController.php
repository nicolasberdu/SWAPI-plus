<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Adapters\SWAPIAdapter;
use App\Http\Controllers\Controller;

class RootController extends Controller
{
    public function index(Request $request){
        $swapi = new SWAPIAdapter();
        return $swapi->getResponse();
    }
}
