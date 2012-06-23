<?php
App::uses('Security', 'Utility');

class ValidationBehavior extends ModelBehavior {
/**
 * @brief check that a field is valid json
 *
 * @param array $field the field being validated
 *
 * @return boolean
 */
	public function validateJson(Model $Model, $field) {
		return $Model->getJson($Model->data[$Model->alias][current(array_keys($field))], array(), false);
	}

/**
 * @brief allow the selection of one field or another
 *
 * This is used in times where one thing should be filled out and another
 * should be left empty.
 *
 * Setting both fields with the allowEmpty rule will allow validation to
 * pass with niether field filled out
 *
 * @param array $field not used
 * @params array $fields list of 2 fields that should be checked
 *
 * @return boolean
 */
	public function validateEitherOr(Model $Model, $field, $fields) {
		$allowEmpty = isset($Model->validate[$fields[0]][__FUNCTION__]['allowEmpty']) &&
			$Model->validate[$fields[0]][__FUNCTION__]['allowEmpty'] &&
			empty($Model->data[$Model->alias][$fields[0]]);

		$allowEmpty &= isset($Model->validate[$fields[1]][__FUNCTION__]['allowEmpty']) &&
			$Model->validate[$fields[1]][__FUNCTION__]['allowEmpty'] &&
			empty($Model->data[$Model->alias][$fields[1]]);

		if($allowEmpty) {
			return true;
		}

		return
			// either
			empty($Model->data[$Model->alias][$fields[0]]) && !empty($Model->data[$Model->alias][$fields[1]]) ||

			// or
			!empty($Model->data[$Model->alias][$fields[0]]) && empty($Model->data[$Model->alias][$fields[1]]);
	}

/**
 * @brief check for urls either /something/here or full
 *
 * this can be a url relative to the site /my/page or full like
 * http://site.com/my/page it can also be empty for times when the selects
 * are used to build the url
 *
 * @param array $field the field being validated
 *
 * @return boolean
 */
	public function validateUrlOrAbsolute(Model $Model, $field) {
		return
			// aboulute url
			substr(current($field), 0, 1) == '/' ||

			// valid url
			Validation::url(current($field), true);
	}

/**
 * @brief compare 2 fields and make sure they are the same
 *
 * This method can compare 2 fields, with password having a special meaning
 * as they will be hashed automatically.
 *
 * the order of password fields is important as you could end up hashing
 * the hashed password again and still having the other one as plain text
 * which will always fail.
 *
 * basic usage
 *
 * @code
 *	// random fields
 *	'rule' => array(
 *		'validateCompareFields', array('field1', 'field2')
 *	),
 *	'message' => 'fields do not match'
 *
 *	// real world
 *	'rule' => array(
 *		'validateCompareFields', array('email', 'compare_email')
 *	),
 *	'message' => 'The email addresses you entered do not match'
 *
 *	'rule' => array(
 *		'validateCompareFields', array('compare_password', 'password')
 *	),
 *	'message' => 'The email addresses you entered do not match'
 * @endcode
 *
 * @param array $field not used
 *
 * @param boolean
 */
	public function validateCompareFields(Model $Model, $field, $fields) {
		if($fields[0] == 'password') {
			return Security::hash($Model->data[$Model->alias][$fields[1]], null, true) === $Model->data[$Model->alias][$fields[0]];
		}

		return $Model->data[$Model->alias][$fields[0]] === $Model->data[$Model->alias][$fields[1]];
	}

/**
 * @brief check if a given foreign key exists
 *
 * This is used in to validate foreign keys while saving or updating
 * records
 *
 * @param array $field contains the field / data to be validated
 * @params array $alias optional the alias on the association to test
 *
 * @return boolean
 */
	public function validateRecordExists(Model $Model, $field) {
		$aliases = array_flip(array_map(create_function('$v', 'return $v["foreignKey"];'), $Model->belongsTo));

		$alias = !empty($aliases[key($field)]) ? $aliases[key($field)] : null;
		if(!$alias) {
			if(key($field) == 'foreign_key' && in_array('model', array_keys($Model->data[$Model->alias])) && !empty($Model->data[$Model->alias]['model'])) {
				list(, $alias) = pluginSplit($Model->data[$Model->alias]['model']);
				$Model->{$alias} = ClassRegistry::init($Model->data[$Model->alias]['model']);
			}

			if(!$alias) {
				return false;
			}
		}

		$count = $Model->{$alias}->find(
			'count',
			array(
				'conditions' => array(
					$alias . '.' . $Model->{$alias}->primaryKey => current($field)
				)
			)
		);

		return $count > 0;
	}

/**
 * @brief validate that the passed in plugin is valid
 *
 * Simple check for installed plugins
 * @code
 *	'rule' => 'validatePluginExists'
 * @endcode
 *
 * You can validate for a specific type of plugin by setting the `pluginType`
 * condition on the validation rule. To validate a core plugin was selected
 * you would do the following
 *
 * @code
 *	'rule' => array('validatePluginExists', array('pluginType' => 'core'))
 * @endcode
 *
 * @param Model $Model the model being validated
 * @param array $field the field being validated
 *
 * @return boolean
 */
	public function validatePluginExists(Model $Model, $field, $conditions = array()) {
		if(empty($conditions['pluginType'])) {
			$conditions['pluginType'] = 'installed';
		}

		return in_array(current($field), InfinitasPlugin::listPlugins($conditions['pluginType']));
	}

/**
 * @brief check if the selected controller is valid
 *
 * By default this method will look for a field in the models data array called
 * 'plugin'. You can change the behavior by setting the validation as follows
 *
 * @code
 *	'rule' => array('validateControllerExists', array('pluginField' => 'my_plugin_field'))
 * @endcode
 *
 * You are able to specify the plugin from another model when doing saveAll. If
 * you are validating the User model but want to check the plugin in the Profile
 * model you would use the following:
 *
 * @code
 *	'rule' => array('validateControllerExists', array('pluginField' => 'Profile.profile_plugin_filed'))
 * @endcode
 *
 * You can also use a hard coded value to force some particular plugin
 *
 * @code
 *	'rule' => array('validateControllerExists', array('setPlugin' => 'SomePlugin'))
 * @endcode
 *
 * @param Model $Model the model being validated
 * @param array $field the field being validated
 * @param array $conditions the config for the validation
 *
 * @return boolean
 */
	public function validateControllerExists(Model $Model, $field, $conditions = array()) {
		if(!empty($conditions['pluginField'])) {
			$plugin = $this->_getField($Model, $conditions['pluginField']);
		}

		if(!empty($conditions['setPlugin'])) {
			$plugin = $conditions['setPlugin'];
		}

		if(empty($plugin)) {
			return false;
		}

		try {
			return array_key_exists(Inflector::camelize(current($field)), $Model->getControllers($plugin));
		}

		catch(Exception $e) {
			return false;
		}
	}

/**
 * @brief check that an action exists for the given plugin / controller pair
 *
 * @param Model $Model the model being validated
 * @param array $field the field being validated
 * @param array $conditions the config for the validation
 *
 * @return boolean
 */
	public function validateActionExists(Model $Model, $field, $conditions = array()) {
		$conditions = array_merge(
			array('pluginField' => 'plugin', 'controllerField' => 'controller'),
			(array)$conditions
		);

		$plugin = Inflector::camelize($this->_getField($Model, $conditions['pluginField']));
		$controller = Inflector::camelize($this->_getField($Model, $conditions['controllerField']));
		if(empty($plugin) || empty($controller)) {
			return false;
		}

		try {
			$actions = $Model->getActions($plugin, $controller);
			foreach($actions as $actionList) {
				if(isset($actionList[current($field)])) {
					return true;
				}
			}
		}

		catch(Exception $e) {
			return false;
		}

		return false;
	}

/**
 * @brief get the value of a field
 *
 * This is an internal method to figure out values based on Model.field or field.
 * If there is no . it is assumed that the field is within the $Model->alias array
 *
 * @param Model $Model The model object
 * @param string $field the field to look up in either field or Model.field format
 *
 * @return mixed
 */
	protected function _getField(Model $Model, $field) {
		$alias = $Model->alias;
		if(strstr('.', $field)) {
			list($alias, $field) = pluginSplit($field);
		}

		return !empty($Model->data[$alias][$field]) ? $Model->data[$alias][$field] : null;
	}
}