<?php
/**
 * JsonView
 *
 * @package Infinitas.Libs.View
 */

/**
 * JsonView
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.Libs.View
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.7a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

class JsonView extends View {
/**
 * Render the view
 *
 * @param string $view the view being rendered
 * @param string $layout the layout being rendered
 *
 * @return string
 */
	public function _render($view = null, $layout = null) {
		if (strtolower($this->request->params['url']['ext']) != 'json') {
			return parent::render($view, $layout);
		}

		$vars = $this->viewVars;

		unset($vars['debugToolbarPanels']);
		unset($vars['debugToolbarJavascript']);

		if (is_array($vars)) {
			header('Content-type: application/json');

			Configure::write('debug', 0); // Omit time in end of view
			return json_encode($vars);
		}

		return 'null';
	}
}