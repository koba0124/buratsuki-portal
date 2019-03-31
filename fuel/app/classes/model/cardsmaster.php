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

	public static function get_list($type, $deck, $name, $pagination)
	{
		$query = DB::select('card_id', 'card_id_display', 'japanese_name', 'deck', 'type')
					->from(self::TABLE_NAME);
		$query = self::append_where_for_list($query, $type, $deck, $name);
		$query->order_by('card_id', 'asc');
		$query->limit($pagination->per_page);
		$query->offset($pagination->offset);
		return $query->execute()->as_array();
	}

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

	public static function count_list($type, $deck, $name)
	{
		$query = DB::select(DB::expr('COUNT(*) AS count'))
					->from(self::TABLE_NAME);
		$query = self::append_where_for_list($query, $type, $deck, $name);
		return $query->execute()->as_array()[0]['count'] ?? 0;
	}

	private static function append_where_for_list($query, $type, $deck, $name)
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
		return $query;
	}

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
}