<?php
	class GlobalContentsController extends ContentsAppController{
		public $name = 'GlobalContents';

		public $helpers = array(
			'Filter.Filter'
		);

		public function admin_index(){
			$this->paginate = array(
				'contain' => array(
					'GlobalLayout',
					'Group'
				)
			);
			
			$contents = $this->paginate(null, $this->Filter->filter);

			$filterOptions = $this->Filter->filterOptions;
			$filterOptions['fields'] = array(
				'model' => $this->GlobalContent->uniqueList('model'),
				'title',
				'layout_id' => $this->GlobalContent->GlobalLayout->find('list')
			);

			$this->set(compact('contents', 'filterOptions'));
		}

		public function admin_transfer(){
			if(!empty($this->data)){
				if(isset($this->data['GlobalContent']['plugin']) && isset($this->data['GlobalContent']['model'])){
					$model = sprintf('%s.%s', $this->data['GlobalContent']['plugin'], $this->data['GlobalContent']['model']);
					$return = $this->GlobalContent->moveContent($model, $this->data['GlobalContent']['rows_to_move']);
					if($return){
						if($return['moved'] == 0  && $return['total'] == 0){
							$this->notice(
								sprintf(__('There are no more items to move', true), $return['moved'], $return['total']),
								array(
									'redirect' => array(
										'action' => 'index'
									)
								)
							);
						}
						$this->notice(
							sprintf(__('%s of %s fields were moved to the global content', true), $return['moved'], $return['total']),
							array(
								'redirect' => array(
									'action' => 'index'
								)
							)
						);
					}
					else{
						$this->notice(
							__('Something went wrong, please try again', true),
							array(
								'level' => 'error'
							)
						);
					}
				}
				else{
					$this->notice(
						__('Please select the model and plugin of the data you would like to move', true),
						array(
							'level' => 'error'
						)
					);
				}
			}

			$plugins = $this->GlobalContent->getPlugins();
			$this->set(compact('plugins'));
		}

		public function admin_edit($id = null, $query = null){
			parent::admin_edit($id, $query);

			$groups = array(0 => __('Public', true)) + $this->GlobalContent->Group->find('list');
			$layouts = $this->GlobalContent->GlobalLayout->find(
				'list',
				array(
					'conditions' => array(
						'GlobalLayout.model' => $this->data['GlobalContent']['model']
					)
				)
			);

			if(empty($layouts)){
				$this->notice(
					__('Please create a layout for this content type', true),
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