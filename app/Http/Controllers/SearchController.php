<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Elasticsearch\ClientBuilder as ES;
use App\Models\Project;
use App\Models\Task;
use App\Models\TaskChecklist;
use App\Models\Job;
use App\Models\User;
use App\Models\Applicant;
use App\Models\Test;
use App\Models\Role;
use Kordy\Ticketit\Models\Ticket;

class SearchController extends Controller {

    public function bulkIndex(Request $request, $type) {

        $client = ES::create()
                ->setHosts(\Config::get('elasticsearch.host'))
                ->build();

        if ($type === 'project') {

            $projects = Project::all();

            $params = array();
            foreach ($projects as $project) {

                $params['body'] = array(
                    'project_title' => $project->project_title
                );
                $params['index'] = 'default';
                $params['type'] = 'project';
                $params['id'] = $project->project_id;
                $results = $client->index($params);       //using Index() function to inject the data
            }

            return "Finished Indexing Projects";
        }

        if ($type === 'briefcase') {
        
            $briefcases = Task::all();
            
            $params = array();
            foreach ($briefcases as $briefcase) {

                $params['body'] = array(
                    'task_title' => $briefcase->task_title
                );
                $params['index'] = 'default';
                $params['type'] = 'briefcase';
                $params['id'] = $briefcase->task_id;
                $results = $client->index($params);       //using Index() function to inject the data
            }

            return "Finished Indexing Briefcases";
        }
        
        if ($type === 'task item') {
        
            $taskitems = TaskChecklist::all();
            
            $params = array();
            foreach ($taskitems as $taskitem) {

                $params['body'] = array(
                    'checklist_header' => $taskitem->checklist_header
                );
                $params['index'] = 'default';
                $params['type'] = 'taskitem';
                $params['id'] = $taskitem->id;
                $results = $client->index($params);       //using Index() function to inject the data
            }

            return "Finished Indexing Task Items";
        }

        if ($type === 'job') {

            $jobs = Job::all();

            $params = array();
            foreach ($jobs as $job) {

                $params['body'] = array(
                    'title' => $job->title
                );
                $params['index'] = 'default';
                $params['type'] = 'job';
                $params['id'] = $job->id;
                $results = $client->index($params);       //using Index() function to inject the data
            }

            return "Finished Indexing Job";
        }

        if ($type === 'applicant') {

            $applicants = Applicant::all();

            $params = array();
            foreach ($applicants as $applicant) {

                $params['body'] = array(
                    'name' => $applicant->name
                );
                $params['index'] = 'default';
                $params['type'] = 'applicant';
                $params['id'] = $applicant->id;
                $results = $client->index($params);       //using Index() function to inject the data
            }

            return "Finished Indexing Applicants";
        }

        if ($type === 'employee') {

            $employees = User::all();

            $params = array();
            foreach ($employees as $employee) {

                $params['body'] = array(
                    'name' => $employee->name
                );
                $params['index'] = 'default';
                $params['type'] = 'employee';
                $params['id'] = $employee->user_id;
                $results = $client->index($params);       //using Index() function to inject the data
            }

            return "Finished Indexing Employees";
        }

        if ($type === 'test') {

            $tests = Test::all();

            $params = array();
            foreach ($tests as $test) {

                $params['body'] = array(
                    'title' => $test->title
                );
                $params['index'] = 'default';
                $params['type'] = 'test';
                $params['id'] = $test->id;
                $results = $client->index($params);       //using Index() function to inject the data
            }

            return "Finished Indexing Tests";
        }

        if ($type === 'ticket') {

            $tickets = Ticket::all();

            $params = array();
            foreach ($tickets as $ticket) {

                $params['body'] = array(
                    'title' => $ticket->title
                );
                $params['index'] = 'default';
                $params['type'] = 'ticket';
                $params['id'] = $ticket->id;
                $results = $client->index($params);       //using Index() function to inject the data
            }

            return "Finished Indexing Tickets";
        }

        if ($type === 'position') {

            $positions = Role::all();

            $params = array();
            foreach ($positions as $position) {

                $params['body'] = array(
                    'name' => $position->name
                );
                $params['index'] = 'default';
                $params['type'] = 'position';
                $params['id'] = $position->id;
                $results = $client->index($params);       //using Index() function to inject the data
            }

            return "Finished Indexing Positions";
        }
    }

