<?php
/**
 * FileUploadComponent
 *
 * @package Infinitas.Filemanager.Controller.Component
 */

/**
 * FileUploadComponent
 *
 * Component for file uploads
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.Filemanager.Controller.Component
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.7a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

class FileUploadComponent extends InfinitasComponent {
/**
 * BeforeRender callback
 *
 * @param Controller $Controller the controller being run
 *
 * @return void
 */
	public function beforeRender(Controller $Controller) {
		parent::beforeRender($Controller);

		if(empty($this->Controller->modelClass) || !$this->Controller->{$this->Controller->modelClass}->Behaviors->attached('Upload')) {
			// no model being used.
			return;
		}

		$fileUploadConfig = array();
		$this->set(compact('fileUploadConfig'));
	}

}