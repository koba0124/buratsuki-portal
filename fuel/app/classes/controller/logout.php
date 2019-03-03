<?php
class Controller_Logout extends Controller
{
	public function action_index()
	{
		Auth::logout();
		Session::set_flash('logout_message', 'ログアウトしました');
		Response::redirect('/login');
	}
}