<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;

use App\Models\Country;
use App\Models\Client;
use App\Models\Ticket;

use Entrust;
use View;
use Redirect;
use Validator;
use DB;
use Input;
use Illuminate\Http\Request;

class ClientController extends BaseController
{

    public function index()
    {
        $countries_option = Country::orderBy('country_name', 'asc')
            ->lists('country_name', 'country_id')
        ->toArray();

        $client = Client::all();

        $assets = ['table'];

        return View::make('client.index', [
            'clients' => $client,
            'countries' => $countries_option,
            'assets' => $assets
        ]);
    }

    public function show($client_id)
    {
        $client = Client::find($client_id);
        
        $countries_option = Country::orderBy('country_name', 'asc')
            ->lists('country_name', 'country_id');

        return View::make('client.show', [
            'client' => $client,
            'countries' => $countries_option
        ]);
    }

    public function create()
    {
        return View::make('client.create');
    }

    public function edit($client_id)
    {
        $client = Client::find($client_id);

        $countries_option = Country::orderBy('country_name', 'asc')
            ->lists('country_name', 'country_id')
        ->toArray();

        return View::make('client.edit', [
            'client' => $client,
            'countries' => $countries_option
        ]);
    }

    public function store(Request $request)
    {

        $validation = Validator::make(Input::all(), [
            'company_name' => 'required|unique:client',
            'contact_person' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'zipcode' => 'numeric',
            'country_id' => 'required'
        ]);

        if ($validation->fails()) {
            return Redirect::back()->withInput()->withErrors($validation->messages());
        }

        $client = new Client;
        
        //$data = Input::all();
        //$data['client_status'] = 'Active';
        //$client->fill($data);
        $client->country_id = $request->input('country_id');
        $client->company_name = $request->input('company_name');
        $client->contact_person = $request->input('contact_person');
        $client->email = $request->input('email');
        $client->phone = $request->input('phone');
        $client->address = $request->input('address');
        $client->zipcode = $request->input('zipcode');
        $client->city = $request->input('city');
        $client->state = $request->input('state');
        $client->password = bcrypt($request->input('password'));        
        $client->save();

        return Redirect::to('client')->withSuccess("Client added successfully!!");
    }

    public function update($client_id)
    {
        $client = Client::find($client_id);

        $validation = Validator::make(Input::all(), [
            'company_name' => 'required|unique:client,company_name,' . $client_id . ',id',
            'contact_person' => 'required',
            'email' => 'required|email',
            'zipcode' => 'numeric',
            'country_id' => 'required'
        ]);

        if ($validation->fails()) {
            return Redirect::to('client')->withErrors($validation->messages());
        }
        $data = Input::all();
        $client->fill($data);
        $client->save();
        return Redirect::to('client')->withSuccess("Client updated successfully!!");
    }

    public function destroy()
    {
    }

    public function delete($client_id)
    {
        $client = Client::find($client_id);

        if (!$client || !Entrust::hasRole('Admin'))
            return Redirect::to('client')->withErrors('This is not a valid link!!');

        $user = User::find($client_id);

        $project = Project::where('client_id', '=', $client->id)->get();

        if (count($project))
            return Redirect::to('client')->withErrors('This client has some projects!! Delete that project first!!');

        $ticket = Ticket::where('username', '=', $user->email)->get();

        if (count($ticket))
            return Redirect::to('client')->withErrors('This client has some ticket!! Delete that ticket first!!');

        DB::table('message')
            ->where('from_username', '=', $user->email)
            ->orWhere('to_username', '=', $user->email)
            ->delete();

        DB::table('events')
            ->where('username', '=', $user->email)
            ->delete();

        $user->delete();

        $client->delete();

        return Redirect::to('client')->withSuccess('Client deleted successfully!!');

    }
}

?>