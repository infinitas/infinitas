<?php
	/**
	 * Custom bake view add / edit
	 *
	 * creates a custom views for infinitas.
	 *
	 * Copyright (c) 2009 Carl Sutton ( dogmatic69 )
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 *
	 * @filesource
	 * @copyright	 Copyright (c) 2009 Carl Sutton ( dogmatic69 )
	 * @link		  http://infinitas-cms.org
	 * @package	   Shell
	 * @subpackage	Shell.templates.views.index
	 * @license	   http://www.opensource.org/licenses/mit-license.php The MIT License
	 *
	 * availabel vars
	 * ===================

	 [directory] => views
	 [filename] => form
	 [vars] =>
	 [themePath] => c:\xampp\htdocs\thinkmoney\vendors\shells\templates\infinitas\
	 [templateFile] => c:\xampp\htdocs\thinkmoney\vendors\shells\templates\infinitas\views\form.ctp
	 [action] => admin_add
	 [plugin] => compare
	 [modelClass] => Supplier
	 [schema] => Array
	 [primaryKey] => id
	 [displayField] => name
	 [singularVar] => supplier
	 [pluralVar] => suppliers
	 [singularHumanName] => Supplier
	 [pluralHumanName] => Suppliers
	 [fields] => Array
	 [associations] => array
	 */

	$ignore = array(
		$primaryKey,
		'locked', 'locked_by', 'locked_since',
		'deleted', 'deleted_date',
		'created', 'modified', 'updated',

		'views', 'slug',

		'lft', 'rght', 

		'active', 'deleted'
	);

	$configs = array(
		'active'
	);
	
	$year = date('Y');

	@$username = trim(`eval whoami`);
	$username ? $username : '{username}';

	$version = Configure::read('Infinitas.version');

	echo<<<COMMENT
<?php
	/**
	 * @brief Add some documentation for this $action form.
	 *
	 * @copyright Copyright (c) 2009 Carl Sutton (dogmatic69)
	 *
	 * @link		  http://infinitas-cms.org/$plugin
	 * @package	   $plugin.views.$action
	 * @license	   http://infinitas-cms.org/mit-license The MIT License
	 * @since $version
	 *
	 * @author $username
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */

COMMENT;

	$possibleFileFields = array('file', 'image');

	$fileUpload = '';

	foreach ($fields as $field) {
		if (in_array($field, $possibleFileFields)) {
			$fileUpload = ", array('type' => 'file')";
		}
	}

	echo "\techo \$this->Form->create('{$modelClass}'{$fileUpload});\n".
		"\t\techo \$this->Infinitas->adminEditHead(); ?>\n";
		echo "\t\t<fieldset>\n" .
			"\t\t\t<h1><?php echo __('" . prettyName($modelClass) . "'); ?></h1><?php\n";
				echo "\t\t\t\techo \$this->Form->input('$primaryKey');\n";
				$end = '';
				foreach ($fields as $field) {
					if (!empty($associations['belongsTo'])) {
						foreach ($associations['belongsTo'] as $alias => $details) {
							if ($field === $details['foreignKey']) {
								if (in_array($field, array('address_id'))) {
									continue;
								}
								$configs[] = $field;
								continue;
							}
						}
					}

					if (!in_array($field, $ignore) && !in_array($field, $configs) && (str_replace('_count', '', $field) == $field)) {
						$emptyOption = '';
						switch($schema[$field]['type']) {
							case 'text':
								$end .= "\t\t\t\techo \$this->Infinitas->wysiwyg('{$modelClass}.{$field}');\n";
								break;

							case in_array($field, $possibleFileFields):
								echo "\t\t\t\techo \$this->Form->input('{$field}'{$fileUpload});\n";
								break;

							default:
								if(strstr($field, '_id')) {
									$emptyOption = ', array(\'empty\' => Configure::read(\'Website.empty_select\'))';
								}
								echo "\t\t\t\techo \$this->Form->input('{$field}'{$emptyOption});\n";
								break;
						} // switch
					}
				}
				echo $end;
			echo "\t\t\t?>\n";
		echo "\t\t</fieldset>\n\n";

		echo "\t\t<fieldset>\n" .
			"\t\t\t<h1><?php echo __('Configuration'); ?></h1><?php\n";
				foreach ($fields as $field) {
					$emptyOption = '';
					if (in_array($field, $configs)) {
						if(strstr($field, '_id')) {
							$emptyOption = ', array(\'empty\' => Configure::read(\'Website.empty_select\'))';
						}
						echo "\t\t\t\techo \$this->Form->input('{$field}'{$emptyOption});\n";
					}
				}

				if (!empty($associations['hasAndBelongsToMany'])) {
					foreach ($associations['hasAndBelongsToMany'] as $assocName => $assocData) {
						echo "\t\t\t\techo \$this->Form->input('{$assocName}');\n";
					}
				}
			echo "\t\t?>\n";
		echo "\t\t</fieldset><?php\n";
	echo "\techo \$this->Form->end();\n";