    public function search(Request $request, $type) {

        $search_client = ES::create()
                ->setHosts(\Config::get('elasticsearch.host'))
                ->build();

        $term = $request->input('term');

        switch ($type) {

            case "project" :

                //Build elasticsearch query
                $params = [
                    'index' => 'default',
                    'type' => 'project',
                    'body' => [
                        'query' => [
                            'query_string' => [
                                'query' => 'project_title:*' . $term . '*'
                            ]
                        ]
                    ]
                ];
                $search_results = $search_client->search($params);

                $searched_projects = $search_results["hits"]["hits"];

                $ids = [];

                foreach ($searched_projects as $project) {
                    array_push($ids, $project["_id"]);
                }

                $results = Project::whereIn('project_id', $ids)->orderBy('project_title', 'desc')->get();

                $assets = ['search'];

                return view('search.results', [
                    'results' => $results,
                    'type' => $type,
                    'term' => $term,
                    'assets' => $assets
                ]);

                break;
                
            case "briefcase" :

                //Build elasticsearch query
                $params = [
                    'index' => 'default',
                    'type' => 'briefcase',
                    'body' => [
                        'query' => [
                            'query_string' => [
                                'query' => 'task_title:*' . $term . '*'
                            ]
                        ]
                    ]
                ];
                $search_results = $search_client->search($params);

                $searched_briefcases = $search_results["hits"]["hits"];

                $ids = [];

                foreach ($searched_briefcases as $briefcase) {
                    array_push($ids, $briefcase["_id"]);
                }

                $results = Task::whereIn('task_id', $ids)->orderBy('task_title', 'desc')->get();

                $assets = ['search'];

                return view('search.results', [
                    'results' => $results,
                    'type' => $type,
                    'term' => $term,
                    'assets' => $assets
                ]);

                break;    
            
            case "task item" :

                //Build elasticsearch query
                $params = [
                    'index' => 'default',
                    'type' => 'taskitem',
                    'body' => [
                        'query' => [
                            'query_string' => [
                                'query' => 'checklist_header:*' . $term . '*'
                            ]
                        ]
                    ]
                ];
                $search_results = $search_client->search($params);

                $searched_taskitems = $search_results["hits"]["hits"];

                $ids = [];

                foreach ($searched_taskitems as $taskitem) {
                    array_push($ids, $taskitem["_id"]);
                }

                $results = TaskChecklist::whereIn('id', $ids)->orderBy('checklist_header', 'desc')->get();

                $assets = ['search'];

                return view('search.results', [
                    'results' => $results,
                    'type' => $type,
                    'term' => $term,
                    'assets' => $assets
                ]);

                break;    
                
            case "job" :

                //Build elasticsearch query
                $params = [
                    'index' => 'default',
                    'type' => 'job',
                    'body' => [
                        'query' => [
                            'query_string' => [
                                'query' => 'title:*' . $term . '*'
                            ]
                        ]
                    ]
                ];
                $search_results = $search_client->search($params);

                $searched_jobs = $search_results["hits"]["hits"];

                $ids = [];

                foreach ($searched_jobs as $job) {
                    array_push($ids, $job["_id"]);
                }

                $results = Job::whereIn('id', $ids)->get();

                $assets = ['search'];

                return view('search.results', [
                    'results' => $results,
                    'type' => $type,
                    'term' => $term,
                    'assets' => $assets
                ]);

                break;

            case "applicant" :

                //Build elasticsearch query
                $params = [
                    'index' => 'default',
                    'type' => 'applicant',
                    'body' => [
                        'query' => [
                            'query_string' => [
                                'query' => 'name:*' . $term . '*'
                            ]
                        ]
                    ]
                ];
                $search_results = $search_client->search($params);

                $searched_applicants = $search_results["hits"]["hits"];

                $ids = [];

                foreach ($searched_applicants as $applicant) {
                    array_push($ids, $applicant["_id"]);
                }

                $results = Applicant::whereIn('id', $ids)->get();

                $assets = ['search'];

                return view('search.results', [
                    'results' => $results,
                    'type' => $type,
                    'term' => $term,
                    'assets' => $assets
                ]);

                break;

            case "employee" :

                //Build elasticsearch query
                $params = [
                    'index' => 'default',
                    'type' => 'employee',
                    'body' => [
                        'query' => [
                            'query_string' => [
                                'query' => 'name:*' . $term . '*'
                            ]
                        ]
                    ]
                ];
                $search_results = $search_client->search($params);

                $searched_employees = $search_results["hits"]["hits"];

                $ids = [];

                foreach ($searched_employees as $employee) {
                    array_push($ids, $employee["_id"]);
                }

                $results = User::with(['profile' => function($query) {
                                $query->with('company')->get();
                            }])->whereIn('user_id', $ids)->get();

                $assets = ['search'];

                return view('search.results', [
                    'results' => $results,
                    'type' => $type,
                    'term' => $term,
                    'assets' => $assets
                ]);

                break;

            case "test" :

                //Build elasticsearch query
                $params = [
                    'index' => 'default',
                    'type' => 'test',
                    'body' => [
                        'query' => [
                            'query_string' => [
                                'query' => 'title:' . $term . '*'
                            ]
                        ]
                    ]
                ];
                $search_results = $search_client->search($params);

                $searched_tests = $search_results["hits"]["hits"];

                $ids = [];

                foreach ($searched_tests as $test) {
                    array_push($ids, $test["_id"]);
                }

                $results = Test::whereIn('id', $ids)->get();

                $assets = ['search'];

                return view('search.results', [
                    'results' => $results,
                    'type' => $type,
                    'term' => $term,
                    'assets' => $assets
                ]);

                break;

            case "position" :

                //Build elasticsearch query
                $params = [
                    'index' => 'default',
                    'type' => 'position',
                    'body' => [
                        'query' => [
                            'query_string' => [
                                'query' => 'name:*' . $term . '*'
                            ]
                        ]
                    ]
                ];
                $search_results = $search_client->search($params);

                $searched_roles = $search_results["hits"]["hits"];

                $ids = [];

                foreach ($searched_roles as $role) {
                    array_push($ids, $role["_id"]);
                }

                $results = Role::whereIn('id', $ids)->get();

                $assets = ['search'];

                return view('search.results', [
                    'results' => $results,
                    'type' => $type,
                    'term' => $term,
                    'assets' => $assets
                ]);

                break;

            case "ticket" :

                //Build elasticsearch query
                $params = [
                    'index' => 'default',
                    'type' => 'ticket',
                    'body' => [
                        'query' => [
                            'query_string' => [
                                'query' => 'subject:*' . $term . '*'
                            ]
                        ]
                    ]
                ];
                $search_results = $search_client->search($params);

                $searched_tickets = $search_results["hits"]["hits"];

                $ids = [];

                foreach ($searched_tickets as $ticket) {
                    array_push($ids, $ticket["_id"]);
                }

                $results = Ticket::whereIn('id', $ids)->get();

                $assets = ['search'];

                return view('search.results', [
                    'results' => $results,
                    'type' => $type,
                    'term' => $term,
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
