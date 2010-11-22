<?php
	final class TagsEvents extends AppEvents{
		public function onAdminMenu($event){
			$menu['main'] = array(
				'Tags' => array('plugin' => 'tags', 'controller' => 'tags', 'action' => 'index')
			);

			return $menu;
		}

		public function onAttachBehaviors($event) {
			if(is_subclass_of($event->Handler, 'Model') && isset($event->Handler->_schema) && is_array($event->Handler->_schema)) {
				$Model = $event->Handler;
				
				if (array_key_exists('tags', $Model->_schema)) {
					$Model->Behaviors->attach('Tags.Taggable');
				}
			}
		}

		public function onRequireHelpersToLoad(){
			return 'Tags.TagCloud';
		}

		public function onRequireJavascriptToLoad($event){
			return array(
				'/tags/js/jq-tags',
				'/tags/js/tags'
			);
		}

		public function onRequireCssToLoad($event){
			return array(
				'/tags/css/tags'
			);
		}
	}