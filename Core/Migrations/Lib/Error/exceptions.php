<?php
/**
 * Exception used when something goes wrong on migrations
 *
 * @package Infinitas.Migrations.Error
 */
class MigrationException extends Exception {

/**
 * Reference to the Migration being processed on time the error ocurred
 *
 * @var CakeMigration
 */
	public $Migration;

/**
 * Constructor
 *
 * @param CakeMigration $Migration Reference to the Migration
 * @param string $message Message explaining the error
 * @param int $code Error code
 *
 * @return void
 */
	public function __construct($Migration, $message = '', $code = 0) {
		parent::__construct($message, $code);
		$this->Migration = $Migration;
	}
}

/**
 * Usually used when migrations file/class or map files are not found
 *
 * @package Infinitas.Migrations.Error
 */
class MigrationVersionException extends Exception {

}