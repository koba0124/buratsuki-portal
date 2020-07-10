<?php
class Model_DraftCards
{
	const TABLE_NAME = 'draft_cards';

	/**
	 * 戦績編集用にDraftCardsレコード取得
	 * @param  string $game_id      ゲームID
	 * @param  int    $player_order 番手
	 * @return array                GamesCardsレコード
	 */
	public static function get_for_edit($game_id, $player_order)
	{
		$query = DB::select()
					->from(self::TABLE_NAME)
					->where('game_id', '=', $game_id)
					->and_where('player_order', '=', $player_order)
					->join(Model_CardsMaster::TABLE_NAME, 'inner')
					->on(self::TABLE_NAME.'.card_id', '=', Model_CardsMaster::TABLE_NAME.'.card_id')
					->order_by('pick_order','asc');
		$records = $query->execute()->as_array();
		$list = [
			'occupations' => array_fill(0, 10,'' ),
			'minor_improvements' => array_fill(0, 10,'' ),
		];
		foreach ($records as $record) {
			$list[$record['type'] . 's'][$record['pick_order']] = $record['card_id'];
		}

		return $list;
	}

	/**
	 * 戦績更新
	 * @param  string $game_id      ゲームID
	 * @param  int    $player_order 番手
	 * @param  array  $cards_list   分割されたカードIDの配列
	 */
	public static function update($game_id, $player_order, $cards_list)
	{
		$delete_query = DB::delete(self::TABLE_NAME)
							->where('game_id', $game_id)
							->and_where('player_order', $player_order);
		$delete_query->execute();
		$query = DB::insert(self::TABLE_NAME)
					->columns(['game_id', 'player_order', 'pick_order','card_id']);
		$count = 0;

		$types = ['occupation','minor_improvement'];

		foreach ($types as $field ) {
			foreach ($cards_list[$field . 's'] as $index => $card){
				if (!empty($card)) {
					$count++;
					$query->values([$game_id, $player_order,$index, $card]);
				}
			}
		}

		if ($count === 0) {
			return;
		}
		$query->execute();
	}

	/**
	 * 戦績詳細表示用にGamesCardsレコード取得
	 * @param  string $game_id ゲームID
	 * @return array           番手 => カードタイプ => GamesCardsレコードの配列
	 */
	public static function get_for_view($game_id,$players_number)
	{
		$query = DB::select()
					->from(self::TABLE_NAME)
					->where('game_id', '=', $game_id)
					->join(Model_CardsMaster::TABLE_NAME)
					->on(self::TABLE_NAME . '.card_id', '=', Model_CardsMaster::TABLE_NAME . '.card_id')
					->join(Model_DecksMaster::TABLE_NAME)
					->on(Model_CardsMaster::TABLE_NAME . '.deck', '=', Model_DecksMaster::TABLE_NAME . '.deck');
		$records = $query->execute()->as_array();

		$data = array();
		for ($i = 1; $i <= $players_number; $i++){
			$data[$i] = 		[
				'occupations' => array_fill(0, 10,'' ),
				'minor_improvements' => array_fill(0, 10,'' ),
			];	
		}
		foreach ($records as $record) {
			$data[$record['player_order']][$record['type'] . 's'][$record['pick_order']] = $record;
		}
		return $data;
	}

	/**
	 * ゲーム削除
	 * @param  string $game_id ゲームID
	 */
	public static function delete($game_id)
	{
		$query = DB::delete(self::TABLE_NAME)
					->where('game_id', '=', $game_id);
		return $query->execute();
	}

	//TODO この下は後程
	public static function get_uses_ranking($card_type, $regulation_type)
	{
		$columns = [
			DB::expr('COUNT(*) AS count'),
			self::TABLE_NAME . '.card_id',
			'card_id_display',
			'japanese_name',
		];
		$query = DB::select_array($columns)
					->from(self::TABLE_NAME)
					->join(Model_Games::TABLE_NAME)
					->on(self::TABLE_NAME . '.game_id', '=', Model_Games::TABLE_NAME . '.game_id')
					->join(Model_CardsMaster::TABLE_NAME)
					->on(self::TABLE_NAME . '.card_id', '=', Model_CardsMaster::TABLE_NAME . '.card_id')
					->where('regulation_type', '=', $regulation_type)
					->and_where('type', '=', $card_type)
					->group_by(self::TABLE_NAME . '.card_id')
					->order_by('count', 'desc')
					->order_by('card_id', 'asc')
					->limit(50);
		$result = $query->execute()->as_array();
		$result = self::append_rank($result, 'count');
		return $result;
	}

	public static function get_wins_ranking($card_type, $regulation_type)
	{
		$columns = [
			DB::expr('COUNT(*) AS count'),
			Model_CardsMaster::TABLE_NAME . '.card_id',
			'card_id_display',
			'japanese_name',
		];
		$query = DB::select_array($columns)
					->from(self::TABLE_NAME)
					->join(Model_GamesScores::TABLE_NAME)
					->on(self::TABLE_NAME . '.game_id', '=', Model_GamesScores::TABLE_NAME . '.game_id')
					->and_on(self::TABLE_NAME . '.player_order', '=', Model_GamesScores::TABLE_NAME . '.player_order')
					->join(Model_Games::TABLE_NAME)
					->on(self::TABLE_NAME . '.game_id', '=', Model_Games::TABLE_NAME . '.game_id')
					->join(Model_CardsMaster::TABLE_NAME)
					->on(self::TABLE_NAME . '.card_id', '=', Model_CardsMaster::TABLE_NAME . '.card_id')
					->where('regulation_type', '=', $regulation_type)
					->and_where('players_number', '>=', 2)
					->and_where('type', '=', $card_type)
					->and_where('rank', '=', 1)
					->group_by(self::TABLE_NAME . '.card_id')
					->order_by('count', 'desc')
					->order_by('card_id', 'asc')
					->limit(50);
		$result = $query->execute()->as_array();

		$result = self::append_rate_of_wins($result, $regulation_type);
		$result = self::append_rank($result, 'count');
		return $result;
	}

