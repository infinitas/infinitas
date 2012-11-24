<?php
/**
 * EventBehavior
 *
 * @package Infinitas.Events.Model.Behavior
 */

/**
 * EventBehavior
 *
 * The event behavior allows easy access to the event system in the model layer
 *
 * <code>
 * $MyModel->trigger('eventName', $params);
 * </code>
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.Events.Model.Behavior
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.7a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */
class EventBehavior extends ModelBehavior {
/**
 * Trigger a event
 *
 * @param string $eventName Name of the event to trigger
 * @param array $data Data to pass to event handler
 *
 * @return array
 */
	public function triggerEvent(Model $Model, $eventName, $data = array()) {
		return EventCore::trigger($Model, $eventName, $data);
	}

/**
 * BeforeFind callback
 *
 * This callback will automatically include conditions on finds that have rows with
 * model / plugin links. This removes any data being shown for disabled plugins
 *
 * @param Model $Model the model doing the find
 * @param array $query the query data
 *
 * @return array
 */
	public function beforeFind(Model $Model, $query) {
		$query['conditions'] = !empty($query['conditions']) ? (array)$query['conditions'] : array();

		if ($Model->hasField('plugin')) {
			$this->__getPossiblePlugins($Model, 'plugin', $query['conditions']);
		}

		if ($Model->hasField('model')) {
			$this->__getPossiblePlugins($Model, 'model', $query['conditions']);
		}

		if ($Model->hasField('class')) {
			//pr('not sure what is going on here :/ ');
			//exit;
		}

		return $query;
	}

/**
 * build up some conditions to only select rows with active pluging data
 *
 * This stops things like routes and modules (or any other data) that is plugin
 * specific being pulled out when a plugin is disabled
 *
 * @param Model $Model the model doing the find
 * @param string $field the feild being looked up
 * @param array $conditions the conditions for the find (by reference)
 *
 * @return array
 */
	private function __getPossiblePlugins(Model $Model, $field, &$conditions) {
		$toIgnore = ClassRegistry::init('Installer.Plugin')->getInactiveInstalledPlugins();

		$return = array();
		foreach ($toIgnore as $plugin) {
			$conditions[] = array(
				sprintf('%s.%s NOT LIKE ', $Model->alias, $field) => sprintf('%%%s%%', $plugin)
			);
		}
	}

}