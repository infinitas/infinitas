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

	$possibleFileFields = array('file', 'image');

	echo "<?php\n".
		"\t/**\n".
		"\t * $name model\n".
		"\t *\n".
		"\t * Add some documentation for $name model.\n".
		"\t *\n".
		"\t * Copyright (c) {yourName}\n".
		"\t *\n".
		"\t * Licensed under The MIT License\n".
		"\t * Redistributions of files must retain the above copyright notice.\n".
		"\t *\n".
		"\t * @filesource\n".
		"\t * @copyright     Copyright (c) 2009 {yourName}\n".
		"\t * @link          http://infinitas-cms.org\n".
		"\t * @package       $plugin\n".
		"\t * @subpackage    $plugin.models.$name\n".
		"\t * @license       http://www.opensource.org/licenses/mit-license.php The MIT License\n".
		"\t */\n\n".

		"\tclass $name extends {$plugin}AppModel {\n".
		"\t\tvar \$name = '$name';\n";

		if ($useDbConfig != 'default'){
			echo "\t\tvar \$useDbConfig = '$useDbConfig';\n";
		}

		if ($useTable && $useTable !== Inflector::tableize($name)){
			echo "\t\tvar \$useTable = '$useTable';\n";
		}


		if ($primaryKey !== 'id'){
			echo "\t\tvar \$primaryKey = '$primaryKey';\n";
		}

		if ($displayField){
			echo "\t\tvar \$displayField = '$displayField';\n";
		}

		echo "\t\tvar \$actsAs = array(\n";
			foreach ($schema as $field => $data){
				switch($field){
					case 'ordering':
						echo
							"\t\t\t'Libs.Sequence' => array(\n".
								"\t\t\t\t'group_fields' => array(\n".
									"\t\t\t\t\t/* Add parent field here */\n".
								"\t\t\t\t)\n".
							"\t\t\t),\n";
						break;

					case 'slug':
						echo "\t\t\t'Libs.Sluggable' => array(\n";
							if ($displayField) {
								echo "\t\t\t\t'label' => array('$displayField')\n";
							}
						echo "\t\t\t),\n";
						break;

					case 'deleted':
						echo "\t\t\t'Libs.SoftDeletable',\n";
						break;

					case 'views':
						echo "\t\t\t'Libs.Viewable',\n";
						break;

					case 'lft':
						echo "\t\t\t'Tree',\n";
						$_order = "\t\t'{$name}.lft' => 'ASC'\n";
						break;

					case 'image':
						echo "\t\t\t'MeioUpload.MeioUpload' => array(\n".
							"\t\t\t\t'image' => array(\n".
								"\t\t\t\t\t'dir' => 'img{DS}content{DS}thinkmoney{DS}creditcards{DS}{ModelName}',\n".
								"\t\t\t\t\t'create_directory' => true,\n".
								"\t\t\t\t\t'allowed_mime' => array(\n".
									"\t\t\t\t\t\t'image/jpeg',\n".
									"\t\t\t\t\t\t'image/pjpeg',\n".
									"\t\t\t\t\t\t'image/png'\n".
								"\t\t\t\t\t),\n".
								"\t\t\t\t\t'allowed_ext' => array(\n".
									"\t\t\t\t\t\t'.jpg',\n".
									"\t\t\t\t\t\t'.jpeg',\n".
									"\t\t\t\t\t\t'.png'\n".
								"\t\t\t\t\t)\n".
							"\t\t\t\t)\n".
						"\t\t\t),\n";
						break;

					case 'file':
						echo "\t\t\t'MeioUpload.MeioUpload' => array(\n".
							"\t\t\t\t'image' => array(\n".
								"\t\t\t\t\t'dir' => 'img{DS}content{DS}thinkmoney{DS}creditcards{DS}{ModelName}',\n".
								"\t\t\t\t\t'create_directory' => true,\n".
								"\t\t\t\t\t'allowed_mime' => array(\n".
									"\t\t\t\t\t\t/** add mime types */\n".
								"\t\t\t\t\t),\n".
								"\t\t\t\t\t'allowed_ext' => array(\n".
									"\t\t\t\t\t\t/** add extentions */\n".
								"\t\t\t\t\t)\n".
							"\t\t\t\t)\n".
						"\t\t\t),\n";
						break;

				} // switch
			} // end foreach

			echo
				"\t\t\t// 'Libs.Feedable',\n".
				"\t\t\t// 'Libs.Commentable',\n".
				"\t\t\t// 'Libs.Rateable\n";
		echo "\t\t);\n\n"; //end actsAs

		echo "\t\tvar \$order = array(\n".
			$_order.
		"\t\t);\n\n";

		foreach (array('hasOne', 'belongsTo') as $assocType){
			echo "\t\tvar \$$assocType = array(\n";
				if ($assocType == 'belongsTo') {
					echo $_belongsTo;
				}
				if (!empty($associations[$assocType])){
					$typeCount = count($associations[$assocType]);

					foreach ($associations[$assocType] as $i => $relation){
						$out = "\t\t\t'{$relation['alias']}' => array(\n";
						$out .= "\t\t\t\t'className' => '{$relation['className']}',\n";
						$out .= "\t\t\t\t'foreignKey' => '{$relation['foreignKey']}',\n";
						$out .= "\t\t\t\t'conditions' => '',\n";
						$out .= "\t\t\t\t'fields' => '',\n";
						$out .= "\t\t\t\t'order' => '',\n";
						if ($assocType == 'belongsTo') {
							$relatedSchema = ClassRegistry::init($relation['className'])->_schema;
							$relatedCounterCache = false;
							$relatedActive = false;
							$relatedDeleted = false;
							foreach($relatedSchema as $field => $data){
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
								$out .= "\t\t\t\t'counterCache' => true,\n";
							}
							if ($relatedActive || $relatedDeleted) {
								$out .= "\t\t\t\t'counterScope' => array(\n";
									if ($relatedActive) {
										$out .= "\t\t\t\t\t'{$name}.active' => 1\n";
									}
									if ($relatedDeleted) {
										$out .= "\t\t\t\t\t'{$name}.deleted' => 1\n";
									}
								$out .= "\t\t\t\t),\n";
							}
						}
						$out .= "\t\t\t)";
						if ($i + 1 < $typeCount) {
							$out .= ",\n";
						}
						else{
							$out .= "\n";
						}
						echo $out;
					}
				}
			echo "\t\t);\n\n";
		}

		echo "\t\tvar \$hasMany = array(\n";
			if (!empty($associations['hasMany'])){
				$belongsToCount = count($associations['hasMany']);

				foreach ($associations['hasMany'] as $i => $relation){
					$out = "\t\t\t'{$relation['alias']}' => array(\n";
					$out .= "\t\t\t\t'className' => '{$relation['className']}',\n";
					$out .= "\t\t\t\t'foreignKey' => '{$relation['foreignKey']}',\n";
					$out .= "\t\t\t\t'dependent' => false,\n";
					$out .= "\t\t\t\t'conditions' => '',\n";
					$out .= "\t\t\t\t'fields' => '',\n";
					$out .= "\t\t\t\t'order' => '',\n";
					$out .= "\t\t\t\t'limit' => '',\n";
					$out .= "\t\t\t\t'offset' => '',\n";
					$out .= "\t\t\t\t'exclusive' => '',\n";
					$out .= "\t\t\t\t'finderQuery' => '',\n";
					$out .= "\t\t\t\t'counterQuery' => ''\n";
					$out .= "\t\t\t)";
					if ($i + 1 < $belongsToCount) {
						$out .= ",\n";
					}
					else{
						$out .= "\n";
					}
					echo $out;
				}
			}
		echo "\t\t);\n\n";

		echo "\t\tvar \$hasAndBelongsToMany = array(\n";
			if (!empty($associations['hasAndBelongsToMany'])){
				$habtmCount = count($associations['hasAndBelongsToMany']);

				foreach ($associations['hasAndBelongsToMany'] as $i => $relation){
					$out = "\t\t\t'{$relation['alias']}' => array(\n";
					$out .= "\t\t\t\t'className' => '{$relation['className']}',\n";
					$out .= "\t\t\t\t'joinTable' => '{$relation['joinTable']}',\n";
					$out .= "\t\t\t\t'foreignKey' => '{$relation['foreignKey']}',\n";
					$out .= "\t\t\t\t'associationForeignKey' => '{$relation['associationForeignKey']}',\n";
					$out .= "\t\t\t\t'unique' => true,\n";
					$out .= "\t\t\t\t'conditions' => '',\n";
					$out .= "\t\t\t\t'fields' => '',\n";
					$out .= "\t\t\t\t'order' => '',\n";
					$out .= "\t\t\t\t'limit' => '',\n";
					$out .= "\t\t\t\t'offset' => '',\n";
					$out .= "\t\t\t\t'finderQuery' => '',\n";
					$out .= "\t\t\t\t'deleteQuery' => '',\n";
					$out .= "\t\t\t\t'insertQuery' => ''\n";
					$out .= "\t\t\t)";
					if ($i + 1 < $habtmCount) {
						$out .= ",\n";
					}
					else{
						$out .= "\n";
					}
					echo $out;
				}
			}
		echo "\t\t);\n\n";

		echo "\t\tfunction __construct(\$id = false, \$table = null, \$ds = null) {\n";
			echo "\t\t\tparent::__construct(\$id, \$table, \$ds);\n\n";

			echo "\t\t\t\$this->validate = array(\n";
				if (!empty($validate)){
					foreach ($validate as $field => $validations){
						if (in_array($field, $validationIgnore) || preg_match('/[a-z_]+_count/i', $field)) {
							continue;
						}
						echo "\t\t\t\t'$field' => array(\n";
						foreach ($validations as $key => $validator){
							echo "\t\t\t\t\t'$key' => array(\n";
							echo "\t\t\t\t\t\t'rule' => array('$validator'),\n";
							echo "\t\t\t\t\t\t//'message' => 'Your custom message here',\n";
							echo "\t\t\t\t\t\t//'allowEmpty' => false,\n";
							echo "\t\t\t\t\t\t//'required' => false,\n";
							echo "\t\t\t\t\t\t//'last' => false, // Stop validation after this rule\n";
							echo "\t\t\t\t\t\t//'on' => 'create', // Limit validation to 'create' or 'update' operations\n";
							echo "\t\t\t\t\t),\n";
						}
						echo "\t\t\t\t),\n";
					}
				}
			echo "\t\t\t);\n";
		echo "\t\t}\n";

		echo "\t}\n";
	echo '?>';
?>