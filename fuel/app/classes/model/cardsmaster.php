<?php
class Model_CardsMaster
{
	const TABLE_NAME = 'cards_master';

	const DECKS = [
		'E' => 'Eデッキ(旧版基本)',
		'I' => 'Iデッキ(旧版基本)',
		'K' => 'Kデッキ(旧版基本)',
		'G' => 'Gデッキ',
		'Z' => 'Zデッキ',
		'alpha' => 'αデッキ(WMデッキ)',
		'beta' => 'βデッキ(WMデッキ)',
		'gamma' => 'γデッキ(WMデッキ)',
		'delta' => 'δデッキ(WMデッキ)',
		'epsilon' => 'εデッキ(WMデッキ)',
		'CZ' => 'Čデッキ',
		'O' => 'Öデッキ',
		'P' => 'πデッキ',
		'WA' => 'WAデッキ',
		'FL' => 'FLデッキ',
		'FR' => 'FRデッキ',
		'NL' => 'NLデッキ',
		'RA' => 'Aデッキ(リバイズド基本)',
		'RB' => 'Bデッキ(リバイズド基本)',
		'LR' => 'Lデッキ(リバイズド基本)',
		'5A' => 'Aデッキ(リバイズド5-6人拡張)',
		'5B' => 'Bデッキ(リバイズド5-6人拡張)',
		'5C' => 'Cデッキ(リバイズド5-6人拡張)',
		'5D' => 'Dデッキ(リバイズド5-6人拡張)',
		'L5' => 'Lデッキ(リバイズド5-6人拡張)',
		'A' => 'Artifexデッキ',
		'B' => 'Bubulcusデッキ',
		'WCR' => 'Cデッキ(Wizkids赤拡張)',
		'WCB' => 'Cデッキ(Wizkids青拡張)',
		'WCW' => 'Cデッキ(Wizkids白拡張)',
		'WCP' => 'Cデッキ(Wizkids紫拡張)',
		'WCG' => 'Cデッキ(Wizkids緑拡張)',
		'WCY' => 'Cデッキ(Wizkids黄拡張)',
		'WDR' => 'Dデッキ(Wizkids赤拡張)',
		'WDB' => 'Dデッキ(Wizkids青拡張)',
		'WDW' => 'Dデッキ(Wizkids白拡張)',
		'WDP' => 'Dデッキ(Wizkids紫拡張)',
		'WDG' => 'Dデッキ(Wizkids緑拡張)',
		'WDY' => 'Dデッキ(Wizkids黄拡張)',
		'L17' => 'Lデッキ(Spiel 17\')',
		'OR' => '旧版基本セット',
		'OM' => '泥沼からの出発(旧版)',
		'RR' => 'リバイズド基本セット',
		'RR5' => 'リバイズド5-6人拡張',
		'RM' => '泥沼からの出発(新版)',

		'EIK' => '旧版基本セット全て',
		'WM' => 'WMデッキ全て',
		'OM' => '泥沼からの出発(旧版)全て',
		'Re' => 'リバイズド基本セット',
		'R5' => 'リバイズド5-6人拡張',
		'Wizkids' => 'Wizkids拡張デッキ',
		'L' => 'リバイズドLデッキ',
		'Adeck' => 'リバイズドAデッキ',
		'Bdeck' => 'リバイズドBデッキ',
		'Cdeck' => 'リバイズドCデッキ',
		'Ddeck' => 'リバイズドDデッキ',
	];

	const DECK_GROUPS = [
		'EIK' => ['E', 'I', 'K', 'OR'], // 旧版基本
		'WM' => ['alpha', 'beta', 'gamma', 'delta', 'epsilon'], // 世界選手権
		'OM' => ['ME', 'MF', 'MR'], // 旧版泥沼
		'Re' => ['RA', 'RB', 'RR'], // リバイズド基本
		'R5' => ['5A', '5B', '5C', '5D', 'R5'], //リバイズド5-6
		'Wizkids' => ['WCR', 'WCB', 'WCW', 'WCP', 'WCG', 'WCY', 'WDR', 'WDB', 'WDW', 'WDP', 'WDG', 'WDY'], // Wizkids
		'L' => ['LR', 'L5', 'L17'], // Lデッキ
		'Adeck' => ['A', 'RA', '5A'], // Aデッキ
		'Bdeck' => ['B', 'RB', '5B'], // Bデッキ
		'Cdeck' => ['5C', 'WCR', 'WCB', 'WCW', 'WCP', 'WCG', 'WCY'], // Cデッキ
		'Ddeck' => ['5D', 'WDR', 'WDB', 'WDW', 'WDP', 'WDG', 'WDY'], // Dデッキ
	];

	const TYPES = [
		'1' => 'occupation',
		'2' => 'minor_improvement',
		'3' => 'major_improvement',
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

	public static function count_list($type, $deck, $name)
	{
		$query = DB::select(DB::expr('COUNT(*) AS count'))
					->from(self::TABLE_NAME);
		$query = self::append_where_for_list($query, $type, $deck, $name);
		return $query->execute()->as_array()[0]['count'] ?? 0;
	}

	private static function append_where_for_list($query, $type, $deck, $name)
	{
		if ($deck) {
			if (isset(self::DECK_GROUPS[$deck])) {
				$query->where('deck', 'in', self::DECK_GROUPS[$deck]);
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
					->limit(1);
		$record = $query->execute()->as_array()[0] ?? null;
		if (! $record) {
			return null;
		}
		$record = self::append_deck_display($record);
		return $record;
	}

	private static function append_deck_display($record)
	{
		$record['deck_display'] = self::DECKS[$record['deck']] ?? '-';
		return $record;
	}
}