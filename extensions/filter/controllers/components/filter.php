<?php
/**
	 * Filter component
	 *
	 * @original concept by Nik Chankov - http://nik.chankov.net
	 * @modified and extended by Maciej Grajcarek - http://blog.uplevel.pl
	 * @modified again by James Fairhurst - http://www.jamesfairhurst.co.uk
	 * @modified yet again by Jose Diaz-Gonzalez - http://josediazgonzalez.com
	 * @modified further by Jeffrey Marvin - http://blitztiger.com
	 * @incoroporating changes made by 'mcurry' - http://github.com/mcurry/
	 * @version 0.5
	 * @author Jeffrey Marvin <support@blitztiger.com>
	 * @license	http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @package	app
	 * @subpackage app.controller.components
	 */
	class FilterComponent extends Object {
	/**
	 * Fields which will replace the regular syntax in where i.e. field = 'value'
	 * @var array
	 */
		var $fieldFormatting = array(
			"string"	=> "LIKE '%%%s%%'",
			"text"		=> "LIKE '%%%s%%'",
			"datetime"	=> "LIKE '%%%s%%'"
		);

	/**
	 * Paginator params sent in URL
	 * @var array
	 */
		var $paginatorParams = array(
			'page',
			'sort',
			'direction',
			'limit'
		);

	/**
	 * Url variable used in paginate helper (array('url'=>$url));
	 * @var string
	 */
		 var $url = '';

	/**
	 * Used to tell whether the data options have been parsed
	 * @var boolean
	 */
		var $parsed = false;

	/**
	 * Used to tell whether to redirect so the url includes filter data
	 * @var boolean
	 */
		var $redirect = false;

	/**
	 * Used to tell whether time should be used in the filtering
	 * @var boolean
	 */
		var $useTime = false;

	// class variables
		var $filter = array();
		var $formOptionsDatetime = array();
		var $filterOptions = array();

	/**
	 * Before any Controller action
	 *
	 * @param array settings['actions'] an array of the action(s) the filter is to be applied to,
	 * @param array settings['redirect'] is whether after filtering is completed it should redirect and put the filters in the url,
	 * @param array settings['useTime'] is whether to filter date times with date in addition to time
	 */
		function initialize(&$controller, $settings = array()) {
			// If no action(s) is/are specified, defaults to 'index'
			if (!isset($settings['actions']) || empty($settings['actions'])) {
				$actions = array('index');
			} else {
				$actions = $settings['actions'];
			}

			if (!isset($settings['redirect']) || empty($settings['redirect'])) {
				$this->redirect = false;
			} else {
				$this->redirect = $settings['redirect'];
			}

			if (!isset($settings['useTime']) || empty($settings['useTime'])) {
				$this->useTime = false;
			} else {
				$this->useTime = $settings['useTime'];
			}

			foreach ($actions as $action){
				$this->processAction($controller, $action);
			}
		}

		function processAction($controller, $controllerAction){
			if ($controller->action == $controllerAction) {
				$this->filter = $this->processFilters($controller);
				$url = (empty($this->url)) ? '/' : $this->url;

				$this->filterOptions = array('url' => array($url));
				$this->formOptionsDatetime = array(
					'dateFormat' => 'DMY',
					'empty' => '-',
					'maxYear' => date("Y"),
					'minYear' => date("Y")-2,
					'type' => 'date');

				if (isset($controller->data['reset']) || isset($controller->data['cancel'])) {
					$this->filter = array();
					$this->url = '/';
					$this->filterOptions = array();
					$controller->redirect("/{$controller->name}/{$controllerAction}");
				}
			}
		}

	/**
	 * Builds up a selected datetime for the form helper
	 *
	 * @param string $fieldname
	 * @return null|string
	 */
		function processDatetime($fieldname) {
			$datetime = null;

			if (isset($this->params['named'][$fieldname])) {
				$exploded = explode('-', $this->params['named'][$fieldname]);
				if (!empty($exploded)) {
					$datetime = '';
					foreach ($exploded as $k => $e) {
						$datetime = (empty($e)) ? (($k == 0) ? '0000' : '00') : $e;
						if ($k != 2) {$datetime .= '-';}
					}
				}
			}
			return $datetime;
		}

	/**
	 * Function which will change controller->data array
	 *
	 * @param object $controller the class of the controller which call this component
	 * @param array $whiteList contains list of allowed filter attributes
	 * @access public
	 */
		function processFilters($controller, $whiteList = null){
			$controller = $this->_prepareFilter($controller);
			$ret = array();

			if (isset($controller->data)) {
				foreach ($controller->data as $model => $fields) {
					$modelFieldNames = array();
					if (isset($controller->{$model})) {
						$modelFieldNames = $controller->{$model}->getColumnTypes();
					} else if (isset($controller->{$controller->modelClass}->belongsTo[$model]) || isset($controller->{$controller->modelClass}->hasOne[$model])) {
						$modelFieldNames = $controller->{$controller->modelClass}->{$model}->getColumnTypes();
					}
					if (!empty($modelFieldNames)) {
						foreach ($fields as $filteredFieldName => $filteredFieldData) {
							if (is_array($filteredFieldData) && $modelFieldNames[$filteredFieldName] == 'datetime') {
								$filteredFieldData = $this->_prepareDatetime($filteredFieldData);
							}
							if ($filteredFieldData != '') {
								if (is_array($whiteList) && !in_array($filteredFieldName, $whiteList) ){
									continue;
								}
								if (isset($modelFieldNames[$filteredFieldName]) && isset($this->fieldFormatting[$modelFieldNames[$filteredFieldName]])) {
									// insert value into fieldFormatting
									$tmp = sprintf($this->fieldFormatting[$modelFieldNames[$filteredFieldName]], $filteredFieldData);
									// don't put key.fieldname as array key if a LIKE clause
									if (substr($tmp, 0, 4) == 'LIKE') {
										$ret[] = "{$model}.{$filteredFieldName} {$tmp}";
									} else {
										$ret["{$model}.{$filteredFieldName}"] = $tmp;
									}
								} else {
									// build up where clause with field and value
									$ret["{$model}.{$filteredFieldName}"] = $filteredFieldData;
								}
								// save the filter data for the url
								$this->url .= "/{$model}.{$filteredFieldName}:{$filteredFieldData}";
							}
						}
					} else {
						if (isset($controller->{$controller->modelClass}->hasMany[$model])) {
							$modelFieldNames = $controller->{$controller->modelClass}->{$model}->getColumnTypes();
							if (!empty($modelFieldNames)) {
								foreach ($fields as $filteredFieldName => $filteredFieldData) {
									if (is_array($filteredFieldData) && $modelFieldNames[$filteredFieldName] == 'datetime') {
										$filteredFieldData = $this->_prepare_datetime($filteredFieldData);
									}
									if ($filteredFieldData != '') {
										if (is_array($whiteList) && !in_array($filteredFieldName, $whiteList) ){
											continue;
										}
										// check if there are some fieldFormatting set
										if (isset($this->fieldFormatting[$modelFieldNames[$filteredFieldName]])) {
											// insert value into fieldFormatting
											$tmp = sprintf($this->fieldFormatting[$modelFieldNames[$filteredFieldName]], $filteredFieldData);
											// don't put key.fieldname as array key if a LIKE clause
											if (substr($tmp, 0, 4) == 'LIKE') {
												$ret[] = "{$model}.{$filteredFieldName} {$tmp}";
											} else {
												$ret["{$model}.{$filteredFieldName}"] = $tmp;
											}
										} else {
											$ret["{$model}.{$filteredFieldName}"] = $filteredFieldData;
										}
										$this->url .= "/{$model}.{$filteredFieldName}:{$filteredFieldData}";
									}
								}
							}
						} else if (isset($controller->{$controller->modelClass}->hasAndBelongsToMany[$model])) {
								$modelFieldNames = $controller->{$controller->modelClass}->{$model}->getColumnTypes();
								if (!empty($modelFieldNames)) {
									foreach ($fields as $filteredFieldName => $filteredFieldData) {
										if (is_array($filteredFieldData) && $modelFieldNames[$filteredFieldName] == 'datetime') {
											$filteredFieldData = $this->_prepare_datetime($filteredFieldData);
										}
										if ($filteredFieldData != '') {
											// if filter is in whitelist
											if (is_array($whiteList) && !in_array($filteredFieldName, $whiteList) ){
												continue;
											}
											// check if there are some fieldFormatting set
											if (isset($this->fieldFormatting[$modelFieldNames[$filteredFieldName]])) {
												// insert value into fieldFormatting
												$tmp = sprintf($this->fieldFormatting[$modelFieldNames[$filteredFieldName]], $filteredFieldData);
												// don't put key.fieldname as array key if a LIKE clause
												if (substr($tmp, 0, 4) == 'LIKE') {
													$ret[] = "{$model}.{$filteredFieldName} {$tmp}";
												} else {
													$ret["{$model}.{$filteredFieldName}"] = $tmp;
												}
											} else {
												$ret["{$model}.{$filteredFieldName}"] = $filteredFieldData;
											}
											$this->url .= "/{$model}.{$filteredFieldName}:{$filteredFieldData}";
										}
									}
								}
							}
					}
					// Unset empty model data
					if (count($fields) == 0){
						unset($controller->data[$model]);
					}
				}
			}
			//If redirect has been set true, and the data had not been parsed before and put into the url, does it now
			if (!$this->parsed && $this->redirect){
				$this->url = "/Filter.parsed:true{$this->url}";
				$controller->redirect("/{$controller->name}/index{$this->url}/");
			}
			return $ret;
		}

	/**
	 * function which will take care of the storing the filter data and loading after this from the Session
	 * JF: modified to not htmlencode, caused problems with dates e.g. -05-
	 *
	 * @param object $controller the class of the controller which call this component
	 */
		function _prepareFilter($controller) {
			$filter = array();
			if (isset($controller->data)) {
				foreach ($controller->data as $model => $fields) {
					if (is_array($fields)) {
						foreach ($fields as $key => $field) {
							if ($field == '') {
								unset($controller->data[$model][$key]);
							}
						}
					}
				}

				App::import('Sanitize');
				$sanitize = new Sanitize();
				$controller->data = $sanitize->clean($controller->data, array('encode' => false));
				$filter = $controller->data;
			}

			if (empty($filter)) {
				$filter = $this->_checkParams($controller);
			}

			$controller->data = $filter;
			return $controller;
		}

	/**
	 * function which will take care of filters from URL
	 * JF: modified to not encode, caused problems with dates
	 *
	 * @param object $controller the class of the controller which call this component
	 */
		function _checkParams($controller) {
			if (empty($controller->params['named'])) {
				$filter = array();
			}

			App::import('Sanitize');
			$sanitize = new Sanitize();

			$controller->params['named'] = $sanitize->clean($controller->params['named'], array('encode' => false));
			if (isset($controller->params['named']['Filter.parsed'])){
				if ($controller->params['named']['Filter.parsed']){
					$this->parsed = true;
					$filter = array();
				}
			}

			foreach ($controller->params['named'] as $field => $value) {
				if (!in_array($field, $this->paginatorParams) && $field != 'Filter.parsed') {
					$fields = explode('.', $field);
					if (sizeof($fields) == 1) {
						$filter[$controller->modelClass][$field] = $value;
					} else {
						$filter[$fields[0]][$fields[1]] = $value;
					}
				}
			}

			return (!empty($filter)) ? $filter : array();
		}

	/**
	 * Prepares a date array for a MySQL WHERE clause
	 *
	 * @author Jeffrey Marvin
	 * @param array $date
	 * @return string
	 */
		function _prepareDatetime($date) {
			if ($this->useTime){
				return  "{$date['year']}-{$date['month']}-{$date['day']}"
					. ' ' . (($date['meridian'] == 'pm' && $date['hour'] != 12) ? $date['hour'] + 12 : $date['hour'])
					. ':' . (($date['min'] < 10) ? "0{$date['min']}" : $date['min']);
			} else {
				return "{$date['year']}-{$date['month']}-{$date['day']}";
			}
		}
	}