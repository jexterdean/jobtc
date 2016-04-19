<?php

namespace App\Http\Controllers;
use App\Http\Controllers\BaseController;
use Bican\Roles\Exceptions\RoleDeniedException;
use Illuminate\Http\Request;

use App\Models\Task;
use App\Models\User;
use App\Models\TaskTimer;
use App\Models\TaskChecklist;
use App\Models\Link;
use App\Models\LinkCategory;

use View;
use Auth;
use Redirect;
use Validator;
use Input;
use \DB;

class TaskController extends BaseController
{

    function __construct()
    {
        //Only staff and admin can access
        if(parent::hasRole('client')){
            throw  new RoleDeniedException('Client or Admin');
        }
    }

    /**
     * @return mixed
     */
    public function index()
    {

        if (parent::hasRole('staff')) {
<<<<<<< HEAD
            $tasks = Task::where('user_id', '=', Auth::user()->user_id)
=======
            $tasks = Task::where('email', '=', Auth::user('user')->email)
>>>>>>> 9c35634d6341f4119334b566861bca0dd430be62
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            $tasks = Task::orderBy('created_at', 'desc')
                ->join('user', 'task.user_id', '=', 'user.user_id')
                ->select(
                    'task.*','user.name', 'user.username'
                )
                ->get();
        }

        $belongsTo = 'task';


<<<<<<< HEAD
<<<<<<< HEAD
        $assign_username = User::orderBy('first_name', 'asc')
            ->lists('email', 'email');
=======
        $assign_username = User::orderBy('name')
            ->lists('id', 'name');
>>>>>>> 7961e7ff7602b9e3394a2c9c4880dfe48422af76
=======
        $assign_username = User::orderBy('name')
            ->lists('name', 'user_id');
=======
        $assign_username = User::orderBy('first_name', 'asc')
            ->lists('email', 'email');
>>>>>>> 9c35634d6341f4119334b566861bca0dd430be62
>>>>>>> project_update

        $assets = ['calendar','table'];

        return View::make('task.index', [
            'assets' => $assets,
            'tasks' => $tasks,
            'belongs_to'=> $belongsTo,
            'isClient'=> parent::hasRole('client'),
            'assign_username' => $assign_username
        ]);
    }

    public function show($id)
    {
        //
        $task = [];
        if (parent::userHasRole('Admin')){
            $task = Task::find($id);
        }
        elseif (parent::userHasRole('Client')) {
            $task = DB::table('task')
                ->join('user', 'user.client_id', '=', 'task.client_id')
                ->where('user_id', '=', Auth::user()->user_id)
                ->where('task_id', '=', $id)
                ->first();
        } elseif (parent::userHasRole('Staff')) {
            $task = DB::table('task')
                ->join('assigned_user', 'assigned_user.unique_id', '=', 'project.project_id')
                ->where('belongs_to', '=', 'project')
                ->where('username', '=', Auth::user()->username)
                ->where('project_id', '=', $id)
                ->first();
        }
        $task_timer = DB::table('task_timer')
            ->leftJoin('user', 'task_timer.user_id', '=', 'user.user_id')
            ->leftJoin('task', 'task_timer.task_id', '=', 'task.task_id')
            ->select(
                'task_timer.*','user.name', 'user.username', 'task.task_title',
                DB::raw(
                    'FORMAT(TIMESTAMPDIFF(SECOND, fp_task_timer.start_time, fp_task_timer.end_time) / 3600, 2) as time'
                ),
                DB::raw(
                    'TIMESTAMPDIFF(SECOND, fp_task_timer.start_time, now()) as _time'
                )
            )
            ->where('task_timer.task_id', '=', $id)
            ->orderBy('start_time','desc')
            ->get();

        $current_time =  DB::table('task_timer')
            ->select(
                DB::raw(
                    'TIMESTAMPDIFF(SECOND, fp_task_timer.start_time, now()) as _time, id'
                )
            )
            ->where('task_timer.task_id', '=', $id)
            ->where('task_timer.end_time', '=', '0000-00-00 00:00:00')
            ->first();

        $checkList = TaskChecklist::where('task_id','=',$id)->get();
        $total_checklist = TaskChecklist::where('task_id','=',$id)->count();
        $finish_checklist = TaskChecklist::where('is_finished','=',1)->count();
        $percentage = ($finish_checklist / $total_checklist) * 100;

        $links = Link::select('links.id','title','url','descriptions','tags',
            'comments',
            'link_categories.name as category_name')
            ->leftJoin('link_categories', 'link_categories.id','=','links.category_id')
            ->where('task_id','=',$id)
            ->get();

        $categories = LinkCategory::all()
            ->lists('name','id')
            ->toArray();

        $assets = ['calendar'];

        return view('task.show', [
            'task'=> $task,
            'assets' => $assets,
            'task_timer' => $task_timer,
            'checkList' => $checkList,
            'current_time' => $current_time,
            'percentage' => number_format($percentage,2),
            'links' => $links,
            'categories' => $categories
        ]);
    }

    public function create()
    {
    }

