<?php
/**
 *  FolderSymlink
 *
 * @package Infinitas.Filemanager.Utility
 */

App::uses('Folder', 'Utility');

/**
 * FolderSymlink
 *
 * @package Infinitas.Filemanager.Utility
 *
 * @property Folder $Folder
 */
class FolderSymlink extends Folder {
/**
 * Folder instance
 *
 * @var Folder
 */
	public $Folder;

/**
 * Constructor
 *
 * @param type $path
 * @param type $create
 * @param type $mode
 *
 * @return void
 */
	public function __construct($path = false, $create = false, $mode = 0775) {
		parent::__construct($path, $create, $mode);
		$this->Folder = new Folder($path, $create, $mode);
	}

/**
 * Create a symlink
 *
 * @param string $link the location for the link
 * @param string $target where the link will point to
 *
 * @return boolean
 *
 * @throws PathIsFileException
 */
	public function create($link, $target = false) {
		self::_validSource($target);
		if (self::isLink($link) || empty($link)) {
			return true;
		}

		if (is_file($link)) {
			throw new PathIsFileException(array($link));
		}
		$link = rtrim($link, DS);
		$nextPathname = substr($link, 0, strrpos($link, DS));

		if ($this->Folder->create($nextPathname)) {
			if (!file_exists($link)) {
				if (symlink($target, $link)) {
					$this->_messages[] = __d('cake_dev', '%s created', $link);
					return true;
				} else {
					$this->_errors[] = __d('cake_dev', '%s NOT created', $link);
					return false;
				}
			}
		}
		return false;
	}

/**
 * Delete a link
 *
 * @param type $path
 *
 * @return boolean
 */
	public function delete($path = null) {
		if (!$path) {
			$path = $this->pwd();
			if (!$path) {
				return false;
			}
		}

		$path = rtrim($path, DS);
		if($this->isLink($path)) {
			return @unlink($path);
		}

		return false;
	}

/**
 * Check if the given path is a symlink
 *
 * @param string $file file path
 *
 * @return boolean
 */
	public function isLink($file = null) {
		if(!$file) {
			$file = $this->path;
		}
		return is_link($file);
	}

/**
 * Check if the passed path is valid for creating links from
 *
 * @param string $path the path to check
 *
 * @return boolean
 *
 * @throws NotValidLinkSourceException
 */
	protected function _validSource($path) {
		if(!is_dir($path)) {
			throw new NotValidLinkSourceException(array($path));
		}
		return true;
	}

}