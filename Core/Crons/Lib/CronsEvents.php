<?php
/**
 * CronsEvents
 *
 * @package Infinitas.Crons.Lib
 */

/**
 * CronsEvents
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.Crons.Lib
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.7a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

class CronsEvents extends AppEvents {
/**
 * Todo list
 *
 * Check if the crons are running and if not add a notice to the admin
 * so that they can take action. If they are setup but something has
 * gone wrong and they are not running show an error.
 *
 * @return boolean
 */
	public function onRequireTodoList(Event $Event) {
		$crons = $this->onAreCronsSetup($Event);
		if (!$crons) {
			return array(
				array(
					'name' => __d('crons', 'Crons are not configured yet'),
					'type' => 'warning',
					'url' => '#'
				)
			);
		}

		if (date('Y-m-d H:i:s', strtotime('-' . Configure::read('Cron.run_every'))) > $crons) {
			return array(
				array(
					'name' => sprintf(__d('crons', 'The crons are not running, last run was %s'), $crons),
					'type' => 'error',
					'url' => '#'
				)
			);
		}

		return true;
	}

/**
 * check if crons are running
 *
 * @return string|boolean
 */
	public function onAreCronsSetup(Event $Event) {
		$date = ClassRegistry::init('Crons.Cron')->getLastRun();
		return $date ? date('Y-m-d H:i:s', strtotime($date)) : $date;
	}

/**
 * housekeeping, clear out old cron logs.
 *
 * @param Event $Event
 *
 * @return boolean
 */
	public function onRunCrons(Event $Event) {
		ClassRegistry::init('Crons.Cron')->clearOldLogs();
		return true;
	}

}