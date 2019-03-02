<?php
class Controller_Index extends Controller_Template
{
	public function action_index()
	{
		$this->template->title = 'TOP';
		$this->template->content = View::forge('index');
	}

	public function action_404()
	{
		$this->response_status = 404;
		$this->template->title = 'Not Found';
		$this->template->content = View::forge('404');
	}
}