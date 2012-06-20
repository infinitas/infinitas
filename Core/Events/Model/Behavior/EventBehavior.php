<?php
	class EventBehavior extends ModelBehavior {
		/**
		 * Trigger a event
		 *
		 * @param string $eventName Name of the event to trigger
		 * @param array $data Data to pass to event handler
		 * @return array:
		 */
		public function triggerEvent($Model, $eventName, $data = array()) {
			return EventCore::trigger($Model, $eventName, $data);
		}

		public function beforeFind($Model, $query) {
			$query['conditions'] = !empty($query['conditions']) ? (array)$query['conditions'] : array();
			
			if($Model->hasField('plugin')) {
				$this->__getPossiblePlugins($Model, 'plugin', $query['conditions']);
			}
			
			if($Model->hasField('model')) {
				$this->__getPossiblePlugins($Model, 'model', $query['conditions']);
			}
			
			if($Model->hasField('class')) {
				//pr('not sure what is going on here :/ ');
				//exit;
			}
			
			return $query;
		}

		/**
		 * @brief build up some conditions to only select rows with active pluging data
		 *
		 * This stops things like routes and modules (or any other data) that is plugin
		 * specific being pulled out when a plugin is disabled
		 *
		 * @access private
		 *
		 * @param Model $Model the model doing the find
		 *
		 * @return array conditions to add to a find
		 */
		private function __getPossiblePlugins($Model, $field, &$conditions) {
			$toIgnore = ClassRegistry::init('Installer.Plugin')->getInactiveInstalledPlugins();

			$return = array();
			foreach($toIgnore as $plugin) {
				$conditions[] = array(
					sprintf('%s.%s NOT LIKE ', $Model->alias, $field) => sprintf('%%%s%%', $plugin) 
				);
			}
		}
	}