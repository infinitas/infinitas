<div id="menucontainer" class="grid_16 center">
	<div id="menunav">
		<ul>
			<?php
				if(isset($pluginInfo['pluginRollCall'][$this->plugin]['icon'])) {
					echo '<li class="icon">'.$this->Html->image(DS . $this->plugin . DS . 'img' . DS . 'icon.png').'</li>';
				}
			?>
			<li><?php echo implode('</li><li>', $this->Menu->builAdminMenu()); ?></li>
		</ul>
		<?php
			echo $this->Html->image(
				'/assets/img/logout.png',
				array(
					'url' => array(
						'plugin' => 'users',
						'controller' => 'users',
						'action' => 'logout',
						'prefix' => 'admin'
					),
					'class' => 'logout-link',
					'title' => __d('libs', 'Logout :: Exit the admin section')
				)
			);
			echo $this->Html->link(
				$this->Html->image('/assets/img/home.png', array('class' => 'logout-link')),
				'/',
				array('target' => '_blank', 'escape' => false, 'title' => __d('libs', 'Frontend :: Visit the frontend of the site'))
			);

			$helpPlugin = !empty($this->request->plugin) ? Inflector::camelize($this->request->plugin) : null;
			$helpUrl = 'http://infinitas-cms.org/infinitas_docs/' . $helpPlugin;
			if(Configure::read('debug') && in_array('InfinitasDocs', InfinitasPlugin::listPlugins())) {
				$helpUrl = array(
					'plugin' => 'infinitas_docs',
					'controller' => 'infinitas_docs',
					'action' => 'index',
					'doc_plugin' => $helpPlugin,
					'admin' => false
				);
			}
			echo $this->Html->link(
				$this->Html->image('/infinitas_docs/img/icon.png', array('class' => 'logout-link')),
				$helpUrl,
				array('target' => '_blank', 'escape' => false, 'title' => __d('infinitas_docs', 'Documentation :: Find help related to this page'))
			);
			echo $this->Html->link(
				$this->Html->image('/filemanager/img/icon.png', array('class' => 'logout-link')),
				array(
					'plugin' => 'filemanager',
					'controller' => 'filemanager',
					'action' => 'admin_ajax_tree',
					'foo',
					'ext' => 'ajax'
				),
				array(
					'id' => 'ajax-filemanager',
					'title' => __d('libs', 'Filemanager :: View and manage assets'),
					'escape' => false
				)
			);
		?>
	</div>
	<div id="filemanager-holder">
		<div class="bar"></div>
		<div class="tree"></div>
		<div class="preview"></div>
	</div>
</div>
<div class="clear"></div>