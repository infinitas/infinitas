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

    echo $this->Form->create('Newsletter');
        echo $this->Infinitas->adminEditHead(); ?>
		<fieldset>
			<h1><?php echo __('Newsletter', true); ?></h1><?php
	        echo $this->Form->input('id');
	        echo $this->Form->input('campaign_id', array('empty' => Configure::read('Website.empty_select')));
	        echo $this->Form->input('from', array('class' => 'title'));
	        echo $this->Form->input('reply_to', array('class' => 'title'));
	        echo $this->Form->input('subject', array('class' => 'title'));
	        echo $this->Infinitas->wysiwyg('Newsletter.html', array('toolbar' => 'EmailHtml'));
	        echo $this->Infinitas->wysiwyg('Newsletter.text', array('toolbar' => 'EmailText')); ?>
		</fieldset><?php
    echo $this->Form->end();