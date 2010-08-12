<?php
	/**
	 * This class implements lazy loading models for CakePHP 1.3.
	 *
	 * It has full compatibility, which means all of CakePHP's core test cases pass with this. Not only the Model test
	 * cases, but also the behavior test cases including Containable's tests.
	 *
	 * Installation is simple. Clone this plugin to your plugins directory in your application (or clone as a git
	 * submodule). Then make your AppModel extend this class and that is it:
	 *
	 * <?php
	 * App::import('Lib', 'LazyModel.LazyModel');
	 * class AppModel extends LazyModel {
	 * }
	 * ?>
	 *
	 * Issues can be posted at the GitHub page. Read the Q&A before posting though!
	 *
	 * Inspiration from:
	 * - http://github.com/mcurry/lazy_loader
	 * - http://bin.cakephp.org/saved/39855
	 *
	 * @author Frank de Graaf (Phally)
	 * @link http://www.frankdegraaf.net/
	 * @link http://github.com/phally/lazy_model/
	 * @license MIT
	 */
	abstract class LazyModel extends Model {

		/**
		 * Holds a map of aliases with their classnames, so it can lookup a classname when lazy loading it. It can't use
		 * the model association properties, because the plugin names are stripped away there when using plugin models.
		 *
		 * @var array
		 * @access private
		 */
		private $map = array();

		/**
		 * Overrides the Model constructor to make an inventory of models it can use lazy loading on.
		 *
		 * @param mixed $id Set this ID for this model on startup, can also be an array of options, see Model::__construct().
		 * @param string $table Name of database table to use.
		 * @param string $ds DataSource connection name.
		 * @return void
		 * @access public
		 */
		public function __construct($id = false, $table = null, $ds = null) {
			foreach ($this->__associations as $type) {
				foreach ((array)$this->{$type} as $key => $properties) {
					if ($type != 'hasAndBelongsToMany') {
						$this->map($key, $properties);
					} elseif (isset($properties['with'])) {
						$this->map($key, $properties);
						$this->map(0, (is_array($properties['with'])) ? key($properties['with']) : $properties['with']);
					}
				}
			}
			parent::__construct($id, $table, $ds);
		}

		/**
		 * Overrides Model::__constructLinkedModel() so it won't instantiate the entire model chain. It will only
		 * instantiate the HABTM associations that use an automodel, because there are two models needed to figure
		 * out what the name is of the join model. To avoid this, use your own join model using `with` or don't use
		 * HABTM at all.
		 *
		 * @param string $assoc Association name.
		 * @param string $className Class name.
		 * @return void
		 * @access public
		 */
		public function __constructLinkedModel($assoc, $className = null) {
			if (!isset($this->map[$assoc])) {
				parent::__constructLinkedModel($assoc, $className);
			}
		}

		/**
		 * Magic method to check whether a propery/model already exists or if it is mapped for lazy loading.
		 *
		 * @param string $alias Name of the property.
		 * @return boolean The property is set or will be set after lazy loading.
		 */
		public function __isset($alias) {
			return property_exists($this, $alias) || isset($this->map[$alias]);
		}

		/**
		 * Magic method which instantiates a model when it is called and when it is mapped for lazy loading.
		 *
		 * @param string $alias Name of the property.
		 * @return mixed Value of the property.
		 */
		public function &__get($alias) {
			if (!property_exists($this, $alias) && isset($this->map[$alias])) {
				$this->constructLazyLinkedModel($alias, $this->map[$alias]);
			}
			return $this->{$alias};
		}

		/**
		 * Creates a model.
		 *
		 * @param string $assoc Association name.
		 * @param string $className Class name.
		 * @return void
		 * @access private
		 */
		private function constructLazyLinkedModel($assoc, $className = null) {
			if (empty($className)) {
				$className = $assoc;
			}
			$this->{$assoc} = ClassRegistry::init(array('class' => $className, 'alias' => $assoc));
			if (strpos($className, '.') !== false) {
				ClassRegistry::addObject($className, $this->{$assoc});
			}
			if ($assoc) {
				$this->tableToModel[$this->{$assoc}->table] = $assoc;
			}
		}

		/**
		 * Maps a model for lazy loading. Examples:
		 *
		 * - array(0 => 'PluginName.Model')
		 * - array('ModelAlias' => array('className' => 'PluginName.Model'))
		 *
		 * @param mixed $key Key in the associations array, could be string or numeric.
		 * @param mixed $properties Properties of the association, could be array or string.
		 * @return array Mapped alias and its properties.
		 * @access private
		 */
		private function map($key, $properties) {
			list($alias, $properties) = $return = $this->properties($key, $properties);
			if (isset($this->map[$alias])) {
				list($plugin, $model) = $this->pluginSplit($this->map[$alias]);
				if ($alias != $model) {
					$this->map[$alias] = $properties['className'];
				}
			} else {
				$this->map[$alias] = $properties['className'];
			}
			return $return;
		}

		/**
		 * Formats model associations.
		 *
		 * @param mixed $key Key in the associations array, could be string or numeric.
		 * @param mixed $properties Properties of the association, could be array or string.
		 * @return array Formatted alias and its properties.
		 * @access private
		 */
		private function properties($key, $properties) {
			if (is_numeric($key)) {
				list($plugin, $alias) = $this->pluginSplit($properties);
				$properties = array('className' => $properties);
			} else {
				$alias = $key;
				if (!isset($properties['className'])) {
					$properties['className'] = $alias;
				}
			}
			return array($alias, $properties);
		}

		/**
		 * Overrides Model::bindModel() so it will use lazy loading too.
		 *
		 * @param array $params Set of bindings (indexed by binding type)
		 * @param boolean $reset Set to false to make the binding permanent
		 * @return boolean Success
		 * @access public
		 */
		public function bindModel($models, $reset = true) {
			foreach ($models as $type => $data) {
				foreach ($data as $key => $properties) {
					list($alias, $properties) = $this->map($key, $properties);
					if (property_exists($this, $alias)) {
						unset($this->{$alias});
					}
				}
			}
			return parent::bindModel($models, $reset);
		}

		/**
		 * Splits plugin name and class. For CakePHP 1.2 compatibility.
		 *
		 * @param string $name The name you want to plugin split.
		 * @param boolean $dotAppend Set to true if you want the plugin to have a '.' appended to it.
		 * @param string $plugin Optional default plugin to use if no plugin is found. Defaults to null.
		 * @return array Array with 2 indexes.  0 => plugin name, 1 => classname.
		 * @access private
		 */
		private function pluginSplit($name, $dotAppend = false, $plugin = null) {
			if (function_exists('pluginSplit')) {
				return pluginSplit($name, $dotAppend, $plugin);
			}
			if (strpos($name, '.') !== false) {
				$parts = explode('.', $name, 2);
				if ($dotAppend) {
					$parts[0] .= '.';
				}
				return $parts;
			}
			return array($plugin, $name);
		}
	}