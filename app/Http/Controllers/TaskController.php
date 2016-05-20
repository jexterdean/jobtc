<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;
use Bican\Roles\Exceptions\RoleDeniedException;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\User;
use App\Models\TaskTimer;
use App\Models\TaskChecklist;
use App\Models\TaskChecklistOrder;
use App\Models\Link;
use App\Models\LinkCategory;
use View;
use Auth;
use Redirect;
use Validator;
use Input;
use \DB;

class TaskController extends BaseController {

    function __construct() {
        //Only staff and admin can access
        if (parent::hasRole('client')) {
            throw new RoleDeniedException('Company or Admin');
        }
    }

    /**
     * @return mixed
     */
    public function index() {

        if (parent::hasRole('staff')) {

            $tasks = Task::orderBy('created_at', 'desc')
                    ->get();
        } else {
            /* $tasks = Task::orderBy('created_at', 'desc')
              ->join('user', 'task.user_id', '=', 'users.id')
              ->select(
              'task.*', 'users.first_name', 'user.email'
              )->get(); */
            $tasks = Task::where('user_id', '=', Auth::user()->user_id)
                    ->orderBy('created_at', 'desc')
                    ->get();
        }

        $belongsTo = 'task';

        $assign_username = User::orderBy('name')
                ->lists('name', 'user_id');

        $assets = ['calendar', 'table'];

        return View::make('task.index', [
                    'assets' => $assets,
                    'tasks' => $tasks,
                    'belongs_to' => $belongsTo,
                    'isCompany' => parent::hasRole('client'),
                    'assign_username' => $assign_username
        ]);
    }

    public function show($id) {
        //
        $task = [];
        if (parent::userHasRole('Admin')) {
            $task = Task::find($id);
        } elseif (parent::userHasRole('client')) {
            $task = DB::table('task')
                    //->join('user', 'user.client_id', '=', 'task.client_id')
                    ->where('user_id', '=', Auth::user('user')->user_id)
                    ->where('task_id', '=', $id)
                    ->first();
        } elseif (parent::userHasRole('Staff')) {
            $task = DB::table('task')
                    ->join('assigned_user', 'assigned_user.unique_id', '=', 'project.project_id')
                    ->where('belongs_to', '=', 'project')
                    ->where('user_id', '=', Auth::user('user')->user_id)
                    ->where('project_id', '=', $id)
                    ->first();
        }
        $task_timer = DB::table('task_timer')
                ->leftJoin('user', 'task_timer.user_id', '=', 'user.user_id')
                ->leftJoin('task', 'task_timer.task_id', '=', 'task.task_id')
                ->select(
                        'task_timer.*', 'user.name', 'task.task_title', DB::raw(
                                'FORMAT(TIMESTAMPDIFF(SECOND, fp_task_timer.start_time, fp_task_timer.end_time) / 3600, 2) as time'
                        ), DB::raw(
                                'TIMESTAMPDIFF(SECOND, fp_task_timer.start_time, now()) as _time'
                        )
                )
                ->where('task_timer.task_id', '=', $id)
                ->orderBy('start_time', 'desc')
                ->get();

        $current_time = DB::table('task_timer')
                ->select(
                        DB::raw(
                                'TIMESTAMPDIFF(SECOND, fp_task_timer.start_time, now()) as _time, id'
                        )
                )
                ->where('task_timer.task_id', '=', $id)
                ->where('task_timer.end_time', '=', '0000-00-00 00:00:00')
                ->first();

        //Check if there is an entry in the taskchecklist order table
        $task_order_count = TaskChecklistOrder::where('task_id', $id)->count();

        if ($task_order_count > 0) {
            $task_order = TaskChecklistOrder::where('task_id', $id)->first();
            $checkList = TaskChecklist::where('task_id', '=', $id)->orderBy(DB::raw('FIELD(id,' . $task_order->task_id_order . ')'))->get();
        } else {
            $task_order = TaskChecklistOrder::where('task_id', $id)->first();
            $checkList = TaskChecklist::where('task_id', '=', $id)->get();
        }

        $total_checklist = TaskChecklist::where('task_id', '=', $id)->count();
        $finish_checklist = TaskChecklist::where('status', '=', 'Completed')->where('task_id', '=', $id)->count();
        $percentage = $total_checklist > 0 ? ($finish_checklist / $total_checklist) * 100 : 0;
        $links = Link::select('links.id', 'title', 'url', 'descriptions', 'tags', 'comments', 'link_categories.name as category_name')
                ->leftJoin('link_categories', 'link_categories.id', '=', 'links.category_id')
                ->where('task_id', '=', $id)
                ->get();

        $categories = LinkCategory::all()
                ->lists('name', 'id')
                ->toArray();


        $assets = ['calendar'];

        return view('task.show', [
            'task' => $task,
            'assets' => $assets,
            'task_timer' => $task_timer,
            'checkList' => $checkList,
            'current_time' => $current_time,
            'percentage' => number_format($percentage, 0),
            'links' => $links,
            'categories' => $categories
        ]);
    }

