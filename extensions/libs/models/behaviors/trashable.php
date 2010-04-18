<?php
class TrashableBehavior extends ModelBehavior {
	var $Trash = null;
	var $excludes = array('Session', 'Trash', 'Tagged');

	public function setup(&$Model, $settings = array()) {
		if($this->Trash === null) {
			$this->Trash = ClassRegistry::init('Management.Trash');
		}
	}

    public function beforeDelete(&$Model, $cascade = true) {
		if(!in_array($Model->name, $this->excludes)) {
			$this->Session = new CakeSession();
			$user_id = $this->Session->read('Auth.User.id');

			$data = array(
				'model' => $Model->modelName(),
				'foreign_key' => $Model->data[$Model->alias][$Model->primaryKey],
				'name' => $Model->data[$Model->alias][$Model->displayField],
				'data' => serialize($Model->data),
				'deleted' => date('Y-m-d H:i:s'),
				'deleted_by' => $user_id
			);

			$this->Trash->create();
			$this->Trash->save($data);
		}

		return true;
	}
}
?>
