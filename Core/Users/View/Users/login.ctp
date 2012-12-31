<?php
/**
 * Login view
 *
 * Copyright (c) 2009 Carl Sutton ( dogmatic69 )
 *
 * @copyright Copyright (c) 2009 Carl Sutton ( dogmatic69 )
 * @link http://infinitas-cms.org
 * @package Users.View
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since v0.9a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

echo $this->Html->tag('div', implode('', array(
	$this->Html->tag('div', $this->element('Users.register'), array(
		'class' => 'span6'
	)),
	$this->Html->tag('div', $this->element('Users.login'), array(
		'class' => 'span6'
	))
)), array('class' => 'row'));

$providers = $this->Event->trigger('oauthProviders');
foreach ($providers['oauthProviders'] as &$provider) {
	if (isset($provider['element']) && isset($provider['config'])) {
		$provider = $this->element((string)$provider['element'], (array)$provider['config']);
	} else {
		$provider = null;
	}
}
echo $this->Html->tag('div', implode('', array(
	$this->Html->tag('div', implode('', $providers['oauthProviders']), array(
		'class' => 'span12'
	))
)), array('class' => 'row'));

$links = array('');
$links[] = $this->Html->link(__d('users', 'Forgot your password'), array(
	'plugin' => 'users',
	'controller' => 'users',
	'action' => 'forgot_password'
));

echo implode($this->Html->tag('br'), $links);