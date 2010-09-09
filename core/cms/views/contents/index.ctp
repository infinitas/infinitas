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

	$i = 0;
	foreach($contents as $content){
		$content['Author']['username'] = $content['Editor']['username'] = 'Admin';
		$eventData = $this->Event->trigger('cmsBeforeContentRender', array('_this' => $this, 'content' => $content));
		?><div class="beforeEvent"><?php
		foreach((array)$eventData['cmsBeforeContentRender'] as $_plugin => $_data){
			echo '<div class="'.$_plugin.'">'.$_data.'</div>';
		}
		?></div>
			<div class="wrapper">
				<div class="introduction">
					<h2>
						<?php
							$eventData = $this->Event->trigger('cms.slugUrl', array('type' => 'contents', 'data' => $content));
							$urlArray = current($eventData['slugUrl']);
							echo $this->Html->link(
								$content['Content']['title'],
								$urlArray
							);
						?><span><?php echo $this->Time->niceShort($content['Content']['created']); ?></span>
					</h2>
					<div class="body">
						<?php
							echo $this->Text->truncate($content['Content']['body'], 200, array('html' => true));
						?>
					</div>
				</div>
				<?php
					echo $this->element(
						'modules/comment',
						array(
							'plugin' => 'comment',
							'content' => $content,
							'modelName' => 'Content',
							'foreign_id' => $content['Content']['id']
						)
					);
				?>
			</div>
			<div class="afterEvent">
				<?php
					$eventData = $this->Event->trigger('cmsAfterContentRender', array('_this' => $this, 'content' => $content));
					foreach((array)$eventData['cmsAfterContentRender'] as $_plugin => $_data){
						echo '<div class="'.$_plugin.'">'.$_data.'</div>';
					}
				?>
			</div>
		<?php
	}
?>