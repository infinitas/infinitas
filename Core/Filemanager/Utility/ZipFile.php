<?php
/**
 * ZipFile
 *
 * @package Infinitas.Filemanager.Utility
 */

/**
 * ZipFile
 *
 * @package Infinitas.Filemanager.Utility
 */
class ZipFile extends File {
/**
 * The path to the zip file
 *
 * @var string
 */
	protected $_path;

/**
 * Constructor
 *
 * Initialise the Zip object and open the specified path
 *
 * @param type $path
 * @param type $create
 * @param type $mode
 *
 * @return void
 */
	public function __construct($path, $create = false, $mode = 0775) {
		parent::__construct($path, $create, $mode);

		$this->Zip = new ZipArchive();
		if ($this->Zip->open($path) !== true) {
			throw new CakeException('Unable to open zip file');
		}
		$this->_path = $path;
	}

/**
 * Extract a zip to the specified path
 *
 * @param string $to the path to extract to
 *
 * @return boolean
 *
 * @throws UnableToCreateTargetException
 */
	public function extract($to, array $options = array()) {
		$options = array_merge(array(
			'delete' => false,
			'move' => true
		), $options);
		if (!$this->Folder->create($to)) {
			throw new UnableToCreateTargetException(array($to));
		}

		if (!$this->Zip->extractTo($to)) {
			return false;
		}

		if ($options['delete']) {
			$this->delete();
		}
		$this->Folder->cd($to);
		$folders = current($this->Folder->read());
		if ($options['move'] && count($folders) == 1) {
			$dir = $to . DS . current($folders) . DS;
			$this->Folder->move(array(
				'to' => $to . DS,
				'from' => $dir
			));
		}

		return true;
	}

/**
 * Destructor
 *
 * Make sure the Zip archive is closed when the class destructs
 *
 * @return void
 */
	public function __destruct() {
		$this->Zip->close();

		parent::__destruct();
	}

}