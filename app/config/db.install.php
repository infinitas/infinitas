<?php
    class DATABASE_CONFIG {

        var $default = array(
        	'driver' => 'mysql',
        	'persistent' => false,
        	'host' => '{default_host}',
        	'login' => '{default_login}',
        	'password' => '{default_password}',
        	'database' => '{default_database}',
        	'prefix' => '',
        );

        var $test = array(
        	'driver' => 'mysql',
        	'persistent' => false,
        	'host' => '{default_host}',
        	'login' => '{default_login}',
        	'password' => '{default_password}',
        	'database' => 'test_{default_database}',
        	'prefix' => '',
        );
    }
?>