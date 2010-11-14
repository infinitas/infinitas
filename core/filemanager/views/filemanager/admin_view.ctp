<?php
    echo $this->Form->create('File');
        echo $this->Infinitas->adminEditHead();
	        ?>
				<div class="data">
					<?php
						App::import('File');
						$File = new File($path);
						$data = $File->read();
						$info = $File->info();
						switch($info['extension']){
							case 'php':
							case 'ctp':
							case 'css':
							case 'html':
							case 'txt':
								echo $this->Form->input('file', array('type' => 'textarea', 'label' => false, 'value' => $data, 'style' => 'width:100%; height:500px;'));
								break;

							case 'jpg';
								break;

						}
				    ?>
				</div>
				<div class="config">
					<?php
							?><h2><?php __('File info'); ?></h2><?php
							pr($info);
							echo 'todo';
				    ?>
				</div>
			<?php
    echo $this->Form->end();