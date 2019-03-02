<?php
namespace Fuel\Migrations;

class Create_Sessions
{
	public function up()
	{
		\DBUtil::create_table(
			'sessions',
			[
				'session_id' => ['constraint' => 40, 'type' => 'varchar'],
				'previous_id' => ['constraint' => 40, 'type' => 'varchar'],
				'user_agent' => ['type' => 'text'],
				'ip_hash' => ['constraint' => 32, 'type' => 'char'],
				'created' => ['constraint' => 10, 'type' => 'int', 'unsigned' => true],
				'updated' => ['constraint' => 10, 'type' => 'int', 'unsigned' => true],
				'payload' => ['type' => 'longtext'],
	        ],
	        ['session_id'],
	        false,
	        'InnoDB',
	        'utf8_unicode_ci'
    	);
	}

	public function down()
	{
		\DBUtil::drop_table('sessions');
	}
}