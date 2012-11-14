<?php
/**
 * Files model
 *
 * @link http://infinitas-cms.org/infinitas_docs/Filemanager Infinitas Filemanager
 *
 * @package Infinitas.Filemanager.Model
 */

/**
 * Files
 *
 * For reading file information from the file system
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://infinitas-cms.org/infinitas_docs/Filemanager Infinitas Filemanager
 * @package Infinitas.Filemanager.Model
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.6a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

class Files extends AppModel {
/**
 * Disable database
 *
 * @var boolean
 */
	public $useTable = false;

/**
 * BelongsTo relations
 *
 * @var array
 */
	public $belongsTo = array(
		'Filemanager.Folders'
	);

/**
 * Files to ignore
 *
 * @var array
 */
	public $ignore = array(
		'.htaccess',
		'.htpasswd',
		'.gitignore'
	);

/**
 * BeforeFind callback
 *
 * @param array $queryData the query data
 *
 * @return boolean
 */
	public function beforeFind($queryData) {
		$this->basePath = Configure::read( 'Filemanager.base_path' );

		if (empty($this->basePath)) {
			$this->validationErrors[] = array(
				'field' => 'basePath',
				'message' => __('Base path does not exist')
				);
			return false;
		}

		$this->path = $this->basePath;

		if (isset($queryData['conditions']['path'])) {
			$this->path = $this->basePath . $queryData['conditions']['path'];
		}

		App::import('Folder');
		App::import('File');
		$Folder = new Folder($this->path);

		if (empty($Folder->path)) {
			$this->validationErrors[] = array(
				'field' => 'path',
				'message' => __('Path does not exist')
				);
			return false;
		}

		$this->fileList = $Folder->read();
		unset($this->fileList[0]);

		if (!empty($queryData['order'])) {
			$this->__order($queryData['order']);
		}

		return true;
	}

/**
 * Get files
 *
 * @param string $findType the find type
 * @param array $conditions the find conditions
 *
 * @return array
 */
	public function find($findType = 'all', $conditions = array()) {
		if (! $this->beforeFind($conditions)) {
			return false;
		}

		$data = Cache::read('files.' . sha1($this->path . $conditions), 'filemanager');
		if ($data !== false) {
			return $data;
		}

		return (array)$this->__read($findType, $conditions);
	}

/**
 * chmod a file
 *
 * @param string $path
 *
 * @return void
 */
	public function chmod($path) {
	}


/**
 * Get file data
 *
 * @param string $findType the type of find
 * @param array $conditions the conditions for the find
 *
 * @return array
 */
	private function __read($findType, $conditions) {
		switch($findType) {
			case 'all':
				$this->__advancedFileFind($conditions);
				break;

			case 'list':
				$this->__simpleFileFind($conditions);
				break;
		}
		$this->return;

		Cache::write('files.' . sha1($this->path . $conditions), $this->return, 'filemanager');

		return $this->return;
	}

/**
 * Get detailed file information
 *
 * @param array $conditions conditions for the find
 *
 * @return boolean
 */
	private function __advancedFileFind($conditions) {
		if (empty($this->fileList[1])) {
			$this->return = array();
			return true;
		}
		$i = 0;

		foreach($this->fileList[1] as $file) {
			if (in_array($file, $this->ignore)) {
				continue;
			}

			if ($this->recursive > - 2) {
				$Folder = new Folder($this->path);
				$this->return[$i]['File']['path'] = $Folder->path . DS . $file;
				$this->return[$i]['File']['relative'] = $this->__relativePath($this->return[$i]['File']['path']);

				$stat = stat($this->return[$i]['File']['path']);
				$this->__fileStatus($i, $stat);

				if ($this->recursive > - 1) {
					$this->return[$i]['File']['accessed'] = date('Y-m-d H:i:s', $stat['atime']);
					$this->return[$i]['File']['modified'] = date('Y-m-d H:i:s', $stat['mtime']);
					$this->return[$i]['File']['created'] = date('Y-m-d H:i:s', $stat['ctime']);

					$File = new File($this->return[$i]['File']['path']);
					$info = $File->info();
					$this->return[$i]['File']['dirname'] = $info['dirname'];
					$this->return[$i]['File']['name'] = $info['basename'];
					$this->return[$i]['File']['extension'] = isset($info['extension']) ? $info['extension'] : null;
					$this->return[$i]['File']['filename'] = $info['filename'];
					$this->return[$i]['File']['writable'] = $File->writable();

					if ($this->recursive > 0) {
						$this->return[$i]['File']['size'] = $File->size();
						$this->return[$i]['File']['owner'] = $File->owner();
						$this->return[$i]['File']['group'] = $File->group();
						$this->return[$i]['File']['accessed'] = $File->lastAccess();
						$this->return[$i]['File']['modidfied'] = $File->lastChange();
						$this->return[$i]['File']['charmod'] = $File->perms();

						if ($this->recursive > 1) {
							$this->return[$i]['File']['type'] = filetype($this->return[$i]['File']['path']);
							$this->return[$i]['File']['md5'] = $File->md5();
							$this->return[$i]['File']['Extended'] = stat($this->return[$i]['File']['path']);

							$i++;
							continue;
						}
						$i++;
					}

					$i++;
				}

				$i++;
			}

			$i++;
		}
		return true;
	}

