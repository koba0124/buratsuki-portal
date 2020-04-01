<?php
class Model_Games
{
	const TABLE_NAME = 'games';

	/**
	 * ゲームデータ作成
	 * @param  string  $game_id         ゲームID
	 * @param  int     $players_number  プレイヤー人数
	 * @param  int     $regulation_type レギュレーション番号
	 * @param  boolean $is_moor         泥沼かどうか
	 * @param  string  $owner           ゲーム作成者のusername
	 */
	public static function create($game_id, $players_number, $regulation_type, $is_moor, $owner)
	{
		$created_at = date('Y-m-d H:i:s');
		$query = DB::insert(self::TABLE_NAME)
					->set([
						'game_id' => $game_id,
						'players_number' => $players_number,
						'regulation_type' => $regulation_type,
						'is_moor' => $is_moor,
						'owner' => $owner,
						'created_at' => $created_at,
					]);
		$query->execute();
	}

	/**
	 * 戦績詳細用にゲームレコード取得
	 * @param  string $game_id ゲームID
	 * @return array           Gamesレコード
	 */
	public static function get_for_view($game_id)
	{
		$query = DB::select()
					->from(self::TABLE_NAME)
					->where('game_id', '=', $game_id)
					->limit(1)
					->join(Model_RegulationsMaster::TABLE_NAME)
					->on(self::TABLE_NAME . '.regulation_type', '=', Model_RegulationsMaster::TABLE_NAME . '.regulation_type');
		$records = $query->execute()->as_array();
		return $records[0] ?? [];
	}

	/**
	 * Gamesのレコード数を数える
	 * @return int レコード数
	 */
	public static function count_list()
	{
		$query = DB::select(DB::expr('COUNT(*) AS count'))
					->from(self::TABLE_NAME);
		return $query->execute()->as_array()[0]['count'] ?? 0;
	}

	/**
	 * 戦績一覧用のGamesリストを取得
	 * @param  Pagination $pagination
	 * @return array                  Gamesレコードの配列
	 */
	public static function get_list($pagination)
	{
		$query = DB::select()
					->from(self::TABLE_NAME);
		$query->order_by('created_at', 'desc');
		$query->limit($pagination->per_page);
		$query->offset($pagination->offset);
		$query->join(Model_RegulationsMaster::TABLE_NAME)
				->on(self::TABLE_NAME . '.regulation_type', '=', Model_RegulationsMaster::TABLE_NAME . '.regulation_type');
		return $query->execute()->as_array();
	}

	/**
	 * ゲームを削除
	 * @param  string $game_id ゲームID
	 */
	public static function delete($game_id)
	{
		$query = DB::delete(self::TABLE_NAME)
					->where('game_id', '=', $game_id);
		return $query->execute();
	}

	/**
	 * ゲーム日時を更新
	 * @param  string $game_id        ゲームID
	 * @param  string $created_at_new 日時
	 */
	public static function update_date($game_id, $created_at_new)
	{
		$query = DB::update(self::TABLE_NAME)
					->value('created_at', date_parse($created_at_new))
					->where('game_id', '=', $game_id);
		$query->execute();
	}
}