<?php
	/**
	 * Comment Template.
	 *
	 * @todo -c Implement .this needs to be sorted out.
	 *
	 * Copyright (c) 2009 Carl Sutton ( dogmatic69 )
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 *
	 * @filesource
	 * @copyright     Copyright (c) 2009 Carl Sutton ( dogmatic69 )
	 * @link          http://infinitas-cms.org
	 * @package       sort
	 * @subpackage    sort.comments
	 * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since         0.5a
	 */

	if (empty($content)) {
		echo 'Nothing to see';
		return true;
	}

	?>
		<style>
			<?php echo $content['Layout']['css']; ?>
		</style>
	<?php

	$content['Content']['modified'] = $this->Time->niceShort($content['Content']['modified']);

	$content['Content']['title'] = $this->Html->link($content['Content']['title'], array('action' => 'view', $content['Content']['id']));

	$__html = $content['Layout']['html'];

	$__html = str_replace( '{{Content.title}}', $content['Content']['title'], $__html);
	$__html = str_replace( '||Viewed||', __('Viewed',true), $__html);
	$__html = str_replace( '[[Content.views]]', $content['Content']['views'], $__html);
	$__html = str_replace( '||times||', __('times',true), $__html);
	$__html = str_replace( '[[Content.introduction]]', $content['Content']['introduction'], $__html);
	$__html = str_replace( '[[Content.body]]', $content['Content']['body'], $__html);
	$__html = str_replace( '||Last updated||', __('Last updated',true), $__html);
	$__html = str_replace( '[[Content.modified]]', $content['Content']['modified'], $__html);

	echo $__html;
?>