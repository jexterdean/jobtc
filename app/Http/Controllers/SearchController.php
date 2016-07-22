<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Elasticsearch\ClientBuilder as ES;
use App\Models\Project;

class SearchController extends Controller {

    public function search(Request $request, $type) {

        $search_client = ES::create()
                ->setHosts(\Config::get('elasticsearch.host'))
                ->build();

        $search = 'Test';

        switch ($type) {

            case "project" :

                //Build elasticsearch query
                $params = [
                    'index' => 'default',
                    'type' => 'project',
                    'body' => [
                        'query' => [
                            'match' => [
                                'project_title' => $search
                            ]
                        ]
                    ]
                ];
                $search_results = $search_client->search($params);

                $searched_projects = $search_results["hits"]["hits"];

                $count = 0;

                $ids = [];
                
                foreach($searched_projects as $project) {
                    array_push($ids,$project["_id"]);
                }
                
                $results = Project::whereIn('project_id',$ids)->get();
                
                $assets = ['search'];

                return view('search.results', [
                    'results' => $results,
                    'type' => $type,
                    'assets' => $assets
                ]);

                break;
        }
    }

    public function searchIndex(Request $request) {
        $assets = ['companies'];
        return view('search.search', [
            'assets' => $assets
        ]);
    }

    public function enter($age, $name) {

        $client = ES::create()
                ->setHosts(\Config::get('elasticsearch.host'))
                ->build();
        $params = array();
        $params['body'] = array(
            'name' => $name,
            'age' => $age
        );
        $params['index'] = 'default';
        $params['type'] = 'project';
        $results = $client->index($params);       //using Index() function to inject the data
        $assets = ['companies'];

        return view('search.results', [
            'type' => $results,
            'assets' => $assets
        ]);
    }

    public function find($age) {

        $client = ES::create()
                ->setHosts(\Config::get('elasticsearch.host'))
                ->build();

        $params['index'] = 'default';
        $params['type'] = 'project';
        $params['body']['query']['match']['project_title'] = $age;

        $results = $client->search($params);       //using Index() function to inject the data

        $assets = ['companies'];

        return view('search.find', [
            'type' => json_encode($results),
            'assets' => $assets
        ]);
    }

}
