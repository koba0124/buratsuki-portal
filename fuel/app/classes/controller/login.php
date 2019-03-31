<?php
class Controller_Login extends Controller_Template
{
	public function get_index()
	{
		$this->template->title = 'ログイン';
		$this->template->breadcrumbs = [
			'/login' => 'ログイン',
		];
		$this->template->content = View::forge('login');
	}

	public function post_index()
	{
		$this->get_index();
		if (! Security::check_token()) {
			$this->template->errors = '再度送信してください';
			return;
		}
		if (! Auth::login()) {
			$this->template->errors = 'ログインに失敗しました';
			return;
		}
		Session::set_flash('messages', 'ログインしました');
		Response::redirect('home');
	}
}