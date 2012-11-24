<?php
/**
 * LetterHelper
 *
 * @package Infinitas.Newsletter.Helper
 */

/**
 * LetterHelper
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.Newsletter.Helper
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.5a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

class LetterHelper extends AppHelper {
/**
 * Helpers to load
 *
 * @var array
 */
	public $helpers = array(
		'Html',
		'Libs.Wysiwyg',
		'Libs.Design',
		'Libs.Image',
		'Form'
	);

/**
 * Allowed previews
 *
 * @var array
 */
	public $allowedPreviews = array(
		'newsletters',
		'templates'
	);

/**
 * List of errors
 *
 * @var array
 */
	public $errors = array();

/**
 * Newsletter toggle button
 *
 * @param string $id
 * @param boolean $status
 * @param string $method
 *
 * @return boolean
 */
	public function toggle($id = null, $status = null, $method = 'toggleSend') {
		if (!$id) {
			$this->errors[] = 'No id passed for newsletter status';
			return false;
		}

		$params = array();

		switch($status) {
			case 0:
				return $link = $this->Html->link($this->Html->image(
						'core/icons/file_types/16/mail.png',
						array(
							'alt' => __d('newsletter', 'Pending'),
							'title' => __d('newsletter', 'Click to start sending'),
							'width' => '16px'
							)
						),
					array(
						'action' => $method,
						$id
						),
					$params + array(
						'escape' => false
						)
					);
				break;

			case 1:
				return $this->Html->image(
					'core/icons/actions/16/active.png',
					array(
						'alt' => __d('newsletter', 'Done'),
						'title' => __d('newsletter', 'Sending Complete'),
						'width' => '16px'
						)
					);
				break;
			default: ;
		}
	}

/**
 * Preview a newsletter
 *
 * @param string $id the newsletter to preview
 * @param string $controller
 *
 * @return string
 */
	public function preview($id = null, $controller = null) {
		if (!$id || !$controller) {
			$this->errors[] = 'You need to pass the id and template|newsletter';
			return false;
		}

		if (!in_array($controller, $this->allowedPreviews)) {
			$this->errors[] = 'There is no preview for the controller';
			return false;
		}

		$url = $this->Html->url(array(
			'plugin' => 'newsletter',
			'controller' => $controller,
			'action' => 'preview',
			$id,
			'admin' => 'true'
		));

		return '<iframe frameborder="0" width="100%" height="500px" name="preview"src="' . $url . '" style="border:1px dotted gray;"></iframe>';
	}

}