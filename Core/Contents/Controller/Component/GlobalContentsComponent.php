<?php
	App::uses('InfinitasComponent', 'Libs.Controller/Component');

	class GlobalContentsComponent extends InfinitasComponent {
		private $__methods = array(
			'admin_edit',
			'admin_add'
		);

		public function beforeRender($Controller) {
			if(empty($Controller->uses)) {
				return true;
			}

			$isContentable = isset($Controller->{$Controller->modelClass}->contentable) && $Controller->{$Controller->modelClass}->contentable;
			if($isContentable && in_array($Controller->params['action'], $this->__methods)) {
				$this->__setFormVariables($Controller);
			}

			$this->__loadLayout(
				$Controller,
				array(
					'plugin' => $Controller->{$Controller->modelClass}->plugin,
					'model' => $Controller->{$Controller->modelClass}->alias,
					'action' => $Controller->params['action']
				)
			);
		}

		private function __setFormVariables($Controller) {
			if(isset($Controller->{$Controller->modelClass}->GlobalContent)) {
				$Model = $Controller->{$Controller->modelClass}->GlobalContent;
			}
			else if(isset($Controller->GlobalContent)) {
				$Model = $Controller->GlobalContent;
			}

			else {
				throw new Exception('Could not find the Content model');
			}

			$authors = $Model->ContentAuthor->getAdmins(
				array(
					$Model->ContentAuthor->alias . '.' . $Model->ContentAuthor->primaryKey,
					$Model->ContentAuthor->alias . '.' . $Model->ContentAuthor->displayField,
				)
			);

			$Controller->set('contentGroups', $Model->Group->find('list'));
			$Controller->set('contentAuthors', $authors);
			$Controller->set('contentLayouts', $Model->GlobalLayout->find('list'));

			$contentCategories = $Model->GlobalCategory->find('list');

			$Controller->set('contentCategories', $contentCategories);
		}

		private function __loadLayout($Controller, $options) {
			$layout = ClassRegistry::init('Contents.GlobalLayout')->find(
				'autoLoadLayout', $options
			);

			if($layout) {
				$Controller->set('globalLayoutTemplate', $layout);
			}
		}
	}