<?php
	/**
	 *
	 *
	 */
	class ReleasesController extends InstallerAppController{
		var $name = 'Releases';

		function beforeFilter(){
			parent::beforeFilter();

			$this->Auth->allow('*');
		}

		function admin_index(){
			if ($this->Release->getCoreData() && $this->Release->getSampleData()) {
				$this->Session->setFlash(__('Data has been updated', true));
			}
			else{
				$this->Session->setFlash(__('Something went wrong', true));
			}
			$this->redirect('/admin');
		}

		function admin_update_core(){
			if ($this->Release->writeCoreData()) {
				$this->Session->setFlash(__('Core data updated', true));
			}
			else{
				$this->Session->setFlash(__('Something went wrong', true));
			}
			$this->redirect('/admin');
		}

		function admin_update_sample(){
			if ($this->Release->writeSampleData()) {
				$this->Session->setFlash(__('Sample data updated', true));
			}
			else{
				$this->Session->setFlash(__('Something went wrong', true));
			}
			$this->redirect('/admin');
		}

		function admin_core_data(){

		}

		function admin_sample_data(){

		}
	}
?>