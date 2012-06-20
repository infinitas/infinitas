<?php
	/**
	 * Infinitas Helper.
	 *
	 * Does a lot of stuff like generating ordering buttons, load modules and
	 * other things needed all over infinitas.
	 *
	 * Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 *
	 * @filesource
	 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * @link http://www.infinitas-cms.org
	 * @package libs
	 * @subpackage libs.views.helpers.infinitas
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.6a
	 *
	 * @author Carl Sutton ( dogmatic69 )
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */
	App::uses('AppHelper', 'View/Helper');

	class InfinitasHelper extends AppHelper {
		public $helpers = array(
			'Html',
			'Form',
			'Libs.Design',
			'Libs.Image',
			'Libs.Wysiwyg'
		);

		/**
		* JSON errors.
		*
		* Set up some errors for json.
		* @access public
		*/
		public $jsonErrors = array(
			JSON_ERROR_NONE	  => 'No error',
			JSON_ERROR_DEPTH	 => 'The maximum stack depth has been exceeded',
			JSON_ERROR_CTRL_CHAR => 'Control character error, possibly incorrectly encoded',
			JSON_ERROR_SYNTAX	=> 'Syntax error',
		);

		protected $_menuData = '';

		protected $_menuLevel = 0;

		public $external = true;

		public $View = null;

		private $__rowCount = 0;

		private $__massActionCheckBoxCounter = 0;

		/**
		 * Set to true when the menu has a current marker to avoid duplicates.
		 * @var unknown_type
		 */
		protected $_currentCssDone = false;

		/**
		* Create a status icon.
		*
		* Takes a int 0 || 1 and returns a icon with title tags etc to be used
		* in places like admin to show iff something is on/off etc.
		*
		* @param int $status the status tinyint(1) from a db find.
		*
		* @return string some html for the generated image.
		*/
		public function status($status = null, $options = array()) {
			$image = false;
			$params = array();
			
			$options = array_merge(
				array(
					'title_yes' => __d('infinitas', 'Status :: This record is active'),
					'title_no' => __d('infinitas', 'Status :: This record is disabled'),
				),
				(array)$options
			);

			switch (strtolower($status)) {
				case 1:
				case 'yes':
				case 'on':
					if ($this->external) {
						$params['title'] = $options['title_yes'];
					}

					$image = $this->Html->image(
						$this->Image->getRelativePath('status', 'active'),
						$params + array(
							'width' => '16px',
							'alt' => __d('infinitas', 'On')
						)
					);
					break;

				case 0:
				case 'no':
				case 'off':
					if ($this->external) {
						$params['title'] = $options['title_no'];
					}

					$image = $this->Html->image(
						$this->Image->getRelativePath('status', 'inactive'),
						$params + array(
							'width' => '16px',
							'alt' => __d('infinitas', 'Off')
						)
					);
					break;
			}

			return $image;
		}

		/**
		 * Featured icon.
		 *
		 * Creates a featured icon like the status and locked.
		 *
		 * @param array $record the data from find
		 * @param string $model the model alias
		 *
		 * @return string html of the icon.
		 */
		public function featured($record = array(), $model = 'Feature') {
			$record = array_filter($record[$model]);
			if (empty($record)) {
				return $this->Html->image(
					$this->Image->getRelativePath('status', 'not-featured'),
					array(
						'alt'   => __('No'),
						'title' => __('Not a featured item'),
						'width' => '16px'
					)
				);
			}

			return $this->Html->image(
				$this->Image->getRelativePath('status', 'featured'),
				array(
					'alt'   => __('Yes'),
					'title' => __('Featured Item'),
					'width' => '16px'
				)
			);
		}

		public function loggedInUserText($counts) {
			$allInIsAre	= ($counts['all'] > 1) ? __('are') : __('is');
			$loggedInIsAre = ($counts['loggedIn'] > 1) ? __('are') : __('is');
			$guestsIsAre   = ($counts['guests'] > 1) ? __('are') : __('is');
			$guests		= ($counts['guests'] > 1) ? __('guests') : __('a guest');

			return '<p>'.
				sprintf(
					__('There %s %s people on the site, %s %s logged in and %s %s %s.'),
					$allInIsAre, $counts['all'],
					$counts['loggedIn'], $loggedInIsAre,
					$counts['guests'], $guestsIsAre,
					$guests
				).
			'</p><p>&nbsp;</p>';
		}

		/**
		 * @brief generate a checkbox for rows that use mass_action stuff
		 *
		 * it will keep track of the $i for the checkbox number so there are no duplicates.
		 * MassActionComponent::filter() will remove these fields from the searches so there
		 * are no sql errors.
		 *
		 * @param array $data the row from find either find('first') or find('all')[x]
		 * @param array $options set the fk or model manually
		 *
		 * @return a checkbox
		 */
		public function massActionCheckBox($data = array(), $options = array()) {
			$model = current(array_keys($this->request->params['models']));
			$modelClass = implode('.', $this->request->params['models'][$model]);
			$options = array_merge(
				array('model' => $model, 'primaryKey' => ClassRegistry::init($modelClass)->primaryKey, 'hidden' => false, 'checked' => false),
				$options
			);

			if(!$data || !isset($data[$options['model']])) {
				return false;
			}

			$checkbox = $this->Form->checkbox(
				$options['model'] . '.' . $this->__massActionCheckBoxCounter . '.' . 'massCheckBox',
				array(
					'value' => $data[$options['model']][$options['primaryKey']],
					'hidden' => $options['hidden'],
					'checked' => $options['checked']
				)
			);

			$this->__massActionCheckBoxCounter++;

			return $checkbox;
		}

		public function hasPlugin($plugin = null) {
			return ClassRegistry::init('Installer.Plugin')->isInstalled($plugin);
		}
	}