	private static function append_rate_of_wins($records, $regulation_type)
	{
		$card_id_list = array_column($records, 'card_id');
		$query = DB::select(Model_CardsMaster::TABLE_NAME . '.card_id', DB::expr('COUNT(*) AS count'))
					->from(self::TABLE_NAME)
					->join(Model_Games::TABLE_NAME)
					->on(self::TABLE_NAME . '.game_id', '=', Model_Games::TABLE_NAME . '.game_id')
					->join(Model_CardsMaster::TABLE_NAME)
					->on(self::TABLE_NAME . '.card_id', '=', Model_CardsMaster::TABLE_NAME . '.card_id')
					->where('regulation_type', '=', $regulation_type)
					->and_where('players_number', '>=', 2)
					->and_where(Model_CardsMaster::TABLE_NAME . '.card_id', 'in', $card_id_list)
					->group_by(Model_CardsMaster::TABLE_NAME . '.card_id');
		$result = $query->execute()->as_array();

		$number_of_uses_list = array_column($result, 'count', 'card_id');

		foreach ($records as &$record) {
			$number_of_uses = $record['count'];
			$number_of_wins = $number_of_uses_list[$record['card_id']];
			if ($number_of_wins == 0) {
				continue;
			}
			$record['rate'] = sprintf('%.2f', $number_of_uses / $number_of_wins * 100);
		}
		unset($record);
		return $records;
	}

	public static function append_rank($records, $key)
	{
		$tmp = -99999;
		$rank = 0;
		$rank_reserve = 1;
		foreach ($records as &$record) {
			if ($record[$key] == $tmp) {
				$rank_reserve++;
			} else {
				$rank += $rank_reserve;
				$rank_reserve = 1;
				$tmp = $record[$key];
			}
			$record['rank'] = $rank;
		}
		unset($record);
		return $records;
	}

	public static function get_uses_ranking_by_user($username, $card_type, $regulation_type)
	{
		$columns = [
			DB::expr('COUNT(*) AS count'),
			self::TABLE_NAME . '.card_id',
			'card_id_display',
			'japanese_name',
		];
		$query = DB::select_array($columns)
					->from(self::TABLE_NAME)
					->join(Model_GamesScores::TABLE_NAME)
					->on(self::TABLE_NAME . '.game_id', '=', Model_GamesScores::TABLE_NAME . '.game_id')
					->and_on(self::TABLE_NAME . '.player_order', '=', Model_GamesScores::TABLE_NAME . '.player_order')
					->join(Model_Games::TABLE_NAME)
					->on(self::TABLE_NAME . '.game_id', '=', Model_Games::TABLE_NAME . '.game_id')
					->join(Model_CardsMaster::TABLE_NAME)
					->on(self::TABLE_NAME . '.card_id', '=', Model_CardsMaster::TABLE_NAME . '.card_id')
					->where('regulation_type', '=', $regulation_type)
					->and_where('type', '=', $card_type)
					->and_where('username', '=', $username)
					->group_by(self::TABLE_NAME . '.card_id')
					->order_by('count', 'desc')
					->order_by('card_id', 'asc')
					->limit(20);
		$result = $query->execute()->as_array();
		$result = self::append_rank($result, 'count');
		$result = self::append_rate_of_uses($result, $regulation_type);
		return $result;
	}

	private static function append_rate_of_uses($records, $regulation_type)
	{
		$card_id_list = array_column($records, 'card_id');
		if ($card_id_list === []) {
			return $records;
		}
		$query = DB::select(Model_CardsMaster::TABLE_NAME . '.card_id', DB::expr('COUNT(*) AS count'))
					->from(self::TABLE_NAME)
					->join(Model_Games::TABLE_NAME)
					->on(self::TABLE_NAME . '.game_id', '=', Model_Games::TABLE_NAME . '.game_id')
					->join(Model_CardsMaster::TABLE_NAME)
					->on(self::TABLE_NAME . '.card_id', '=', Model_CardsMaster::TABLE_NAME . '.card_id')
					->where('regulation_type', '=', $regulation_type)
					->and_where('players_number', '>=', 2)
					->and_where(Model_CardsMaster::TABLE_NAME . '.card_id', 'in', $card_id_list)
					->group_by(Model_CardsMaster::TABLE_NAME . '.card_id');
		$result = $query->execute()->as_array();

		$number_of_all_list = array_column($result, 'count', 'card_id');

		foreach ($records as &$record) {
			$number_of_mine = $record['count'];
			$number_of_all = $number_of_all_list[$record['card_id']];
			if ($number_of_all == 0) {
				continue;
			}
			$record['rate'] = sprintf('%.2f', $number_of_mine / $number_of_all * 100);
		}
		unset($record);
		return $records;
	}
}