<?php

	class TreeHelper extends AppHelper {
	
		public $settings = array();
		
		public $defaults = array(
			'data' => array(),
			'model' => false,
			'left'=> 'lft',
			'right' => 'rght',
			'primaryKey' => 'id',
			'parent' => 'parent_id'
		);
		
		private $__stack = array();
		
		private $__i = 0;
		
		public $node = array(
			'root' => false, //False or True
			'depth' => 0,
			'hasChildren' => 0,
			'firstChild' => false,
			'lastChild' => false
		);
		
		/**
		 * settings:
		 *  - data: The data array ordered by left
		 *  - model: The model alias used in the data
		 *  - left: Name of the left field
		 *  - right: Name of the right field
		 * @param type $settings 
		 */
		public function settings($settings) {
			$this->settings = array_merge($this->defaults, $settings);
			
			$this->__resetData();
		}
		
		public function tick() {
			if(count($this->__stack) > 0) {
				while($this->__stack[count($this->__stack) - 1] < $this->__field('right')) {
					array_pop($this->__stack);
				}
			}

			//Setting usefull data			
			$this->node['depth'] = count($this->__stack);
			$this->node['root'] = !$this->__field('parent') ? true : false;
			$this->node['hasChildren'] = $this->__field('left') != $this->__field('right') - 1;
			$this->node['firstChild'] = $this->isFirstChild();
			$this->node['lastChild'] = $this->isLastChild();
			//$this->node['hasChildren'] = ($this->__field('right') - $this->__field('left') - 1) / 2;
			
			$this->__stack[] = $this->__field('right');
			
			$this->__i++;
			
			return $this->nodeInfo();
		}
		
		public function isFirstChild() {
			$firstChild = false;

			if(!isset($this->settings['data'][$this->__i - 1]) || $this->__field('left', $this->__i - 1) == $this->__field('left') - 1) {
				$firstChild = true;
			}

			return $firstChild;
		}
		
		public function isLastChild() {
			$lastChild = false;

			if(!isset($this->settings['data'][$this->__i + 1]) || (!$this->__stack && $this->__field('right') >= $this->__field('right', count($this->settings['data']) - 1)) || ($this->__stack && $this->__stack[count($this->__stack) - 1] == $this->__field('right', $this->__i) + 1)) {
				$lastChild = true;
			}

			return $lastChild;
		}
		
		public function nodeInfo() {
			return $this->node;
		}
		
		private function __field($field, $i = false) {
			if($i === false) {
				$i = $this->__i;
			}

			return $this->settings['data'][$i][$this->settings['model']][$this->settings[$field]];
		}
		
		private function __resetData() {
			$this->__stack = array();
			$this->__i = 0;
		}
	}
