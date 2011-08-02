<?php
/**
 * Test Case bake template
 *
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2009, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright	 Copyright 2005-2009, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link		  http://cakephp.org CakePHP(tm) Project
 * @package	   cake
 * @subpackage	cake.console.libs.templates.objects
 * @since		 CakePHP(tm) v 1.3
 * @license	   MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
$extraSetup = array();
$extraSetupOut = '';

$extraMethods = array();
$extraMethodsOut = '';

$methodTemplate = <<<METHOD
		public function test%s() {
			
		}
METHOD;

$fixturesTemplate = <<<FIXTURES
			'fixtures' => array(
				'do' => array(
					%s
				)
			)
FIXTURES;

if(!empty($fixtures)) {
	foreach($fixtures as $fixture) {
		$parts = explode('.', $fixture);
		
		if($parts[0] == 'plugin' && count($parts) == 3) {
			$extraSetup['fixtures'][] = Inflector::classify($parts[1]) . '.' . Inflector::classify($parts[2]);
		}
	}
}

if($type == 'Model') {
	$extraMethods[] = sprintf($methodTemplate, 'Validation');
}

if(!empty($methods)) {	
	foreach($methods as $method) {
		$extraMethods[] = sprintf($methodTemplate, Inflector::classify($method));
	}
}

$testCase = <<<TESTCASE
<?php
	App::import('lib', 'App%sTestCase');
	
	class Test$fullClassName extends App%sTestCase {
		public \$setup = array(
			'%s' => '$plugin$className'%s
		);%s
	}
TESTCASE;

if(!empty($extraMethods)) {
	$extraMethodsOut = "\n\n" . implode("\n\n", $extraMethods);
}

if(!empty($extraSetup['fixtures'])) {
	$out = sprintf($fixturesTemplate, implode(",\n\t\t\t\t\t", array_map(create_function('$v', 'return "\'" . $v."\'";'), $extraSetup['fixtures'])));
	
	$extraSetupOut .= ",\n" . $out;
}

echo sprintf($testCase, $type, $type, strtolower($type), $extraSetupOut, $extraMethodsOut);