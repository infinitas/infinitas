<?php
pr($data);
	$action    = (isset($action)) ? $action : 'rate';
	$modelName = (isset($modelName)) ? $modelName : Inflector::singularize($this->name);

	if (!isset($data[$modelName]['rating'])){
		return false;
	}
?>
<div class="rating">
	<p>
		<?php echo sprintf(__('This article is rated %s out of %s votes', true), $data[$modelName]['rating'], $data[$modelName]['rating_count']); ?>
	</p>
	<p>
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
            // @todo -c Implement .remove id and use the models value for pk
            echo $this->Form->input($modelName.'.id', array('value' => $data[$modelName]['id']));
            echo $this->Form->hidden('Rating.class', array('value' => ($this->params['plugin'] ? Inflector::classify( $this->params['plugin'] ).'.'.$modelName : $modelName)));
            echo $this->Form->hidden('Rating.foreign_id', array('value' => $data[$modelName]['id']));

            echo $this->Form->input(
            	'Rating.rating',
            	array(
	            	'type'=>'radio',
	            	'div'=>false,
	            	'options'=>array(1=>1,2=>2,3=>3,4=>4,5=>5)
	            )
	        );

            echo $form->end('Submit');
		?>
	</p>
</div>