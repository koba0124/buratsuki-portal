<?php
namespace Fuel\Tasks;

class Auth
{
	public static function reset_password($username)
	{
		echo 'New Password: ', \Auth::reset_password($username), "\n";
	}
}