    public function edit($id)
    {
        $task = Task::find($id);

<<<<<<< HEAD
<<<<<<< HEAD
        $assign_username = User::orderBy('first_name', 'asc')
            ->lists('email', 'email');
        if($task){
=======
=======
>>>>>>> project_update
        $assign_username = User::orderBy('name')
            ->lists('name', 'user_id');

        if(count($task) > 0){
<<<<<<< HEAD
>>>>>>> 7961e7ff7602b9e3394a2c9c4880dfe48422af76
=======
=======
        $assign_username = User::orderBy('first_name', 'asc')
            ->lists('email', 'email');
        if($task){
>>>>>>> 9c35634d6341f4119334b566861bca0dd430be62
>>>>>>> project_update

            return view('task.edit', [
                'task'=> $task,
                'isClient'=> parent::hasRole('client'),
                'assign_username'=>$assign_username
            ]);
        }

    }

    public function store(Request $request)
    {

        $validation = Validator::make($request->all(), [
            'task_title' => 'required',
            'belongs_to' => 'required',
            'unique_id' => 'required',
            'is_visible' => 'required|in:yes,no'
        ]);

        if ($validation->fails()) {
            return Redirect::back()->withInput()->withErrors($validation->messages());
        }

        $task = new Task;
        $data = Input::all();
        $data['task_status'] = 'pending';
        $data['due_date'] = date("Y-m-d H:i:s", strtotime($data['due_date']));
        $data['user_id'] = Input::get('user_id','Open');

        $task->fill($data);
        $task->save();

        return Redirect::back()->withSuccess('Successfully added!!');
    }

    public function updateTaskStatus()
    {

        $task = Task::find(Input::get('task_id'));
        $validation = Validator::make(Input::all(), [
            'task_id' => 'required',
            'task_status' => 'required|in:pending,progress,completed'
        ]);

        if ($validation->fails()) {
            return Redirect::back()->withErrors($validation->messages());
        } elseif (!$task) {
            return Redirect::back()->withErrors('Wrong URL!!');
        }

        $task->task_status = Input::get('task_status');
        $task->save();

        return Redirect::back()->withSuccess('Saved!!');
    }

    public function update(Request $request, $id)
    {

        $task = Task::find($id);

        $data = $request->all();
        $data['due_date'] = date("Y-m-d H:i:s", strtotime($data['due_date']));

        $task->update($data);

        return redirect()->route('task.show', $id);
    }

    public function destroy($task_id)
    {
        $task = Task::find($task_id);
        if (!$task) {
            return Redirect::back()->withErrors('This is not a valid link!!');
        }
        $task->delete($task_id);

        return Redirect::back()->withSuccess('Deleted successfully!!');
    }

    public function taskTimer(Request $request,$id){
        $taskTimer = new TaskTimer($request->all());
        $taskTimer->save();
        $data['table'] = DB::table('task_timer')
            ->leftJoin('user', 'task_timer.user_id', '=', 'user.user_id')
            ->leftJoin('task', 'task_timer.task_id', '=', 'task.task_id')
            ->select(
                'task_timer.*', 'user.name', 'user.username', 'task.task_title',
                DB::raw(
                    'FORMAT(TIMESTAMPDIFF(SECOND, fp_task_timer.start_time, fp_task_timer.end_time) / 3600, 2) as time'
                )
            )
            ->where('task_timer.task_id', '=', $id)
            ->orderBy('start_time','desc')
            ->get();
        $data['return_task_timer'] = $taskTimer->id;
        return json_encode($data);
    }

    public function updateTaskTimer(Request $request,$id){
        $taskTimer = TaskTimer::find($id);
        $taskTimer->update($request->all());

        $data['table'] = DB::table('task_timer')
            ->leftJoin('user', 'task_timer.user_id', '=', 'user.user_id')
            ->leftJoin('task', 'task_timer.task_id', '=', 'task.task_id')
            ->select(
                'task_timer.*',
                'user.name',
                'user.username',
                'task.task_title',
                DB::raw(
                    'FORMAT(TIMESTAMPDIFF(SECOND, fp_task_timer.start_time, fp_task_timer.end_time) / 3600, 2) as time'
                )
            )
            ->where('task_timer.task_id', '=', $taskTimer->task_id)
            ->orderBy('start_time','desc')
            ->get();

        return json_encode($data);
    }

    public function deleteTaskTimer($id)
    {
        $task = TaskTimer::find($id);
        $task->delete($id);

        return Redirect::back()->withSuccess('Deleted successfully!!');
    }

    public function checkList(Request $request){
        $taskCheckList = new TaskChecklist($request->all());
        $taskCheckList->save();
        $data = TaskChecklist::where('task_id', '=', $request->task_id)
            ->get();

        return json_encode($data);
    }

    public function updateCheckList(Request $request, $id){
        $taskCheckList = TaskChecklist::find($id);
        $data = $request->all();
        $data['is_finished'] = Input::get('is_finished') != 0 ? 1 : 0;

        $taskCheckList->update($data);

        return json_encode($data);
    }

    public function deleteCheckList($id){
        $checkList = TaskChecklist::find($id);
        $checkList->delete($id);

        return Redirect::back()->withSuccess('Deleted successfully!!');
    }
}

?>