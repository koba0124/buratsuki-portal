<?php
class Model_CardsReview
{
	const TABLE_NAME = 'cards_review';

	/**
	 * カード評価レコードを取得
	 * @param  string $card_id  カードID
	 * @param  string $username ユーザID
	 * @return array            カード評価レコード
	 */
	public static function get($card_id, $username)
	{
		$query = DB::select()
					->from(self::TABLE_NAME)
					->where('card_id', '=', $card_id)
					->and_where('username', '=', $username)
					->limit(1);
		return $query->execute()->as_array()[0] ?? [];
	}

	/**
	 * カードごとの全ての評価を取得
	 * @param  string $card_id カードID
	 * @return array           カード評価レコードの配列
	 */
	public static function get_list_by_card_id($card_id)
	{
		$query = DB::select()
					->from(self::TABLE_NAME)
					->where('card_id', '=', $card_id)
					->join(Model_Users::TABLE_NAME, 'inner')
					->on(self::TABLE_NAME . '.username', '=', Model_Users::TABLE_NAME . '.username');
		$records = $query->execute()->as_array();
		$records = Model_Users::append_profile_fields($records);
		return $records;
	}

	/**
	 * カード評価レコードを更新、レコードがなければ挿入
	 * @param  stirng $card_id        カードID
	 * @param  string $username       ユーザID
	 * @param  string $review_points  評価点
	 * @param  string $review_comment ひとこと
	 */
	public static function update($card_id, $username, $review_points, $review_comment)
	{
		$query_string = <<<'EOT'
INSERT INTO `cards_review`
(`card_id`, `username`, `review_points`, `review_comment`)
VALUES
(:card_id, :username, :review_points, :review_comment)
ON DUPLICATE KEY UPDATE
`review_points` = :review_points, `review_comment` = :review_comment;
EOT;
		$query = DB::query($query_string)
					->bind('card_id', $card_id)
					->bind('username', $username)
					->bind('review_points', $review_points)
					->bind('review_comment', $review_comment);
		$query->execute();
	}
}