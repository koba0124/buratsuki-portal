<?php
class Controller_Statistics extends Controller_Template
{
	public function action_index()
	{
		$this->template->title = '統計';
		$this->template->breadcrumbs = [
			'/statistics' => '統計',
		];
		$this->template->content = View::forge('statistics/index');
	}

	public function action_cardsuses()
	{
		$this->template->title = 'カード使用・勝利回数';
		$this->template->breadcrumbs = [
			'/statistics' => '統計',
			'/statistics/cardsuses' => $this->template->title,
		];
		$this->template->content = View::forge('statistics/cardsuses');
		Asset::js(['statistics_cardsuses.js'], [], 'add_js');

		$types = ['occupation', 'minor_improvement'];
		$this->template->content->uses_ranking = [];
		$this->template->content->wins_ranking = [];
		foreach ($types as $type) {
			$this->template->content->uses_ranking[$type] = Model_GamesCards::get_uses_ranking($type, 2);
			$this->template->content->wins_ranking[$type] = Model_GamesCards::get_wins_ranking($type, 2);
		}
	}

	public function action_revisedcardsuses()
	{
		$this->template->title = 'カード使用・勝利回数(Revised)';
		$this->template->breadcrumbs = [
			'/statistics' => '統計',
			'/statistics/revisedcardsuses' => $this->template->title,
		];
		$this->template->content = View::forge('statistics/cardsuses');
		Asset::js(['statistics_cardsuses.js'], [], 'add_js');

		$types = ['occupation', 'minor_improvement'];
		$this->template->content->uses_ranking = [];
		$this->template->content->wins_ranking = [];
		foreach ($types as $type) {
			$this->template->content->uses_ranking[$type] = Model_GamesCards::get_uses_ranking($type, 4);
			$this->template->content->wins_ranking[$type] = Model_GamesCards::get_wins_ranking($type, 4);
		}
	}

	public function action_score()
	{
		$this->template->title = 'ハイスコア';
		$this->template->breadcrumbs = [
			'/statistics' => '統計',
			'/statistics/score' => $this->template->title,
		];
		$this->template->content = View::forge('statistics/score');
		Asset::js(['statistics_score.js'], [], 'add_js');

		$this->template->content->normal_ranking = Model_GamesScores::get_score_ranking(2, 0);
		$this->template->content->moor_ranking = Model_GamesScores::get_score_ranking(2, 1);
		$this->template->content->revised_ranking = Model_GamesScores::get_score_ranking(4, 0);
	}

	public function action_user($username)
	{
		$this->template->content = View::forge('statistics/user');
		Asset::js(['https://cdn.jsdelivr.net/npm/chart.js@2.8.0', 'statistics_user.js'], [], 'add_js');

		$user_data = Model_Users::get_by_user_id($username);
		// ユーザが存在しないとき
		if (! $user_data) {
			throw new HttpNotFoundException;
		}
		$this->template->content->user_data = $user_data;

		$this->template->title = '['.$username.'] '.$user_data['screen_name'];
		$this->template->breadcrumbs = [
			'/users' => 'メンバー',
			'/users/view/' . $username => $this->template->title,
			'/statistics/user/' . $username => '統計',
		];

		$this->template->content->transition_normal = Model_GamesScores::get_transition($username, 0);
		$this->template->content->distribution_normal = Model_GamesScores::get_distribution($username, 0);
		$this->template->content->uses_rankings = [
			'occupation' => Model_GamesCards::get_uses_ranking_by_user($username, 'occupation', 4),
			'minor_improvement' => Model_GamesCards::get_uses_ranking_by_user($username, 'minor_improvement', 4),
		];
	}

	public function action_order()
	{
		$this->template->title = '番手別着順・平均点';
		$this->template->breadcrumbs = [
			'/statistics' => '統計',
			'/statistics/order' => $this->template->title,
		];
		$this->template->content = View::forge('statistics/order');
		Asset::js(['statistics_order.js'], [], 'add_js');

		$this->template->content->score_average_data = Model_GamesScores::get_score_average_by_order();
		$this->template->content->rank_average_data = Model_GamesScores::get_rank_average_by_order();
	}
}
