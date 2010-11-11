<?php
	class Trash extends TrashAppModel {
	    //put your code here
		public $useTable = 'trash';

		public $belongsTo = array(
			'User' => array(
				'className' => 'Users.User',
				'foreignKey' => 'deleted_by',
				'fields' => array(
					'User.id',
					'User.username'
				)
			)
		);

		public function restore($ids) {
			$trashed = $this->find('all', array('conditions' => array('id' => $ids)));

			$result = true;
			foreach($trashed as $trash) {
				$data = unserialize($trash['Trash']['data']);

				$model = ClassRegistry::init($trash['Trash']['model']);
				if($model) {
					$model->create();
					$result = $result && $model->save($data);
					$this->delete($trash['Trash']['id']);
				}
			}

			return $result;
		}
	}