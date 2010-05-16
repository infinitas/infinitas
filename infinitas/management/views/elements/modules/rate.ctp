<?php
	$modelName = (isset($modelName)) ? $modelName : Inflector::singularize($this->name);
    $Model     = ClassRegistry::init($this->params['plugin'].'.'.$modelName);
	$data = &${strtolower($modelName)};
    $allow = Configure::read(ucfirst($this->params['plugin']).'.allow_ratings');

    if(Configure::read('Rating.time_limit')){
    	$allow &= date('Y-m-d H:i:s', strtotime('- '.Configure::read('Rating.time_limit'))) < $data[$modelName]['modified'];
    }

	if (!isset(${strtolower($modelName)}[$modelName]['rating']) || $allow !== true){
		echo __('Rating is currently dissabled for this page', true);
		return false;
	}
?>
<div id="star-rating" class="rating {currentRating: '<?php echo $data[$modelName]['rating']; ?>', url:{action:'rate', id: <?php echo $data[$modelName]['id']; ?>}, target:'this'}">
	<span class="star-rating-result">
		<?php
			if ($data[$modelName]['rating_count'] > 0) {
				echo sprintf(__('Currently rated %s (out of %s votes)', true), $data[$modelName]['rating'], $data[$modelName]['rating_count']);
			}
			else{
				echo sprintf(__('This %s has not been rated yet', true), prettyName($modelName));
			}
		?>
	</span>
	<div id="coreRatingBox">
		<?php
            echo $this->Form->create(
                $modelName,
                array(
                	'url' => array(
                		'plugin' => $this->params['plugin'],
                		'controller' => $this->params['controller'],
                		'action' => 'rate'
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