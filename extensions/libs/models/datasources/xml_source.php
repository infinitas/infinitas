<?php
	class XmlSource extends DataSource {
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

		function __construct($config) {
			parent::__construct($config);
			//$this->Http =& new HttpSocket();
		}

		function describe(&$model){
			return $model->schema;
		}

		function listSources(){
			return 'listSources';
		}

		function read(&$model, $map = array(), $recursive = null){
			$this->request = array_merge($this->request, $model->request);
			$data = $this->__process(
				$this->__getData($this->request)
			);

			return $data;
		}

		function _sort($map = array(), $data = array()){
			if (empty($data)) {
				return array();
			}

			if (empty($map)) {
				return $data;
			}

			return Set::extract($map, $data);
		}

		function __getData($request){
			App::import('HttpSocket');
			$this->HttpSocket = new HttpSocket();
			$data = $this->HttpSocket->request($request);

			unset($this->HttpSocket);

			return $data;
		}

		function __process($response) {
			App::import('Xml');
			$this->Xml = new Xml($response);

			$data = $this->Xml->toArray();
			$this->Xml->__destruct();
			unset($this->Xml);

			return $data;
		}
	}
?>