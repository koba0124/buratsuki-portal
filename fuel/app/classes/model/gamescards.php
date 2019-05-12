<?php
class Model_GamesCards
{
	const TABLE_NAME = 'games_cards';

	/**
	 * 戦績編集用にGamesCardsレコード取得
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

	/**
	 * 戦績更新
	 * @param  string $game_id      ゲームID
	 * @param  int    $player_order 番手
	 * @param  array  $cards_list   カードタイプごとに分割されたカードIDの配列
	 */
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

	/**
	 * 戦績詳細表示用にGamesCardsレコード取得
	 * @param  string $game_id ゲームID
	 * @return array           番手 => カードタイプ => GamesCardsレコードの配列
	 */
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

	/**
	 * 指定したカードが使われた回数を数える
	 * @param  string $card_id カードID
	 * @return int             使用された回数
	 */
	public static function count_by_card_id($card_id)
	{
		$query = DB::select(DB::expr('COUNT(*) AS count'))
					->from(self::TABLE_NAME)
					->where('card_id', '=', $card_id);
		return $query->execute()->as_array()[0]['count'] ?? 0;
	}

	/**
	 * カード詳細表示用に、指定したカードが使われたゲームのレコードを取得
	 * @param  string $card_id カードID
	 * @return array           GamesScoresの配列
	 */
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
		$card_id_list2 = array_column($result, 'card_id');

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
}