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

use Auth;
use View;
use Redirect;
use Validator;
use DB;
use Input;
class CompanyController extends BaseController
{

    public function index(Request $request)
    {
        
        
        $user_id = Auth::user()->user_id;
        
        $countries_option = Country::orderBy('country_name', 'asc')->get();

        $companies = Company::all();
        
        $profiles = Profile::all();
        
        $projects = Project::where('user_id',$user_id)->get();
        
        $assets = ['table','companies'];

        return View::make('company.index', [
            'projects' => $projects,
            'profiles' => $profiles,
            'companies' => $companies,
            'countries' => $countries_option,
            'assets' => $assets
        ]);
    }

    public function show($company_id)
    {
        $companies = Company::find($company_id);

        $countries_option = Country::orderBy('country_name', 'asc')
            ->pluck('country_name', 'country_id');

        return View::make('company.show', [
            'companies' => $companies,
            'countries' => $countries_option
        ]);
    }

    public function create()
    {
        return View::make('company.create');
    }

    public function edit($company_id)
    {
        $companies = Company::find($company_id);

        $countries_option = Country::orderBy('country_name', 'asc')
            ->lists('country_name', 'country_id')
        ->toArray();

        return View::make('company.edit', [
            'companies' => $companies,
            'countries' => $countries_option
        ]);
    }

    public function store()
    {

        $validation = Validator::make(Input::all(), [
            'name' => 'required|unique:companies',
            'email' => 'required|email',
            'country' => 'required'
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

    public function update($company_id)
    {
        $companies= Company::find($company_id);

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

    public function delete()
    {
    }

    public function destroy($company_id)
    {
        $company = Company::find($company_id);

        if (!$company || !parent::hasRole('Admin'))
            return Redirect::to('company')->withErrors('This is not a valid link!!');

        $user = User::find($company_id);

        $project = Project::where('company_id', '=', $company->id)->get();

        if (count($project)) {
            return Redirect::to('company')->withErrors('This client has some projects!! Delete that project first!!');
        }
        
        $ticket = Ticket::where('user_id',$user->id)->get();

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
}

?>