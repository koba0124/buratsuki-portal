<?php
namespace Fuel\Tasks;

class Cache
{
	public static function delete_all()
	{
		\Cache::delete_all();
		echo 'success' . "\n";
	}
}