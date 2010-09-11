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

	class InfinitasHelper extends AppHelper{
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
		public $_json_errors = array(
		    JSON_ERROR_NONE      => 'No error',
		    JSON_ERROR_DEPTH     => 'The maximum stack depth has been exceeded',
		    JSON_ERROR_CTRL_CHAR => 'Control character error, possibly incorrectly encoded',
		    JSON_ERROR_SYNTAX    => 'Syntax error',
		);

		var $_menuData = '';

		var $_menuLevel = 0;

		public $external = true;

		var $View = null;

		/**
		 * Set to true when the menu has a current marker to avoid duplicates.
		 * @var unknown_type
		 */
		var $_currentCssDone = false;

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
		public function status($status = null){
			$image = false;
			$params = array();

			switch (strtolower($status)){
				case 1:
				case 'yes':
				case 'on':
					if ($this->external){
						$params['title'] = __( 'Active', true );
					}

					$image = $this->Html->image(
					    $this->Image->getRelativePath('status', 'active'),
					    $params + array(
					        'width' => '16px',
					        'alt' => __('On', true)
					    )
					);
					break;

				case 0:
				case 'no':
				case 'off':
					if ($this->external){
						$params['title'] = __('Disabled', true);
					}

					$image = $this->Html->image(
					    $this->Image->getRelativePath('status', 'inactive'),
					    $params + array(
					        'width' => '16px',
					        'alt' => __('Off', true)
					    )
					);
					break;
			}

			return $image;
		}

		/**
		 * Create a locked icon.
		 *
		 * takes the data from a find and shows if it is locked and if so who by
		 * and when.
		 *
		 * @param array $item the data
		 * @param mixed $model the model the data is from
		 *
		 * @return mixed some html with the image
		 */
		public function locked($item = array(), $model = null){
			if (!$model || empty($item) || empty($item[$model])){
				$this->errors[] = 'you missing some data there.';
				return false;
			}

			switch (strtolower($item[$model]['locked'])){
				case 1:
					$this->Time = new TimeHelper();
					$image = $this->Html->image(
					    $this->Image->getRelativePath('status', 'locked'),
					    array(
					        'alt' => __('Locked', true),
					        'width' => '16px',
					        'title' => sprintf(
					            __( 'This record was locked %s by %s', true ),
					            $this->Time->timeAgoInWords($item[$model]['locked_since']),
					            $item['Locker']['username']
					        )
					    )
					);
					unset($this->Time);
					break;

				case 0:
					$image = $this->Html->image(
					    $this->Image->getRelativePath('status', 'not-locked'),
					    array(
					        'alt' => __('Not Locked', true),
					        'width' => '16px',
					        'title' => __('This record is not locked', true)
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
		public function featured($record = array(), $model = 'Feature'){
			if (empty($record[$model])){
				$this->messages[] = 'This has no featured items.';

				return $this->Html->image(
				    $this->Image->getRelativePath('status', 'not-featured'),
				    array(
				        'alt'   => __('No', true),
				        'title' => __('Not a featured item', true),
				        'width' => '16px'
				    )
				);
			}

			return $this->Html->image(
			    $this->Image->getRelativePath('status', 'featured'),
			    array(
			        'alt'   => __('Yes', true),
			        'title' => __('Featured Item', true),
			        'width' => '16px'
			    )
			);
		}

		public function loggedInUserText($counts){
			$allInIsAre    = ($counts['all'] > 1) ? __('are', true) : __('is', true);
			$loggedInIsAre = ($counts['loggedIn'] > 1) ? __('are', true) : __('is', true);
			$guestsIsAre   = ($counts['guests'] > 1) ? __('are', true) : __('is', true);
			$guests        = ($counts['guests'] > 1) ? __('guests', true) : __('a guest', true);

			return '<p>'.
				sprintf(
					__('There %s %s people on the site, %s %s logged in and %s %s %s.', true),
					$allInIsAre, $counts['all'],
					$counts['loggedIn'], $loggedInIsAre,
					$counts['guests'], $guestsIsAre,
					$guests
				).
			'</p><p>&nbsp;</p>';
		}
	}
?>