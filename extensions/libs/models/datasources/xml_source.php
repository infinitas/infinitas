<?php
	class XmlSource extends DataSource {
		/**
		 * A default request array
		 */
		var $request = array(
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
		);

		var $driver = null;


		/**
		 * __construct()
		 *
		 * @param mixed $config
		 */
		function __construct($config) {
			parent::__construct($config);
		}

		/**
		 * describe the data
		 *
		 * @param mixed $model
		 * @return array the shcema of the model
		 */
		function describe(&$model){
			return $model->schema;
		}

		/**
		 * listSources
		 *
		 * list the sources???
		 *
		 * @return array sources
		 */
		function listSources(){
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
		function read(&$model, $query){
			$this->request = array_merge($this->request, $model->request);
			$response = $this->__process(
				$this->__getData($this->request)
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
			foreach ($response as $key => $value) {
				if (isset($query['limit']) && $i >= $query['limit']) {
					return $result;
				}

				$result[$key][$model->alias] = $value;
				$i++;
			}
			return $result;
		}

		function calculate(&$model, $func, $params = array()) {
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
		function __getData($request){
			App::import('HttpSocket');
			$this->HttpSocket = new HttpSocket();
			$data = $this->HttpSocket->request($request);

			unset($this->HttpSocket);

			return $data;
		}

		/**
		 * Process xml.
		 *
		 * Takes xml and convers it to an array using cakes xml class
		 *
		 * @param string $response the xml that will be converted
		 *
		 * @return array the data from xml or empty array if there is nothing passed
		 */
		function __process($response = null) {
			if (empty($response)) {
				return array();
			}

			App::import('Xml');
			$this->Xml = new Xml($response);

			$data = $this->Xml->toArray();
			$this->Xml->__destruct();
			unset($this->Xml);

			return $data;
		}
	}