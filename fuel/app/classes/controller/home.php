<?php
class Controller_Home extends Controller_Template
{
	public function before()
	{
		parent::before();
		if (! Auth::check()) {
			Response::redirect('/login');
		}
	}

	public function action_index()
	{
		$login_message = Session::get_flash('login_message');
		if ($login_message) {
			$this->template->messages = [$login_message];
		}
		$this->template->title = 'マイページ';
		$this->template->breadcrumbs = [
			'/home' => 'マイページ',
		];
	}
}