<?php
App::uses('HttpSocket', 'Network/Http');
class PluginDownload {
	public $HttpSocket;
	protected static function _fetch($type) {
		$HttpSocket = new HttpSocket();
		if ($type == 'list') {
			$HttpSocket->get('https://api.github.com/users/Infinitas-Plugins/repos');
		} else {
			$HttpSocket->get(sprintf('https://api.github.com/repos/Infinitas-Plugins/%s', $type));
		}

		if ($HttpSocket->response->code == 200) {
			return json_decode($HttpSocket->response->body, true);
		}

		return false;
	}

	public static function plugins() {
		return self::_fetch();
	}

	public static function plugin($plugin) {
		$plugin = self::_fetch($plugin);
		if (!empty($plugin['contributors_url'])) {
			$HttpSocket = new HttpSocket();
			$HttpSocket->get($plugin['contributors_url']);

			if ($HttpSocket->response->code == 200) {
				$plugin['contributors'] = json_decode($HttpSocket->response->body, true);
			}

		}
		var_dump($plugin);
	}

/**
 * Download a plugin
 *
 * @param type $plugin
 */
	public static function download($plugin) {
		self::_fileDownloadn($plugin);
	}

	protected static function _fileDownload($plugin) {
		App::uses('DownloadSocket', 'Filemanager.Network');
		App::uses('ZipFile', 'Filemanager.Utility');

		$DownloadSocket = new DownloadSocket();
		if ($DownloadSocket->download('https://github.com/Infinitas-Plugins/InfinitasBacklinks/archive/master.zip')) {
			$ZipFile = new ZipFile($DownloadSocket->pwd());
			$ZipFile->extract(APP . 'Plugin' . DS . 'MyPlugin', array(
				'delete' => true
			));
		}
	}
}