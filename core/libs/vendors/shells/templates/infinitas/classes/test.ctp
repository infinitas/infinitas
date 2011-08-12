<?php
/**
 * @brief Test case bake template
 *
 * @copyright Copyright (c) 2010 Jelle Henkens
 * @link http://www.infinitas-cms.org
 * @package Infinitas.Libs
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.9
 *
 * @author jellehenkens
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 */

//Setup variables and templates
$extraSetup = array();
$extraSetupOut = '';

$extraMethods = array();
$extraMethodsOut = '';

$methodTemplate = <<<METHOD
		/**
		 * @brief Tests %s
		 *
		 * @test Enter description here
		 */
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

$testCase = <<<TESTCASE
<?php
	App::import('lib', 'libs.test/App%sTest');
	
	class Test$fullClassName extends App%sTestCase {

		/**
		 * @brief Configuration for the test case
		 *
		 * Loading fixtures:
		 * 
		 * List all the needed fixtures in the do part of the fixture array.
		 * In replace you can overwrite fixtures of other plugins by your own.
		 *
		 * 'fixtures' => array(
		 *		'do' => array(
		 *			'SomePlugin.SomeModel
		 *		),
		 *		'replace' => array(
		 *			'Core.User' => 'SomePlugin.User
		 *		)
		 * )
		 * @var array 
		 */
		public \$setup = array(
			'%s' => '$plugin$className'%s
		);
		
		/**
		 * @brief Contains a list of test methods to run
		 *
		 * If it is set to false all the methods will run. Otherwise pass in an array
		 * with a list of tests to run.
		 *
		 * @var mixed 
		 */
		public \$tests = false;%s
	}
TESTCASE;

if($type == 'Behavior') {
	App::import('Core', 'ModelBehavior');
}

//Get the class methods
if(in_array($type, array('Helper', 'Controller', 'Component', 'Behavior'))) {
	$excluded = array('initialize', 'startup', 'beforeFilter', 'afterFilter', 'beforeSave', 'afterSave',
		'beforeValidate', 'beforeDelete', 'afterDelete', 'beforeFind', 'afterFind');
	App::import($type, $plugin.$className);
	
	$reflection = new ReflectionClass($fullClassName);
	$classMethods = array_filter($reflection->getMethods(), create_function('$v', 'return $v->class == "'.$fullClassName.'" && substr($v->name, 0, 1) != "_";'));
	$classMethods = array_map(create_function('$v', 'return $v->name;'), $classMethods);
	$classMethods = array_diff($classMethods, $excluded);
}

//Add fixtures
if(!empty($fixtures)) {
	foreach($fixtures as $fixture) {
		$parts = explode('.', $fixture);
		
		if($parts[0] == 'plugin' && count($parts) == 3) {
			$extraSetup['fixtures'][] = Inflector::classify($parts[1]) . '.' . Inflector::classify($parts[2]);
		}
	}
}

//Add default methods
if($type == 'Model') {
	$extraMethods[] = sprintf($methodTemplate, 'Validation', 'Validation');
}

//Add auto added methods
if(isset($classMethods) && !empty($classMethods)) {	
	foreach($classMethods as $method) {
		$extraMethods[] = sprintf($methodTemplate, $method, Inflector::classify($method));
	}
}

//Build templates
if(!empty($extraMethods)) {
	$extraMethodsOut = "\n\n" . implode("\n\n", $extraMethods);
}

if(!empty($extraSetup['fixtures'])) {
	$out = sprintf($fixturesTemplate, implode(",\n\t\t\t\t\t", array_map(create_function('$v', 'return "\'" . $v."\'";'), $extraSetup['fixtures'])));
	
	$extraSetupOut .= ",\n" . $out;
}

echo sprintf($testCase, $type, $type, strtolower($type), $extraSetupOut, $extraMethodsOut);