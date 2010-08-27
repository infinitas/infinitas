<?php
	/* 
	 * Generic data fetcher.
	 * 
	 * Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * 
	 * @filesource
	 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * @link http://www.infinitas-cms.org
	 * @package libs
	 * @subpackage libs.models.datasources.reader
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.6a
	 *
	 * @author dogmatic69
	 * @author Ceeram
	 * 
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */

	abstract class ReaderSource extends DataSource {
		/**
		 * __construct()
		 *
		 * @param mixed $config
		 */
		function __construct($config) {
			parent::__construct($config);
		}
		/**
		 * A default request array
		 */
		public $request = array();/*
			'method' => 'GET',
			'uri' => array(
				'scheme' => 'http',
				'host' => null,
				'port' => 80,
				'user' => null,
				'pass' => null,
				'path' => null,
				'query' => null,
				'fragment' => null
			),
			'auth' => array(
				'method' => 'Basic',
				'user' => null,
				'pass' => null
			),
			'version' => '1.1',
			'body' => '',
			'line' => null,
			'header' => array(
				'Connection' => 'close',
				'User-Agent' => 'Infinitas'
			),
			'raw' => null,
			'cookies' => array()
		);*/

		public $driver = null;

		/**
		 * describe the data
		 *
		 * @param mixed $model
		 * @return array the shcema of the model
		 */
		public function describe(&$model){
			return $model->schema;
		}

		/**
		 * listSources
		 *
		 * list the sources???
		 *
		 * @return array sources
		 */
		public function listSources(){
			return array('listSources');
		}

		/**
		 * read data
		 *
		 * this is the main method that reads data from the datasource and
		 * formats it according to the request from the model.
		 *
		 * @param mixed $model the model that is requesting data
		 * @param mixed $query the qurey that was sent
		 *
		 * @return the data requested by the model
		 */
		public function read(&$model, $query){
			$this->request = array_merge($this->request, $model->request);			
			$response = $this->_process(
				$this->_getData($this->request)
			);

			if ($query['fields'] == 'count') {
				if (isset($model->map['count'])) {
					$count = Set::extract($model->map['count'], $response);
					$result[0][$model->alias]['count'] = $count;
				}
				else{
					$count = count(Set::extract($model->map['data'], $response));
					$result[0][$model->alias]['count'] = $count;
				}
				return $result;
			}

			if (isset($model->map['data'])) {
				if (strtolower($query['conditions']) !== 'raw') {
					$response = Set::extract($model->map['data'], $response);
				}
			}

			$i = 0;
			foreach ((array)$response as $key => $value) {
				if (isset($query['limit']) && $i >= $query['limit']) {
					return $result;
				}

				$result[$key][$model->alias] = $value;
				$i++;
			}
			return $result;
		}

		public function calculate(&$model, $func, $params = array()) {
			$params = (array)$params;
			switch (strtolower($func)) {
				case 'count':
					return 'count';
					break;
			}
		}

		/**
		 * Get xml data from a site.
		 *
		 * Uses cake socket to get the data based on the connection specified
		 * in the model
		 *
		 * @param array $request the request details
		 *
		 * @return string the xml that is returned from the site.
		 */
		protected function _getData($request){
			App::import('Lib', 'Libs.OauthSocket');
			$this->OauthSocket = new OauthSocket();
			$data = $this->OauthSocket->request($request);

			unset($this->OauthSocket);

			return $data;
		}

		/**
		 * This method shoud be over-ridden and used to format your data how you
		 * would like it to be.
		 * 
		 * @param string $response the data to be formatted
		 * @return mixed
		 */
		abstract protected function _process();
	}