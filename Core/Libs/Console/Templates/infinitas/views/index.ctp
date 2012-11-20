<?php
	/**
	 * Custom bake index view
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
	 * Available vars
	 * ============================
	 *
	 [directory] => views
	 [filename] => index
	 [vars] =>
	 [themePath] => c:\xampp\htdocs\thinkmoney\vendors\shells\templates\infinitas\
	 [templateFile] => c:\xampp\htdocs\thinkmoney\vendors\shells\templates\infinitas\views\index.ctp
	 [action] => admin_index
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
	 [associations] => Array
	 */

	$ignore = array(
		'id', 'slug', 'password', // general fields
		'locked', 'locked_by', 'locked_since', // lockable
		'deleted', 'deleted_date', // soft delete
		'slug',
		'lft', 'rght' // mptt fields
	);

	foreach($fields as $field) {
		$check = $schema[$field]['type'] == 'text' || (
			$field == 'created' && in_array('modifeid', $fields)
		);
		if ($check) {
			$ignore[] = $field;
		}
	}

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
 * @package	   $plugin.View.$action
 * @license	   http://infinitas-cms.org/mit-license The MIT License
 * @since $version
 *
 * @author $username
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 */

COMMENT;
	echo "\necho \$this->Form->create(null, array('action' => 'mass'));\n" .
		"echo \$this->Infinitas->adminIndexHead(\$filterOptions, array(\n" .
			"\t'add',\n" .
			"\t'edit',\n" .
			"\t'toggle',\n" .
			"\t'copy',\n" .
			"\t'delete',\n\n" .
		"echo ));\n" .
		"echo \$this->Filter->alphabetFilter();\n" .
	"?>\n";
?>
<table class="listing">
	<?php
		echo "<?php\n" .
			"\t\t\techo \$this->Infinitas->adminTableHeader(\n" .
				"\t\t\t\tarray(\n" .
					"\t\t\t\t\t\$this->Form->checkbox('all') => array(\n" .
						"\t\t\t\t\t\t'class' => 'first',\n" .
					"\t\t\t\t\t),\n";
					$endFields = '';
					foreach($fields as $field ) {
						if (in_array($field, $ignore)) {
							continue;
						}

						$isKey = false;
						if (!empty($associations['belongsTo'])) {
							foreach ($associations['belongsTo'] as $alias => $details) {
								if ($field === $details['foreignKey']) {
									$isKey = true;
									echo "\t\t\t\t\t\$this->Paginator->sort('{$alias}.{$details['displayField']}', '{$alias}'),\n";
									break;
								}
							}
						}
						if ($isKey !== true) {
							switch($field) {
								case 'created':
								case 'modified':
									$endFields .= "\t\t\t\t\t\$this->Paginator->sort('{$field}') => array(\n" .
										"\t\t\t\t\t\t'style' => 'width:75px;'\n" .
									"\t\t\t\t\t),\n";
									break;

								case 'active':
								case 'locked':
									$endFields = "\t\t\t\t\t\$this->Paginator->sort('{$field}') => array(\n" .
										"\t\t\t\t\t\t'style' => 'width:50px;'\n" .
									"\t\t\t\t\t),\n" .$endFields;
									break;

								case str_replace('_count', '', $field) != $field:
									$name = Inflector::humanize(Inflector::pluralize(Inflector::underscore(str_replace('_count', '', $field))));
									echo "\t\t\t\t\t\$this->Paginator->sort('{$field}', '{$name}') => array(\n" .
										"\t\t\t\t\t\t'style' => 'width:50px;'\n" .
									"\t\t\t\t\t),\n";
									break;

								default:
									echo "\t\t\t\t\t\$this->Paginator->sort('{$field}'),\n";
							} // switch
						}
					}
					echo $endFields;
				echo "\t\t\t\t)\n" .
			"\t\t\t);\n\n" .

			"\t\t\tforeach (\${$pluralVar} as \${$singularVar}) { ?>\n" .
				"\t\t\t\t<tr>\n" .
					"\t\t\t\t\t<td><?php echo \$this->Infinitas->massActionCheckBox(\${$singularVar}); ?>&nbsp;</td>\n";

					$endFields = '';
					foreach ($fields as $field) {
						if (in_array($field, $ignore)) {
							continue;
						}

						$isKey = false;
						if (!empty($associations['belongsTo'])) {
							foreach ($associations['belongsTo'] as $alias => $details) {
								if ($field === $details['foreignKey']) {
									$isKey = true;
									echo "\t\t\t\t\t<td>\n\t\t\t\t\t\t<?php echo \$this->Html->link(\${$singularVar}['{$alias}']['{$details['displayField']}'], array('controller' => '{$details['controller']}', 'action' => 'view', \${$singularVar}['{$alias}']['{$details['primaryKey']}'])); ?>\n\t\t\t\t\t</td>\n";
									break;
								}
							}
						}
						if ($isKey !== true) {
							switch($field) {
								case 'created':
								case 'modified':
									$endFields .= "\t\t\t\t\t<td><?php echo \$this->Infinitas->date(\${$singularVar}['{$modelClass}']); ?>&nbsp;</td>\n";
									break;

								case 'active':
									$endFields = "\t\t\t\t\t<td><?php echo \$this->Infinitas->status(\${$singularVar}['{$modelClass}']['{$field}']); ?>&nbsp;</td>\n" .$endFields;
									break;

								case 'locked':
									$endFields = "\t\t\t\t\t<td><?php echo \$this->Locked->display(\${$singularVar}, '{$modelClass}'); ?>&nbsp;</td>\n" .$endFields;
									break;

								case 'email':
									echo "\t\t\t\t\t<td><?php echo \$this->Text->autoLinkEmails(\${$singularVar}['{$modelClass}']['{$field}']); ?>&nbsp;</td>\n" .$endFields;
									break;

								case 'ordering':
									echo "\t\t\t\t\t<td><?php echo \$this->{$plugin}->ordering(\${$singularVar}['{$modelClass}']['{$primaryKey}'], \${$singularVar}['{$modelClass}']['ordering']); ?>&nbsp;</td>\n";
									break;


								case $displayField:
									$title = '';
									switch(in_array('slug', $fields)) {
										case true:
											$title = " title=\"<?php echo \${$singularVar}['{$modelClass}']['slug']; ?>\"";

										default:
											echo "\t\t\t\t\t<td{$title}><?php echo \$this->Html->adminQuickLink(\${$singularVar}['{$modelClass}']); ?>&nbsp;</td>\n";
									}
									break;

								default:
									echo "\t\t\t\t\t<td><?php echo \${$singularVar}['{$modelClass}']['{$field}']; ?>&nbsp;</td>\n";
							}
						}
					}
					echo $endFields;
				echo "\t\t\t\t</tr><?php\n" .
			"\t\t\t}\n" .
		"\t\t?>\n"
	?>
</table>
<?php
	echo "<?php\n";
	echo "\techo \$this->Form->end();\n";
	echo "\techo \$this->element('pagination/admin/navigation');" ?>