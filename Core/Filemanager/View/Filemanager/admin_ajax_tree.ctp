<?php
	$out = array();
	foreach($files as $file) {
		if(!is_dir($file)) {
			continue;
		}

		$out[] = sprintf(
			'<li class="directory collapsed">%s</li>',
			$this->Html->link(
				basename(str_replace(DS . 'webroot' . DS . 'img', '', htmlentities($file))),
				$this->here . '#',
				array(
					'rel' => str_replace('//', '/', htmlentities($file) . DS)
				)
			)
		);
	}
		
	echo sprintf('<ul class="jqueryFileTree" style="display: none;">%s</ul>', implode('', $out));