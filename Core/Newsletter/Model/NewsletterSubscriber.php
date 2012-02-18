<?php
	class NewsletterSubscriber extends NewsletterAppModel {
		public $useTable = 'subscribers';

		public $belongsTo = array(
			'User' => array(
				'className' => 'Users.User',
				'foreignKey' => 'user_id'
			)
		);

		public $findMethods = array(
			'paginated' => true
		);

		public function  __construct($id = false, $table = null, $ds = null) {
			parent::__construct($id, $table, $ds);

			$this->virtualFields = array(
				'subscriber_name' => 'CASE WHEN COALESCE(User.username, \'\') = \'\' THEN ' . $this->alias . '.prefered_name else User.username end',
				'subscriber_email' => 'CASE WHEN COALESCE(User.email, \'\') = \'\' THEN ' . $this->alias . '.email else User.email end'
			);
		}

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

		protected function _findPaginated($state, $query, $results = array()) {
			if ($state === 'before') {
				if(!is_array($query['fields'])) {
					$query['fields'] = array($query['fields']);
				}

				return $query;
			}

			if (!empty($query['operation'])) {
				return $this->_findPaginatecount($state, $query, $results);
			}

			return $results;
		}
	}