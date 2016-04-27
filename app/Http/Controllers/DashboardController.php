<?php
namespace App\Http\Controllers;
use App\Http\Controllers\BaseController;

use \Auth;
use Illuminate\Http\Request;
use \View;
use \Form;
use \Input;
use \Redirect;
use \DB;
use App\Models\Client;
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
        $client = Client::all();
        $user = User::all();
        $events = Events::where('username', '=', parent::getActiveUser()->username)
            ->orWhere('public', '=', '1')
            ->get();

        $assets = ['knob', 'calendar', 'date', 'select', 'magicSuggest', 'waiting'];
        $data = [];
        
        $user_type = Auth::user('user')->user_type;

        if ($user_type === 1 || $user_type === 2 || $user_type === 3) {
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

            $inCompleteProjects = Project::where('project_progress', '!=', '100')
                ->count('project_id');

            $projects = Project::where('project_progress', '!=', '100')
                ->get();

            $bugs = Bug::where('bug_status', '!=', 'resolved')
                ->get();

            $tickets = Ticket::where('ticket_status', '=', 'open')
                ->get();

            $events = Events::where('username', '=', Auth::user()->username)
                ->orWhere('public', '=', '1')
                ->get();

            $tasks = Task::where('task_status', '!=', 'completed')
                ->get();


            $data = [
                'clients' => $client,
                'users' => $user,
                'estimates' => $estimate,
                'invoices' => $invoice,
                'payable' => $payable,
                'paid' => $paid,
                'completedProjects' => $completedProjects,
                'inCompletProjects' => $inCompleteProjects,
                'projects' => $projects,
                'bugs' => $bugs,
                'tickets' => $tickets,
                'assets' => $assets,
                'events' => $events,
                'tasks' => $tasks
            ];
        } elseif ($user_type === 4) {

            $projects = DB::table('project')
                ->join('assigned_user', 'assigned_user.unique_id', '=', 'project.project_id')
                ->where('belongs_to', '=', 'project')
                ->where('username', '=', Auth::user()->username)
                ->where('project_progress', '!=', '100')
                ->get();

            $total_projects = DB::table('project')
                ->join('assigned_user', 'assigned_user.unique_id', '=', 'project.project_id')
                ->where('belongs_to', '=', 'project')
                ->where('project_progress', '=', '100')
                ->where('username', '=', Auth::user()->username)
                ->get();

            $bugs = DB::table('bug')
                ->join('assigned_user', 'assigned_user.unique_id', '=', 'bug.bug_id')
                ->where('belongs_to', '=', 'bug')
                ->where('bug_status', '!=', 'resolved')
                ->where('username', '=', Auth::user()->username)
                ->get();

            $total_bugs = DB::table('bug')
                ->join('assigned_user', 'assigned_user.unique_id', '=', 'bug.bug_id')
                ->where('belongs_to', '=', 'bug')
                ->where('bug_status', '=', 'resolved')
                ->where('username', '=', Auth::user()->username)
                ->get();

            $tickets = DB::table('ticket')
                ->join('assigned_user', 'assigned_user.unique_id', '=', 'ticket.ticket_id')
                ->where('belongs_to', '=', 'ticket')
                ->where('assigned_user.username', '=', Auth::user()->username)
                ->where('ticket_status', '=', 'open')
                ->get();

            $total_tickets = DB::table('ticket')
                ->join('assigned_user', 'assigned_user.unique_id', '=', 'ticket.ticket_id')
                ->where('belongs_to', '=', 'ticket')
                ->where('assigned_user.username', '=', Auth::user()->username)
                ->where('ticket_status', '=', 'close')
                ->get();

            $tasks = Task::where('task_status', '!=', 'completed')
                ->where('username', '=', Auth::user()->username)
                ->get();

            $data = [
                'projects' => $projects,
                'bugs' => $bugs,
                'tickets' => $tickets,
                'total_projects' => $total_projects,
                'total_bugs' => $total_bugs,
                'total_tickets' => $total_tickets,
                'assets' => $assets,
                'tasks' => $tasks,
                'events' => $events
            ];
        }
        else {
            $projects = DB::table('project')
                ->join('user', 'user.client_id', '=', 'project.client_id')
                ->where('user_id', '=', Auth::user()->user_id)
                ->where('project_progress', '!=', '100')
                ->get();

            $total_projects = DB::table('project')
                ->join('user', 'user.client_id', '=', 'project.client_id')
                ->where('user_id', '=', Auth::user()->user_id)
                ->where('project_progress', '=', '100')
                ->get();

            $bugs = DB::table('bug')
                ->join('project', 'project.project_id', '=', 'bug.project_id')
                ->join('user', 'user.client_id', '=', 'project.client_id')
                ->where('bug_status', '!=', 'resolved')
                ->where('user.user_id', '=', Auth::user()->user_id)
                ->get();

            $total_bugs = DB::table('bug')
                ->join('project', 'project.project_id', '=', 'bug.project_id')
                ->join('user', 'user.client_id', '=', 'project.client_id')
                ->where('bug_status', '=', 'resolved')
                ->where('user.user_id', '=', Auth::user()->user_id)
                ->get();

            $tickets = Ticket::where('username', '=', Auth::user()->username)
                ->where('ticket_status', '=', 'open')
                ->get();

            $total_tickets = Ticket::where('username', '=', Auth::user()->username)
                ->where('ticket_status', '=', 'close')
                ->get();

            $data = [
                'clients' => $client,
                'projects' => $projects,
                'bugs' => $bugs,
                'tickets' => $tickets,
                'total_projects' => $total_projects,
                'total_bugs' => $total_bugs,
                'total_tickets' => $total_tickets,
                'assets' => $assets,
                'events' => $events
            ];
        }
        return View::make('user.dashboard', $data);
    }
}

?>