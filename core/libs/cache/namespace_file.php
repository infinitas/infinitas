<?php
/**
	 * Partial reimplementation of Cake's original FileEngine. This
	 * engine supports namespaces similar to:
	 *
	 * Cache::write('app.pictures.recent', $recentPictures, 'app');
	 * Cache::write('app.pictures.top', $topPictures, 'app');
	 *
	 * This namespacing allows deletion of parent namespaces like:
	 *
	 * Cache::delete('app.pictures', 'app');
	 *
	 * The settings for this cache should be
	 * defined at the bottom of /config/bootstrap.php like the following:
	 *
	 * App::import('Vendor', 'NamespaceFile');
	 * Cache::config('app', array('engine' => 'NamespaceFile', 'duration'=> '+1 hour', 'prefix' => 'cake.'));
	 *
	 * Place this file in APP/vendors/.
	 *
	 * WARNING: DO NOT USE AS YOUR APP'S DEFAULT CACHE!
	 *
	 * @author Frank de Graaf (Phally)
	 * @link http://www.frankdegraaf.net
	 * @license MIT license
	 */
	class NamespaceFileEngine extends CacheEngine {
	/**
	 * Instance of File class
	 *
	 * @var File
	 * @access private
	 */
	    var $__File = null;
	/**
	 * settings
	 *         path = absolute path to cache directory, default => CACHE
	 *         prefix = string prefix for filename, default => cake.
	 *         lock = enable file locking on write, default => false
	 *         serialize = serialize the data, default => true
	 *
	 * @var array
	 * @see CacheEngine::__defaults
	 * @access public
	 */
	    var $settings = array();
	/**
	 * Set to true if NamespaceFileEngine::init(); and NamespaceFileEngine::__active(); do not fail.
	 *
	 * @var boolean
	 * @access private
	 */
	    var $__active = false;
	/**
	 * True unless NamespaceFileEngine::__active(); fails
	 *
	 * @var boolean
	 * @access private
	 */
	    var $__init = true;
	/**
	 * Initialize the Cache Engine
	 *
	 * Called automatically by the cache frontend
	 * To reinitialize the settings call Cache::engine('EngineName', [optional] settings = array());
	 *
	 * @param array $setting array of setting for the engine
	 * @return boolean True if the engine has been successfully initialized, false if not
	 * @access public
	 */
	    function init($settings = array()) {
	    	parent::init(array_merge(
	            array(
	                'engine' => 'NamespaceFile', 'path' => CACHE, 'prefix'=> 'cake.', 'lock'=> false,
	                'serialize'=> true, 'isWindows' => false
	            ),
	            $settings
	        ));

	        if (!isset($this->__File)) {
	            if (!class_exists('File')) {
	                require LIBS . 'file.php';
	            }
	            $this->__File =& new File($this->settings['path']);
	        }

	        if (DS === '\\') {
	            $this->settings['isWindows'] = true;
	        }

	        return $this->__active();
	    }
	/**
	 * Garbage collection. Permanently remove all expired and deleted data
	 *
	 * @return boolean True if garbage collection was succesful, false on failure
	 * @access public
	 */
	    function gc() {
	        return $this->clear(true);
	    }
	/**
	 * Write data for key into cache
	 *
	 * @param string $key Identifier for the data
	 * @param mixed $data Data to be cached
	 * @param mixed $duration How long to cache the data, in seconds
	 * @return boolean True if the data was succesfully cached, false on failure
	 * @access public
	 */
	    function write($key, &$data, $duration) {
	        $writable =& $this->__setKey($key);
	        if (!is_a($writable, 'File') || !$this->__init || $data === '') {
	            return false;
	        }

	        if (!$writable->exists()) {
	            $writable->Folder->create($this->__getFolderPath($key));
	            $writable->create();
	        }

	        $lineBreak = "\n";
	        if ($this->settings['isWindows']) {
	            $lineBreak = "\r\n";
	        }

	        if (!empty($this->settings['serialize'])) {
	            if ($this->settings['isWindows']) {
	                $data = str_replace('\\', '\\\\\\\\', serialize($data));
	            } else {
	                $data = serialize($data);
	            }
	        }

	        if ($this->settings['lock']) {
	            $writable->lock = true;
	        }

	        $expires = time() + $duration;
	        $contents = $expires . $lineBreak . $data . $lineBreak;
	        $success = $writable->write($contents);
	        $writable->close();
	        return $success;
	    }
	/**
	 * Read a key from the cache
	 *
	 * @param string $key Identifier for the data
	 * @return mixed The cached data, or false if the data doesn't exist, has expired, or if there was an error fetching it
	 * @access public
	 */
	    function read($key) {
	        $readable =& $this->__setKey($key);
	        if (!is_a($readable, 'File') || !$this->__init) {
	            return false;
	        }

	        if (!$readable->exists()) {
	            return false;
	        }

	        if ($this->settings['lock']) {
	            $readable->lock = true;
	        }

	        $time = time();
	        $cachetime = intval($readable->read(11));

	        if ($cachetime !== false && ($cachetime < $time || ($time + $this->settings['duration']) < $cachetime)) {
	            $readable->close();
	            $readable->delete();
	            return false;
	        }
	        $data = $readable->read(true);

	        if ($data !== '' && !empty($this->settings['serialize'])) {
	            if ($this->settings['isWindows']) {
	                $data = str_replace('\\\\\\\\', '\\', $data);
	            }
	            $data = unserialize((string)$data);
	        }
	        $readable->close();
	        return $data;
	    }
	/**
	 * Delete a key from the cache
	 *
	 * @param string $key Identifier for the data
	 * @return boolean True if the value was successfully deleted, false if it didn't exist or couldn't be removed
	 * @access public
	 */
	    function delete($key) {
	        $deletable =& $this->__setKey($key);
	        if ($deletable === false) {
	            return false;
	        }
	        $deletable->delete();
	    }
	/**
	 * Delete all values from the cache
	 *
	 * @param boolean $check Optional - only delete expired cache items
	 * @return boolean True if the cache was succesfully cleared, false otherwise
	 * @access public
	 */
	    function clear($check = false) {
	        if (!$this->__init) {
	            return false;
	        }

	        $tree = $this->__File->Folder->tree($this->settings['path'] . substr($this->settings['prefix'], 0, -1) . DS);
	        foreach ($tree[1] as $file) {
	            $deletable = $this->__setPath($file);
	            if ($check) {
	                $now = time();
	                $threshold = $now - $this->settings['duration'];
	                $mtime = $deletable->lastChange();

	                if ($mtime === false || $mtime > $threshold) {
	                    continue;
	                }

	                $expires = $deletable->read(11);
	                $deletable->close();

	                if ($expires > $now) {
	                    continue;
	                }

	            }
	            $deletable->delete();
	        }

	        $tree[0] = array_reverse($tree[0]);
	        foreach ($tree[0] as $folder) {
	            $deletable = $this->__setPath($folder);
	            if (!is_a($deletable, 'Folder')) {
	                continue;
	            }
	            $contents = $deletable->read();
	            if (empty($contents[0]) && empty($contents[1])) {
	                $deletable->delete();
	            }
	        }
	    }
	/**
	 * Get absolute file for a given key
	 *
	 * @param string $key The key
	 * @return mixed Absolute cache file for the given key or false if erroneous
	 * @access private
	 */
	    function __getPath($key) {
	        return $this->settings['path'] . str_replace('.', DS, $key);
	    }

	/**
	 * Get absolute folder for a given key
	 *
	 * @param string $key The key
	 * @return mixed Absolute cache file for the given key or false if erroneous
	 * @access private
	 */
	    function __getFolderPath($key) {
	        return $this->__getPath(substr($key, 0, strrpos($key, '.') + 1));
	    }

	/**
	 * Return the class (File/Folder) for a key.
	 *
	 * @param string $key The key
	 * @return mixed File or Folder class for a key.
	 * @access private
	 */
	    function __setKey($key) {
	        return $this->__setPath($this->__getPath($key));
	    }

	/**
	 * Return the class (File/Folder) for a path.
	 *
	 * @param string $path The path
	 * @return mixed File or Folder class for a path.
	 * @access private
	 */
	    function __setPath($path) {
	        $this->__File->Folder->cd($path);
	        if (is_dir($path)) {
	            $object  = &$this->__File->Folder;
	            return $object;
	        }

	        $this->__File->path = $path;
	        $object  = &$this->__File;
	        return $object;
	    }
	/**
	 * Determine is cache directory is writable
	 *
	 * @return boolean
	 * @access private
	 */
	    function __active() {
	        if (!$this->__active && $this->__init && !is_writable($this->settings['path'])) {
	            $this->__init = false;
	            trigger_error(sprintf(__('%s is not writable', true), $this->settings['path']), E_USER_WARNING);
	        } else {
	            $this->__active = true;
	        }
	        return true;
	    }

	/**
	 * Override the parents key method, so the keys don't get transformed
	 *
	 * @param string $key The key
	 * @return mixed The key if one is passed else return false
	 * @access public
	 */
	    function key($key = null) {
	        if (empty($key)) {
	            return false;
	        }
	        return $key;
	    }
	}
