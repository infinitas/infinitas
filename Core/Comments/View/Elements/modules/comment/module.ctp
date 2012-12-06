<?php
    /**
     * Comment Template.
     *
     * @todo -c Implement .this needs to be sorted out.
     *
     * Copyright (c) 2009 Carl Sutton ( dogmatic69 )
     *
     * Licensed under The MIT License
     * Redistributions of files must retain the above copyright notice.
     *
     * @filesource
     * @copyright     Copyright (c) 2009 Carl Sutton ( dogmatic69 )
     * @link          http://infinitas-cms.org
     * @package       sort
     * @subpackage    sort.comments
     * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
     * @since         0.5a
     */

	if (!Configure::read(Inflector::camelize($this->plugin) . '.allow_comments')) {
		return false;
	}

	$config = array_merge(array(
		'view_all' => 'View all comments',
		'login' => 'Please log in to leave a comment'
	), $config);
?>
<div id="comment" class="comments">
	<?php
        $commentFields = explode(',', Configure::read('Comments.fields'));

        $modelName = (isset($modelName)) ? $modelName : Inflector::singularize($this->name);
    	$Model = ClassRegistry::init($this->request->plugin . '.' . $modelName);
		$data = &${strtolower($modelName)};

		$comments = isset($data[$modelName][$modelName . 'Comment'])
			? $data[$modelName][$modelName . 'Comment']
			: $data[$modelName . 'Comment'];

		if (!empty($comments)) {
			echo $this->Html->link(__d($this->request->params['plugin'], $config['view_all']), array(
				'plugin' => 'comments',
				'controller' => 'infinitas_comments',
				'action' => 'index',
				'Comment.class' => $this->request->plugin . '.' . $modelName,
				'Comment.foreign_id' => $foreign_id
			));

			$_comments = array();
			foreach ($comments as $comment) {
				$_comments[] = $this->element('Comments.single_comment', array('comment' => $comment));
			}

			echo implode('', $_comments);
		}

		if (Configure::read('Comments.require_auth') === true && !AuthComponent::user('id')) {
			echo $this->Html->tag('div', __d($this->request->params['plugin'], $config['login']), array(
				'class' => 'comment'
			));
			echo '</div>'; // dont remove it keeps things even when exiting early
			return;
		}

		if (isset($this->data[$modelName . 'Comment']) && is_array($this->data[$modelName . 'Comment'])) {
			$this->request->data[$modelName . 'Comment'] = array_merge((array)AuthComponent::user(), $this->data[$modelName . 'Comment']);
		} else {
			$this->request->data[$modelName . 'Comment'] = AuthComponent::user();
		}

		$formConfig = array(
			'url' => array(
				'action' => 'comment'
			)
		);
        if (isset($urlParams)) {
			$formConfig['url'] = array_merge($formConfig['url'], $urlParams);
        }
		echo $this->Form->create($modelName, $formConfig);

			echo $this->Form->hidden($modelName . 'Comment.foreign_id', array(
				'value' => $data[$modelName][$Model->primaryKey]
			));

			$_fields = array();
			foreach ($commentFields as $field) {
				if ($field != 'comment') {
					$value = '';
					$method = 'input';
					if (isset($this->data[$modelName . 'Comment'][$field])) {
						$value = isset($this->data[$modelName . 'Comment'][$field]) ? $this->data[$modelName . 'Comment'][$field] : '';
						if ($this->action != 'comment') {
							$method = 'hidden';
						}
					}
					$_fields[] = $this->Form->{$method}($modelName . 'Comment.' . $field, array(
						'value' => $value
					));
					continue;
				}


				$options = array('type' => 'textarea', 'class' => 'title');
				$submitOptions = array();
				if ($this->action != 'comment') {
					$options = array_merge($options, array(
						'label' => false,
						'div' => false,
					));
					$submitOptions = array('div' => false, 'class' => 'submit');
				}

				$_fields[] = $this->Form->input($modelName . 'Comment.comment', $options);
				$_fields[] = $this->Form->hidden($modelName . 'Comment.om_non_nom');
				$_fields[] = $this->Form->submit(__d('comments', 'Submit'), $submitOptions);
			}
			echo $this->Html->tag('div', implode('', $_fields), array(
				'class' => 'comment'
			));
		echo $this->Form->end();
	?>
</div>