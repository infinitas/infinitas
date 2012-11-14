<?php
/**
 * GlobalContentsController
 *
 * @package Infinitas.Contents.Controller
 */

/**
 * GlobalContentsController
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.Contents.Controller
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.7a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */
class GlobalContentsController extends ContentsAppController {
/**
 * Contents dashboard
 *
 * @return void
 */
	public function admin_dashboard() {

	}

/**
 * Content index
 *
 * @return void
 */
	public function admin_index() {
		$this->__filter();
		$this->set('contents', $this->Paginator->paginate(null, $this->Filter->filter));
	}

/**
 * Display content issues
 *
 * @return void
 */
	public function admin_content_issues() {
		$this->__filter();
		$this->Paginator->settings = array(
			'contentIssues',
			array('conditions' => $this->Filter->filter)
		);

		$this->set('contents', $this->Paginator->paginate());
	}

/**
 * Filter options
 *
 * @return void
 */
	private function __filter() {
		$filterOptions = $this->Filter->filterOptions;
		$filterOptions['fields'] = array(
			//'model' => $this->GlobalContent->uniqueList('model'),
			'title',
			'layout_id' => $this->GlobalContent->GlobalLayout->find('list')
		);

		$this->set(compact('filterOptions'));
	}

/**
 * Transfer content from normal model to contents plugin
 *
 * @return void
 */
	public function admin_transfer() {
		if(!empty($this->request->data)) {
			if(isset($this->request->data['GlobalContent']['plugin']) && isset($this->request->data['GlobalContent']['model'])) {
				$model = sprintf('%s.%s', $this->request->data['GlobalContent']['plugin'], $this->request->data['GlobalContent']['model']);
				$return = $this->GlobalContent->moveContent($model, $this->request->data['GlobalContent']['rows_to_move']);
				if($return) {
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
 * Disable adding content from this plugin
 *
 * @return void
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

/**
 * Content edit (for layouts)
 *
 * @param string $id the id of the record to edit
 * @param array $query
 *
 * @return void
 */
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