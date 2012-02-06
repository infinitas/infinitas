<?php
	class GlobalContentComponent extends InfinitasComponent {
		private $__methods = array(
			'admin_edit',
			'admin_add'
		);
		
		public function beforeRender($Controller) {
			$should = isset($Controller->{$Controller->modelClass}) && $Controller->{$Controller->modelClass}->contentable;
			if(!$should || !in_array($Controller->params['action'], $this->__methods)) {
				return;
			}

			$Controller->set('contentGroups', $Controller->Content->Group->generatetreelist());
			$Controller->set('contentLayouts', $Controller->Content->Layout->find('list'));

			$contentCategories = $Controller->Content->GlobalCategory->generatetreelist(
				array(),
				'{n}.GlobalCategory.id',
				'{n}.GlobalCategory.title'
			);
			
			$Controller->set('contentCategories', $contentCategories);
		}
	}