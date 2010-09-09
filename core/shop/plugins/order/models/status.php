<?php
	class Status extends OrderAppModel{
		var $name = 'Status';

		function getFirst(){
			$status = Cache::read('status_first', 'orders');
			if($status !== false){
				return $status;
			}

			$status = $this->find(
				'first',
				array(
					'order' => array(
						'Status.ordering' => 'ASC'
					)
				)
			);

			$status_id = false;
			if(isset($status['Status']['id'])){
				$status_id = $status['Status']['id'];
			}

			Cache::write('status_first', $status_id, 'orders');

			return $status_id;
		}

		function afterSave($created){
			return $this->dataChanged('afterSave');
		}

		function afterDelete(){
			return $this->dataChanged('afterDelete');
		}

		function dataChanged($from){
			App::import('Folder');
			$Folder = new Folder(CACHE . 'orders');
			$files = $Folder->read();

			foreach($files[1] as $file){
				if(strstr($file, 'status_') != false){
					Cache::delete($file, 'shop');
				}
			}

			return true;
		}
	}