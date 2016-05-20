<?php
namespace App\Helpers;

use Session;
use DB;
use Auth;
use App\Models\Company;
use App\Models\Profile;
use App\Models\Project;
use App\Models\Team;
use App\Models\TeamMember;
use App\Models\TeamProject;
use Illuminate\Http\Request;

class Helper
{
    public static function showMessage()
    {
        if (Session::has('errors')) {

            $error = Session::get('errors')->First();
            echo "<div class='alert alert-danger alert-dismissable'>
					<i class='fa fa-ban'></i>
					<button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
					<strong>$error</strong>
				</div>";

        } elseif (Session::has('success')) {

            $success = Session::get('success');
            echo "<div class='alert alert-success alert-dismissable'>
					<i class='fa fa-check'></i>
					<button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
					<strong>$success</strong>
				</div>";
        }
    }

    public static function getRandomHexColor()
    {
        return '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
    }

    public static function getRandomColor()
    {
        $PORTLETCOLOR = array(
            "primary",
            "danger",
            "success",
            "warning"
        );
        $index = array_rand($PORTLETCOLOR);
        echo $PORTLETCOLOR[$index];
    }

    public static function getAvatar($username)
    {
        $user = DB::table('user')->where('username', '=', $username)->first();
        if (isset($user->user_avatar))
            $url = 'assets/user/' . $user->user_avatar;
        else
            $url = 'assets/user/avatar.png';
        echo url($url);
    }

    public static function getProgressStatus($value)
    {
        if ($value == 0)
            return "<span class='label label-sm label-danger'>Pending</span>";
        elseif ($value < 50)
            return "<span class='label label-sm label-warning'>$value %</span>";
        elseif ($value < 99)
            return "<span class='label label-sm label-info'>$value %</span>";
        else
            return "<span class='label label-sm label-success'>Completed</span>";
    }

    public static function getProjectLinks(){
        /*$project = DB::table('project')
            ->orderBy('project_title', 'asc')
            ->get();*/
        
        $user_id = Auth::user()->user_id;
        
        /*$team_projects = DB::table('project')
                         ->join('team_project','team_project.project_id','=','project.project_id')
                         ->join('team_member','team_member.team_id','=','team_project.team_id')
                         ->distinct()
                         ->select('team_member.user_id')
                         ->get();*/
        
        $project_id_list = [];
        
        //Get owned projects
        $owned_projects = Project::where('user_id',$user_id)->get();
        
        //Get Team Member projects
        $team_members = TeamMember::where('user_id',$user_id)->get();
        
        $team_projects = TeamProject::all();
        
        foreach($owned_projects as $owned_project) {
            array_push($project_id_list, $owned_project->project_id);
        }
        
        //Use the team id to get the projects the users are involved with
        foreach($team_members as $member) {
            foreach($team_projects as $project) {
                if ($member->team_id === $project->team_id) {
                    array_push($project_id_list, $project->project_id);
                }
            }
        }
        
        $project_list = Project::whereIn('project_id',$project_id_list)->get();
        
        return $project_list;
    }
    
    public static function getCompanyLinks(){
        
        /*$companies = Company::with(['profile' => function($query) {
            //Get user that is logged in
            $user_id = Auth::user()->user_id;
            $query->where('user_id',$user_id)->get();
        }])->get();*/
        
        $user_id = Auth::user()->user_id;
        
        $companies = Profile::with('company')->where('user_id',$user_id)->get();

        return $companies;
    }
    
    
    public static function br2nl($string)
    {
        return preg_replace('/\<br(\s*)?\/?\>/i', "", $string);
    }

    public static function mynl2br($string)
    {
        $string = str_replace("'", "&#039;", $string);
        $string = nl2br($string);
        return ($string);
    }

    public static function DisplayArray($ar, $color = "000"){
        echo '<pre style="color: #' . $color . '">';
        print_r($ar);
        echo '</pre>';
    }
}

?>
