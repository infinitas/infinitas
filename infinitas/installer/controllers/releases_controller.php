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
			//$this->Release->getCoreData();
			//$this->Release->getSampleData();

			$this->Release->writeCoreData();
			$this->Release->writeSampleData();
		}

		function admin_core_data(){

		}

		function admin_sample_data(){

		}
	}
?>