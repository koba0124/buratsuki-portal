<?php
class Model_CardsMaster
{
	const TABLE_NAME = 'cards_master';

	const TYPES = [
		'1' => 'occupation',
		'2' => 'minor_improvement',
		'3' => 'major_improvement',
	];

	const TYPES_LABEL = [
		'occupation' => '職業',
		'minor_improvement' => '小さい進歩',
		'major_improvement' => '大きい進歩',
	];

	/**
	 * 条件に合致したカードリストを取得
	 * @param  array      $type       職業/小さい進歩/大きい進歩のtype番号
	 * @param  stirng     $deck       デッキ/デッキグループ
	 * @param  string     $name       名前に含まれる文字列
	 * @param  string     $descriotion_query カードテキスト
	 * @param  Pagination $pagination ページネーション
	 * @return array      カードレコードの配列
	 */
	public static function get_list($type, $deck, $name, $descriotion_query, $pagination)
	{
		$query = DB::select('card_id', 'card_id_display', 'japanese_name', 'deck', 'type')
					->from(self::TABLE_NAME);
		$query = self::append_where_for_list($query, $type, $deck, $name, $descriotion_query);
		$query->order_by('card_id', 'asc');
		$query->limit($pagination->per_page);
		$query->offset($pagination->offset);
		return $query->execute()->as_array();
	}

	/**
	 * 条件に合致するカードの総数を取得
	 * @param  array  $type 職業/小さい進歩/大きい進歩のtype番号
	 * @param  stirng $deck デッキ/デッキグループ
	 * @param  string $name 名前に含まれる文字列
	 * @param  string $descriotion_query カードテキスト
	 * @return int          カード総数
	 */
	public static function count_list($type, $deck, $name, $descriotion_query)
	{
		$query = DB::select(DB::expr('COUNT(*) AS count'))
					->from(self::TABLE_NAME);
		$query = self::append_where_for_list($query, $type, $deck, $name, $descriotion_query);
		return $query->execute()->as_array()[0]['count'] ?? 0;
	}

	/**
	 * カードリストのためのクエリにwhere句を追加
	 * @param  DB     $query クエリオブジェクト
	 * @param  array  $type  職業/小さい進歩/大きい進歩のtype番号
	 * @param  string $deck  デッキ/デッキグループ
	 * @param  string $name  名前に含まれる文字列
	 * @param  string $descriotion_query カードテキスト
	 * @return DB            クエリオブジェクト
	 */
	private static function append_where_for_list($query, $type, $deck, $name, $descriotion_query)
	{
		$deck_groups_list = Model_DeckGroupsMaster::get_list();
		if ($deck) {
			if (isset($deck_groups_list[$deck])) {
				$query->where('deck', 'in', $deck_groups_list[$deck]['deck_group_elements']);
			} else {
				$query->where('deck', '=', $deck);
			}
		}
		if (is_array($type)) {
			$types = [];
			foreach ($type as $t) {
				$types[] = self::TYPES[$t] ?? $t;
			}
			$query->where('type', 'in', $types);
		}
		if ($name) {
			$query->where('japanese_name', 'like', '%' . $name . '%');
		}
		if ($descriotion_query) {
			$descriotion_query = trim(str_replace('　', ' ', $descriotion_query));
			$keywords = explode(' ', $descriotion_query);
			foreach ($keywords as $keyword) {
				if (strlen($keyword) === 0) continue;
				if (mb_substr($keyword, 0, 1) === '-') {
					$query->where('description', 'not like', '%' . mb_substr($keyword, 1, null) . '%');
				} else {
					$query->where('description', 'like', '%' . $keyword . '%');
				}
			}
		}
		return $query;
	}

	/**
	 * id配列に対応するカードリストを取得
	 * @param  array $card_ids カードIDの配列
	 * @return array           カードレコードの配列
	 */
	public static function get_list_by_card_ids($card_ids)
	{
		if (! count($card_ids)) {
			return [];
		}
		$query = DB::select('card_id', 'card_id_display', 'japanese_name', 'deck', 'type')
					->from(self::TABLE_NAME)
					->where('card_id', 'in', $card_ids)
					->order_by('card_id', 'asc');
		return $query->execute()->as_array();
	}

	/**
	 * カードタイプに対応するカードIDのリストを取得
	 * @param  string $type occupationなどカードタイプ文字列、nullなら全種
	 * @return array        カードIDの配列
	 */
	public static function get_card_ids_list($type = null)
	{
		$query = DB::select('card_id')
					->from(self::TABLE_NAME);
		$cache_name = 'cardmaster_card_ids';
		if ($type !== null) {
			$query->where('type', '=', $type);
			$cache_name .= '_' . $type;
		}
		$records = $query->cached(3600, $cache_name)->execute()->as_array();
		if ($records === []) {
			return [];
		}
		$list = array_column($records, 'card_id');
		return $list;
	}

	/**
	 * idに対応するカード詳細を取得
	 * @param  stirng $card_id カードIDの配列
	 * @return array           カードレコード
	 */
	public static function get_by_card_id($card_id)
	{
		$query = DB::select()
					->from(self::TABLE_NAME)
					->where('card_id', '=', $card_id)
					->limit(1)
					->join(Model_DecksMaster::TABLE_NAME, 'INNER')
					->on(self::TABLE_NAME . '.deck', '=', Model_DecksMaster::TABLE_NAME . '.deck');
		$record = $query->execute()->as_array()[0] ?? null;
		if (! $record) {
			return null;
		}
		return $record;
	}

	public static function get_type($card)
	{
		switch ($card['deck']) {
			case 'WB':
				return 'WB';
			case 'X':
				return 'X';
			case 'LF':
				return 'LF';
			default:
				return $card['type'];
		}
	}
}