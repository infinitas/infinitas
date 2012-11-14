<?php
/**
 * Template
 *
 * @package Infinitas.Newsletter.Model
 */

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

class Template extends NewsletterAppModel {
	//public $lockable = true;

/**
 * Template order
 *
 * @var array
 */
	public $order = array(
		'Template.name' => 'asc'
	);

/**
 * HasMany relations
 *
 * @var array
 */
	public $hasMany = array(
		'Newsletter.Newsletter',
		'Newsletter.Campaign'
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

		if($data) {
			$template = $this->find(
				'first',
				array(
					'fields' => $fields,
					'conditions' => array(
						'or' => array(
							$this->alias . '.id' => $data,
							$this->alias . '.name' => $data
						)
					)
				)
			);

			if(!empty($template)) {
				return $template;
			}
		}

		$template = $this->find(
			'first',
			array(
				'fields' => $fields,
				'conditions' => array(
					$this->alias . '.name' => Configure::read('Newsletter.template')
				)
			)
		);

		if(empty($template)) {
			throw new Exception(sprintf('No template found for %s', $data));
		}

		return $template;
	}

}