<?php
class Controller_Cards extends Controller_Template
{
	const CACHE_NAME = 'form_decks_list';

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
		$pagination = Pagination::forge('cards', [
			'pagination_url' => Uri::create('cards', [], ['t' => $type, 'd' => $deck, 'n' => $name]),
			'uri_segment' => 'p',
			'per_page' => 30,
			'total_items' => $count,
		]);

		$this->template->content->cards_list = Model_CardsMaster::get_list($type, $deck, $name, $pagination);
	}

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
	}

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
		Cache::set(self::CACHE_NAME,
			$form_decks_list,
			null,
			[
				Model_DecksMaster::CACHE_NAME,
				Model_DeckGroupsMaster::CACHE_NAME,
			]
		);
		return $form_decks_list;
	}
}