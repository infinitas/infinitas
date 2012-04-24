<?php
	class InfinitasContainableBehavior extends ModelBehavior {
		private $__cache = array();
		
		private $__relationTypes = array(
			'hasOne',
			'belongsTo',
			'hasMany',
			'hasAndBelongsTo'
		);
		
		private $__afterFind = array(
			
		);


		public function beforeFind(Model $Model, $query) {
			$query['recursive'] = -1;
			
			if(empty($query['contain'])) {
				return $query;
			}
			
			$this->__normaliseQuery($query);
			
			$this->__cache[$Model->alias]['currentQuery'] = md5($Model->alias . serialize($query));
			
			$joins = $fields = array();
			if(empty($query['fields'])) {
				$fields = array(sprintf('%s.*', $Model->alias));
			}
			
			foreach($query['contain'] as $join => $config) {
				if(is_int($join) && !is_array($config)) {
					$join = $config;
					$config = array();
				}
				
				$joins[] = $this->__getJoin($Model, $join, $config);
				$fields = array_merge($fields, $this->__getFields($Model, $join, $config));
			}
			
			$query['joins'] = array_filter(array_merge(array_filter($joins), $query['joins']));
			$query['fields'] = array_merge(array_filter($fields), $query['fields']);
			
			unset($query['contain']);
			
			return $query;
		}
		
		public function afterFind(Model $Model, $results, $primary) {
			if(empty($this->__afterFind[$Model->alias])) {
				return $results;
			}
			
			foreach($this->__afterFind[$Model->alias]['hasMany'] as $relation => $data) {
				$joinConditions = Set::extract('/' . $Model->alias . '/' . $Model->primaryKey, $results);
				if(empty($joinConditions)) {
					continue;
				}
				
				$joinConditions = array(
					$Model->{$relation}->alias . '.' . $Model->hasMany[$relation]['foreignKey'] => $joinConditions
				);
				$data['conditions'] = array_merge($data['conditions'], $joinConditions);
				
				$hasManyData = $Model->{$relation}->find('all', $data);
				
				foreach($results as $k => $result) {
					$template = sprintf(
						'/%s[%s=/%s/i]',
						$Model->{$relation}->alias,
						$Model->hasMany[$relation]['foreignKey'],
						$results[$k][$Model->alias][$Model->{$relation}->primaryKey]
					);
						
					$results[$k][$Model->{$relation}->alias] = (array)Set::extract(
						'{n}.' . $Model->{$relation}->alias, 
						Set::extract($template, $hasManyData)
					);
				}
			}
			
			return $results;
		}
		
		private function __normaliseQuery(&$query) {
			if(empty($query['fields'])) {
				$query['fields'] = array();
			}
			
			if(is_string($query['fields'])) {
				$query['fields'] = array($query['fields']);
			}
		}
		
		private function __getJoin($Model, $join, $config) {
			$join = $this->{$this->__joinMethod($Model, $join)}($Model, $join, $config);
			if(!$join) {
				return $join;
			}
			
			if(!empty($this->__cache[$this->__cache[$Model->alias]['currentQuery']])) {
				if(is_array($this->__cache[$this->__cache[$Model->alias]['currentQuery']])) {
					$this->__cache[$this->__cache[$Model->alias]['currentQuery']][] = $join['alias'];
					return $join;
				}				
			}
			
			$this->__cache[$this->__cache[$Model->alias]['currentQuery']] = array($join);
			return $join;
		}
		
		private function __getFields($Model, $join, $config) {
			return $this->{$this->__fieldsMethod($Model, $join)}($Model, $join, $config);
		}
		
		private function __joinMethod($Model, $join) {
			return $this->__getMethodName($Model, $join, 'Join');
		}
		
		private function __fieldsMethod($Model, $join) {
			return $this->__getMethodName($Model, $join, 'Fields');
		}
		
		private function __getMethodName($Model, $join, $type) {
			$method = sprintf('__%s%s', $this->__relationType($Model, $join), $type);
			
			if(!method_exists($this, $method)) {
				throw new Exception(sprintf('No method found for join "%s"', $method));
			}
			
			return $method;
		}
		
		private function __relationType($Model, $join) {
			if(!empty($this->__cache[$Model->alias]['relation'][$join])) {
				return $this->__cache[$Model->alias]['relation'][$join];
			}
			
			foreach($this->__relationTypes as $type) {
				if(empty($Model->{$type})) {
					continue;
				}
				
				if(in_array($join, $Model->{$type}) || !empty($Model->{$type}[$join])) {
					$this->__cache[$Model->alias]['relation'][$join] = $type;
					break;
				}
			}
			
			if(empty($this->__cache[$Model->alias]['relation'][$join])) {
				throw new Exception(sprintf('Invalid type specified "%s"', $type));
			}
			
			return $this->__cache[$Model->alias]['relation'][$join];
		}
		
		private function __belongsToJoin($Model, $join, $config) {
			$hash = md5(serialize(array($join) + $config));
			if(!empty($this->__cache[$Model->alias]['joins'][$hash])) {
				return $this->__cache[$Model->alias]['joins'][$hash];
			}
			
			$joinConfig = $this->__defaultConfig($Model, $join, __METHOD__);
			
			if(!empty($config['conditions'])) {
				$joinConfig['conditions'] = $config['conditions'];
			}
			
			$this->__cache[$Model->alias]['joins'][$hash] = $joinConfig;
			
			return $this->__cache[$Model->alias]['joins'][$hash];
		}
		
		private function __hasOneJoin($Model, $join, $config) {
			$hash = md5(serialize(array($join) + $config));
			if(!empty($this->__cache[$Model->alias]['joins'][$hash])) {
				return $this->__cache[$Model->alias]['joins'][$hash];
			}
			
			$joinConfig = $this->__defaultConfig($Model, $join, __METHOD__);
			
			if(!empty($config['conditions'])) {
				$joinConfig['conditions'] = $config['conditions'];
			}
			
			$this->__cache[$Model->alias]['joins'][$hash] = $joinConfig;
			
			return $this->__cache[$Model->alias]['joins'][$hash];
		}
		
		private function __hasManyJoin($Model, $join, $config) {
			if(empty($config['conditions'])) {
				$this->__afterFind[$Model->alias]['hasMany'][$join]['conditions'] = array();
				return array();
			}
			
			if(isset($this->__afterFind[$Model->alias]['hasMany'][$join]['conditions'])) {
				$this->__afterFind[$Model->alias]['hasMany'][$join]['conditions'] = array_merge(
					$this->__afterFind[$Model->alias]['hasMany'][$join]['conditions'],
					$config['conditions']
				);
				return array();
			}
			$this->__afterFind[$Model->alias]['hasMany'][$join]['conditions'] = $config['conditions'];
			return array();
		}
		
		private function __belongsToFields($Model, $join, $config) {
			if(empty($config['fields'])) {
				$config['fields'] = array(sprintf('%s.*', $Model->{$join}->alias));
			}
			
			return (array)$config['fields'];
		}
		
		private function __hasOneFields($Model, $join, $config) {
			if(empty($config['fields'])) {
				$config['fields'] = array(sprintf('%s.*', $Model->{$join}->alias));
			}
			
			return (array)$config['fields'];
		}
		
		private function __hasManyFields($Model, $join, $config) {
			if(empty($config['fields'])) {
				$config['fields'] = array(sprintf('%s.*', $Model->{$join}->alias));
			}
			
			if(empty($this->__afterFind[$Model->alias]['hasMany'][$join]['fields'])) {
				$this->__afterFind[$Model->alias]['hasMany'][$join]['fields'] = $config['fields'];
				return array();
			}
			
			$this->__afterFind[$Model->alias]['hasMany'][$join]['fields'] = array_merge(
				$this->__afterFind[$Model->alias]['hasMany'][$join]['fields'],
				$config['fields']
			);
			
			return array();
		}
		
		private function __defaultConfig($Model, $join, $type) {
			list(,$type) = explode('::', str_replace(array('__', 'Join'), '', $type));
			
			$config = array(
				'table' => $Model->{$join}->tablePrefix . $Model->{$join}->useTable,
				'alias' => $Model->{$join}->alias,
				'type' => 'LEFT',
				'conditions' => array(
					$this->__joinCondition($Model, $join, $type)
				)
			);
			
			return $config;
		}
		
		private function __joinCondition($Model, $join, $type) {
			$return = null;
			
			switch($type) {				
				case 'belongsTo':
					$return = sprintf(
						'%s.%s = %s.%s',
						$Model->alias, $Model->{$type}[$Model->{$join}->alias]['foreignKey'],
						$Model->{$join}->alias, $Model->{$join}->primaryKey
					);
					break;
				
				case 'hasOne':
				case 'hasMany':
					$return = sprintf(
						'%s.%s = %s.%s',
						$Model->alias, $Model->primaryKey,
						$Model->{$join}->alias, $Model->{$type}[$Model->{$join}->alias]['foreignKey']
					);
					break;
				
				case 'hasAndBelongsToMany':
					
					break;
			}
			
			if(!$return) {
				throw new Exception(sprintf('Could not build the join for "%s"', $type));
			}
			
			return $return;
		}
	}