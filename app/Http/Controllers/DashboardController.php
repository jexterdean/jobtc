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

class DashboardController extends BaseController
{

    public function index(Request $request)
    {
        $companies = Company::all();
        $user = User::find($request->user()->user_id);
        
        $events = Events::where('user_id', '=', Auth::user()->id)
            ->orWhere('public', '=', '1')
            ->get();

        $assets = ['knob', 'calendar', 'date', 'select', 'magicSuggest', 'waiting'];
        $data = [];
        
        
        //Level 1 is always the highest user level
        if ($user->level() === 1) {
            $estimate = Billing::where('billing_type', '=', 'estimate')
                ->get();

            $invoice = Billing::where('billing_type', '=', 'invoice')
                ->get();

            $payable = DB::table('item')
                ->join('billing', 'billing.billing_id', '=', 'item.billing_id')
                ->select(DB::raw('sum(item_quantity*unit_price) AS totalSales'))
                ->where('billing_type', '=', 'invoice')
                ->first();

            $paid = DB::table('payment')
                ->select(DB::raw('sum(payment_amount) AS totalPaid'))
                ->first();

            $completedProjects = Project::where('project_progress', '=', '100')
                ->count('project_id');

            $inCompletProjects = Project::where('project_progress', '!=', '100')
                ->count('project_id');

            $projects = Project::where('project_progress', '!=', '100')
                ->get();

            $bugs = Bug::where('bug_status', '!=', 'resolved')
                ->get();

            $tickets = Ticket::where('ticket_status', '=', 'open')
                ->get();

            $events = Events::where('user_id', '=', Auth::user()->id)
                ->orWhere('public', '=', '1')
                ->get();

            $tasks = Task::where('task_status', '!=', 'completed')
                ->get();


            $data = [
                'companies' => $companies,
                'users' => $user,
                'estimates' => $estimate,
                'invoices' => $invoice,
                'payable' => $payable,
                'paid' => $paid,
                'completedProjects' => $completedProjects,
                'inCompletProjects' => $inCompletProjects,
                'projects' => $projects,
                'bugs' => $bugs,
                'tickets' => $tickets,
                'assets' => $assets,
                'events' => $events,
                'tasks' => $tasks
            ];
        }
        elseif ($user->level() > 1) {
           
            $estimate = Billing::where('billing_type', '=', 'estimate')
                ->get();

            $invoice = Billing::where('billing_type', '=', 'invoice')
                ->get();

            $payable = DB::table('item')
                ->join('billing', 'billing.billing_id', '=', 'item.billing_id')
                ->select(DB::raw('sum(item_quantity*unit_price) AS totalSales'))
                ->where('billing_type', '=', 'invoice')
                ->first();

            $paid = DB::table('payment')
                ->select(DB::raw('sum(payment_amount) AS totalPaid'))
                ->first();

            $completedProjects = Project::where('project_progress', '=', '100')
                ->count('project_id');

            $inCompletProjects = Project::where('project_progress', '!=', '100')
                ->count('project_id');

            $projects = Project::where('project_progress', '!=', '100')
                ->get();

            $bugs = Bug::where('bug_status', '!=', 'resolved')
                ->get();

            $tickets = Ticket::where('ticket_status', '=', 'open')
                ->get();

            $events = Events::where('user_id', '=', Auth::user()->id)
                ->orWhere('public', '=', '1')
                ->get();

            $tasks = Task::where('task_status', '!=', 'completed')
                ->get();


            $data = [
                'companies' => $companies,
                'users' => $user,
                'estimates' => $estimate,
                'invoices' => $invoice,
                'payable' => $payable,
                'paid' => $paid,
                'completedProjects' => $completedProjects,
                'inCompletProjects' => $inCompletProjects,
                'projects' => $projects,
                'bugs' => $bugs,
                'tickets' => $tickets,
                'assets' => $assets,
                'events' => $events,
                'tasks' => $tasks
            ]; 

        }
        
        return View::make('user.dashboard', $data);
    }
}
?>
