<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\BaseController;
use Bican\Roles\Exceptions\RoleDeniedException;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Models\TaskTimer;
use App\Models\TaskChecklist;
use App\Models\TaskChecklistOrder;
use App\Models\TaskCheckListPermission;
use App\Models\Link;
use App\Models\LinkCategory;
use App\Models\Profile;
use App\Models\Permission;
use App\Models\PermissionRole;
use App\Models\PermissionUser;
use View;
use Auth;
use Redirect;
use Validator;
use Input;
use \DB;

class BriefcaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {

        $user_id = Auth::user('user')->user_id;
        $user = User::find($user_id);

        $task = [];
        if ($user->level() === 1) {
            $task = Task::find($id);
        } elseif ($user->level() > 1) {
            $task = Task::find($id);
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
        $links = Link::select(
                    'links.id', 'title',
                    'url', 'descriptions',
                    'tags', 'comments',
                    'task_item_id', 'user_id',
                    'link_categories.name as category_name'
                )
            ->leftJoin('link_categories', 'link_categories.id', '=', 'links.category_id')
            ->where('task_id', '=', $id)
            ->get();

        $categories = LinkCategory::all()
                ->lists('name', 'id')
                ->toArray();


        $company_id = Project::where('project_id', $task->project_id)->pluck('company_id');

        $user_profile_role = Profile::where('user_id', $user_id)
                ->where('company_id', $company_id)
                ->first();

        $permissions_list = [];

        $permissions_user = PermissionUser::with('permission')
                ->where('company_id', $company_id)
                ->where('user_id', $user_id)
                ->get();

        foreach ($permissions_user as $role) {
            array_push($permissions_list, $role->permission_id);
        }

        $module_permissions = Permission::whereIn('id', $permissions_list)->get();
        
        $project_owner = Project::where('project_id',$task->project_id)->pluck('user_id');

        $assets = ['briefcases','real-time'];

        return view('briefcases.show', [
            'task' => $task,
            'assets' => $assets,
            'task_timer' => $task_timer,
            'checkList' => $checkList,
            'current_time' => $current_time,
            'percentage' => number_format($percentage, 0),
            'links' => $links,
            'user_id' => $user_id,
            'categories' => $categories,
            'module_permissions' => $module_permissions,
            'company_id' => $company_id,
            'project_owner' => $project_owner
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
