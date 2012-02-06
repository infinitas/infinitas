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

			if(isset($Controller->{$Controller->modelClass}->GlobalContent)) {
				$Model = $Controller->{$Controller->modelClass}->GlobalContent;
			}
			else if(isset($Controller->GlobalContent)) {
				$Model = $Controller->GlobalContent;
			}

			else {
				throw new Exception('Could not find the Content model');
			}

			$Controller->set('contentGroups', $Model->Group->generatetreelist());
			$Controller->set('contentLayouts', $Model->GlobalLayout->find('list'));

			$contentCategories = $Model->GlobalCategory->generatetreelist(
				array(),
				'{n}.GlobalCategory.id',
				'{n}.GlobalCategory.title'
			);
			
			$Controller->set('contentCategories', $contentCategories);
		}
	}