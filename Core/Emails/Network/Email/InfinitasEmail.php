<?php
/**
 * InfinitasEmail
 *
 * @package Infinitas.Emails.Network
 */

/**
 * InfinitasEmail
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.Emails.Network
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.8a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

class InfinitasEmail extends CakeEmail {
/**
 * default from name
 *
 * @var string
 */
	protected $_fromName;

/**
 * set default settings
 *
 * @param array $config the configs for the class
 *
 * @return void
 */
	public function __construct($config = null) {
		$this->_fromName = Configure::read('Website.name');
		$this->_fromEmail = Configure::read('Website.email');
		parent::__construct($config);
	}

/**
 * send a basic email
 *
 * @param array $options the options for the email to be sent
 *
 * @return array
 */
	public function sendMail(array $options) {
		$this->config();
		$options = array_merge(array(
			'to' => array($this->_fromEmail => $this->_fromName),
			'cc' => null,
			'bcc' => null,
			'from' => $this->_fromEmail,
			'sender' => array($this->_fromEmail => $this->_fromName),
			'replyTo' => null,
			'subject' => 'Infinitas email',
			'html' => null,
			'text' => null,
			'readReceipt' => false,
			'charset' => Configure::read('App.encoding'),
			'headerCharset' => null,
			'emailFormat' => 'both',
			'viewVars' => array()
		), $options);

		if ($options['replyTo'] === null) {
			$options['replyTo'] = $options['from'];
		}

		$options['charset'] = strtolower($options['charset']);
		if ($options['headerCharset'] === null) {
			$options['headerCharset'] = $options['charset'];
		}

		if ($options['readReceipt'] !== false) {
			$options['readReceipt'] = $options['replyTo'];
		} else {
			unset($options['readReceipt']);
		}

		foreach ($options as $k => $v) {
			switch($k) {
				case 'to':
				case 'cc':
				case 'bcc':
					if ($v === null) {
						continue;
					}
					if (!is_array($v)) {
						$v = array($v => null);
					}
					$this->_setEmail('_' . $k, key($v), current($v));
				break;

				case 'subject':
					$v = $this->_parseSubject($v, $options['viewVars']);

				case 'from':
				case 'replyTo':
					if (empty($v)) {
						$v = $this->_systemAccount;
					}
					
				case 'sender':
				case 'readReceipt':
				case 'emailFormat':
				case 'charset':
				case 'headerCharset':
					if (is_array($v)) {
						$this->{$k}(key($v), current($v));
						continue;
					}
					$this->{$k}($v);
					break;

				case 'viewVars':
					if (!empty($v)) {
						$this->{$k}($v);
					}
					break;
			}
		}
		$this->viewRender('Libs.Infinitas');
		$this->template('default');

		return $this->send($options['html']);
	}

/**
 * Render mustache
 *
 * Used for the subject mainly so variables can be used (its not passed in the normal views)
 *
 * The flags {{%UNESCAPED}} and {{%DOT-NOTATION}} are set so all mustache in the subject should be
 * in the form Var.foo.bar, no lists and so on.
 *
 * @param string $value
 * @param array $data
 *
 * @return string
 */
	protected function _parseSubject($value, array &$data) {
		$data['infinitasJsData']['config'] = Configure::read();
		App::uses('Mustache', 'Libs.Lib');
		$Mustache = new Mustache();
		return $Mustache->render('{{%UNESCAPED}}{{%DOT-NOTATION}}' . $value, $data);
	}

/**
 * overload the config method to use the database email configs
 *
 * Passing a string will check the db for a system email config that can be used
 * for sending.
 *
 * Passing null will search for the default email config
 *
 * Passing array will use CakeEmail normally.
 *
 * @param string|array $config the configuration to use for sending emails
 *
 * @return parent::config()
 */
	public function config($config = null) {
		if (!is_array($config)) {
			$config = ClassRegistry::init('Emails.EmailAccount')->find('systemAccount', array(
				'config' => (string)$config
			));

			$this->_systemAccount = $config['email'];

			$this->_buildConfigs($config);
		}

		return parent::config($config);
	}

/**
 * get information from the configs that will be used in sending mails
 *
 * @param array $config the db config
 *
 * @return void
 */
	protected function _buildConfigs(&$config) {
		$this->_fromName = $config['name'];
		$this->_fromEmail = $config['email'];

		unset($config['name'], $config['email']);
	}
}