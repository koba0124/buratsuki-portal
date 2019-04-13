<?php
namespace Fuel\Migrations;

class Create_CardsReview
{
	private $table_name = 'cards_review';

	public function up()
	{
		\DBUtil::create_table(
			$this->table_name,
			[
				'card_id' => ['constraint' => 32, 'type' => 'varchar'],
				'username' => ['constraint' => 50, 'type' => 'varchar'],
				'review_points' => ['type' => 'decimal', 'constraint' => '10,1'],
				'review_comment' => ['type' => 'text'],
			],
			['card_id', 'username'],
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