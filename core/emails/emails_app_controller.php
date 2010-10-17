<?php
	class EmailsAppController extends AppController{
		public function beforeFilter(){
			parent::beforeFilter();

			$this->helpers[] = 'Filter.Filter';
		}
	}
