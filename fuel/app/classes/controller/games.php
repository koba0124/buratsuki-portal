<?php
class Controller_Games extends Controller_Template
{
	const PLAYERS_NUMBER_LIST = [
		null => '[未選択]',
		1 => '1人',
		2 => '2人',
		3 => '3人',
		4 => '4人',
		5 => '5人',
		6 => '6人',
	];

	const BASIC_POINTS_LIST = [
		'fields' => '畑',
		'pastures' => '牧場',
		'grain' => '小麦',
		'vegetable' => '野菜',
		'sheep' => '羊',
		'boar' => '猪',
		'cattle' => '牛',
	];

	const ADVANCED_POINTS_LIST = [
		'unused_spaces' => '未使用スペース',
		'stable' => '柵に囲まれた厩',
		'rooms' => '部屋',
		'family' => '家族',
		'begging' => '物乞い',
		'card_points' => 'カード点',
		'bonus_points' => 'ボーナス点',
	];

	/**
	 * ゲーム作成 games/create GET
	 */
	public function get_create()
	{
		if (! Auth::check()) {
			Response::redirect('/login');
		}

		$this->template->title = 'ゲーム作成';
		$this->template->breadcrumbs = [
			'/games' => '戦績',
			'/games/create' => 'ゲーム作成',
		];
		$this->template->content = View::forge('games/create');
		$this->template->content->error_fields = [];
		$this->template->content->players_number_list = self::PLAYERS_NUMBER_LIST;
		$this->template->content->regulation_type_list = Model_RegulationsMaster::get_list();
		Asset::js(['games_create.js'], [], 'add_js');
	}

	/**
	 * ゲーム作成 games/create POST
	 */
	public function post_create()
	{
		$this->get_create();
		if (! Security::check_token()) {
			$this->template->errors = '再度送信してください';
			return;
		}

		$val = self::validation_create();
		if (! $val->run()) {
			$this->template->errors = [];
			foreach ($val->error() as $field => $error) {
				$this->template->errors[] = $error->get_message();
				$this->template->content->error_fields[] = $field;
			}
			return;
		}

		$game_id = uniqid(rand());
		Model_Games::create(
			$game_id,
			Input::post('players_number'),
			Input::post('regulation_type'),
			Input::post('is_moor'),
			Auth::get_screen_name()
		);
		Model_GamesScores::create($game_id, Input::post('players'));

		Session::set_flash('messages', 'ゲームを作成しました');
		Response::redirect('/home');
	}

	/**
	 * ゲーム作成 Validation
	 * @return object Validation
	 */
	private static function validation_create()
	{
		$val = Validation::forge();
		$val->add_callable('ValidationRule');
		$val->add('players_number', 'プレイ人数')
			->add_rule('required')
			->add_rule('match_collection', array_keys(self::PLAYERS_NUMBER_LIST));
		$val->add('regulation_type', 'レギュレーション')
			->add_rule('required')
			->add_rule('match_collection', array_keys(Model_RegulationsMaster::get_list()));
		$val->add('is_moor', 'レギュレーション2')
			->add_rule('required')
			->add_rule('match_collection', range(0, 1));
		for ($i = 0; $i < Input::post('players_number'); $i++) {
			$val->add('players.' . $i, 'ユーザID(' . ($i + 1) . '番手)')
				->add_rule('required')
				->add_rule('exists_user');
		}
		return $val;
	}

	/**
	 * 戦績編集 games/edit GET
	 */
	public function get_edit($game_id, $player_order)
	{
		if (! Auth::check()) {
			Response::redirect('/login');
		}

		$data = Model_GamesScores::get_for_edit($game_id, $player_order);
		if (! $data) {
			throw new HttpNotFoundException;
		}

		$this->template->title = '編集';
		$this->template->breadcrumbs = [
			'/games' => '戦績',
			'/games/view/'.$game_id => date('Y/m/d', strtotime($data['created_at'])),
			'/games/edit/'.$game_id.'/'.$player_order => '編集',
		];
		$this->template->content = View::forge('games/edit');
		$this->template->content->error_fields = [];
		$this->template->content->data = $data;
		$this->template->content->cards_data = Model_GamesCards::get_for_edit($game_id, $player_order);
		$this->template->content->basic_points_list = self::BASIC_POINTS_LIST;
		$this->template->content->cards_type_list = Model_CardsMaster::TYPES_LABEL;
		Asset::js(['games_edit.js'], [], 'add_js');
	}

