<?php
	if(!isset($plugin) || !$plugin || $plugin == 'contact'){
		$plugin = $this->plugin;
	}
	if(!isset($model) || !$model){
		$model = $this->request->params['models'][0];
	}

	if(!isset($countries) || !$countries){
		$countries = ClassRegistry::init('Contact.ContactAddress')->Country->find('list');
	}

	$addressSelect = (isset($addressSelect)) ?(bool)$addressSelect : false;
?>
<fieldset>
	<h1><?php echo __('Address'); ?></h1><?php
	echo $this->Form->hidden('ContactAddress.id');

	$options = ClassRegistry::init('Contact.ContactAddress')->getAddressesByRelated(
		array(
			'ContactAddress.plugin' => $plugin,
			'ContactAddress.model' => $model
		)
	);

	if($addressSelect){
		echo $this->Form->input($model . '.address_id', array('options' => $options, 'label' => __('Use existing'), 'empty' => Configure::read('Website.empty_select')));
	}

	echo $this->Form->hidden('ContactAddress.plugin', array('value' => $plugin));
	echo $this->Form->hidden('ContactAddress.model', array('value' => $model));
	echo $this->Form->hidden('ContactAddress.foreign_key');

	echo $this->Form->input('ContactAddress.name');
	echo $this->Form->input('ContactAddress.street');
	echo $this->Form->input('ContactAddress.city');
	echo $this->Form->input('ContactAddress.province');
	echo $this->Form->input('ContactAddress.postal');
	echo $this->Form->input('ContactAddress.continent_id', array('empty' => Configure::read('Website.empty_select'), 'options' => Configure::read('Contact.continents')));
	echo $this->Form->input('ContactAddress.country_id', array('empty' => Configure::read('Website.empty_select'), 'options' => $countries)); ?>
</fieldset>