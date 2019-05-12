<?php
/**
 * The development database settings. These get merged with the global settings.
 */

return array(
	'default' => array(
		'connection'  => array(
			'dsn'        => 'mysql:host=buratsuki-portal_mysql_1;dbname=buratsuki-portal',
			'username'   => 'root',
			'password'   => 'passw0rd',
		),
		'profiling'  => true,
	),
);