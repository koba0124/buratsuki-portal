<?php
class Controller_Test extends Controller_Template
{
	public $section = 'テスト';

	public function action_index()
	{
		$this->template->title = 'テスト';
	}
}