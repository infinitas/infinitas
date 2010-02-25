<?php
class Page extends ManagementAppModel {
	var $name = 'Page';
	
	var $useTable = false;
	
	var $actsAs = false;
	
	var $primaryKey = 'file_name';
	
	function __construct( $id = false, $table = NULL, $ds = NULL )
	{
		parent::__construct($id, $table, $ds);

		$this->_schema = array(
			'file_name' => array(
				'type' => 'string',
				'null' => false,
				'default' => null,
				'key' => 'primary'
			),
			'name' => array(
				'type' => 'string',
				'null' => false,
				'default' => null,
				'key' => 'unique'
			),
			'body' => array(
				'type' => 'text',
				'null' => false,
				'default' => '',
				'length' => null
			)
		);
		
		$this->validate = array(
			'file_name' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => __('Please enter a filename for this item', true)
				),
				'validFileName' => array(
					'rule' => '/^[A-Za-z0-9_]*\.ctp$/',
					'message' => __('The filename can only be alphanumeric or _ (underscore)', true)
				)
			),
			'body' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => __('The page can not be empty', true)
				)
			)
		);		
	}
	
	function schema($field = false)
	{
		if(is_string($field))
		{
			return $this->_schema[$field];
		}
		
		return $this->_schema;
	}
	
	function paginate($conditions, $fields, $order, $limit, $page = 1, $recursive = null, $extra = array()) 
	{
		App::import('Core', 'Folder');
		
		$pagePath = APP . str_replace(array('/', '\\'), DS, Configure::read('CORE.page_path'));
		$Folder = new Folder($pagePath);
		
		$pages = $Folder->read();
		$pages = $pages[1];
		
		$returnPages = array();
		foreach($pages as $page)
		{
			if(strpos($page, '.ctp') !== false)
			{
				$returnPage = array(
					'name' => Inflector::humanize(substr($page,0,strlen($page)-4)),
					'file_name' => $page
				);
				
				$returnPages[]['Page'] = $returnPage;
			}
		}
		
		return Set::sort($returnPages, '{n}.Page.file_name', 'asc');
	}
	
	function paginateCount($conditions = null, $recursive = 0, $extra = array()) 
	{
		App::import('Core', 'Folder');
		
		$pagePath = APP . str_replace(array('/', '\\'), DS, Configure::read('CORE.page_path'));
		$Folder = new Folder($pagePath);
		
		$pages = $Folder->read();
		
		return count($pages[1]);
	}
	
	function read($fields = null, $filename = null)
	{
		if($filename === null)
			$filename = $this->id;
			
		$pageFile = APP . str_replace(array('/', '\\'), DS, Configure::read('CORE.page_path')) . DS . $filename;
	
		if(file_exists($pageFile))
		{
			$this->id = $filename;
			return array('Page' => array('body' => file_get_contents($pageFile), 'file_name' => $filename));
		}
		else
		{
			$this->id = null;
			return false;
		}
	}
	
	function find($type, $options = array())
	{
		$pagePath = APP . str_replace(array('/', '\\'), DS, Configure::read('CORE.page_path'));
		$Folder = new Folder($pagePath);

		$conditions = '.*';
		if(isset($options['conditions']['Page.file_name']))
		{
			$conditions = regexEscape($options['conditions']['Page.file_name']);
		}

		$results = $Folder->find($conditions);

		switch($type)
		{
			case 'count' :
				return count($results);
				break;
			case 'all' :
				return $results;
				break;
			case 'first' :
				if(isset($results[0]))
					return $results[0];
				else
					return false;
				break;
		}
	}
	
	function save($data = null, $validate = true)
	{
		if($data !== null)
			$this->data['Page'] = $data['Page'];
			
		if(empty($this->data))
		{
			return false;
		}
		
		if(strpos($this->data['Page']['file_name'], '.ctp') === false)
		{
			$this->data['Page']['file_name'] .= '.ctp';
		}
			
		$this->id = $this->data['Page']['file_name'];
		
		$pageFile = APP . str_replace(array('/', '\\'), DS, Configure::read('CORE.page_path')) . DS . $this->id;
		
		if($validate === false || $this->validates())
			return file_put_contents($pageFile, $this->data['Page']['body']);
		else
			return false;
	}	
}