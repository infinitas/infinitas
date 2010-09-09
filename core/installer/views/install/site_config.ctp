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
?>
<h2><?php __('Set up your site'); ?></h2>
<blockquote class="extract">
	<p><?php echo __('You can now set up some basic information about your website. You can hover over each of the field titles for more information.', true); ?></p>
</blockquote>
<?php
	echo $this->Form->create('Config', array('url' => array('controller' => 'install', 'action' => 'siteConfig')));

	$i = 0;
	foreach($configs as $config){
		$_name = explode('.', $config['Config']['key']);
		echo '<div style="padding-top:10px; width:100%; clear:both; font-weight:bold;" title="'.strip_tags($config['Config']['description']).'">'.
			Inflector::humanize($_name[1]).
		'</div>';

		echo $this->Form->hidden('Config.'.$i.'.id', array('value' => $config['Config']['id']));
		echo $this->Form->hidden('Config.'.$i.'.type', array('value' => $config['Config']['type']));
        echo $this->Form->hidden('Config.'.$i.'.core', array('value' => $config['Config']['core']));
        echo $this->Form->hidden('Config.'.$i.'.description', array('value' => $config['Config']['description']));

        switch($config['Config']['type']){
            case 'bool':
                $_label = explode( '.', $config['Config']['key'] );
                $label = isset($_label[1]) ? $_label[1] : $_label[0];
                $config['Config']['value'] = $config['Config']['value'] == 'true' ? '1' : '0';
                echo $this->Form->input(
                    'Config.'.$i.'.value',
                    array(
                        'type' => 'checkbox',
                        'label' => false,
                        'style' => 'width:100%;'
                    )
                );
                break;

            case 'dropdown':
                $_options = explode(',', $config['Config']['options']);
                foreach($_options as $o){
                    $options[$o] = Inflector::humanize($o);
                }
                echo $this->Form->input(
                    'Config.'.$i.'.value',
                    array(
                        'type' => 'select',
                        'options' => $options,
                        'selected' => $config['Config']['value'],
						'label' => false,
                        'style' => 'width:100%;'
                    )
                );
                break;

            case 'integer':
            case 'string':
                echo $this->Form->input('Config.'.$i.'.value', array('value' => $config['Config']['value'], 'label' => false, 'style' => 'width:100%;'));
                break;
        } // switch
		$i++;
	}

	echo $this->Form->end('Save and continue');
?>