    public function create() {
        
    }

    public function edit($id) {
        $task = Task::find($id);

        $assign_username = User::orderBy('name')
                ->lists('name', 'user_id');

        if (count($task) > 0) {
            return view('task.edit', [
                'task' => $task,
                'isCompany' => parent::hasRole('client'),
                'assign_username' => $assign_username
            ]);
        }
    }

    public function store(Request $request) {

        $validation = Validator::make($request->all(), [
                    'task_title' => 'required',
                    'belongs_to' => 'required',
                    'unique_id' => 'required'
        ]);

        if ($validation->fails()) {
            return Redirect::back();
        }

        $task = new Task;
        $data = Input::all();
        $data['task_status'] = 'pending';
        $data['due_date'] = date("Y-m-d H:i:s", strtotime($data['due_date']));

        $data['user_id'] = Auth::user()->user_id;

        $task->fill($data);
        $task->save();

        return Redirect::back();
    }

    public function updateTaskStatus() {

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

        return Redirect::back();
    }

    public function update(Request $request, $id) {

        $task = Task::find($id);

        $data = $request->all();
        $data['due_date'] = date("Y-m-d H:i:s", strtotime($data['due_date']));

        $task->update($data);

        return Redirect::back();
    }

    public function destroy($task_id) {
        $task = Task::where('task_id', $task_id);
        if (!$task) {
            return Redirect::back()->withErrors('This is not a valid link!!');
        }
        $task->delete($task_id);

        return Redirect::back();
    }

    public function delete(Request $request, $id) {
        $task = Task::where('task_id', $id)->delete();

        return json_encode($task);
    }

    public function taskTimer(Request $request, $id) {
        $input = $request->except(['is_finished']);
        $taskTimer = new TaskTimer($input);
        $taskTimer->save();

        $data['table'] = DB::table('task_timer')
                ->leftJoin('user', 'task_timer.user_id', '=', 'user.user_id')
                ->leftJoin('task', 'task_timer.task_id', '=', 'task.task_id')
                ->select(
                        'task_timer.*', 'user.name', 'task.task_title', DB::raw(
                                'FORMAT(TIMESTAMPDIFF(SECOND, fp_task_timer.start_time, fp_task_timer.end_time) / 3600, 2) as time'
                        )
                )
                ->where('task_timer.task_id', '=', $id)
                ->orderBy('start_time', 'desc')
                ->get();

        $data['return_task_timer'] = $taskTimer->id;
        return json_encode($data);
    }

    public function updateTaskTimer(Request $request, $id) {
        $taskTimer = TaskTimer::find($id);
        $taskTimer->update($request->all());

        $data['table'] = DB::table('task_timer')
                ->leftJoin('user', 'task_timer.user_id', '=', 'user.user_id')
                ->leftJoin('task', 'task_timer.task_id', '=', 'task.task_id')
                ->select(
                        'task_timer.*', 'user.name', 'task.task_title', DB::raw(
                                'FORMAT(TIMESTAMPDIFF(SECOND, fp_task_timer.start_time, fp_task_timer.end_time) / 3600, 2) as time'
                        )
                )
                ->where('task_timer.task_id', '=', $taskTimer->task_id)
                ->orderBy('start_time', 'desc')
                ->get();

        return json_encode($data);
    }

    public function deleteTaskTimer($id) {
        $task = TaskTimer::find($id);
        $task->delete($id);

        return Redirect::back();
    }

    public function getChecklist(Request $request) {
        $tasklist = TaskChecklist::where('task_id', $request->task_id)->get();

        return json_encode($tasklist);
    }

