<?php
class Controller_Users extends Controller_Template
{
	public function action_index()
	{
		$data = Model_Users::get_list();
		$this->template->content = View::forge('users/index');
		$this->template->content->data = $data;
		$this->template->title = 'メンバー';
		$this->template->breadcrumbs = [
			'/users' => 'メンバー',
		];
	}

	public function action_view($user_id)
	{
		$this->template->content = View::forge('users/view');

		$user_data = Model_Users::get_by_user_id($user_id);
		if (! $user_data) {
			throw new HttpNotFoundException;
		}
		$this->template->content->user_data = $user_data;
		$this->template->title = '['.$user_id.'] '.$user_data['screen_name'];
		$this->template->breadcrumbs = [
			'/users' => 'メンバー',
			'/members/view/'.$user_id => $this->template->title,
		];

		$occupations = Model_CardsMaster::get_list_by_card_ids($user_data['occupations'] ?? []);
		$minor_improvements = Model_CardsMaster::get_list_by_card_ids($user_data['minor_improvements'] ?? []);
		$this->template->content->occupations = $occupations;
		$this->template->content->minor_improvements = $minor_improvements;

		$count = Model_GamesScores::count_list_for_users($user_id);
		$pagination = Pagination::forge('games', [
			'pagination_url' => Uri::create('users/view/'. $user_id),
			'uri_segment' => 'p',
			'per_page' => 10,
			'total_items' => $count,
		]);

		$this->template->content->games_list = Model_GamesScores::get_list_for_users($user_id, $pagination);
	}
}