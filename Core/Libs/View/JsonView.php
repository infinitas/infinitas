<?php
	class JsonView extends View {
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