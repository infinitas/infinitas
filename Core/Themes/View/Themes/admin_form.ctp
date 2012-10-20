<?php
/**
 * Management Config admin edit post.
 *
 * this page is for admin to manage the setup of the site
 *
 * Copyright (c) 2009 Carl Sutton ( dogmatic69 )
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright     Copyright (c) 2009 Carl Sutton ( dogmatic69 )
 * @link          http://infinitas-cms.org
 * @package       management
 * @subpackage    management.views.configs.admin_edit
 * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
 */

echo $this->Form->create(null, array(
    'inputDefaults' => array(
        'empty' => Configure::read('Website.empty_select')
    )
));
    echo $this->Infinitas->adminEditHead(); 
    echo $this->Form->input('id');

    $tabs = array(
        __d('themes', 'Theme'),
        __d('themes', 'Author')
    );

    $contents = array(
        implode('', array(
            $this->Form->input('name', array('options' => $themes, 'type' => 'select')),
            !empty($defaultLayouts) ? $this->Form->input('default_layout', array('options' => $defaultLayouts)) : '',
            $this->Form->input('active')
        )),
        implode('', array(
            $this->Form->input('author'),
            $this->Form->input('url'),
            $this->Form->input('update_url'),
            $this->Form->input('licence'),
            $this->Infinitas->wysiwyg('Theme.description')
        ))
    );

    echo $this->Design->tabs($tabs, $contents);
echo $this->Form->end();