/**
 * Get file information
 *
 * @param string $currentI
 * @param array $stat
 *
 * @return void
 */
	private function __fileStatus($currentI, $stat) {
		$ts = array(0140000 => 'ssocket', 0120000 => 'llink', 0100000 => '-file',
			0060000 => 'bblock', 0040000 => 'ddir', 0020000 => 'cchar',
			0010000 => 'pfifo'
			);
		$p = $stat['mode'];
		$t = decoct($stat['mode'] &0170000);

		$str = (array_key_exists(octdec($t), $ts)) ? $ts[octdec($t)]{0} : 'u';
		$str .= ($p&0x0100) ? 'r' : '-';
		$str .= ($p&0x0080) ? 'w' : '-';

		if($p&0x0040) {
			$str .= ($p&0x0800) ? 's' : 'x';
		}

		else{
			$str .= ($p&0x0800) ? 'S' : '-';
		}

		$str .= ($p&0x0020) ? 'r' : '-';
		$str .= ($p&0x0010) ? 'w' : '-';

		if($p&0x0008) {
			$str .= ($p&0x0400) ? 's' : 'x';
		}

		else{
			$str .= ($p&0x0400) ? 'S' : '-';
		}

		$str .= ($p&0x0004) ? 'r' : '-';
		$str .= ($p&0x0002) ? 'w' : '-';

		if($p&0x0001) {
			$str .= ($p&0x0200) ? 't' : 'x';
		}

		else{
			$str .= ($p&0x0200) ? 'T' : '-';
		}

		$this->return[$currentI]['File']['permission'] = $str;
		$this->return[$currentI]['File']['octal'] = sprintf("0%o", 0777 &$p);
		$this->return[$currentI]['File']['owner'] = $stat['uid'];
		$this->return[$currentI]['File']['group'] = $stat['gid'];
		$this->return[$currentI]['File']['owner_name'] =
			(function_exists('posix_getpwuid')) ? posix_getpwuid($this->return[$currentI]['File']['owner']) : '';
		$this->return[$currentI]['File']['group_name'] =
			(function_exists('posix_getgrgid')) ? posix_getgrgid($this->return[$currentI]['File']['group']) : '';
	}
/**
 * Find files
 *
 * @param array $conditions
 *
 * @return boolean
 */
	private function __simpleFileFind($conditions) {
		if (empty($FileList[1])) {
			$this->return = array();
			return true;
		}

		foreach($FileList[1] as $file) {
			if (in_array($file, $this->ignore)) {
				continue;
			}

			$this->return[] = $Folder->path . DS . $file;
		}
		return true;
	}

/**
 * Get the relative path to the folder
 *
 * @param string $path the path
 *
 * @return string
 */
	private function __relativePath($path) {
		return 'todo';
	}

/**
 * Order files
 *
 * @param array $order the order options
 *
 * @return void
 */
	public function __order($order = array('name' => 'ASC')) {
		if (!is_array($order)) {
			$order = array($order);
		}

		foreach($order as $field => $direction) {
			if ($field == 'name') {
				if (strtolower($direction) == 'asc') {
					sort($this->fileList[1]);
				}else {
					rsort($this->fileList[1]);
				}
			}
		}
	}

}