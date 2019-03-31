<?php
namespace Fuel\Migrations;

class Create_Games
{
	private $table_name = 'games';
	private $score_table_name = 'games_scores';

	public function up()
	{
		\DBUtil::create_table(
			$this->table_name,
			[
				'game_id' => ['constraint' => 32, 'type' => 'varchar'],
				'players_number' => ['type' => 'int'],
				'regulation_type' => ['type' => 'int'],
				'is_moor' => ['type' => 'tinyint'],
				'owner' => ['constraint' => 50, 'type' => 'varchar'],
				'created_at' => ['type' => 'datetime'],
			],
			['game_id'],
			false,
			'InnoDB',
			'utf8_unicode_ci'
		);
		\DBUtil::create_table(
			$this->score_table_name,
			[
				'game_id' => ['constraint' => 32, 'type' => 'varchar'],
				'player_order' => ['type' => 'int'],
				'username' => ['constraint' => 50, 'type' => 'varchar'],
				'fields' => ['type' => 'int', 'null' => true],
				'pastures' => ['type' => 'int', 'null' => true],
				'grain' => ['type' => 'int', 'null' => true],
				'vegetable' => ['type' => 'int', 'null' => true],
				'sheep' => ['type' => 'int', 'null' => true],
				'boar' => ['type' => 'int', 'null' => true],
				'cattle' => ['type' => 'int', 'null' => true],
				'horses' => ['type' => 'int', 'null' => true],
				'unused_spaces' => ['type' => 'int', 'null' => true],
				'stable' => ['type' => 'int', 'null' => true],
				'rooms' => ['type' => 'int', 'null' => true],
				'family' => ['type' => 'int', 'null' => true],
				'begging' => ['type' => 'int', 'null' => true],
				'card_points' => ['type' => 'int', 'null' => true],
				'bonus_points' => ['type' => 'int', 'null' => true],
				'total_points' => ['type' => 'int', 'null' => true],
				'rank' => ['type' => 'int', 'null' => true],
				'image' => ['constraint' => 50, 'type' => 'varchar', 'null' => true],
				'comment' => ['type' => 'text', 'null' => true],
			],
			['game_id', 'player_order'],
			false,
			'InnoDB',
			'utf8_unicode_ci'
		);
	}

	public function down()
	{
		\DBUtil::drop_table($this->table_name);
		\DBUtil::drop_table($this->score_table_name);
	}
}