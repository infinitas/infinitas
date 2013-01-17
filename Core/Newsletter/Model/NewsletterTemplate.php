<?php
/**
 * Template
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.Newsletter.Model
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.5a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

/**
 * Template
 *
 * @package Infinitas.Newsletter.Model
 *
 * @property Newsletter $Newsletter
 * @property NewsletterCampaign $NewsletterCampaign
 */

class NewsletterTemplate extends NewsletterAppModel {

/**
 * Make templates lockable
 *
 * @var boolean
 */
	public $lockable = false;

/**
 * HasMany relations
 *
 * @var array
 */
	public $hasMany = array(
		'Newsletter' => array(
			'className' => 'Newsletter.Newsletter'
		),
		'NewsletterCampaign' => array(
			'className' => 'Newsletter.NewsletterCampaign'
		)
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

		$this->order = array(
			$this->alias . '.' . $this->displayField => 'asc'
		);

		$this->validate = array(
			'name' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Please enter the name for this template'
				),
				'isUnique' => array(
					'rule' => 'isUnique',
					'message' => 'A template with that name already exists'
				)
			)
		);
	}

/**
 * Get a template
 *
 * @param string $data the template name or id
 * 
 * @return array
 *
 * @throws Exception
 */
	public function getTemplate($data = null) {
		$fields = array(
			$this->alias . '.' . $this->primaryKey,
			$this->alias . '.' . $this->displayField,
			$this->alias . '.header',
			$this->alias . '.footer',
		);

		if ($data) {
			$template = $this->find('first', array(
				'fields' => $fields,
				'conditions' => array(
					'or' => array(
						$this->alias . '.' . $this->primaryKey => $data,
						$this->alias . '.' . $this->displayField => $data
					)
				)
			));

			if (!empty($template)) {
				return $template;
			}
		}

		$template = $this->find('first', array(
			'fields' => $fields,
			'conditions' => array(
				$this->alias . '.' . $this->displayField => Configure::read('Newsletter.template')
			)
		));

		if (empty($template)) {
			throw new CakeException(sprintf('No template found for %s', $data));
		}

		return $template;
	}
}