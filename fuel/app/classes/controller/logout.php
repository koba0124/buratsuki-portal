<?php
class Controller_Logout extends Controller
{
	/**
	 * ログアウト /logout GET
	 */
	public function action_index()
	{
		Auth::logout();
		Session::set_flash('messages', 'ログアウトしました');
		Response::redirect('/login');
	}
}