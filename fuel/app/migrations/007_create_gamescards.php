<?php
namespace Fuel\Migrations;

class Create_GamesCards
{
	private $table_name = 'games_cards';

	public function up()
	{
		\DBUtil::create_table(
			$this->table_name,
			[
				'game_id' => ['constraint' => 32, 'type' => 'varchar'],
				'player_order' => ['type' => 'int'],
				'card_id' => ['constraint' => 32, 'type' => 'varchar'],
			],
			['game_id', 'player_order', 'card_id'],
			false,
			'InnoDB',
			'utf8_unicode_ci'
		);
	}

	public function down()
	{
		\DBUtil::drop_table($this->table_name);
	}
}