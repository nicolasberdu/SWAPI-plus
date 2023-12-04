<?php

namespace App\Adapters;

use Illuminate\Http\Request;
use App\Services\SWAPI\SWAPI;
use Illuminate\Support\Collection;
use Illuminate\Http\Client\Response;


class SWAPIAdapter extends SWAPI{

    //private Request $request;
    private $response;
    private $baseAppUrl;


    public function __construct($resource = '', $params = [], $id=null){
        //$this->request = $request;
        $this->baseAppUrl = env("APP_URL") . '/api';
        parent::__construct();
        $this->setResource($resource);

        $this->setParams($params);
        $this->response = $this->get($id);
        //$this->adaptRequest();
        $this->adaptResponse();
    }

    public function getResponse(){
        return $this->response;
    }

    public function toCollection(): Collection{
        //
    }
    public function toArray(){
        //
    }

    private function adaptRequest(): void{
        $params = $this->request->toArray();
        $id = (isset($this->request['id'])) ? $this->request['id'] : null;

        $this->setParams($params);
        $this->response = $this->get($id);
    }
    private function adaptResponse(): void{
        if($this->response['status'] === 200){
            $this->response = $this->response->replace([
                'body' => $this->transformUrls($this->response['body']), 
            ]);
        }
    }

    private function setResource($resource){
        $this->methodSelector($resource);
    }
    private function setParams(array $params = []){
        foreach($params as $key => $param){
            $this->methodSelector($key, $param);
        }
    }

    private function methodSelector(string $key = null, $value = null): SWAPIAdapter|null{
        switch($key){
            case 'people':
                return $this->people();
            case 'planets':
                return $this->planets();
            case 'films':
                return $this->films();
            case 'species':
                return $this->species();
            case 'vehicles':
                return $this->vehicles();
            case 'starships':
                return $this->starships();

            case 'page':
                return $this->page($value);
            case 'search':
                return $this->search($value);
            case 'format':
                return $this->format($value);
            break;
        }
        return null;
    }

    private function transformUrls(Collection $collection){
        if($this->resource == ''){
            $collection = $collection->map(function ($item) {
                return $this->transformUrl($item);
            });
        }elseif($this->isSingular){
            $collection = $this->tansformItemsResult($collection);
        }else{
            $collection = $collection->replace([
                'next' => $this->transformUrl($collection['next']), 
                'previous' => $this->transformUrl($collection['previous']),
                'results' => $this->tansformResultsItem($collection['results'])
            ]);
        }

        return $collection;
    }

    private function tansformResultsItem(array $results){
        foreach ($results as $key => $result){
            $results[$key] = $this->tansformItemsResult($result);
        }

        return $results;
    }
    private function tansformItemsResult($result){
        $items = [
            'films',
            'species', 
            'vehicles', 
            'starships', 
            'residents', 
            'characters', 
            'people', 
            'planets', 
            'pilots'
        ];

        foreach($items as $item){
            if(isset($result[$item])){
                $result[$item] = array_map(function ($value){
                    
                    return $this->transformUrl($value);
                }, $result[$item]);
            }
        }
        if(isset($result['url'])){
            $result['url'] = $this->transformUrl($result['url']);
        }
        if(isset($result['homeworld'])){
            $result['homeworld'] = $this->transformUrl($result['homeworld']);
        }
        return $result;
    }


    private function transformUrl($url){
        return str_replace($this->baseUrl, $this->baseAppUrl, $url);
    }

    public function getIds(): array{
        $results = $this->response['body']['results'];
        $ids = array();

        foreach($results as $result){
            $id[] = $this->getId($result);
        }
        return $id;
    }

    private function getId(array $item): int{
        return (int) str_replace($this->baseAppUrl . $this->resource . '/', '', $item['url']);
    }

    public function filterById($id): array|null{
        $results = $this->response['body']['results'];

        foreach($results as $result){
            if($id == $this->getId($result)){
                return $result;
            }
        }
        return null;
    }
}