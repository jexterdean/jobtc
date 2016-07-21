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

                $body = [
                    "sort" => [
                        ["order" => ["order" => "asc"]],
                        ["id" => ["order" => "asc"]]
                    ]
                ];

                $matches = [
                    ['match' => ['title' => $search]]
                ];
                preg_match('!\d+!', $search, $m);
                $version = array_key_exists(0, $m) ? $m[0] : '';
                if ($version) {
                    $matches[] = ['match' => ['version' => $version]];
                }

                $body['query'] = [
                    'bool' => [
                        'must' => $matches
                    ]
                ];

                //Build elasticsearch query
                $params = [
                    'index' => 'default',
                    'type' => 'projects',
                    'client' => [
                        'ignore' => 404
                    ],
                    "fields" => "",
                    'body' => $body
                ];
                $search_results = $search_client->search($params);

                $searched_projects = $search_results['hits']['hits'];

                $project_ids = [];

                if (count($searched_projects) > 0) {
                    foreach ($searched_projects as $searched_project) {
                        $project_ids[] = $searched_project['_id'];
                    }
                }

                $results = Project::whereIn('project_id', $project_ids)->get();

                $assets = ['companies'];

                return view('search.results', [
                    'type' => $search_results,
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

}
