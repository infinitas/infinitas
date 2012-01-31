<?php
	class EventBehavior extends ModelBehavior {
		var $name = 'Events';

		/**
		 * Trigger a event
		 *
		 * @param string $eventName Name of the event to trigger
		 * @param array $data Data to pass to event handler
		 * @return array:
		 */
		public function triggerEvent($Model, $eventName, $data = array()){
			return EventCore::trigger($Model, $eventName, $data);
		}

		public function beforeFind($Model, $query) {
			if($Model->hasField('plugin')) {
				$query['conditions'] = array_merge(
					!empty($query['conditions']) ? $query['conditions'] : array(),
					$this->__getPossiblePlugins($Model, 'plugin')
				);
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
		private function __getPossiblePlugins($Model, $field) {
			$camelCasePlugins = EventCore::getAvailablePlugins();

			$mixedCasePlugins = array('');
			foreach($camelCasePlugins as $plugin) {
				$mixedCasePlugins[] = $plugin;
				$mixedCasePlugins[] = Inflector::underscore($plugin);
			}

			return array(
				'and' => array(
					'or' => array(
						$Model->alias . '.' . $field => $mixedCasePlugins,
					)
				)
			);
		}
	}