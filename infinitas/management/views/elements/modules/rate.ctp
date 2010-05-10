<?php
	$action    = (isset($action)) ? $action : 'rate';
	$modelName = (isset($modelName)) ? $modelName : Inflector::singularize($this->name);
    $Model     = ClassRegistry::init($this->params['plugin'].'.'.$modelName);

    $allow = Configure::read(ucfirst($this->params['plugin']).'.allow_ratings');

	if (!isset($data[$modelName]['rating']) || $allow !== true){
		return false;
	}
?>
<div class="rating">
	<p>
		<?php
			if ($data[$modelName]['rating_count'] > 0) {
				echo sprintf(__('Currently rated %s (out of %s votes)', true), $data[$modelName]['rating'], $data[$modelName]['rating_count']);
			}
			else{
				echo sprintf(__('This %s has not been rated yet', true), prettyName($modelName));
			}
		?>
	</p>
	<div id="coreRatingBox">
		<?php
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

            echo $this->Form->input($modelName.'.'.$Model->primaryKey, array('value' => $data[$modelName][$Model->primaryKey]));
            echo $this->Form->hidden('Rating.class', array('value' => ucfirst($this->params['plugin']).'.'.$modelName));
            echo $this->Form->hidden('Rating.foreign_id', array('value' => $data[$modelName][$Model->primaryKey]));

            echo $this->Form->input(
            	'Rating.rating',
            	array(
	            	'type'=>'radio',
            		'legend' => false,
	            	'div' => false,
	            	'options' => array(1=>1, 2=>2, 3=>3, 4=>4, 5=>5)
	            )
	        );

            echo $form->end('Submit');
		?>
	</div>
</div>