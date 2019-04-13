<?php
class Model_DecksMaster
{
	const TABLE_NAME = 'decks_master';
	const CACHE_NAME = 'decks_master';

	/**
	 * デッキのリストを取得
	 * @return array デッキの配列
	 */
	public static function get_list()
	{
		try {
			$list = Cache::get(self::CACHE_NAME);
			return $list;
		} catch (CacheNotFoundException $e) {}
		$query = DB::select('deck', 'deck_name')
					->from(self::TABLE_NAME);
		$records = $query->execute()->as_array();
		$list = array_column($records, 'deck_name', 'deck');
		Cache::set(self::CACHE_NAME, $list, 3 * 30 * 24 * 60 * 60);
		return $list;
	}
}