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

	if(!Configure::read(Inflector::camelize($this->plugin).'.allow_comments')){
		return false;
	}
?>
<div class="comments">
	<?php       
        $commentFields = explode(',',Configure::read('Comments.fields'));

        $modelName = (isset($modelName)) ? $modelName : Inflector::singularize($this->name);
    	$Model     = ClassRegistry::init($this->params['plugin'].'.'.$modelName);
		$data = &${strtolower($modelName)};

		$comments = isset($data[$modelName][$modelName.'Comment'])
			? $data[$modelName][$modelName.'Comment']
			: $data[$modelName.'Comment'];

		if(!empty($comments)){
			echo $this->Html->link(
				__('View all comments', true),
				array(
					'plugin' => 'comments',
					'controller' => 'comments',
					'action' => 'index',
					'Comment.class' => Inflector::camelize($this->params['plugin']).'.'.$modelName,
					'Comment.foreign_id' => $foreign_id
				)
			);

			$_comments = array();
			foreach($comments as $comment){
				$_comments[] = $this->element('single_comment', array('plugin' => 'comments', 'comment' => $comment));
			}

			echo implode('', $_comments);
		}

		if(Configure::read('Comments.require_auth') === true && !$this->Session->read('Auth.User.id')){
			?><div class="comment"><?php echo __('Please log in to leave a comment', true); ?></div><?php
			echo '</div>'; // dont remove it keeps things even when exiting early
			return;
		}

		if(isset($this->data[$modelName.'Comment']) && is_array($this->data[$modelName.'Comment'])){
			$this->data[$modelName.'Comment'] = array_merge((array)$this->Session->read('Auth.User'), $this->data[$modelName.'Comment']);
		}
		
		else{
			$this->data[$modelName.'Comment'] = $this->Session->read('Auth.User');
		}

        if (isset($urlParams)){
            echo $this->Form->create(
                $modelName,
                array(
                	'url' => array(
                		'action' => 'comment',
                		$urlParams
                	)
                )
            );
        }

        else{
            echo $this->Form->create(
                $modelName,
                array(
                	'url' => array(
                		'action' => 'comment'
                	)
                )
            );
        }

			echo $this->Form->hidden($modelName.'Comment.foreign_id', array('value' => $data[$modelName][$Model->primaryKey]));
			
			echo '<div class="comment">';
			foreach($commentFields as $field){

				if ($field != 'comment'){
					$value = '';
					$method = 'input';
					if(isset($this->data[$modelName.'Comment'][$field])){
						$value = isset($this->data[$modelName.'Comment'][$field]) ? $this->data[$modelName.'Comment'][$field] : '';
						if($this->action != 'comment'){
							$method = 'hidden';
						}
					}
					echo $this->Form->{$method}($modelName.'Comment.'.$field, array('value' => $value));
					continue;
				}
				
				
				$options = array('type' => 'textarea', 'class' => 'title');
				$submitOptions = array();
				if($this->action != 'comment'){
					$options = array_merge(
						$options,
						array(
							'label' => false,
							'div' => false,
						)
					);
					$submitOptions = array('div' => false, 'class' => 'submit');					
				}
				
				echo $this->Form->input($modelName.'Comment.comment', $options);
				echo $this->Form->submit('Submit', $submitOptions);
			}
			echo '</div>';
		echo $this->Form->end();
	?>
</div>