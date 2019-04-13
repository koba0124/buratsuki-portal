<?php
class Model_GamesCards
{
	const TABLE_NAME = 'games_cards';

	public static function get_for_edit($game_id, $player_order)
	{
		$query = DB::select()
					->from(self::TABLE_NAME)
					->where('game_id', '=', $game_id)
					->and_where('player_order', '=', $player_order)
					->join(Model_CardsMaster::TABLE_NAME, 'inner')
					->on(self::TABLE_NAME.'.card_id', '=', Model_CardsMaster::TABLE_NAME.'.card_id');
		$records = $query->execute()->as_array();
		$list = [
			'occupations' => [],
			'minor_improvements' => [],
			'major_improvements' => [],
		];
		foreach ($records as $record) {
			$list[$record['type'] . 's'][] = $record['card_id'];
		}
		return $list;
	}

	public static function update($game_id, $player_order, $cards_list)
	{
		$delete_query = DB::delete(self::TABLE_NAME)
							->where('game_id', $game_id)
							->and_where('player_order', $player_order);
		$delete_query->execute();
		$query = DB::insert(self::TABLE_NAME)
					->columns(['game_id', 'player_order', 'card_id']);
		foreach (Model_CardsMaster::TYPES_LABEL as $field => $label) {
			foreach ($cards_list[$field . 's'] as $card) {
				$query->values([$game_id, $player_order, $card]);
			}
		}
		$query->execute();
	}

	public static function get_for_view($game_id)
	{
		$query = DB::select()
					->from(self::TABLE_NAME)
					->where('game_id', '=', $game_id)
					->join(Model_CardsMaster::TABLE_NAME)
					->on(self::TABLE_NAME . '.card_id', '=', Model_CardsMaster::TABLE_NAME . '.card_id')
					->join(Model_DecksMaster::TABLE_NAME)
					->on(Model_CardsMaster::TABLE_NAME . '.deck', '=', Model_DecksMaster::TABLE_NAME . '.deck');
		$records = $query->execute()->as_array();

		$data = [];
		foreach ($records as $record) {
			$data[$record['player_order']][$record['type'] . 's'][] = $record;
		}
		return $data;
	}

	public static function delete($game_id)
	{
		$query = DB::delete(self::TABLE_NAME)
					->where('game_id', '=', $game_id);
		return $query->execute();
	}

	public static function count_by_card_id($card_id)
	{
		$query = DB::select(DB::expr('COUNT(*) AS count'))
					->from(self::TABLE_NAME)
					->where('card_id', '=', $card_id);
		return $query->execute()->as_array()[0]['count'] ?? 0;
	}

	public static function get_list_by_card_id($card_id)
	{
		$columns = [
			self::TABLE_NAME . '.card_id',
			Model_GamesScores::TABLE_NAME . '.game_id',
			Model_GamesScores::TABLE_NAME . '.player_order',
			Model_GamesScores::TABLE_NAME . '.username',
			'total_points',
			'rank',
			Model_Games::TABLE_NAME . '.created_at',
			'players_number',
			'regulation_name',
			'is_moor',
			Model_Users::TABLE_NAME . '.profile_fields',
		];
		$query = DB::select_array($columns)
					->from(self::TABLE_NAME)
					->where('card_id', '=', $card_id)
					->join(Model_GamesScores::TABLE_NAME)
					->on(self::TABLE_NAME . '.game_id', '=', Model_GamesScores::TABLE_NAME . '.game_id')
					->and_on(self::TABLE_NAME . '.player_order', '=', Model_GamesScores::TABLE_NAME . '.player_order')
					->join(Model_Games::TABLE_NAME)
					->on(self::TABLE_NAME . '.game_id', '=', Model_Games::TABLE_NAME . '.game_id')
					->join(Model_RegulationsMaster::TABLE_NAME)
					->on(Model_Games::TABLE_NAME . '.regulation_type', '=', Model_RegulationsMaster::TABLE_NAME . '.regulation_type')
					->join(Model_Users::TABLE_NAME, 'left outer')
					->on(Model_GamesScores::TABLE_NAME . '.username', '=', Model_Users::TABLE_NAME . '.username')
					->order_by(Model_Games::TABLE_NAME . '.created_at', 'desc');
		$records = $query->execute()->as_array();
		$records = Model_Users::append_profile_fields($records);
		return $records;
	}
}