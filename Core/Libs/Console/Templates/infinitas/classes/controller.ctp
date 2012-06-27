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

	$parentController = $plugin . 'AppController';
	$year = date('Y');

	@$username = trim(`eval whoami`);
	$username ? $username : '{username}';

	$version = Configure::read('Infinitas.version');

	echo <<<COMMENT
<?php
/**
 * $controllerName controller
 *
 * @brief Add some documentation for $controllerName controller.
 *
 * @copyright Copyright (c) 2009 Carl Sutton (dogmatic69)
 *
 * @link		  http://infinitas-cms.org/$plugin
 * @package	   $plugin.Controller
 * @license	   http://infinitas-cms.org/mit-license The MIT License
 * @since $version
 *
 * @author $username
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 */

class {$controllerName}Controller extends $parentController {

COMMENT;

		echo <<<COMMENT
/**
 * The helpers linked to this controller
 *
 * @access public
 * @var array
 */

COMMENT;
		echo "\tpublic \$helpers = array(\n";
			if (count($helpers)) {
				for ($i = 0, $len = count($helpers); $i < $len; $i++) {
					echo "'" . Inflector::camelize($helpers[$i]) . "', ";
				}
			}
			echo "\t\t//'$plugin.$plugin', // uncoment this for a custom plugin controller\n";
			echo "\t\t//'Libs.Gravatar',\n";
		echo "\t);\n\n";

		if (count($components)) {
	echo <<<COMMENT
/**
 * The components linked to this controller
 *
 * @access public
 * @var array
 */

COMMENT;
			echo "\tpublic \$components = array(";
			for ($i = 0, $len = count($components); $i < $len; $i++) {
				if ($i != $len - 1) {
					echo "\t\t'" . Inflector::camelize($components[$i]) . "',\n";
				}
				else{
					echo "\t\t'" . Inflector::camelize($components[$i]) . "'\n";
				}
			}
			echo "\t);\n";
		}

		if(trim($actions) == 'scaffold') {
			echo "\tpublic \$scaffold;\n";
		}
		else{
			echo $actions;
		}
	echo "}";