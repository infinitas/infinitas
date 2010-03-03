<?php
	/**
	* generate the index code
	*/
	echo "\t\tfunction {$admin}index() {\n";
		echo "\t\t\t\$this->{$currentModelName}->recursive = 1;\n";
		echo "\t\t\t\$$pluralName = \$this->paginate(null, \$this->Filter->filter);\n\n";

		echo "\t\t\t\$filterOptions = \$this->Filter->filterOptions;\n";
		echo "\t\t\t\$filterOptions['fields'] = array(\n";
			foreach ($modelObj->belongsTo as $associationName => $relation){
				// @todo check the relations and add field_id to the list with a find
				// like 'layout_id' => $this->Content->Layout->find('list'),
			}
			echo "\t\t\t\t'active' => (array)Configure::read('CORE.active_options')\n";
		echo "\t\t\t);\n\n";
		echo "\t\t\t\$this->set(compact('$pluralName','filterOptions'));\n";
	echo "\t\t}\n\n";

	/**
	 * generate the view code
	 */
	echo "\t\tfunction {$admin}view(\$id = null) {\n";
		echo "\t\t\tif(!\$slug){\n";
			echo "\t\t\t\t\$this->Session->setFlash(sprintf(__('Invalid %s', true), '".strtolower($singularHumanName)."'));\n";
			echo "\t\t\t\t\$this->redirect(array('action' => 'index'));\n";
		echo "\t\t\t}\n\n";

		echo "\t\t\t\${$pluralName} = \$this->{$currentModelName}->read(null, \$id);\n";
		echo "\t\t\t\$this->set(compact('$pluralName'));\n";
	echo "\t\t}\n\n";

	/**
	 * generate the add code
	 */
	echo "\t\tfunction {$admin}add() {\n";
		echo "\t\t\tif (!empty(\$this->data)) {\n";
			echo "\t\t\t\t\$this->{$currentModelName}->create();\n";
			echo "\t\t\t\tif(\$this->{$currentModelName}->saveAll(\$this->data)){\n";
				echo "\t\t\t\t\t\$this->Session->setFlash(sprintf(__('The %s has been saved', true), '".strtolower($singularHumanName)."'));\n";
				echo "\t\t\t\t\t\$this->redirect(array('action' => 'index'));\n";
			echo "\t\t\t\t}\n"; // saveAll
			echo "\t\t\t\telse {\n";
				echo "\t\t\t\t\t\$this->Session->setFlash(sprintf(__('The %s could not be saved. Please, try again', true), '".strtolower($singularHumanName)."'));\n";
			echo "\t\t\t\t}\n"; // else
		echo "\t\t\t}\n"; // if empty

		$compact = array();
		foreach (array('belongsTo', 'hasAndBelongsToMany') as $assoc){
			foreach ($modelObj->{$assoc} as $associationName => $relation){
				if (!empty($associationName)){
					$otherModelName  = $this->_modelName($associationName);
					$otherPluralName = $this->_pluralName($associationName);

					echo "\t\t\t\${$otherPluralName} = \$this->{$currentModelName}->{$otherModelName}->find('list');\n";
					$compact[] = "'{$otherPluralName}'";
				}
			}
		}

		if (!empty($compact)){
			echo "\t\t\t\$this->set(compact(".join(', ', $compact)."));\n";
		}
	echo "\t\t}\n\n";

	/**
	 * generate the edit code
	 */
	echo "\t\tfunction {$admin}edit(\$id = null) {\n";
		echo "\t\t\tif (!\$id && empty(\$this->data)) {\n";
			echo "\t\t\t\t\t\$this->Session->setFlash(sprintf(__('Invalid %s', true), '".strtolower($singularHumanName)."'));\n";
		echo "\t\t\t}\n\n"; // if empty and !$id


		echo "\t\t\tif (!empty(\$this->data)) {\n";
			echo "\t\t\t\tif(\$this->{$currentModelName}->saveAll(\$this->data)){\n";
				echo "\t\t\t\t\t\$this->Session->setFlash(sprintf(__('The %s has been saved', true), '".strtolower($singularHumanName)."'));\n";
				echo "\t\t\t\t\t\$this->redirect(array('action' => 'index'));\n";
			echo "\t\t\t\t}\n"; // saveAll
			echo "\t\t\t\telse {\n";
				echo "\t\t\t\t\t\$this->Session->setFlash(sprintf(__('The %s could not be saved. Please, try again', true), '".strtolower($singularHumanName)."'));\n";
			echo "\t\t\t\t}\n"; // else
		echo "\t\t\t}\n\n"; // if empty

		echo "\t\t\tif (empty(\$this->data)) {\n";
			echo "\t\t\t\t\t\$this->data = \$this->{$currentModelName}->read(null, \$id);\n";
		echo "\t\t\t}\n"; // if empty and !$id


		$compact = array();
		foreach (array('belongsTo', 'hasAndBelongsToMany') as $assoc){
			foreach ($modelObj->{$assoc} as $associationName => $relation){
				if (!empty($associationName)){
					$otherModelName  = $this->_modelName($associationName);
					$otherPluralName = $this->_pluralName($associationName);

					echo "\t\t\t\${$otherPluralName} = \$this->{$currentModelName}->{$otherModelName}->find('list');\n";
					$compact[] = "'{$otherPluralName}'";
				}
			}
		}

		if (!empty($compact)){
			echo "\t\t\t\$this->set(compact(".join(', ', $compact)."));\n";
		}
	echo "\t\t}\n";

?>