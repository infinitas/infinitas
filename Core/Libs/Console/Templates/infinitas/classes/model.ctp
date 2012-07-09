<?php
	$class = implode('.', array($plugin, $name));
	$schema = ClassRegistry::init($class)->_schema;

	$validationIgnore = array(
		'slug', 'views', 'ordering', 'active',
		'locked', 'locked_by', 'locked_since',
		'deleted', 'deleted_date',
		'created_by', 'modified_by'
	);

	$_order = $_belongsTo = null;

	$parentModel = $plugin . 'AppModel';

	$possibleFileFields = array('file', 'image');
	$year = date('Y');

	@$username = trim(`eval whoami`);
	$username ? $username : '{username}';

	$version = Configure::read('Infinitas.version');

	$output = <<<COMMENT
<?php
/**
 * $name model
 *
 * @brief Add some documentation for $name model.
 *
 * @copyright Copyright (c) 2009 Carl Sutton (dogmatic69)
 *
 * @link		  http://infinitas-cms.org/$plugin
 * @package	   $plugin.Model
 * @license	   http://infinitas-cms.org/mit-license The MIT License
 * @since $version
 *
 * @author $username
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 */

class $name extends $parentModel {

COMMENT;
		if ($useDbConfig != 'default') {
		$output .= <<<COMMENT
/**
 * The database config to use
 *
 * @access public
 * @var string
 */

COMMENT;
			$output .= "\tpublic \$useDbConfig = '$useDbConfig';\n\n";
		}

		if ($useTable && $useTable !== Inflector::tableize($name)) {
		$output .= <<<COMMENT
/**
 * The table that the model is using
 *
 * @access public
 * @var string
 */

COMMENT;
			$output .= "\tpublic \$useTable = '$useTable';\n\n";
		}

		if ($primaryKey !== 'id') {
		$output .= <<<COMMENT
/**
 * The primary key of the table
 *
 * @access public
 * @var string
 */

COMMENT;
			$output .= "\tpublic \$primaryKey = '$primaryKey';\n\n";
		}

		if ($displayField) {
		$output .= <<<COMMENT
/**
 * The display field for select boxes
 *
 * @access public
 * @var string
 */

COMMENT;
			$output .= "\tpublic \$displayField = '$displayField';\n\n";
		}

		if(in_array('views', array_keys($schema))) {
		$output .= <<<COMMENT
/**
 * Set to true if you would like to track view counts
 *
 * @access public
 * @var string
 */

COMMENT;
			$output .= "\tpublic \$viewable = true;\n\n";
		}

		$output .= <<<COMMENT
/**
 * Additional behaviours that are attached to this model
 *
 * @access public
 * @var array
 */

COMMENT;
		$output .= "\tpublic \$actsAs = array(\n";

		echo $output;
			foreach ($schema as $field => $data) {
				switch($field) {
					case 'deleted':
						echo "\t\t'Libs.SoftDeletable',\n";
						break;

					case 'image':
						echo "\t\t'MeioUpload.MeioUpload' => array(\n".
							"\t\t\t'image' => array(\n".
								"\t\t\t\t'dir' => 'img{DS}content{DS}$plugin{DS}images{DS}{ModelName}',\n".
								"\t\t\t\t'create_directory' => true,\n".
								"\t\t\t\t'allowed_mime' => array(\n".
									"\t\t\t\t\t'image/jpeg',\n".
									"\t\t\t\t\t'image/pjpeg',\n".
									"\t\t\t\t\t'image/png'\n".
								"\t\t\t\t),\n".
								"\t\t\t\t'allowed_ext' => array(\n".
									"\t\t\t\t\t'.jpg',\n".
									"\t\t\t\t\t'.jpeg',\n".
									"\t\t\t\t\t'.png'\n".
								"\t\t\t\t)\n".
							"\t\t\t)\n".
						"\t\t),\n";
						break;

					case 'file':
						echo "\t\t'MeioUpload.MeioUpload' => array(\n".
							"\t\t\t'image' => array(\n".
								"\t\t\t\t'dir' => 'img{DS}content{DS}$plugin{DS}files{DS}{ModelName}',\n".
								"\t\t\t\t'create_directory' => true,\n".
								"\t\t\t\t'allowed_mime' => array(\n".
									"\t\t\t\t\t/** add mime types */\n".
								"\t\t\t\t),\n".
								"\t\t\t\t'allowed_ext' => array(\n".
									"\t\t\t\t\t/** add extentions */\n".
								"\t\t\t\t)\n".
							"\t\t\t)\n".
						"\t\t),\n";
						break;

					case 'lft':
						$_order = "\t'{$name}.lft' => 'ASC'\n";
						break;

				} // switch
			} // end foreach

