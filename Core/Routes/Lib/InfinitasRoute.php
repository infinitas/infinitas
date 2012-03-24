<?php
	class InfinitasRoute extends CakeRoute {
		public function parse($url) {
			$params = parent::parse($url);
			
			if(!empty($params['plugin'])) {
				$plugin = Inflector::camelize($params['plugin']);
				$data = current(EventCore::trigger($this, $plugin . '.routeParse', $params));
				
				if(isset($data[$plugin]) && $data[$plugin] !== null) {
					return $data[$plugin];
				}
			}
			
			return $params;
		}
		
		public function match($url) {
			return parent::match($url);
		}
	}