<?php
	class JsonView extends View{
		function render($action = null, $layout = null, $file = null){
			if (strtolower($this->params['url']['ext']) != 'json'){
				return parent::render($action, $layout, $file);
			}

			$vars = $this->viewVars;

			unset($vars['debugToolbarPanels']);
			unset($vars['debugToolbarJavascript']);

			if (is_array($vars)){
				header('Content-type: application/json');

				Configure::write('debug', 0); // Omit time in end of view
				return json_encode($vars);
			}

			return 'null';
		}
	}
?>