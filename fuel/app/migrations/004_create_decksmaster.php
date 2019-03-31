<?php
namespace Fuel\Migrations;

class Create_DecksMaster
{
	private $deck_table_name = 'decks_master';
	private $group_table_name = 'deck_groups_master';

	public function up()
	{
		\DBUtil::create_table(
			$this->deck_table_name,
			[
				'deck' => ['constraint' => 32, 'type' => 'varchar'],
				'deck_name' => ['constraint' => 255, 'type' => 'varchar'],
				'deck_type' => ['type' => 'int', 'default' => 0],
			],
			['deck'],
			false,
			'InnoDB',
			'utf8_unicode_ci'
		);
		\DBUtil::create_table(
			$this->group_table_name,
			[
				'deck_group' => ['constraint' => 32, 'type' => 'varchar'],
				'deck_group_name' => ['constraint' => 255, 'type' => 'varchar'],
				'deck_group_elements' => ['type' => 'text'],
			],
			['deck_group'],
			false,
			'InnoDB',
			'utf8_unicode_ci'
		);
	}

	public function down()
	{
		\DBUtil::drop_table($this->deck_table_name);
		\DBUtil::drop_table($this->group_table_name);
	}
}