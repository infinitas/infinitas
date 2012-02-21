<?php
	class GlobalContentsController extends ContentsAppController {
		public function admin_dashboard() {

		}

		public function admin_index() {
			$this->__filter();
			$this->set('contents', $this->paginate(null, $this->Filter->filter));
		}

		public function admin_content_issues() {
			$this->__filter();
			$this->paginate = array(
				'missingData',
				array('conditions' => $this->Filter->filter)
			);

			$this->set('contents', $this->paginate());
		}

		private function __filter() {
			$filterOptions = $this->Filter->filterOptions;
			$filterOptions['fields'] = array(
				//'model' => $this->GlobalContent->uniqueList('model'),
				'title',
				'layout_id' => $this->GlobalContent->GlobalLayout->find('list')
			);

			$this->set(compact('filterOptions'));
		}

		public function admin_transfer() {
			if(!empty($this->request->data)) {
				if(isset($this->request->data['GlobalContent']['plugin']) && isset($this->request->data['GlobalContent']['model'])) {
					$model = sprintf('%s.%s', $this->request->data['GlobalContent']['plugin'], $this->request->data['GlobalContent']['model']);
					$return = $this->GlobalContent->moveContent($model, $this->request->data['GlobalContent']['rows_to_move']);
					if($return){
						if($return['moved'] == 0  && $return['total'] == 0) {
							$this->notice(
								sprintf(__('There are no more items to move'), $return['moved'], $return['total']),
								array(
									'redirect' => array(
										'action' => 'index'
									)
								)
							);
						}
						$this->notice(
							sprintf(__('%s of %s fields were moved to the global content'), $return['moved'], $return['total']),
							array(
								'redirect' => array(
									'action' => 'index'
								)
							)
						);
					}
					else{
						$this->notice(
							__('Something went wrong, please try again'),
							array(
								'level' => 'error'
							)
						);
					}
				}
				else{
					$this->notice(
						__('Please select the model and plugin of the data you would like to move'),
						array(
							'level' => 'error'
						)
					);
				}
			}

			$plugins = $this->GlobalContent->getPlugins();
			$this->set(compact('plugins'));
		}

		/**
		 * cant add from here
		 */
		public function admin_add() {
			$this->notice(
				__('Please do not add content from here'),
				array(
					'level' => 'warning',
					'redirect' => true
				)
			);
		}

		public function admin_edit($id = null, $query = null) {
			parent::admin_edit($id, $query);

			$groups = array(0 => __('Public')) + $this->GlobalContent->Group->find('list');
			$layouts = $this->GlobalContent->GlobalLayout->find(
				'list',
				array(
					'conditions' => array(
						'GlobalLayout.model' => $this->request->data['GlobalContent']['model']
					)
				)
			);

			if(empty($layouts)) {
				$this->notice(
					__('Please create a layout for this content type'),
					array(
						'level' => 'warning',
						'redirect' => array(
							'controller' => 'global_layouts',
							'action' => 'add'
						)
					)
				);
			}

			$this->set(compact('groups', 'layouts'));
		}
	}