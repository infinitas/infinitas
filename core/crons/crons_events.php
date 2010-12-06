<?php
	class CronsEvents extends AppEvents{
		public function onSetupConfig($event, $data = null) {
			Configure::load('crons.config');
		}

		/**
		 * @copydoc AppError::onRequireTodoList()
		 *
		 * Check if the crons are running and if not add a notice to the admin
		 * so that they can take action. If they are setup but something has
		 * gone wrong and they are not running show an error.
		 */
		public function onRequireTodoList($event) {
			$crons = $this->onAreCronsSetup();
			if(!$crons){
				return array(
					array(
						'name' => __('Crons are not configured yet', true),
						'type' => 'warning',
						'url' => '#'
					)
				);
			}
			else if(date('Y-m-d H:i:s', strtotime('-' . Configure::read('Cron.run_every'))) > $crons){
				return array(
					array(
						'name' => sprintf(__('The crons are not running, last run was %s', true), $crons),
						'type' => 'error',
						'url' => '#'
					)
				);
			}
			
			return false;
		}

		/**
		 * @brief check if crons are running
		 *
		 * @return string|bool false if not, or datetime of last run
		 */
		public function onAreCronsSetup(){
			return ClassRegistry::init('Crons.Cron')->getLastRun();
		}
	}