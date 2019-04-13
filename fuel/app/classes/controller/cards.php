<?php
class Controller_Cards extends Controller_Template
{
	// カード一覧用デッキリストのキャッシュ
	const CACHE_NAME = 'form_decks_list';

	/**
	 * カード一覧 /cards GET
	 */
	public function action_index()
	{
		$this->template->title = 'カード';
		$this->template->breadcrumbs = [
			'/cards' => 'カード',
		];
		$this->template->content = View::forge('cards/index');
		Asset::js(['cards_index.js'], [], 'add_js');

		$type = Input::get('t');
		$deck = Input::get('d');
		$name = Input::get('n');
		$this->template->content->decks = self::get_form_decks_list();
		$this->template->content->t = is_array($type) ? $type : ['1', '2', '3'];

		$count = Model_CardsMaster::count_list($type, $deck, $name);
		$config = [
			'pagination_url' => Uri::create('cards', [], ['t' => $type, 'd' => $deck, 'n' => $name]),
			'uri_segment' => 'p',
			'per_page' => 30,
			'total_items' => $count,
		];
		$pagination = Pagination::forge('cards', $config);

		$this->template->content->cards_list = Model_CardsMaster::get_list($type, $deck, $name, $pagination);
	}

	/**
	 * ゲーム一覧用のデッキリスト配列をキャッシュから取得
	 * @return array 絞り込みフォーム用デッキリスト
	 */
	private static function get_form_decks_list()
	{
		try {
			return Cache::get(self::CACHE_NAME);
		} catch (CacheNotFoundException $e) {}
		$decks_list = Model_DecksMaster::get_list();
		$deck_groups_list = Model_DeckGroupsMaster::get_list();
		$form_deck_keys = [
			'旧版基本セット' => ['E', 'I', 'K', 'EIK'],
			'BI', 'CZ', 'FL', 'FR', 'G', 'NL', 'O', 'P', 'WA', 'WM', 'Z',
			'Re',  'R5', 'A', 'B', 'Wizkids', 'L',
			'リバイズドデッキ別' => ['Adeck', 'Bdeck', 'Cdeck', 'Ddeck']
		];
		$form_decks_list = [null => 'すべてのデッキ'];
		$decks_keys = array_keys($decks_list);
		foreach ($form_deck_keys as $key => $value) {
			if (is_array($value)) {
				$form_deck_list[$key] = [];
				foreach ($value as $v) {
					if (in_array($v, $decks_keys)) {
						// デッキの場合
						$form_decks_list[$key][$v] = $decks_list[$v];
					} else {
						// グループの場合
						$form_decks_list[$key][$v] = $deck_groups_list[$v]['deck_group_name'];
					}
				}
			} else {
				if (in_array($value, $decks_keys)) {
					// デッキの場合
					$form_decks_list[$value] = $decks_list[$value];
				} else {
					// グループの場合
					$form_decks_list[$value] = $deck_groups_list[$value]['deck_group_name'];
				}
			}
		}
		Cache::set(
			self::CACHE_NAME,
			$form_decks_list,
			null,
			[Model_DecksMaster::CACHE_NAME, Model_DeckGroupsMaster::CACHE_NAME]
		);
		return $form_decks_list;
	}

	/**
	 * カード詳細 /cards/view/:card_id GET
	 */
	public function action_view($card_id)
	{
		$card = Model_CardsMaster::get_by_card_id($card_id);
		if (! $card) {
			throw new HttpNotFoundException;
		}

		$this->template->content = View::forge('cards/view');
		$this->template->content->card = $card;
		$this->template->title = '[' . $card['card_id_display'] . ']' . $card['japanese_name'];
		$this->template->breadcrumbs = [
			'/cards' => 'カード',
			'/cards/view/'.$card_id => $this->template->title,
		];

		$review_data = Model_CardsReview::get_list_by_card_id($card_id);
		$this->template->content->review_data = $review_data;
		if ($review_data !== []) {
			$review_points_avg = Arr::average(array_column($review_data, 'review_points'));
			$this->template->content->review_points_avg = sprintf('%.1f', $review_points_avg);
		}

		$count = Model_GamesCards::count_by_card_id($card_id);
		$this->template->content->count = $count;
		// $pagination = Pagination::forge('games', [
		// 	'pagination_url' => Uri::create('cards/view/'. $card_id),
		// 	'uri_segment' => 'p',
		// 	'per_page' => 10,
		// 	'total_items' => $count,
		// ]);
		$games_data = Model_GamesCards::get_list_by_card_id($card_id);
		$this->template->content->games_data = $games_data;

		$count_rank_first = array_reduce($games_data, function($c, $i) {
			if ($i['rank'] === '1') $c++;
			return $c;
		}, 0);
		$this->template->content->count_rank_first = $count_rank_first;
	}

	/**
	 * 評価入力 /cards/review/:card_id GET
	 */
	public function get_review($card_id)
	{
		if (! Auth::check()) {
			throw new HttpNotFoundException;
		}

		$card = Model_CardsMaster::get_by_card_id($card_id);
		if (! $card) {
			throw new HttpNotFoundException;
		}

		$this->template->content = View::forge('cards/review');
		$this->template->content->card = $card;
		$this->template->title = '評価';
		$this->template->breadcrumbs = [
			'/cards' => 'カード',
			'/cards/view/'.$card_id => '[' . $card['card_id_display'] . ']' . $card['japanese_name'],
			'/cards/review/'.$card_id => '評価',
		];

		$this->template->content->card = $card;
		$this->template->content->review_data = Model_CardsReview::get($card_id, Auth::get_screen_name());
		$this->template->content->error_fields = [];
	}

	/**
	 * 評価入力 cards/review/:card_id POST
	 */
	public function post_review($card_id)
	{
		$this->get_review($card_id);
		if (! Security::check_token()) {
			$this->template->errors = '再度送信してください';
			return;
		}

		$val = self::validation_review();
		if (! $val->run()) {
			$this->template->errors = [];
			foreach ($val->error() as $field => $error) {
				$this->template->errors[] = $error->get_message();
				$this->template->content->error_fields[] = $field;
			}
			return;
		}

		Model_CardsReview::update(
			$card_id,
			Auth::get_screen_name(),
			Input::post('review_points'),
			Input::post('review_comment')
		);

		Session::set_flash('messages', '評価を更新しました');
		Response::redirect('/cards/view/' . $card_id);
	}

	/**
	 * 評価入力 Validatipn
	 * @return Validation
	 */
	private static function validation_review()
	{
		$val = Validation::forge();
		$val->add('review_points', '評価点')
			->add_rule('required');
		$val->add('review_comment', 'ひとこと');
		return $val;
	}
}