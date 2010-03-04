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
	 * @copyright     Copyright (c) 2009 Carl Sutton ( dogmatic69 )
	 * @link          http://infinitas-cms.org
	 * @package       Shell
	 * @subpackage    Shell.templates.views.index
	 * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
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
		'id',
		'locked_by', 'locked_since',
		'password',
		'deleted', 'deleted_date',
		'slug'
	);

	foreach($fields as $field){
		if ($schema[$field]['type'] == 'text') {
			$ignore[] = $field;
		}
	}

	echo "<?php\n".
		"\t/**\n".
		"\t * $modelClass index\n".
		"\t *\n".
		"\t * Add some documentation for $modelClass.\n".
		"\t *\n".
		"\t * Copyright (c) {yourName}\n".
		"\t *\n".
		"\t * Licensed under The MIT License\n".
		"\t * Redistributions of files must retain the above copyright notice.\n".
		"\t *\n".
		"\t * @filesource\n".
		"\t * @copyright     Copyright (c) 2009 {yourName}\n".
		"\t * @link          http://infinitas-cms.org\n".
		"\t * @package       $modelClass\n".
		"\t * @subpackage    $modelClass.views.$pluralVar.index\n".
		"\t * @license       http://www.opensource.org/licenses/mit-license.php The MIT License\n".
		"\t */\n\n".
		"\techo \$this->Form->create( '$modelClass', array( 'url' => array( 'controller' => '".Inflector::pluralize(Inflector::underscore($modelClass))."', 'action' => 'mass', 'admin' => 'true' ) ) );\n\n".
		"\t\$massActions = \$this->Infinitas->massActionButtons(\n".
			"\t\tarray(\n".
				"\t\t\t'add',\n".
				"\t\t\t'edit',\n".
				"\t\t\t'toggle',\n".
				"\t\t\t'copy',\n".
				"\t\t\t'delete'\n".
			"\t\t)\n".
		"\t);\n\n".
		"\techo \$this->Infinitas->adminIndexHead( \$this, \$paginator, \$filterOptions, \$massActions );\n".
	"?>\n";
?>
<div class="table">
    <table class="listing" cellpadding="0" cellspacing="0">
        <?php
			echo "<?php\n".
	            "\t\t\techo \$this->Infinitas->adminTableHeader(\n".
	                "\t\t\t\tarray(\n".

	                    "\t\t\t\t\t\$this->Form->checkbox( 'all' ) => array(\n".
	                        "\t\t\t\t\t\t'class' => 'first',\n".
	                        "\t\t\t\t\t\t'style' => 'width:25px;'\n".
	                    "\t\t\t\t\t),\n";
		                $endFields = '';
		                foreach($fields as $field ){
		                	if (in_array($field, $ignore)) {
		                		continue;
		                	}

							$isKey = false;
							if (!empty($associations['belongsTo'])) {
								foreach ($associations['belongsTo'] as $alias => $details) {
									if ($field === $details['foreignKey']) {
										$isKey = true;
										echo "\t\t\t\t\t\$this->Paginator->sort('{$alias}, {$alias}.{$details['displayField']}'),\n";
										break;
									}
								}
							}
							if ($isKey !== true) {

								switch($field){
									case 'created':
									case 'modified':
					                    $endFields .= "\t\t\t\t\t\$this->Paginator->sort( '{$field}' ) => array(\n".
					                        "\t\t\t\t\t\t'style' => 'width:75px;'\n".
					                    "\t\t\t\t\t),\n";
										break;

									case 'active':
									case 'locked':
										$endFields = "\t\t\t\t\t\$this->Paginator->sort( '{$field}' ) => array(\n".
					                        "\t\t\t\t\t\t'style' => 'width:50px;'\n".
					                    "\t\t\t\t\t),\n".$endFields;
										break;

									case str_replace('_count', '', $field) != $field:
										$name = Inflector::humanize(Inflector::pluralize(Inflector::underscore(str_replace('_count', '', $field))));
										echo "\t\t\t\t\t\$this->Paginator->sort('$name', '{$field}') => array(\n".
					                        "\t\t\t\t\t\t'style' => 'width:50px;'\n".
					                    "\t\t\t\t\t),\n";
										break;

									default:
					                    echo "\t\t\t\t\t\$this->Paginator->sort('{$field}'),\n";
								} // switch
							}
		                }
						echo $endFields;
	                echo "\t\t\t\t)\n".
	            "\t\t\t);\n".

				"\t\t\tforeach (\${$pluralVar} as \${$singularVar}){ ?>\n".
					"\t\t\t\t<tr class=\"<?php echo \$this->Infinitas->rowClass(); ?>\">\n".
						"\t\t\t\t\t<td><?php echo \$this->Form->checkbox( \${$singularVar}['{$modelClass}']['id'] ); ?>&nbsp;</td>\n";

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
								switch($field){
									case $displayField && in_array('slug', $fields):
										echo "\t\t\t\t\t<td title=\"<?php echo \${$singularVar}['{$modelClass}']['{$field}']; ?>\"><?php echo \${$singularVar}['{$modelClass}']['{$field}']; ?>&nbsp;</td>\n";
										break;

									case 'email':
										echo "\t\t\t\t\t<td><?php echo \$this->Text->autoLinkEmails(\${$singularVar}['{$modelClass}']['{$field}']); ?>&nbsp;</td>\n".$endFields;
										break;

									case 'created':
									case 'modified':
										$endFields .= "\t\t\t\t\t<td><?php echo \$this->Time->niceShort(\${$singularVar}['{$modelClass}']['{$field}']); ?>&nbsp;</td>\n";
										break;

									case 'active':
										$endFields = "\t\t\t\t\t<td><?php echo \$this->Infinitas->status(\${$singularVar}['{$modelClass}']['{$field}'], \${$singularVar}['{$modelClass}']['id']); ?>&nbsp;</td>\n".$endFields;
										break;

									case 'locked':
										$endFields = "\t\t\t\t\t<td><?php echo \$this->Infinitas->locked(\${$singularVar}, '{$modelClass}'); ?>&nbsp;</td>\n".$endFields;
										break;

									default:
										echo "\t\t\t\t\t<td><?php echo \${$singularVar}['{$modelClass}']['{$field}']; ?>&nbsp;</td>\n";
								} // switch
							}
						}
						echo $endFields;
					echo "\t\t\t\t</tr><?php\n".
				"\t\t\t}\n".
			"\t\t?>\n"
		?>
	</table>
	<?php echo "<?php echo \$this->Form->end(); ?>\n"; ?>
</div>
<?php echo "<?php echo \$this->element( 'pagination/navigation' ); ?>" ?>