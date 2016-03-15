<?php

namespace App\Http\Controllers;

use App\Models\User;

class BaseController extends Controller
{

    /**
     * Setup the layout used by the controller.
     *
     * @return void
     */
    protected function setupLayout()
    {
        if (!is_null($this->layout)) {
            $this->layout = View::make($this->layout);
        }
    }

    /**
     * @return User
     */
    protected function getActiveUser(){
        return request()->user();
    }

    protected function userHasRole($role){
        $user = $this->getActiveUser();
        return $user->is(strtolower($role));
    }

}
