<?php
class Controller_Cards extends Controller_Template
{
	const FORM_DECKS = [
		null => 'すべてのデッキ',
		'旧版基本セット' => [
			'E' => 'Eデッキ',
			'I' => 'Iデッキ',
			'K' => 'Kデッキ',
			'EIK' => '旧版基本全て',
		],
		'BI' => 'BIデッキ',
		'C' => 'Čデッキ',
		'FL' => 'FLデッキ',
		'FR' => 'FRデッキ',
		'G' => 'Gデッキ',
		'NL' => 'NLデッキ',
		'O' => 'Öデッキ',
		'P' => 'πデッキ',
		'WA' => 'WA デッキ',
		'WM' => 'WMデッキ',
		'Z' => 'Zデッキ',
		'Re' => 'リバイズド基本セット',
		'R5' => 'リバイズド5-6人拡張',
		'A' => 'Artifexデッキ',
		'B' => 'Bubulcusデッキ',
		'Wizkids' => 'Wizkids拡張',
		'L' => 'Lデッキ',
		'リバイズドデッキ別' => [
			'Adeck' => 'Aデッキ',
			'Bdeck' => 'Bデッキ',
			'Cdeck' => 'Cデッキ',
			'Ddeck' => 'Dデッキ',
		],
	];

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
		$this->template->content->decks = self::FORM_DECKS;
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
}