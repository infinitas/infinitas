<?php
	class FileUploadComponent extends InfinitasComponent {
		public function beforeRender($Controller) {
			parent::beforeRender($Controller);
			
			if(empty($this->Controller->modelClass) || !$this->Controller->{$this->Controller->modelClass}->Behaviors->attached('Upload')) {
				// no model being used.
				return;
			}
			
			$fileUploadConfig = array();
			$this->set(compact('fileUploadConfig'));
		}
	}
