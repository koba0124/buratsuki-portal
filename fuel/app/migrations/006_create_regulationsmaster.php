<?php
namespace Fuel\Migrations;

class Create_RegulationsMaster
{
	private $table_name = 'regulations_master';

	public function up()
	{
		\DBUtil::create_table(
			$this->table_name,
			[
				'regulation_type' => ['type' => 'int'],
				'regulation_name' => ['constraint' => 50, 'type' => 'varchar'],
			],
			['regulation_type'],
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