	/**
	 * 戦績編集 games/edit POST
	 */
	public function post_edit($game_id, $player_order)
	{
		$this->get_edit($game_id, $player_order);
		if (! Security::check_token()) {
			$this->template->errors = '再度送信してください';
			return;
		}

		// 空要素を詰める
		$cards_list = [];
		foreach ($this->template->content->cards_type_list as $field => $label) {
			$cards = Input::post($field . 's', []);
			$cards_list[$field . 's'] = array_values(array_filter($cards, 'strlen'));
		}
		$this->template->content->cards_list = $cards_list;

		$val = self::validation_edit($this->template->content->data, $cards_list);
		if (! $val->run($cards_list)) {
			$this->template->errors = [];
			foreach ($val->error() as $field => $error) {
				$this->template->errors[] = $error->get_message();
				$this->template->content->error_fields[] = $field;
			}
			return;
		}

		$image = null;
		if (Input::file('image')['name'] !== '') {
			$config = [
				'path' => DOCROOT.'assets/img/upload/games',
				'ext_whitelist' => ['jpg', 'jpeg', 'png', 'gif'],
				'new_name' => $game_id.'_'.$player_order,
				'auto_rename' => false,
				'overwrite' => true,
				'max_size' => 5 * 1024 * 1024,
				'create_path' => true,
			];
			Upload::process($config);
			if (! Upload::is_valid()) {
				$errors = Upload::get_errors('image')['errors'];
				$this->template->errors = array_column($errors, 'message');
				return;
			}
			Upload::save();
			$image = 'upload/games/' . Upload::get_files('image')['saved_as'];
		}

		Model_GamesScores::update($game_id, $player_order, $image);
		Model_GamesCards::update($game_id, $player_order, $cards_list);

		Session::set_flash('messages', '戦績の編集に成功しました');
		Response::redirect('/games/view/'.$game_id);
	}

	/**
	 * 戦績編集 Validation
	 * @return object Validation
	 */
	private static function validation_edit($data, $cards)
	{
		$val = Validation::forge();
		$val->add_callable('ValidationRule');
		foreach (self::BASIC_POINTS_LIST as $field => $label) {
			$val->add($field, $label)
				->add_rule('required')
				->add_rule('numeric_between', -1, 4);
		}
		if ($data['is_moor']) {
			$val->add('horses', '馬')
				->add_rule('required')
				->add_rule('numeric_min', -1);
		}
		$val->add('unused_spaces', '未使用スペース')
			->add_rule('required')
			->add_rule('numeric_max', 0);
		$val->add('stable', '柵に囲まれた厩')
			->add_rule('required')
			->add_rule('numeric_between', 0, 4);
		$val->add('rooms', '部屋')
			->add_rule('required')
			->add_rule('numeric_min', 0);
		$val->add('family', '家族')
			->add_rule('required')
			->add_rule('numeric_between', 0, 15);
		$val->add('begging', '物乞い')
			->add_rule('required')
			->add_rule('numeric_max', 0);
		$val->add('card_points', 'カード点')
			->add_rule('required');
		$val->add('bonus_points', 'ボーナス点')
			->add_rule('required');
		$val->add('total_points', '合計点')
			->add_rule('required');
		$val->add('rank', '順位')
			->add_rule('required')
			->add_rule('numeric_between', 1, $data['players_number']);
		foreach (Model_CardsMaster::TYPES_LABEL as $field => $label) {
			$field_pr = $field . 's';
			$val->add($field_pr, $label)
				->add_rule('array_unique');
			foreach ($cards[$field_pr] as $key => $card) {
				$val->add($field_pr . '.' . $key, $label . '(' . ($key + 1) . '枚目)')
					->add_rule('valid_' . $field . '_id', $card);
			}
		}
		return $val;
	}

