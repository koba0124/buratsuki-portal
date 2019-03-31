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
}