			echo
				"\t\t// 'Libs.Feedable',\n".
				"\t\t// 'Libs.Rateable'\n";
		echo "\t);\n\n"; //end actsAs

		echo <<<COMMENT
/**
 * How the default ordering on this model is done
 *
 * @access public
 * @var array
 */

COMMENT;
		echo "\tpublic \$order = array(\n".
			$_order.
		"\t);\n\n";

		foreach (array('hasOne', 'belongsTo') as $assocType) {
		echo <<<COMMENT
/**
 * $assocType relations for this model
 *
 * @access public
 * @var array
 */

COMMENT;
			echo "\tpublic \$$assocType = array(\n";
				if ($assocType == 'belongsTo') {
					echo $_belongsTo;
				}
				if (!empty($associations[$assocType])) {
					$typeCount = count($associations[$assocType]);

					foreach ($associations[$assocType] as $i => $relation) {
						$out = "\t\t'{$relation['alias']}' => array(\n";
						$out .= "\t\t\t'className' => '{$relation['className']}',\n";
						$out .= "\t\t\t'foreignKey' => '{$relation['foreignKey']}',\n";
						$out .= "\t\t\t'conditions' => '',\n";
						$out .= "\t\t\t'fields' => '',\n";
						$out .= "\t\t\t'order' => '',\n";
						if ($assocType == 'belongsTo') {
							$relatedSchema = ClassRegistry::init($relation['className'])->_schema;
							$relatedCounterCache = false;
							$relatedActive = false;
							$relatedDeleted = false;
							foreach($relatedSchema as $field => $data) {
								if (strstr($field, '_count')) {
									$relatedCounterCache = true;
								}

								if ($field == 'active') {
									$relatedActive = true;
								}

								else if ($field == 'active') {
									$relatedDeleted = true;
								}
							}

							if ($relatedCounterCache) {
								$out .= "\t\t\t'counterCache' => true,\n";
							}

							if ($relatedActive || $relatedDeleted) {
								$out .= "\t\t\t'counterScope' => array(\n";
									if ($relatedActive) {
										$out .= "\t\t\t\t'{$name}.active' => 1\n";
									}

									if ($relatedDeleted) {
										$out .= "\t\t\t\t'{$name}.deleted' => 1\n";
									}
								$out .= "\t\t\t),\n";
							}
						}
						$out .= "\t\t)";

						if ($i + 1 < $typeCount) {
							$out .= ",\n";
						}

						else{
							$out .= "\n";
						}

						echo $out;
					}
				}
			echo "\t);\n\n";
		}

		echo <<<COMMENT
/**
 * hasMany relations for this model
 *
 * @access public
 * @var array
 */

COMMENT;

		echo "\tpublic \$hasMany = array(\n";
			if (!empty($associations['hasMany'])) {
				$belongsToCount = count($associations['hasMany']);

				foreach ($associations['hasMany'] as $i => $relation) {
					$out = "\t\t'{$relation['alias']}' => array(\n";
					$out .= "\t\t\t'className' => '{$relation['className']}',\n";
					$out .= "\t\t\t'foreignKey' => '{$relation['foreignKey']}',\n";
					$out .= "\t\t\t'dependent' => false,\n";
					$out .= "\t\t\t'conditions' => '',\n";
					$out .= "\t\t\t'fields' => '',\n";
					$out .= "\t\t\t'order' => '',\n";
					$out .= "\t\t\t'limit' => '',\n";
					$out .= "\t\t\t'offset' => '',\n";
					$out .= "\t\t\t'exclusive' => '',\n";
					$out .= "\t\t\t'finderQuery' => '',\n";
					$out .= "\t\t\t'counterQuery' => ''\n";
					$out .= "\t\t)";
					if ($i + 1 < $belongsToCount) {
						$out .= ",\n";
					}
					else{
						$out .= "\n";
					}
					echo $out;
				}
			}
		echo "\t);\n\n";


