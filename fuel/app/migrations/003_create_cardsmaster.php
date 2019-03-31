<?php
namespace Fuel\Migrations;

class Create_CardsMaster
{
	private $table_name = 'cards_master';

	public function up()
	{
		\DBUtil::create_table(
			$this->table_name,
			[
				'card_id' => ['constraint' => 32, 'type' => 'varchar'],
				'card_id_display' => ['constraint' => 255, 'type' => 'varchar'],
				'japanese_name' => ['constraint' => 255, 'type' => 'varchar'],
				'deck' => ['constraint' => 32, 'type' => 'varchar'],
				'description' => ['type' => 'text'],
				'type' => ['constraint' => 32, 'type' => 'varchar'],
				'category' => ['type' => 'int'],
				'prerequisite' => ['constraint' => 255, 'type' => 'varchar'],
				'costs' => ['constraint' => 255, 'type' => 'varchar'],
				'card_points' => ['type' => 'int', 'default' => 0],
			],
			['card_id'],
			false,
			'InnoDB',
			'utf8_unicode_ci'
		);
		\DBUtil::create_index($this->table_name, ['deck']);
		\DBUtil::create_index($this->table_name, ['type']);
		\DBUtil::create_index($this->table_name, ['deck', 'type']);
	}

	public function down()
	{
		\DBUtil::drop_table($this->table_name);
	}
}