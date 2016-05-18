<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use App\Models\Country;
use App\Models\Company;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Profile;
use App\Models\Project;
use App\Models\Team;
use App\Models\TeamMember;
use App\Models\TeamProject;
use Auth;
use View;
use Redirect;
use Validator;
use DB;
use Input;

class CompanyController extends BaseController {

    public function index(Request $request) {

        $user_id = Auth::user()->user_id;

        $countries_option = Country::orderBy('country_name', 'asc')->get();

        $companies = Company::all();

        $profiles = Profile::all();

        $projects = Project::where('user_id', $user_id)->get();

        $assets = ['table', 'companies'];

        return View::make('company.index', [
                    'projects' => $projects,
                    'profiles' => $profiles,
                    'companies' => $companies,
                    'countries' => $countries_option,
                    'assets' => $assets
        ]);
    }

    public function show($company_id) {

        $user_id = Auth::user()->user_id;

        $countries_option = Country::orderBy('country_name', 'asc')->get();

        $companies = Company::where('id', $company_id)->get();

        $teams = Team::with(['team_member' => function($query) {
                        $query->with('user')->get();
                    }])->get();

        $team_grouping = Project::with('team_project')->where('company_id', $company_id)->get();

        $profiles = Profile::where('company_id', $company_id)->orWhere('user_id', $user_id)->get();

        $project_id_list = [];

        //Get owned projects
        $owned_projects = Project::where('user_id', $user_id)->where('company_id',$company_id)->get();

        //Get Team Member projects
        $team_members = TeamMember::where('user_id', $user_id)->get();

        $team_projects = TeamProject::all();

        foreach ($owned_projects as $owned_project) {
            array_push($project_id_list, $owned_project->project_id);
        }

        //Use the team id to get the projects the users are involved with
        foreach ($team_members as $member) {
            foreach ($team_projects as $project) {
                if ($member->team_id === $project->team_id) {
                    array_push($project_id_list, $project->project_id);
                }
            }
        }

        $projects = Project::whereIn('project_id', $project_id_list)->get();

        $assets = ['companies'];

        return View::make('company.show', [
                    'projects' => $projects,
                    'profiles' => $profiles,
                    'companies' => $companies,
                    'teams' => $teams,
                    'team_grouping' => $team_grouping,
                    'countries' => $countries_option,
                    'assets' => $assets
        ]);
    }

    public function create() {
        return View::make('company.create');
    }

    public function edit($company_id) {
        $companies = Company::find($company_id);

        $countries_option = Country::orderBy('country_name', 'asc')
                ->lists('country_name', 'country_id')
                ->toArray();

        return View::make('company.edit', [
                    'companies' => $companies,
                    'countries' => $countries_option
        ]);
    }

    public function store() {

        $validation = Validator::make(Input::all(), [
                    'name' => 'required|unique:companies',
                    'email' => 'required|email',
                    'country_id' => 'required'
        ]);

        if ($validation->fails()) {
            return Redirect::back()->withInput()->withErrors($validation->messages());
        }

        $companies = new Company;
        $data = Input::all();
        $data['client_status'] = 'Active';
        $companies->fill($data);
        $companies->save();

        return Redirect::to('company')->withSuccess("Company added successfully!!");
    }

    public function update($company_id) {
        $companies = Company::find($company_id);

        $validation = Validator::make(Input::all(), [
                    'company_name' => 'required|unique:companies,company_name,' . $company_id . ',company_id',
                    'contact_person' => 'required',
                    'email' => 'required|email',
                    'zipcode' => 'numeric',
                    'country_id' => 'required'
        ]);

        if ($validation->fails()) {
            return Redirect::to('client')->withErrors($validation->messages());
        }
        $data = Input::all();
        $companies->fill($data);
        $companies->save();
        return Redirect::to('client')->withSuccess("Company updated successfully!!");
    }

    public function delete() {
        
    }

    public function destroy($company_id) {
        $company = Company::find($company_id);

        if (!$company || !parent::hasRole('Admin'))
            return Redirect::to('company')->withErrors('This is not a valid link!!');

        $user = User::find($company_id);

        $project = Project::where('company_id', '=', $company->id)->get();

        if (count($project)) {
            return Redirect::to('company')->withErrors('This client has some projects!! Delete that project first!!');
        }

        $ticket = Ticket::where('user_id', $user->id)->get();

        if (count($ticket))
            return Redirect::to('client')->withErrors('This client has some ticket!! Delete that ticket first!!');

        DB::table('message')
                ->where('from_user_id', '=', $user->id)
                ->orWhere('to_user_id', '=', $user->id)
                ->delete();

        DB::table('events')
                ->where('user_id', '=', $user->id)
                ->delete();

        $user->delete();

        $company->delete();

        return Redirect::to('client')->withSuccess('Company deleted successfully!!');
    }

    //Create a team for a project,     
    public function createTeam(Request $request) {

        //Create a new team if that project isn't mapped to a team yet
        $team = new Team();
        $team_member = new TeamMember();
        $team_project = new TeamProject();

        $user_id = $request->input('user_id');
        $project_id = $request->input('project_id');

        $project_exists = Team::where('project_id', $project_id)->count();

        if ($project_exists > 0) {

            $team_id = Team::where('project_id', $project_id)->pluck('id');

            $team_member_exists = TeamMember::where('user_id', $user_id)->where('team_id', $team_id)->first();

            $team_project_exists = TeamProject::where('team_id', $team_id)->where('team_id', $team_id)->first();

            if (!$team_member_exists > 0) {
                $team_member->team_id = $team_id;
                $team_member->user_id = $user_id;

                $team_member->save();
            }

            if (!$team_project_exists > 0) {

                $team_project->team_id = $team_id;
                $team_project->project_id = $project_id;

                $team_project->save();
            }
        } else {

            //Save the project id as an new team
            $team->project_id = $project_id;
            $team->save();

            //Get the team id
            $team_id = $team->id;
            
            //Save the user as a team member
            $team_member->team_id = $team_id;
            $team_member->user_id = $user_id;
            $team_member->save();

            //Map Project to the team id    
            $team_project->team_id = $team_id;
            $team_project->project_id = $project_id;

            $team_project->save();
        }

        return $team_id;
    }

    public function unassignTeamMember(Request $request) {

        $user_id = $request->input('user_id');
        $team_id = $request->input('team_id');

        //Delete team member from the Team Member table to unassign them from the project
        $team_member = TeamMember::where('user_id', $user_id)->where('team_id', $team_id);
        $team_member->delete();

        return $user_id;
    }

}
?>
