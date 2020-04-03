<?php
class Controller_Index extends Controller_Template
{
	/**
	 * トップページ / GET
	 */
	public function action_index()
	{
		$this->template->title = 'TOP';
		$this->template->content = View::forge('index');
	}

	/**
	 * 本サイトについて /about GET
	 */
	public function action_about()
	{
		$this->template->title = '本サイトについて';
		$this->template->breadcrumbs = [
			'/about' => '本サイトについて',
		];
		$this->template->content = View::forge('about');
	}

	/**
	 * ネットでアグリコラ(TTS) /tts GET
	 */
	public function action_tts()
	{
		$this->template->title = 'Table Top Simulator';
		$this->template->breadcrumbs = [
			'/tts' => 'Table Top Simulator',
		];
		$this->template->content = View::forge('tts');
	}


	/**
	 * 404ページ GET
	 */
	public function action_404()
	{
		$this->response_status = 404;
		$this->template->title = 'Not Found';
		$this->template->breadcrumbs = [
			'#' => 'Not Found',
		];
		$this->template->content = View::forge('404');
	}
}