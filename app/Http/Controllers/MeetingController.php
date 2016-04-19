<?php

namespace App\Http\Controllers;
use App\Http\Controllers\BaseController;

use App\Models\Meeting;
use \View;
use \DB;
use \Input;
use \Redirect;
use \Auth;
use \Validator;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Traits\UserRoleTrait;

class MeetingController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $assets = ['calendar', 'date', 'select'];

        return View::make('meeting.default', [
            'assets' => $assets
        ]);
    }

    public function meetingJson(){
        header("Content-type: application/json");

        //find user timezone and get current offset (like +08:00)
        $user_timezone = DB::table('timezone')
            ->where('timezone.timezone_id', '=', parent::getActiveUser()->timezone_id)
            ->pluck('timezone_name');
        date_default_timezone_set($user_timezone);
        $p = date('P');

        //set custom timezone
        if(isset($_GET['timezone'])) {
            date_default_timezone_set($_GET['timezone']);
        }

        $m = DB::table('meeting')
            ->select(DB::raw('
                fp_meeting.*,
                fp_project.project_title,
                fp_meeting_type.type as meeting_type,
                fp_meeting_priority.priority as meeting_priority,
                DATE_FORMAT(fp_meeting.start_date, "%Y-%m-%dT%T' . $p . '") as start,
                DATE_FORMAT(fp_meeting.end_date, "%Y-%m-%dT%T' . $p . '") as end,
                IF(
                    fp_meeting.meeting_url != "",
                    CONCAT("<a href=\"", fp_meeting.meeting_url , "\">", fp_meeting.meeting_url, "</a>"),
                    ""
                ) as meeting_url
            '))
            ->leftJoin('project', 'project.project_id','=','meeting.project_id')
            ->leftJoin('meeting_type', 'meeting_type.id','=','meeting.type_id')
            ->leftJoin('meeting_priority', 'meeting_priority.id','=','meeting.priority_id')
            ->get();
        $meeting = array();
        if(count($m) > 0) {
            foreach ($m as $v) {
                //change the date and time from user timezone to the custom timezone
                $v->start = date('c', strtotime($v->start));
                $v->end = date('c', strtotime($v->end));

                $color = \App\Helpers\Helper::getRandomHexColor();
                $v->color = $color;

                //if has attendees search and pass as variable into one string
                if($v->attendees) {
                    $attendees = json_decode($v->attendees);
                    $u = DB::table('user')
                        ->select(DB::raw('GROUP_CONCAT(username separator ", ") as attendees'))
                        ->whereIn('user_id', $attendees)
                        ->first('attendees');
                    $v->attendees = $u->attendees;
                }

                $meeting[] = $v;
            }
        }

        return response()->json($meeting);
    }

    public function meetingTimezone(){
        header("Content-type: application/json");

        $t = DB::table('timezone')
            ->select('timezone_id', 'timezone_name')
            ->get();
        $current_timezone = '';
        $timezone= array();
        if(count($t) > 0){
            foreach($t as $v){
                //get user timezone
                if(parent::getActiveUser()->timezone_id == $v->timezone_id){
                    $current_timezone = $v->timezone_name;
                }

                //timezone array
                $timezone[$v->timezone_name] = $v->timezone_name;
            }
        }

        return response()->json(array(
            'timezone' => $timezone,
            'current_timezone' => $current_timezone
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $date = $_GET['date'];

        $p = DB::table('project')
            ->select('project_id', 'project_title')
            ->get();
        $project = array();
        if(count($p) > 0){
            foreach($p as $v){
                $project[$v->project_id] = $v->project_title;
            }
        }

        $m = DB::table('meeting_type')
            ->select('id', 'type')
            ->get();
        $meeting_type = array();
        if(count($m) > 0){
            foreach($m as $v){
                $meeting_type[$v->id] = $v->type;
            }
        }

        $m = DB::table('meeting_priority')
            ->select('id', 'priority')
            ->get();
        $meeting_priority = array();
        if(count($m) > 0){
            foreach($m as $v){
                $meeting_priority[$v->id] = $v->priority;
            }
        }

        $u = DB::table('user')
            ->select('user_id', 'username')
            ->get();
        $user = array();
        if(count($u) > 0){
            foreach($u as $v){
                $user[$v->user_id] = $v->username;
            }
        }

        return View::make('meeting.create', [
            'date' => $date,
            'project' => $project,
            'meeting_type' => $meeting_type,
            'meeting_priority' => $meeting_priority,
            'user' => $user
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $start_date = date('c', strtotime(str_replace("/", "-", Input::get('start_date'))));
        $end_date = date('c', strtotime(str_replace("/", "-", Input::get('end_date'))));

        $validation = Validator::make($request->all(), [
            'project_id' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'type_id' => 'required',
            'description' => 'required',
            'estimated_length' => 'required|numeric',
            'priority_id' => 'required',
        ]);

        if ($validation->fails()) {
            return Redirect::to('meeting')
                ->withInput()
                ->withErrors($validation->messages());
        }

        $meeting = new Meeting();
        $meeting->project_id = Input::get('project_id');
        $meeting->user_id = Auth::user()->user_id;
        $meeting->start_date = $start_date;
        $meeting->end_date = $end_date;
        $meeting->type_id = Input::get('type_id');
        $meeting->description = Input::get('description');
        $meeting->estimated_length = Input::get('estimated_length');
        $meeting->priority_id = Input::get('priority_id');
        $meeting->attendees = Input::get('attendees') ? json_encode(Input::get('attendees')) : '';
        $meeting->meeting_url = Input::get('meeting_url');
        $meeting->save();

        return Redirect::to('meeting')
                ->withSuccess("Meeting added successfully!!");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $event = Meeting::where('id', $id)
            ->first();

        $p = DB::table('project')
            ->select('project_id', 'project_title')
            ->get();
        $project = array();
        if(count($p) > 0){
            foreach($p as $v){
                $project[$v->project_id] = $v->project_title;
            }
        }

        $m = DB::table('meeting_type')
            ->select('id', 'type')
            ->get();
        $meeting_type = array();
        if(count($m) > 0){
            foreach($m as $v){
                $meeting_type[$v->id] = $v->type;
            }
        }

        $m = DB::table('meeting_priority')
            ->select('id', 'priority')
            ->get();
        $meeting_priority = array();
        if(count($m) > 0){
            foreach($m as $v){
                $meeting_priority[$v->id] = $v->priority;
            }
        }

        $u = DB::table('user')
            ->select('user_id', 'username')
            ->get();
        $user = array();
        if(count($u) > 0){
            foreach($u as $v){
                $user[$v->user_id] = $v->username;
            }
        }

        return View::make('meeting.edit', [
            'event' => $event,
            'project' => $project,
            'meeting_type' => $meeting_type,
            'meeting_priority' => $meeting_priority,
            'user' => $user
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
        $meeting = Meeting::find($id);
        if(Input::get('is_drag')){
            $start = strtotime(Input::get('start_date'));
            $end = strtotime(Input::get('end_date'));
            $new = strtotime(Input::get('new_date'));

            $datediff = $new - $start;
            $days_diff = floor($datediff/(60*60*24));


            $start_date = Input::get('new_date') . ' ' . date('H:i:s', $start);
            $end_date =  date('Y-m-d H:i:s', strtotime(Input::get('end_date') . ' ' . $days_diff . ' day'));

            $meeting->start_date = $start_date;
            $meeting->end_date = $end_date;
            $meeting->save();
        }
        else{
            $start_date = date('c', strtotime(str_replace("/", "-", Input::get('start_date'))));
            $end_date = date('c', strtotime(str_replace("/", "-", Input::get('end_date'))));

            $validation = Validator::make($request->all(), [
                'project_id' => 'required',
                'start_date' => 'required',
                'end_date' => 'required',
                'type_id' => 'required',
                'description' => 'required',
                'estimated_length' => 'required|numeric',
                'priority_id' => 'required',
            ]);

            if ($validation->fails()) {
                return Redirect::to('meeting')
                    ->withInput()
                    ->withErrors($validation->messages());
            }

            $meeting->project_id = Input::get('project_id');
            $meeting->start_date = $start_date;
            $meeting->end_date = $end_date;
            $meeting->type_id = Input::get('type_id');
            $meeting->description = Input::get('description');
            $meeting->estimated_length = Input::get('estimated_length');
            $meeting->priority_id = Input::get('priority_id');
            $meeting->attendees = Input::get('attendees') ? json_encode(Input::get('attendees')) : '';
            $meeting->meeting_url = Input::get('meeting_url');
            $meeting->save();

            return Redirect::to('meeting')
                ->withSuccess("Meeting edited successfully!!");
        }
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
