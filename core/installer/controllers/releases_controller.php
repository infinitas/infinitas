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
			//$this->_uppdateDatabase();

			if ($this->Release->writeCoreData()) {
				$this->Session->setFlash(__('Core data updated', true));
			}
			else{
				$this->Session->setFlash(__('Something went wrong', true));
			}
			$this->redirect('/admin');
		}

		function admin_update_sample(){
			//$this->_uppdateDatabase();

			if ($this->Release->writeSampleData()) {
				$this->Session->setFlash(__('Sample data updated', true));
			}
			else{
				$this->Session->setFlash(__('Something went wrong', true));
			}
			$this->redirect('/admin');
		}

		function _uppdateDatabase(){
			$type = 'app';

			if(!isset($this->MigrationVersion)){
				App::import('Lib', 'Migrations.MigrationVersion');
				$this->MigrationVersion = new MigrationVersion();
			}

			// Get the mapping and the latest version avaiable
			$mapping = $this->MigrationVersion->getMapping($type);
			$latest = array_pop($mapping);
			if (count(ClassRegistry::init('SchemaMigration')->find('list')) == count($mapping)){
				$this->MigrationVersion->run(array('type' => $type, 'version' => $latest['version'])); exit;
			}
		}
	}