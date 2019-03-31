<?php
namespace Fuel\Tasks;

class Games
{
	public static function calc_rank()
	{
		$games = \Model_GamesScores::get_all();
		foreach ($games as $game_id => $records) {
			echo $game_id . "\n";
			\Controller_Games::calc_rank($game_id, $records);
		}
		echo 'success' . "\n";
	}
}