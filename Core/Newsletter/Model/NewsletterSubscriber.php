<?php
/**
 * NewsletterSubscriber
 *
 * @package Infinitas.Newsletter.Model
 */

/**
 * NewsletterSubscriber
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.Newsletter.Model
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.6a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

class NewsletterSubscriber extends NewsletterAppModel {
/**
 * Custom table
 *
 * @var string
 */
	public $useTable = 'subscribers';

/**
 * BelongsTo relations
 *
 * @var array
 */
	public $belongsTo = array(
		'User' => array(
			'className' => 'Users.User',
			'foreignKey' => 'user_id'
		)
	);

/**
 * Custom find methods
 *
 * @var array
 */
	public $findMethods = array(
		'paginated' => true
	);

/**
 * Constructor
 *
 * @param type $id
 * @param type $table
 * @param type $ds
 *
 * @return void
 */
	public function  __construct($id = false, $table = null, $ds = null) {
		parent::__construct($id, $table, $ds);

		$this->virtualFields = array(
			'subscriber_name' => 'CASE WHEN COALESCE(User.username, \'\') = \'\' THEN ' . $this->alias . '.prefered_name else User.username end',
			'subscriber_email' => 'CASE WHEN COALESCE(User.email, \'\') = \'\' THEN ' . $this->alias . '.email else User.email end'
		);
	}

/**
 * BeforeFind callback
 *
 * @param array $queryData the query array
 *
 * @return array
 */
	public function beforeFind($queryData) {
		$queryData['fields'] = array_merge(
			(array)$queryData['fields'],
			array(
				'NewsletterSubscriber.*',
				'User.*',
			)
		);

		$queryData['joins'][] = array(
			'table' => 'core_users',
			'alias' => 'User',
			'type' => 'LEFT',
			'conditions' => array(
				'User.id = NewsletterSubscriber.user_id'
			)
		);

		return $queryData;
	}

/**
 * Paginated find method
 *
 * @param string $state
 * @param array $query
 * @param array $results
 *
 * @return array
 */
	protected function _findPaginated($state, $query, $results = array()) {
		if ($state === 'before') {
			if(!is_array($query['fields'])) {
				$query['fields'] = array($query['fields']);
			}

			return $query;
		}

		return $results;
	}

}