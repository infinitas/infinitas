<?php
class CronsEvents extends AppEvents {
/**
 * @copydoc AppError::onRequireTodoList()
 *
 * Check if the crons are running and if not add a notice to the admin
 * so that they can take action. If they are setup but something has
 * gone wrong and they are not running show an error.
 */
	public function onRequireTodoList(Event $Event) {
		$crons = $this->onAreCronsSetup();
		if(!$crons) {
			return array(
				array(
					'name' => __d('crons', 'Crons are not configured yet'),
					'type' => 'warning',
					'url' => '#'
				)
			);
		}

		if(date('Y-m-d H:i:s', strtotime('-' . Configure::read('Cron.run_every'))) > $crons) {
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
 * @brief check if crons are running
 *
 * @return string|bool false if not, or datetime of last run
 */
	public function onAreCronsSetup() {
		$date = ClassRegistry::init('Crons.Cron')->getLastRun();
		return $date ? date('Y-m-d H:i:s', strtotime($date)) : $date;
	}

/**
 * @brief housekeeping, clear out old cron logs.
 *
 * @param Event $Event
 * 
 * @return bool
 */
	public function onRunCrons(Event $Event) {
		ClassRegistry::init('Crons.Cron')->clearOldLogs();
		return true;
	}
}