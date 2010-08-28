<?php
	class CategoriesAppController extends AppController {
		public $view = 'Libs.Mustache';
		
		public $helpers = array(
			'Categories.Categories'
		);
	}