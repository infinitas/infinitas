<?php
	/**
	 *
	 *
	 */
	class Backlink extends ManagementAppModel{
		var $name = 'Backlink';
		var $useDbConfig = 'xml';
		var $actsAs = false;
		var $useTable = false;
		var $schema = array();

		var $request = array(
			'method' => 'GET',
			'uri' => array(
				'scheme' => 'http',
				'host' => 'blogsearch.google.com',
				'path' => '/blogsearch_feeds',
				'query' => array(
					'scoring' => 'd',
					'ie' => 'utf-8',
					'num' => 20,
					'output' => 'rss',
					'partner' => 'infinitas',
					'q' => 'link:%site%'
				)
			)
		);


		var $map = array(
			'count'  => 'Rss.Channel.totalResults',
			'limit'  => '/Rss/Channel/itemsPerPage',
			'offset' => '/Rss/Channel/itemsPerPage',
			'data'   => 'Rss.Channel.Item'
		);

		function __construct($id = false, $table = null, $ds = null) {
			parent::__construct($id, $table, $ds);

			$this->request['uri']['query']['q'] = str_replace('%site%', 'http://cakephp.org', $this->request['uri']['query']['q']);
		}
	}
?>