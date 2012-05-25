<?php
	App::uses('AuthComponent', 'Controller/Component');
	
	class TrashableBehavior extends ModelBehavior {
		public $Trash = null;

		public function beforeDelete($Model, $cascade = true) {
			if($this->Trash === null) {
				$this->Trash = ClassRegistry::init('Trash.Trash');
			}
			
			$userId = AuthComponent::user('id');
			
			if(!$userId) {
				return false;
			}

			$Model->contain();
			$item = $Model->read();
			$data = array(
				'model' => $Model->modelName(),
				'foreign_key' => $Model->id,
				'name' => !empty($item[$Model->alias][$Model->displayField]) ? $item[$Model->alias][$Model->displayField] : __d('trash', 'Unknown'),
				'data' => serialize($Model->data),
				'deleted' => date('Y-m-d H:i:s'),
				'deleted_by' => $userId
			);

			$this->Trash->create();
			$this->Trash->save($data);

			return true;
		}
	}