	public function get_view($game_id)
	{
		$data = Model_Games::get_for_view($game_id);
		if (! $data) {
			throw new HttpNotFoundException;
		}

		$this->template->content = View::forge('games/view');
		$this->template->title = date('Y/m/d', strtotime($data['created_at'])) . 'の戦績';
		$this->template->breadcrumbs = [
			'/games' => '戦績',
			'/games/view/'.$game_id => date('Y/m/d', strtotime($data['created_at'])),
		];

		$this->template->content->data = $data;
		$score_data = Model_GamesScores::get_for_view($game_id);
		$this->template->content->score_data = $score_data;
		$this->template->content->cards_data = Model_GamesCards::get_for_view($game_id);
		$this->template->content->basic_points_list = self::BASIC_POINTS_LIST;
		$this->template->content->advanced_points_list = self::ADVANCED_POINTS_LIST;
		$this->template->content->cards_type_list = Model_CardsMaster::TYPES_LABEL;

		Asset::js(['games_view.js'], [], 'add_js');

		$sort = array_column($score_data, 'rank');
		array_multisort($sort, SORT_DESC, $score_data);
		$this->template->ogp_image_large = array_reduce($score_data, function($c, $i) {
			if (! empty($i['image'])) $c = $i['image'];
			return $c;
		}, 'noimage_ogp.png');
		$this->template->description = date('Y/m/d', strtotime($data['created_at'])) . 'に行われた' . $data['regulation_name'];
		if ($data['is_moor']) {
			$this->template->description .= '(泥沼)';
		}
		$this->template->description .= $data['players_number'] .'人ゲームの戦績です。';

		foreach ($score_data as $record) {
			if ($record['total_points'] === null) return;
		}
		$score_rank_first = $score_data[$data['players_number'] - 1];
		$this->template->description .= '1位は' . $score_rank_first['total_points'] . '点の' . ($score_rank_first['profile_fields']['screen_name'] ?? 'unknown') . '(' . $score_rank_first['player_order'] . '番手)でした。';
	}

	public function post_view($game_id)
	{
		$this->get_view($game_id);
		if (! Security::check_token()) {
			Session::set_flash('errors', '再度送信してください');
			Response::redirect('games/view/' . $game_id);
			return;
		}

		if (Auth::get_screen_name() !== $this->template->content->data['owner']) {
			Session::set_flash('errors', '権限がありません');
			Response::redirect('games/view/' . $game_id);
			return;
		}

		switch (Input::post('submit')) {
			case '削除する':
				Model_Games::delete($game_id);
				Model_GamesScores::delete($game_id);
				Model_GamesCards::delete($game_id);
				Session::set_flash('messages', 'ゲームを削除しました');
				Response::redirect('home');
				break;
			case '計算する':
				$score_data = $this->template->content->score_data;
				foreach ($score_data as $record) {
					if ($record['total_points'] === null) {
						Session::set_flash('errors', '全員分の点数が入力されていません');
						Response::redirect('games/view/' . $game_id);
						return;
					}
				}
				self::calc_rank($game_id, $score_data);
				Session::set_flash('messages', '順位を自動計算しました');
				Response::redirect('games/view/' . $game_id);
				break;
			default:
		}
	}

	public static function calc_rank($game_id, $score_data)
	{
		$sort = array_column($score_data, 'total_points');
		array_multisort($sort, SORT_DESC, $score_data);
		$tmp = -9999;
		$rank = 0;
		$rank_reserve = 1;
		foreach ($score_data as $record) {
			if ($record['total_points'] == $tmp) {
				$rank_reserve++;
			} else {
				$rank += $rank_reserve;
				$tmp = $record['total_points'];
			}
			$rank_list[$record['player_order']] = $rank;
		}
		Model_GamesScores::set_rank($game_id, $rank_list);
	}

	public function action_index()
	{
		$this->template->content = View::forge('games/index');
		$this->template->title = '戦績';
		$this->template->breadcrumbs = [
			'/games' => '戦績',
		];

		$count = Model_Games::count_list();
		$pagination = Pagination::forge('games', [
			'pagination_url' => Uri::create('games'),
			'uri_segment' => 'p',
			'per_page' => 10,
			'total_items' => $count,
		]);

		$this->template->content->games_list = Model_Games::get_list($pagination);
	}
}