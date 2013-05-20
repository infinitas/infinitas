<?php
echo $this->Form->input('ModuleConfig.diable_cache', array(
	'type' => 'checkbox',
	'label' => __d('modules', 'Dont cache this module'),
	'default' => 0
));
echo $this->Form->input('ModuleConfig.cache_key', array(
	'type' => 'text',
	'label' => __d('modules', 'Module cache key'),
	'placeholder' => __d('modules', 'The modules id')
));

$config = array();
foreach (Cache::configured() as $cache) {
	$config[$cache] = Inflector::humanize($cache);
}
asort($config);
echo $this->Form->input('ModuleConfig.cache_config', array(
	'type' => 'select',
	'label' => __d('modules', 'Module cache config'),
	'empty' => __d('modules', 'Default: plugins cache config'),
	'options' => $config
));