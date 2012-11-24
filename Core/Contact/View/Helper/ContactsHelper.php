<?php
/**
 * ContactsHelper is a collection of methods to help build up contact related
 * pages
 *
 * Currently can output a business card type markup for contacts.
 *
 * @todo contact forms. pass in a contact row and a form will be made using the
 * supplied email address etc.
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.Contact.helpers
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.8a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

class ContactsHelper extends AppHelper {
/**
 * Helpers to load
 *
 * @var array
 */
	public $helpers = array(
		'Text',
		'Html'
	);

/**
 * Render a country flag
 *
 * Possible options:
 *	- size: the size of the flag, currently only small is available
 *	- other: any other options for HtmlHelper::image()
 *
 * @param string $code the two / three letter country code
 * @param array $options options for the flag
 *
 * @return string
 */
	public function flag($code, array $options = array()) {
		$options = array_merge(array(
			'size' => 'small',
			'alt' => $code,
			'name' => null
		), $options);
		$size = $options['size'];
		unset($options['size']);

		$flag = $this->Html->image(sprintf('Contact.flags/%s/%s.png', $size, $code), $options);

		if ($options['name'] == true) {
			return $flag . $this->Html->tag('span', $code);
		}
		return $flag;
	}

	public function drawCard($details) {
		if (is_array(current($details))) {
			$this->drawCards($details);
		}

		return $this->Html->tag('div', $this->_output($details), array(
			'class' => 'contact-card'
		));
	}

	protected function _output($details) {
		$details = array_merge(array(
			'checkbox' => null,
			'avitar' => null,
			'user' => null,
			'company' => null,
			'mobile' =>  null,
			'landline' => null,
			'email' => null,
			'address' => null
		), $details);

		$return = array();
		if (!empty($details['avitar'])) {
			$return[] = $this->Html->image(
				$details['avitar'],
				array(
					'style' => 'width: 75; height: 75',
					'title' => sprintf('%s - %s', $details['user'], $details['company'])
				)
			);
		}

		if (!empty($details['mobile'])) {
			$return[] = $this->Html->tag('li', $details['mobile'], array('class' => 'mobile'));
		}

		if (!empty($details['landline'])) {
			$return[] = $this->Html->tag('li', $details['landline'], array('class' => 'landline'));
		}

		if (!empty($details['email'])) {
			$return[] = $this->Html->tag('li', $this->Text->autoLinkEmails($details['email']), array('class' => 'email'));
		}

		if (!empty($details['address'])) {
			$return[] = $this->Html->tag('li', $details['address'], array('class' => 'address'));
		}

		if (!empty($details['extra'])) {
			$return[] = $this->Html->tag('li', $details['extra'], array('class' => 'extra'));
		}

		$name = $details['user'];
		if (empty($details['user']) && !empty($details['company'])) {
			$name = $details['company'];
		} else if (!empty($details['company'])) {
			$name = sprintf('%s - %s', $details['company'], $details['user']);
		}

		return $this->Html->tag('h2', $details['checkbox'] . $name) . $this->Html->tag('ul', implode('', $return), array(
			'class' => 'details'
		));
	}

}