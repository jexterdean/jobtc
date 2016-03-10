<?php
namespace App\Helpers;

use Session;

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
        $user = DB::table('fp_user')->where('username', '=', $username)->first();
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
}

?>