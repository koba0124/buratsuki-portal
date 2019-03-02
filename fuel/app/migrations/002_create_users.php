<?php
namespace Fuel\Migrations;

class Create_Users
{
	public function up()
	{
		\DBUtil::create_table(
			'users',
			[
				'id' => ['type' => 'int', 'auto_increment' => true],
				'username' => ['constraint' => 50, 'type' => 'varchar'],
				'password' => ['constraint' => 255, 'type' => 'varchar'],
				'group' => ['type' => 'int', 'default' => 1],
				'email' => ['constraint' => 255, 'type' => 'varchar'],
				'last_login' => ['constraint' => 25, 'type' => 'varchar'],
				'login_hash' => ['constraint' => 255, 'type' => 'varchar'],
				'profile_fields' => ['type' => 'text'],
				'created_at' => ['constraint' => 11, 'type' => 'int', 'unsigned' => true],
				'updated_at' => ['constraint' => 11, 'type' => 'int', 'unsigned' => true],
	        ],
	        ['id'],
	        false,
	        'InnoDB',
	        'utf8_unicode_ci'
    	);
    	\DBUtil::create_index('users', ['username', 'email'] ,'unique', 'unique');
	}

	public function down()
	{
		\DBUtil::drop_table('users');
	}
}