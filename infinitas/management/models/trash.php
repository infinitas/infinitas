<?php
class Trash extends ManagementAppModel {
    //put your code here
	var $useTable = 'trash';

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
?>