		echo <<<COMMENT
/**
 * hasAndBelongsToMany relations for this model
 *
 * @access public
 * @var array
 */

COMMENT;

		echo "\tpublic \$hasAndBelongsToMany = array(\n";
			if (!empty($associations['hasAndBelongsToMany'])) {
				$habtmCount = count($associations['hasAndBelongsToMany']);

				foreach ($associations['hasAndBelongsToMany'] as $i => $relation) {
					$out = "\t\t'{$relation['alias']}' => array(\n";
					$out .= "\t\t\t'className' => '{$relation['className']}',\n";
					$out .= "\t\t\t'joinTable' => '{$relation['joinTable']}',\n";
					$out .= "\t\t\t'foreignKey' => '{$relation['foreignKey']}',\n";
					$out .= "\t\t\t'associationForeignKey' => '{$relation['associationForeignKey']}',\n";
					$out .= "\t\t\t'unique' => true,\n";
					$out .= "\t\t\t'conditions' => '',\n";
					$out .= "\t\t\t'fields' => '',\n";
					$out .= "\t\t\t'order' => '',\n";
					$out .= "\t\t\t'limit' => '',\n";
					$out .= "\t\t\t'offset' => '',\n";
					$out .= "\t\t\t'finderQuery' => '',\n";
					$out .= "\t\t\t'deleteQuery' => '',\n";
					$out .= "\t\t\t'insertQuery' => ''\n";
					$out .= "\t\t)";
					if ($i + 1 < $habtmCount) {
						$out .= ",\n";
					}
					else{
						$out .= "\n";
					}
					echo $out;
				}
			}
		echo "\t);\n\n";


		echo <<<COMMENT
/**
 * overload the construct method so that you can use translated validation
 * messages.
 *
 * @access public
 *
 * @param mixed \$id string uuid or id
 * @param string \$table the table that the model is for
 * @param string \$ds the datasource being used
 *
 * @return void
 */

COMMENT;
		echo "\tpublic function __construct(\$id = false, \$table = null, \$ds = null) {\n";
			echo "\t\tparent::__construct(\$id, \$table, \$ds);\n\n";

			echo "\t\t\$this->validate = array(\n";
				if (!empty($validate)) {
					foreach ($validate as $field => $validations) {
						if (in_array($field, $validationIgnore) || preg_match('/[a-z_]+_count/i', $field)) {
							continue;
						}
						echo "\t\t\t'$field' => array(\n";
						foreach ($validations as $key => $validator) {
							echo "\t\t\t\t'$key' => array(\n";
							echo "\t\t\t\t\t'rule' => array('$validator'),\n";
							echo "\t\t\t\t\t//'message' => 'Your custom message here',\n";
							echo "\t\t\t\t\t//'allowEmpty' => false,\n";
							echo "\t\t\t\t\t//'required' => false,\n";
							echo "\t\t\t\t\t//'last' => false, // Stop validation after this rule\n";
							echo "\t\t\t\t\t//'on' => 'create', // Limit validation to 'create' or 'update' operations\n";
							echo "\t\t\t\t),\n";
						}
						echo "\t\t\t),\n";
					}
				}
			echo "\t\t);\n";
		echo "\t}\n\n";

		echo <<<COMMENT
/**
 * General method for the view pages. Gets the required data and relations
 * and can be used for the admin preview also.
 *
 * @param array \$conditions conditions for the find
 * @return array the data that was found
 */

COMMENT;
		echo "\tpublic function getViewData(\$conditions = array()) {\n";
			echo "\t\tif(!\$conditions) {\n";
				echo "\t\t\treturn false;\n";
			echo "\t\t}\n\n";

			echo "\t\t\$data = \$this->find(\n";
				echo "\t\t\t'first',\n";
				echo "\t\t\tarray(\n";
					echo "\t\t\t\t'fields' => array(\n";
					echo "\t\t\t\t),\n";
					echo "\t\t\t\t'conditions' => \$conditions,\n";
					echo "\t\t\t\t'contain' => array(\n";
					echo "\t\t\t\t)\n";
				echo "\t\t\t)\n";
			echo "\t\t);\n\n";

			echo "\t\treturn \$data;\n";
		echo "\t}\n";

		echo "}\n";