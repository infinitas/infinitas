<?php
    class DATABASE_CONFIG {

        var $default = array(
        	'driver' => 'mysql',
        	'persistent' => false,
        	'host' => 'localhost',
        	'login' => 'root',
        	'password' => 'root',
        	'database' => 'testing',
        	'prefix' => '',
        );

        var $test = array(
        	'driver' => 'mysql',
        	'persistent' => false,
        	'host' => 'localhost',
        	'login' => 'root',
        	'password' => 'root',
        	'database' => 'test_testing',
        	'prefix' => '',
        );
    }
?>