<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Discussion;
use App\Models\Profile;
use App\Models\User;
use Auth;

class DiscussionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $user_id = Auth::user()->user_id;
        
        $company_id = Profile::where('user_id',$user_id)->pluck('company_id');
        
        $discussions = Discussion::where('company_id',$company_id)->get();
        
        $assets = ['discussions'];
        
        return view('discussions.index',[
            'discussions' => $discussions,
            'assets' => $assets
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('discussions.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user_id = Auth::user()->user_id;
        
        $company_id = Profile::where('user_id',$user_id)->pluck('company_id');
        
        $room_name = $request->input('room_name');
        
        $add_discussion_room = new Discussion([
            'owner_id' => $user_id,
            'company_id' => $company_id,
            'room_name' => $room_name
        ]);
        $add_discussion_room->save();
        
        $room_details = json_encode(array('id' => $add_discussion_room->id, 'room_name' => $room_name), JSON_FORCE_OBJECT);
        
        return $room_details;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
         $assets = ['discussions-room'];
        
         $user_id = Auth::user()->user_id;
         
         $display_name = User::where('user_id',$user_id)->pluck('name');
         
        return view('discussions.show',[
            'assets' => $assets,
            'display_name' => $display_name
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
