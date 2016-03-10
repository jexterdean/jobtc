<?php
Class DashboardController extends BaseController{

	public function index(){

		$client = Client::all();
		$user = User::all();
		$events = Events::where('username','=',Auth::user()->username)
			->orWhere('public','=','1')
			->get();
			
		$assets = ['knob','calendar'];

		if(Entrust::hasRole('Admin')){
			$estimate = Billing::where('billing_type','=','estimate')
				->get();

			$invoice = Billing::where('billing_type','=','invoice')
				->get();

			$payable = DB::table('fp_item')
				->join('fp_billing','fp_billing.billing_id','=','fp_item.billing_id')
				->select(DB::raw('sum(item_quantity*unit_price) AS totalSales'))
				->where('billing_type','=','invoice')
				->first();

			$paid = DB::table('fp_payment')
				->select(DB::raw('sum(payment_amount) AS totalPaid'))
				->first();

			$completedProjects = Project::where('project_progress','=','100')
				->count('project_id');

			$inCompletProjects = Project::where('project_progress','!=','100')
				->count('project_id');

			$projects = Project::where('project_progress','!=','100')
				->get();

			$bugs = Bug::where('bug_status','!=','resolved')
				->get();

			$tickets = Ticket::where('ticket_status','=','open')
				->get();

			$events = Events::where('username','=',Auth::user()->username)
				->orWhere('public','=','1')
				->get();	

			$tasks = Task::where('task_status','!=','completed')
				->get();	


			$data = [
						'clients' => $client,
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
		elseif(Entrust::hasRole('Staff')){

			$projects = DB::table('fp_project')
				->join('fp_assigned_user','fp_assigned_user.unique_id','=','fp_project.project_id')
				->where('belongs_to','=','project')
				->where('username','=',Auth::user()->username)
				->where('project_progress','!=','100')
				->get();

			$total_projects = DB::table('fp_project')
				->join('fp_assigned_user','fp_assigned_user.unique_id','=','fp_project.project_id')
				->where('belongs_to','=','project')
				->where('project_progress','=','100')
				->where('username','=',Auth::user()->username)
				->get();

			$bugs = DB::table('fp_bug')
				->join('fp_assigned_user','fp_assigned_user.unique_id','=','fp_bug.bug_id')
				->where('belongs_to','=','bug')
				->where('bug_status','!=','resolved')
				->where('username','=',Auth::user()->username)
				->get();

			$total_bugs = DB::table('fp_bug')
				->join('fp_assigned_user','fp_assigned_user.unique_id','=','fp_bug.bug_id')
				->where('belongs_to','=','bug')
				->where('bug_status','=','resolved')
				->where('username','=',Auth::user()->username)
				->get();

			$tickets = DB::table('fp_ticket')
				->join('fp_assigned_user','fp_assigned_user.unique_id','=','fp_ticket.ticket_id')
				->where('belongs_to','=','ticket')
				->where('fp_assigned_user.username','=',Auth::user()->username)
				->where('ticket_status','=','open')
				->get();

			$total_tickets = DB::table('fp_ticket')
				->join('fp_assigned_user','fp_assigned_user.unique_id','=','fp_ticket.ticket_id')
				->where('belongs_to','=','ticket')
				->where('fp_assigned_user.username','=',Auth::user()->username)
				->where('ticket_status','=','close')
				->get();

			$tasks = Task::where('task_status','!=','completed')
				->where('username','=',Auth::user()->username)
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
		elseif(Entrust::hasRole('Client')){

			$projects = DB::table('fp_project')
				->join('fp_user','fp_user.client_id','=','fp_project.client_id')
				->where('user_id','=',Auth::user()->user_id)
				->where('project_progress','!=','100')
				->get();

			$total_projects = DB::table('fp_project')
				->join('fp_user','fp_user.client_id','=','fp_project.client_id')
				->where('user_id','=',Auth::user()->user_id)
				->where('project_progress','=','100')
				->get();

			$bugs = DB::table('fp_bug')
				->join('fp_project','fp_project.project_id','=','fp_bug.project_id')
				->join('fp_user','fp_user.client_id','=','fp_project.client_id')
				->where('bug_status','!=','resolved')
				->where('fp_user.user_id','=',Auth::user()->user_id)
				->get();

			$total_bugs = DB::table('fp_bug')
				->join('fp_project','fp_project.project_id','=','fp_bug.project_id')
				->join('fp_user','fp_user.client_id','=','fp_project.client_id')
				->where('bug_status','=','resolved')
				->where('fp_user.user_id','=',Auth::user()->user_id)
				->get();

			$tickets = Ticket::where('username','=',Auth::user()->username)
				->where('ticket_status','=','open')
				->get();

			$total_tickets = Ticket::where('username','=',Auth::user()->username)
				->where('ticket_status','=','close')
				->get();

			$data = [
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
		return View::make('user.dashboard',$data);
	}
}
?>