<?php
	/**
	 * Infinitas controller bake template
	 *
	 * This is the file that is used to bake the controllers when using infinitas skel
	 *
	 * Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 *
	 * @filesource
	 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * @link http://www.infinitas-cms.org
	 * @package bake
	 * @subpackage bake.classes.controller
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.7a
	 *
	 * @author Carl Sutton ( dogmatic69 )
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */

	echo "<?php\n";
		echo "\tclass {$controllerName}Controller extends {$plugin}AppController {\n";
			echo "\t\tvar \$name = '$controllerName';\n\n";

			echo "\t\tvar \$helpers = array(\n";
				echo "\t\t\t'Html', 'Form', 'Session',\n";
				echo "\t\t\t'Filter.Filter',\n";
				if (count($helpers)){
					for ($i = 0, $len = count($helpers); $i < $len; $i++){
						echo "'" . Inflector::camelize($helpers[$i]) . "', ";
					}
				}
				echo "\t\t\t'$plugin.$plugin',\n";
				echo "\t\t\t//'Libs.Design',\n";
				echo "\t\t\t//'Libs.Gravatar',\n";
				echo "\t\t\t//'Libs.Infinitas',\n";
				echo "\t\t\t//'Libs.Slug'\n";
			echo "\t\t);\n\n";

			if (count($components)){
				echo "\t\tvar \$components = array(";
				for ($i = 0, $len = count($components); $i < $len; $i++){
					if ($i != $len - 1){
						echo "\t\t\t'" . Inflector::camelize($components[$i]) . "',\n";
					}
					else{
						echo "\t\t\t'" . Inflector::camelize($components[$i]) . "'\n";
					}
				}
				echo "\t\t);\n";
			}

			echo $actions;
		echo "\t}\n";
	echo "?>";
?>