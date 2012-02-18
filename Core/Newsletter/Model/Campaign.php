<?php
	/**
	 * Comment Template.
	 *
	 * @todo Implement .this needs to be sorted out.
	 *
	 * Copyright (c) 2009 Carl Sutton ( dogmatic69 )
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 * @filesource
	 * @copyright Copyright (c) 2009 Carl Sutton ( dogmatic69 )
	 * @link http://infinitas-cms.org
	 * @package sort
	 * @subpackage sort.comments
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.5a
	 */

	class Campaign extends NewsletterAppModel {
		public $lockable = true;

		public $order = array(
			'Campaign.name' => 'asc'
		);

		public $hasMany = array(
			'Newsletter.Newsletter'
		);

		public $belongsTo = array(
			'Newsletter.Template'
		);

		public $findMethods = array(
			'paginated' => true
		);

		public function  __construct($id = false, $table = null, $ds = null) {
			parent::__construct($id, $table, $ds);

			$this->validate = array(
				'name' => array(
					'notEmpty' => array(
						'rule' => 'notEmpty',
						'message' => __('Please enter the name of this campaign')
					),
					'isUnique' => array(
						'rule' => 'isUnique',
						'message' => __('There is already a campaign with that name')
					)
				),
				'template_id' => array(
					'notEmpty' => array(
						'rule' => 'notEmpty',
						'message' => __('Please select the default template for this campaign')
					)
				)
			);
		}

		/**
		 * @brief custom paginated find for campaigns
		 *
		 * @param string $state before or after the find
		 * @param array $query the find query
		 * @param array $results rows found
		 *
		 * @return string
		 */
		protected function _findPaginated($state, $query, $results = array()) {
			if ($state === 'before') {
				if(!is_array($query['fields'])) {
					$query['fields'] = array($query['fields']);
				}

				$query['fields'] = array_merge(
					$query['fields'],
					array(
						'Campaign.id',
						'Campaign.name',
						'Campaign.description',
						'Campaign.newsletter_count',
						'Campaign.active',
						'Campaign.created',
						'Campaign.modified',
						'Template.id',
						'Template.name',
					)
				);

				$query['joins'][] = array(
					'table' => 'newsletter_templates',
					'alias' => 'Template',
					'type' => 'LEFT',
					'foreignKey' => false,
					'conditions' => array(
						'Template.id = Campaign.template_id'
					)
				);

				return $query;
			}

			if (!empty($query['operation'])) {
				return $this->_findPaginatecount($state, $query, $results);
			}

			return $results;
		}
	}