<?php

namespace App\Http\Controllers;

//use App\Http\Controllers\BaseController;

use \Auth;
use Illuminate\Http\Request;
use \View;
use \Form;
use \Input;
use \Redirect;
use \DB;
use App\Models\Company;
use App\Models\User;
use App\Models\Events;
use App\Models\Bug;
use App\Models\Project;
use App\Models\Ticket;
use App\Models\Task;
use App\Models\Billing;

class DashboardController extends BaseController {

    public function index(Request $request) {

        $user_id = Auth::user('user')->user_id;

        $projects = Project::where('user_id', $user_id)->get();
        
        $assets = ['dashboard'];
        
        return view('user.dashboard', [
                    'projects' => $projects,
                    'assets' => $assets,
                    'company_id' => 0
        ]);
    }

}

?>
