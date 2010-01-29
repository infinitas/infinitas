<?php
/**
 * Api Docs Helper
 * 
 * Wraps common docs pages view functions
 *
 * PHP 5.2+
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2009, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2009, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org
 * @package       api_generator
 * @subpackage    api_generator.views.helpers
 * @since         ApiGenerator 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 **/
class ApiDocHelper extends AppHelper {
/**
 * Helpers used by ApiDocHelper
 *
 * @var array
 */
	public $helpers = array('Html');
/**
 * internal basePath used when browsing files. and making links to them
 *
 * @var string
 **/
	protected $_basePath;
/**
 * default Urls
 *
 * @var array
 **/
	protected $_defaultUrl = array(
		'file' => array(
			'controller' => 'api_files',
			'action' => 'view_file',
			'plugin' => 'api_generator',
		),
		'class' => array(
			'controller' => 'api_classes',
			'action' => 'view_class',
			'plugin' => 'api_generator',
		),
		'package' => array(
			'controller' => 'api_packages',
			'action' => 'view',
			'plugin' => 'api_generator'
		)
	);
/**
 * pattern for finding Class::method() type links.
 *
 * @var string
 **/
	protected $_methodLinkPattern = '/([A-Z-_0-9]+)\:\:([A-Z-_0-9]+)\(\)/i';
/**
 * classList 
 *
 * @var array
 **/
	protected $_classList = array();
/**
 * Constructor.
 *
 * @return void
 **/
	public function __construct($config = array()) {
		$view = ClassRegistry::getObject('view');
		$this->setBasePath($view->getVar('basePath'));
	}

/**
 * set the basePath
 *
 * @return void
 **/
	public function setBasePath($path) {
		$this->_basePath = Folder::slashTerm(realpath($path));
	}

/**
 * inBasePath
 * 
 * Check if a filename is within the ApiGenerator.basePath path
 *
 * @return boolean
 **/
	function inBasePath($filename) {
		return (strpos($filename, $this->_basePath) !== false);
	}

/**
 * Create a link to a filename if it is in the basePath
 *
 * @param string $filename Name of file to make link to.
 * @param array $url Additional url params you want for some reason.
 * @param array $attributes attributes for link if one is generated.
 * @return string either a link or plain text depending on files location relative to basepath
 **/
	public function fileLink($filename, $url = array(), $attributes = array()) {
		$url = array_merge($this->_defaultUrl['file'], $url);
		$trimmedFile = $this->trimFileName($filename);
		if (!empty($trimmedFile) && $trimmedFile != $filename) {
			$url[] = $trimmedFile;
			return $this->Html->link($trimmedFile, $url, $attributes);
		}
		return $filename;
	}
/**
 * trim the basePath from a filename so it can be used in links
 *
 * @return string $filename Filename to trim basepath from
 * @return string trimmed filename
 **/
	public function trimFileName($filename) {
		if ($this->inBasePath($filename)) {
			return str_replace($this->_basePath, '', $filename);
		}
		$realPath = $this->_searchBasePath($filename);
		if ($this->inBasePath($realPath)) {
			return $this->trimFileName($realPath);
		}
		return $filename;
	}

/**
 * Will break a filename up and scan through the basePath for 
 * any possible matches.
 *
 * @return string Adjusted path.
 **/
	protected function _searchBasePath($filename) {
		$pathBits = explode(DS, $filename);
		$currentPath = $testPath = $this->_basePath;
		while (!empty($pathBits)) {
			$pathSegment = array_shift($pathBits);
			if (empty($pathSegment)) {
				continue;
			}
			$testPath .= $pathSegment;
			if (count($pathBits)) {
				$testPath .= DS;
			}
			if (is_dir($testPath) || file_exists($testPath)) {
				$currentPath = $testPath;
			} else {
				$testPath = $currentPath;
			}
		}
		return $currentPath;
	}

/**
 * Set the Class list so that linkClassName will know which classes are in the index.
 *
 * @param array $classList The list of classes to use when making links.
 * @return void
 **/
	public function setClassIndex($classList) {
		$this->_classList = $classList;
	}

/**
 * Check if a class is in the classIndex
 *
 * @param string $className The class 
 * @return boolean
 **/
	public function inClassIndex($className) {
		return in_array($className, $this->_classList);
	}

/**
 * Create a link to a class name if it exists in the classList
 *
 * @param string $className the class name you wish to make a link to
 * @param array $url A url array to override defaults
 * @param array $attributes Additional attributes for an html link if generated.
 * @return string Html link or plaintext
 **/
	public function classLink($className, $url = array(), $attributes = array()) {
		$url = array_merge($this->_defaultUrl['class'], $url);
		$listFlip = array_flip($this->_classList);
		if (array_key_exists($className, $listFlip)) {
			$url[] = $listFlip[$className];
			return $this->Html->link($className, $url, $attributes);
		}
		return $className;
	}
/**
 * Parses the text and converts any supported markup syntax
 *
 * @return void
 **/
	public function parseText($text) {
		$text = preg_replace_callback($this->_methodLinkPattern, array($this, '_parseMethodLink'), $text);
		return $text;
	}
/**
 * Parse out the method links
 *
 * @return void
 **/
	protected function _parseMethodLink($matches) {
		$listFlip = array_flip($this->_classList);
		if (array_key_exists($matches[1], $listFlip)) {
			$link = array($this->slug($matches[1]), '#' => 'method-' . $matches[1] . $matches[2]);
			$url = array_merge($this->_defaultUrl['class'], $link);
			return $this->Html->link($matches[0], $url);
		}
		return $matches[0];
	}
/**
 * Check the access string against the excluded method access.
 *
 * @param string $accessString name of the accessString you are checking ie. public
 * @param string $type Type of access to check, (method or property)
 * @return boolean
 **/
	public function excluded($accessString, $type) {
		$view = ClassRegistry::getObject('view');
		$accessName = Inflector::variable('exclude_' . Inflector::pluralize($type));
		$exclusions = $view->getVar($accessName);
		if (in_array($accessString, $exclusions)) {
			return true;
		}
		return false;
	}

/**
 * Slugs a classname to match the format in the database.
 *
 * @param string $className Name of class to sluggify.
 * @return string
 **/
	public function slug($className) {
		return str_replace('_', '-', Inflector::underscore($className));
	}

/**
 * Create a nested inheritance tree from an array.
 * Uses an array stack like a tree. So
 *     array('foo', 'bar', 'baz')
 * will create a tree like
 *  * foo
 *  ** bar
 *  *** baz
 *
 * @param array $parents Array of parents you want to make into a tree
 * @return string
 **/
	public function inheritanceTree($parents) {
		$out = $endTags = '';
		$totalParents = count($parents);
		foreach ($parents as $i => $class) {
			$htmlClass = 'parent-class';
			if ($i == $totalParents - 1) {
				$htmlClass .= ' last';
			}
			$out .= '<span class="' . $htmlClass . '">' . $this->classLink($class) . "</span>\n";
		}
		return '<p class="inheritance-tree">' . $out . '</p>';
	}

/**
 * Generate an HTML tree structure out of a package Index tree.
 *
 * @param array $packageTree Array of package tree from find(threaded)
 * @return string Formatted HTML
 **/
	public function generatePackageTree($packageTree, $depth = 0) {
		$out = '<ul class="package-tree depth-'. $depth . '">' . "\n";
		foreach ($packageTree as $branch) {
			$children = null;
			$link = $this->packageLink($branch['ApiPackage']['name']);
			if (!empty($branch['children'])) {
				$depth++;
				$children = $this->generatePackageTree($branch['children'], $depth);
			}
			$out .= sprintf("\t<li class=\"package\"><span>%s</span> %s</li>\n", $link, $children);
		}
		$out .= "</ul>\n";
		return $out;
	}

/**
 * Create a link to a package
 *
 * @param string $package The package name you want to link to.
 * @param array $url A url array to override defaults
 * @param array $attributes Additional attributes for an html link if generated.
 * @return string Html link
 **/
	public function packageLink($package, $url = array(), $attributes = array()) {
		$url = array_merge($this->_defaultUrl['package'], $url);
		$slug = $this->slug($package);
		if (strpos($slug, '.') !== false) {
			$slug = $this->_parsePackageString($slug);
		}
		$url[] = $slug;
		return $this->Html->link($package, $url, $attributes);
	}
/**
 * parse a dot split package string and return the last package element.
 *
 * @param string $package A package string with .'s in it.
 * @return string
 **/
	protected function _parsePackageString($package) {
		$bits = explode('.', $package);
		return $this->slug(end($bits));
	}

}