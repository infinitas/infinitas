<?php
/**
 * Header search form
 *
 */
?>
<div id="header-search">
<?php echo $form->create('ApiClass', array(
	'url' => array(
		'plugin' => 'api_generator', 'controller' => 'api_classes',
		'action' => 'search'
	),
	'type' => 'get',
)); ?>
<fieldset id="search-bar">
<?php
	if ($this->action === 'search' && !empty($this->passedArgs[0])) {
		$value = $this->passedArgs[0];
	} else {
		$value = '';
	}
	echo $form->text('Search.query', array(
		'class' => 'query',
		'value' => $value
	)); ?>
<?php echo $form->submit(__d('api_generator', 'Search', true), array('div' => false, 'class' => 'submit')); ?>
</fieldset>
<?php echo $form->end(null); ?>
</div>