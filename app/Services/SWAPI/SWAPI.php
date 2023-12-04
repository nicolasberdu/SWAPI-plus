<?php

namespace App\Services\SWAPI;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Client\Response;

class SWAPI{

    protected string $baseUrl;
    protected int $liveTimeCache;
    protected string $resource = '';
    protected bool $isSingular;
    private string $params ='';
    
    
    public function __construct(){
        $this->baseUrl = env('SWAPI_BASE_URL');
        $this->liveTimeCache = (env('app.SWAPI_CACHE_TIME')) ? env('app.SWAPI_CACHE_TIME') : 60;
    }

    public function people(): SWAPI{
        $this->resource = '/people';
        return $this;
    }
    public function planets(): SWAPI{
        $this->resource = '/planets';
        return $this;
    }
    public function films(): SWAPI{
        $this->resource = '/films';
        return $this;
    }
    public function species(): SWAPI{
        $this->resource = '/species';
        return $this;
    }
    public function vehicles(): SWAPI{
        $this->resource = '/vehicles';
        return $this;
    }
    public function starships(): SWAPI{
        $this->resource = '/starships';
        return $this;
    }

    public function page(int $page = null): SWAPI{
        $this->setParam($page, 'page');
        return $this;
    }
    public function search(string $search = null): SWAPI{
        $this->setParam($search, 'search');
        return $this;
    }
    public function format(string $format = null): SWAPI{
        $this->setParam($format, 'format');
        return $this;
    }

    private function setParam(string $param = null, string $paramName = null): void{
        if($param !== null && $paramName !== null){
            $this->params .= (empty($this->params)) ? '/?'. $paramName . '=' . $param : '&'. $paramName . '=' . $param;
        }
    }

    public function get(int $id = null){
        $path = $this->resource;
        if($id !== null){
            $this->isSingular = true;
            $path .=  '/' . $id . $this->params; ;
        }else{
            $this->isSingular = false;
            $path .= $this->params;
        }

        $url = $this->baseUrl . $path;
        
        $response = Cache::remember('swapi_' . $path, (int) $this->liveTimeCache, function () use ($url) {
            $response = Http::withOptions(['verify' => false])->get($url);
            return collect([
                'status' => $response->status(),
                'body' => $response->collect(),
            ]);
        });
        return $response;
    }
}