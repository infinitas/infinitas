<?php
/**
 * GlobalContentsHelper
 *
 * @package Infinitas.Contents.Helper
 */

/**
 * GlobalContentsHelper
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.Contents.Helper
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.9a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

class GlobalContentsHelper extends AppHelper {
/**
 * Helpers to load
 *
 * @var array
 */
	public $helpers = array(
		'Events.Event',
		'Html'
	);

/**
 * generate a link to view an author
 *
 * @param array $data the record
 *
 * @return string
 */
	public function author($data) {
		if(empty($data['ContentAuthor']['id']) && empty($data['GlobalContent']['author_alias'])) {
			return false;
		}

		$author = !empty($data['GlobalContent']['author_alias']) ? $data['GlobalContent']['author_alias'] : $data['ContentAuthor']['username'];

		return $this->Html->link(
			$author,
			array(
				'plugin' => 'contents',
				'controller' => 'global_authors',
				'action' => 'view',
				'author' => $author
			)
		);
	}

/**
 * render a template
 *
 * @param array $data
 *
 * @throws InvalidArgumentException
 */
	public function renderTemplate($data = array()) {
		if(empty($data['Layout']) && empty($data['GlobalLayout'])) {
			throw new InvalidArgumentException('Can not find template to render');
		}

		return $this->__renderCss($data) . $this->__renderHtml($data);
	}

/**
 * Get a url for the content
 *
 * @param array $data the record data
 *
 * @return array
 */
	public function url($data = null, $full = true) {
		list($plugin, $model) = pluginSplit($data['GlobalContent']['model']);

		$event = $this->Event->trigger($plugin . '.slugUrl', $data);

		return current($event['slugUrl']);
	}

/**
 * Render css
 *
 * Will use the layout attached to the content if available or the global layout
 * that was automatically loaded
 *
 * @param array $data the record from Model::find()
 *
 * @return string
 */
	private function __renderCss($data) {
		if(!empty($data['Layout']['css'])) {
			return sprintf('<style type="text/css">%s</style>', $data['Layout']['css']);
		}

		if(!empty($data['GlobalLayout']['css'])) {
			return sprintf("<style type=\"text/css\">\n%s\n</style>", $data['Layout']['css']);
		}
	}

/**
 * Render html template
 *
 * Will use the layout attached to the content if available or the global layout
 * that was automatically loaded
 *
 * @param array $data the record from Model::find()
 *
 * @return void
 */
	private function __renderHtml($data) {
		if(!empty($data['Layout']['html'])) {
			echo $data['Layout']['html'];
		}

		if(!empty($data['GlobalLayout']['html'])) {
			echo $data['GlobalLayout']['html'];
		}
	}

}