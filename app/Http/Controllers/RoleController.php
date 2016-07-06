<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Profile;
use App\Models\Module;
use App\Models\Permission;
use App\Models\PermissionRole;
use App\Models\PermissionUser;
use Auth;

class RoleController extends Controller
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
    public function show($id)
    {
        $user_id = Auth::user('user')->user_id;

        $positions = Role::where('company_id', $id)->get();
        $modules = Module::all();
        $permissions = Permission::all();
        $permission_role = PermissionRole::all();

        $assets = ['companies', 'real-time'];
        
        return view('roles.show', [
            'positions' => $positions,
            'permissions' => $permissions,
            'permission_role' => $permission_role,
            'modules' => $modules,
            'assets' => $assets,
            'company_id' => $id
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
    
    
    public function addPositionForm() {
        return view('forms.addPositionForm');
    }
    
    public function addPosition(Request $request) {
        $company_id = $request->input('company_id');
        $position_title = $request->input('position_title');
        $position_description = $request->input('position_description');
        
        $position = new Role();
        $position->company_id = $company_id;
        $position->company_division_id = 0;
        $position->name = $position_title;
        $position->slug = strtolower($position_title).'-'.$company_id;
        $position->description = $position_description;
        $position->level = 1;
        $position->save();
                
        $modules = Module::all();
        $permissions = Permission::all();
        $permission_role = PermissionRole::all();
        
        return view('roles.partials._newposition',[
            'position' => $position,
            'permissions' => $permissions,
            'permission_role' => $permission_role,
            'modules' => $modules,
            'company_id' => $company_id
        ]);
    }
    
    public function assignPositionPermission(Request $request) {
        
        $role_id = $request->input('role_id');
        $permission_id = $request->input('permission_id');
        $company_id = $request->input('company_id');
        
        $permission_role = new PermissionRole();
        $permission_role->role_id = $role_id;
        $permission_role->permission_id = $permission_id;
        $permission_role->company_id = $company_id;
        $permission_role->save();
        
        return "true";
    }
    
    public function unassignPositionPermission(Request $request) {
        
        $role_id = $request->input('role_id');
        $permission_id = $request->input('permission_id');
        $company_id = $request->input('company_id');
         
        $permission_role = PermissionRole::where('permission_id',$permission_id)->where('role_id',$role_id)->where('company_id',$company_id);
        $permission_role->delete();
        
        return "true";
    }
}
