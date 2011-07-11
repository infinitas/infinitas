<?php
	class ValidationBehavior extends ModelBehavior {
		/**
		 * @brief This can either be empty or a valid json string.
		 *
		 * @deprecated
		 *
		 * @param array $field the field being validated
		 * @access public
		 *
		 * @return bool is it valid
		 */
		public function validateEmptyOrJsonDeprecated($Model, $field){
			return strlen(current($field)) == 0 || $Model->validateJson(current($field));
		}
		
		/**
		 * @brief This can either be empty or a valid json string.
		 *
		 * @deprecated
		 *
		 * @param array $field the field being validated
		 * @access public
		 *
		 * @return bool is it valid
		 */
		public function validateJson($Model, $field){
			return $Model->getJson($Model->data[$Model->alias][current(array_keys($field))], array(), false);
		}

		/**
		 * @brief allow the selection of one field or another or nothing
		 *
		 * @deprecated
		 *
		 * @param array $field not used
		 * @params array $fields list of 2 fields that should be checked
		 * @access public
		 *
		 * @return bool is it valid?
		 */
		public function validateNothingEitherOr($Model, $field, $fields = array()){
			return
				// nothing
				empty($Model->data[$Model->alias][$fields[0]]) && empty($Model->data[$Model->alias][$fields[1]]) ||

				// either or
				$this->validateEitherOr($Model, $field, $fields);

		}

		/**
		 * @brief allow the selection of one field or another
		 *
		 * This is used in times where one thing should be filled out and another
		 * should be left empty.
		 *
		 * @param array $field not used
		 * @params array $fields list of 2 fields that should be checked
		 * @access public
		 *
		 * @return bool is it valid?
		 */
		public function validateEitherOr($Model, $field, $fields){
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
		 * @todo remove current($field) == '' || as 'notEmpty' works fine
		 *
		 * @param array $field the field being validated
		 * @access public
		 *
		 * @return bool is it valid
		 */
		public function validateUrlOrAbsolute($Model, $field){
			return
				// not in use
				current($field) == '' ||

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
		 * @todo move to a validation behavior
		 *
		 * @param array $field not used
		 * @access public
		 *
		 * @param bool $fields the fields to compare
		 */
		public function validateCompareFields($Model, $field, $fields){
			if($fields[0] == 'password'){
				if(!class_exists('Security')){
					App::import('Security');
				}
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
		 * @access public
		 *
		 * @return bool is it valid?
		 */
		public function validateRecordExists($Model, $field){
			$aliases = array_flip(array_map(create_function('$v', 'return $v["foreignKey"];'), $Model->belongsTo));

			$alias = $aliases[key($field)];
	
			return $Model->{$alias}->find('count', array(
				'conditions' => array(
					$alias . '.' . $Model->{$alias}->primaryKey => current($field)
				),
				'recursive' => -1
			)) == 1;
		}
	}