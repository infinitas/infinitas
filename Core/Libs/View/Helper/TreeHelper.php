<?php
/**
 * TreeHelper
 *
 * @package Infinitas.Libs.Helper
 */

/**
 * TreeHelper
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.Libs.Helper
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.7a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

class TreeHelper extends AppHelper {
/**
 * Settings
 *
 * @var array
 */
	public $settings = array();

/**
 * The default settings that are overloadable by calling TreeHelper::settings()
 *
 * @var array
 */
	public $defaults = array(
		'model' => false,
		'left'=> 'lft',
		'right' => 'rght',
		'primaryKey' => 'id',
		'parent' => 'parent_id'
	);

/**
 * Contains the tree that will be iterated
 *
 * @var array
 */
	public $data = array();

/**
 * Contains the information for the current selected node
 *
 * @var array
 */
	public $node = array(
		'root' => false,
		'depth' => 0,
		'hasChildren' => 0,
		'firstChild' => false,
		'lastChild' => false
	);

/**
 * Internal stack variable that contains the right values
 *
 * @var array
 */
	private $__stack = array();

/**
 * Contains the key of the current node in the data array
 *
 * @var int
 */
	private $__i = 0;

/**
 * This function has to be called to initialise the helper for iteration
 *
 * settings:
 *  - data: The data array ordered by left (required)
 *  - model: The model alias used in the data (required)
 *  - left: Name of the left field, defaults to 'lft'
 *  - right: Name of the right field, defaults to 'rght'
 *  - parent: Name of the parent field, defaults to 'parent_id'
 *  - primaryKey: Name of the primary key field, defaults to 'id'
 *
 * @param array $settings A list of options (see above)
 *
 * @return void
 */
	public function settings($settings) {
		$this->__resetData();

		$this->data = $settings['data'];
		unset($settings['data']);

		$this->settings = array_merge($this->defaults, $settings);
	}

/**
 * Get the node info for the next record
 *
 * Loads the node information for the next node in the loop. This method has to
 * be called the moment you enter a new loop. It will return the node info.
 *
 * @return array
 */
	public function tick() {
		if(count($this->__stack) > 0) {
			while($this->__stack && $this->__stack[count($this->__stack) - 1] < $this->__field('right')) {
				array_pop($this->__stack);
			}
		}

		//Setting usefull data
		$this->node['depth'] = count($this->__stack);
		$this->node['root'] = empty($this->__stack) ? true : false;
		$this->node['hasChildren'] = $this->__field('left') != $this->__field('right') - 1;
		$this->node['firstChild'] = $this->isFirstChild();
		$this->node['lastChild'] = $this->isLastChild();
		//$this->node['hasChildren'] = ($this->__field('right') - $this->__field('left') - 1) / 2;

		$this->__stack[] = $this->__field('right');

		$this->__i++;

		return $this->nodeInfo();
	}

/**
 * Checks if the currently selected node is the first child in its tree.
 *
 * @return boolean
 */
	public function isFirstChild() {
		$firstChild = false;

		if(!isset($this->data[$this->__i - 1]) || $this->__field('left', $this->__i - 1) == $this->__field('left') - 1) {
			$firstChild = true;
		}

		return $firstChild;
	}

/**
 * Checks if the currently selected node is the last child in its tree.
 *
 * @return boolean
 */
	public function isLastChild() {
		return !isset($this->data[$this->__i + 1]) ||
			(!$this->__stack && $this->__field('right') >= $this->__field('right', count($this->data) - 1)) ||
			($this->__stack && $this->__stack[count($this->__stack) - 1] == $this->__field('right', $this->__i) + 1);
	}

/**
 * Returns all the node information of the currently active node in the loop.
 *
 * @return array
 */
	public function nodeInfo() {
		return $this->node;
	}

/**
 * Get the value of a field
 *
 * Returns the value of a field for the selected node. It defaults to the curent active one.
 * To get the field of a different node you have to pass the $i parameter.
 *
 * @param string $field The name of the field to fetch
 * @param type $i Optional, the node number to fetch
 *
 * @return string
 */
	private function __field($field, $i = false) {
		if($i === false) {
			$i = $this->__i;
		}

		return $this->data[$i][$this->settings['model']][$this->settings[$field]];
	}

/**
 * Resets all the data, settings etc so we can print out multiple trees
 *
 * @return void
 */
	private function __resetData() {
		$this->data = array();
		$this->__stack = array();
		$this->__i = 0;
	}

}