    public function checkList(Request $request) {
        //Save the task list item immediately
        $taskCheckList = new TaskChecklist($request->all());
        $taskCheckList->save();

        $has_order_list = TaskChecklistOrder::where('task_id', '=', $taskCheckList->task_id)->count();

        if ($has_order_list > 0) {
            //then get the new task list item id and append it as the last item on the order
            $taskCheckListOrderString = TaskChecklistOrder::where('task_id', '=', $taskCheckList->task_id)->pluck('task_id_order');
            $task_list_id_array = $taskCheckListOrderString . ',' . $taskCheckList->id;
            $taskCheckListOrderUpdate = TaskChecklistOrder::where('task_id', $request->task_id)->update([
                'task_id_order' => $task_list_id_array
            ]);

            //$data = TaskChecklist::where('task_id', '=', $taskCheckList->task_id)->get();
            $data = TaskChecklist::where('task_id', '=', $taskCheckList->task_id)->orderBy(DB::raw('FIELD(id,' . $task_list_id_array . ')'))->get();
        } else {
            $data = TaskChecklist::where('task_id', '=', $taskCheckList->task_id)->get();
        }

        return json_encode($data);
    }

    public function sortCheckList(Request $request, $id) {

        $taskCheckListOrder = new TaskChecklistOrder();

        //Check if the task id has an ordering list
        $task_list_id_count = TaskChecklistOrder::where('task_id', $id)->count();

        //Turn list of task item ids into a string
        $task_list_id_array = implode(",", str_replace("\"", '', $request->get('task_item')));

        if ($task_list_id_count > 0) {

            $taskCheckListOrder->where('task_id', $id)->delete();

            $taskCheckListOrder->task_id = $id;
            $taskCheckListOrder->task_id_order = $task_list_id_array;
            $taskCheckListOrder->save();
        } else {

            $taskCheckListOrder->task_id = $id;
            $taskCheckListOrder->task_id_order = $task_list_id_array;
            $taskCheckListOrder->save();
        }

        return json_encode($task_list_id_array);
    }

    public function updateCheckList(Request $request, $id) {
        $taskCheckList = TaskChecklist::find($id);
        $data = $request->all();
        //$data['is_finished'] = Input::get('is_finished') != 0 ? 1 : 0;

        $taskCheckList->update($data);

        return json_encode($data);
    }

    public function deleteCheckList($id) {
        //Find the task item to delete 
        $checkList = TaskChecklist::find($id);

        //Delete the task item from the task order
        $task_order = explode(",", TaskChecklistOrder::where('task_id', '=', $checkList->task_id)->pluck('task_id_order'));

        $new_task_order = [];
        foreach ($task_order as $order) {
            if (str_replace('"', '', $order) !== $id) {
                array_push($new_task_order, $order);
            }
        }
        $task_order_update = TaskChecklistOrder::where('task_id', '=', $checkList->task_id)->update([
            'task_id_order' => implode(',', $new_task_order)
        ]);

        //Delete the task item
        $checkList->delete($id);

        //If Checklist item was the last item in the list, delete the task order
        $task_list_count = TaskChecklist::where('task_id', $checkList->task_id)->count();

        if (!$task_list_count > 0) {

            $delete_task_order = TaskChecklistOrder::where('task_id', $checkList->task_id)->delete();
        }

        return $checkList;
    }

    public function changeCheckList(Request $request, $task_id, $task_list_item_id) {

        $taskCheckList = TaskCheckList::where('id', $task_list_item_id)
                ->update([
            'task_id' => $task_id
        ]);


        $taskCheckListOrder = new TaskChecklistOrder();

        //Check if the task id has an ordering list
        $task_list_id_count = TaskChecklistOrder::where('task_id', $task_id)->count();

        //Turn list of task item ids into a string
        $task_list_id_array = implode(",", str_replace("\"", '', $request->get('task_item')));

        if ($task_list_id_count > 0) {

            $taskCheckListOrder->where('task_id', $task_id)->delete();

            $taskCheckListOrder->task_id = $task_id;
            $taskCheckListOrder->task_id_order = $task_list_id_array;
            $taskCheckListOrder->save();
        } else {

            $taskCheckListOrder->task_id = $task_id;
            $taskCheckListOrder->task_id_order = $task_list_id_array;
            $taskCheckListOrder->save();
        }

        return json_encode($task_list_id_array);
    }

    //For CKEditor Image uploads
    public function saveImage(Request $request) {

        $file_name = $request->file('upload');

        $file_name->move(
                'assets/ckeditor_uploaded_images/', $file_name->getClientOriginalName()
        );

        $data = array(
        "uploaded" => 1,
        "fileName" => $file_name->getClientOriginalName(),
        "url" => url('/assets/ckeditor_uploaded_images/'.$file_name->getClientOriginalName())
        );

        return json_encode($data);
    }

}

?>
