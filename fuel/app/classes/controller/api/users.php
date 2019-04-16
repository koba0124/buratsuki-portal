<?php
class Controller_Api_Users extends Controller_Rest
{
	protected $format = 'json';

	public function get_index()
	{
		$records = Model_Users::get_list(true);
		$data = [];
		foreach ($records as $record) {
			$data[$record['username']] = Asset::get_file($record['icon'], 'img');
		}
		return $data;
	}
}