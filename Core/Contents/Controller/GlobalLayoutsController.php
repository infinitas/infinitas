<?php
	/**
	 *
	 *
	 */
	class GlobalLayoutsController extends ContentsAppController {
		/**
		 * Its pointless trying to use a wysiwyg here, so we will just put if off
		 * completely for the layouts.
		 */
		public function beforeRender() {
			parent::beforeRender();
			Configure::write('Wysiwyg.editor', 'text');
		}

		public function admin_index() {
			$this->Paginator->settings = array(
				'fields' => array(
					$this->{$this->modelClass}->alias .'.*',
					'Theme.*'
				),
				'joins' => array(
					array(
						'table' => 'core_themes',
						'alias' => 'Theme',
						'type' => 'LEFT',
						'conditions' => array(
							$this->{$this->modelClass}->alias . '.theme_id = Theme.id'
						)
					)
				),
				'order' => array(
					$this->{$this->modelClass}->alias . '.model',
					$this->{$this->modelClass}->alias . '.name'
				)
			);
			$layouts = $this->Paginator->paginate(null, $this->Filter->filter);

			$filterOptions = $this->Filter->filterOptions;
			$filterOptions['fields'] = array(
				'name'
			);

			$this->set(compact('layouts', 'filterOptions'));
		}

		public function admin_add() {
			parent::admin_add();

			$plugins = $this->GlobalLayout->getPlugins();
			$this->set(compact('plugins'));
		}

		public function admin_edit($id = null, $query = array()) {
			$this->GlobalLayout->GlobalContent->hasField('id');
			parent::admin_edit($id, $query);

			$plugins = $this->GlobalLayout->getPlugins();
			if(!empty($this->request->data['GlobalLayout']['plugin'])) {
				$models = $this->GlobalLayout->getModels($this->request->data['GlobalLayout']['plugin']);
			}
			$this->set(compact('plugins', 'models'));
		}
	}