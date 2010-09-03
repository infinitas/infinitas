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

	if(!Configure::read('Cms.allow_comments')){
		return false;
	}
?>
<div id="comment-box">
	<?php
        /**
         * fields allowed in the comments
         */
        $commentFields = explode(',',Configure::read('Comment.fields'));

	    $action    = (isset($action)) ? $action : 'comment';
        $modelName = (isset($modelName)) ? $modelName : Inflector::singularize($this->name);
    	$Model     = ClassRegistry::init($this->params['plugin'].'.'.$modelName);
		$data = &${strtolower($modelName)};

		if(isset($this->data[$modelName]) && is_array($this->data[$modelName])){
			$this->data[$modelName] = array_merge((array)$this->Session->read('Auth.User'), $this->data[$modelName]);
		}
		else{
			$this->data[$modelName] = $this->Session->read('Auth.User');
		}

        if (isset($urlParams)){
            echo $this->Form->create(
                $modelName,
                array(
                	'url' => array(
                		'plugin' => $this->params['plugin'],
                		'controller' => $this->params['controller'],
                		'action' => $action,
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
                		'plugin' => $this->params['plugin'],
                		'controller' => $this->params['controller'],
                		'action' => $action
                	)
                )
            );
        }
    ?>
        <h5><?php __('Post a '.$commentModel);?></h5>
        <?php
            echo $this->Form->input($modelName.'.'.$Model->primaryKey, array('value' => $data[$modelName][$Model->primaryKey]));

            foreach($commentFields as $field){
                if ($field != 'comment'){
                    echo $this->Form->input('Comment.'.$field, array('value' => isset($this->data[$modelName][$field]) ? $this->data[$modelName][$field] : ''));
                }
                else{
                    echo $this->Form->input('Comment.comment', array('type' => 'textarea', 'class' => 'title'));
                }
            }
        ?>
	<?php echo $this->Form->end('Submit'); ?>
</div>