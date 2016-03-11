<?php

namespace App\Models;


use Zizaco\Entrust\EntrustPermission;

class Permission extends EntrustPermission
{

    protected $table = 'fp_permissions';
}